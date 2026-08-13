<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\ProfissionalController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\BloqueioController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AgendamentoRecorrenteController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\GoogleCalendarSyncController;
use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Profissional;
use App\Models\Servico;



Route::get('/login', function () { return view('auth.gateway'); })->name('login');
Route::get('/login/proprietario', function () { return view('auth.login'); })->name('login.proprietario');
Route::post('/login/proprietario', function (\Illuminate\Http\Request $request) { 
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/panel/dashboard');
    }

    return back()->withErrors([
        'email' => 'As credenciais fornecidas estão incorretas.',
    ])->onlyInput('email');
})->name('login.proprietario.post');

Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/register', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nome' => 'required|string|max:255',
        'barbearia_nome' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'telefone' => 'nullable|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Create Barbearia
    $barbearia = \App\Models\Barbearia::create([
        'nome' => $request->barbearia_nome,
        'slug' => \Illuminate\Support\Str::slug($request->barbearia_nome) . '-' . rand(100, 999),
        'email' => $request->email,
        'telefone' => $request->telefone,
        'ativo' => true,
        'plano' => 'basic',
    ]);

    // Create Admin User
    $user = \App\Models\User::create([
        'barbearia_id' => $barbearia->id,
        'nome' => $request->nome,
        'email' => $request->email,
        'telefone' => $request->telefone,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        'role' => 'admin',
    ]);

    // Login
    \Illuminate\Support\Facades\Auth::login($user);

    return redirect()->route('panel.dashboard');
})->name('register.post');
Route::match(['get', 'post'], '/logout', function (\Illuminate\Http\Request $request) { 
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login'); 
})->name('logout');

// Recuperação de senha
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    $status = Password::sendResetLink($request->only('email'));
    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'token'    => 'required',
        'email'    => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (\App\Models\User $user, string $password) {
            $user->forceFill(['password' => $password])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        }
    );
    return $status === Password::PASSWORD_RESET
        ? redirect('/login/proprietario')->with('status', 'Senha redefinida com sucesso. Faça login.')
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');

// Rotas do Funcionário
Route::get('/funcionario/login', [FuncionarioController::class, 'showLogin'])->name('funcionario.login');
Route::post('/funcionario/login', [FuncionarioController::class, 'login'])->name('funcionario.login.post');
Route::post('/funcionario/logout', [FuncionarioController::class, 'logout'])->name('funcionario.logout');
Route::get('/funcionario/dashboard', [FuncionarioController::class, 'dashboard'])->name('funcionario.dashboard');
Route::get('/funcionario/agendamentos', [FuncionarioController::class, 'agendamentos'])->name('funcionario.agendamentos');
Route::post('/funcionario/agendamentos/{agendamento}/finalizar', [FuncionarioController::class, 'finalizar'])->name('funcionario.agendamento.finalizar');
Route::get('/funcionario/bloquear-horarios', [FuncionarioController::class, 'bloqueios'])->name('funcionario.bloqueios');
Route::post('/funcionario/bloquear-horarios', [FuncionarioController::class, 'storeBloqueio'])->name('funcionario.bloqueios.store');
Route::delete('/funcionario/bloquear-horarios/{bloqueio}', [FuncionarioController::class, 'destroyBloqueio'])->name('funcionario.bloqueios.destroy');
Route::get('/funcionario/horarios', [FuncionarioController::class, 'horarios'])->name('funcionario.horarios');
Route::post('/funcionario/horarios', [FuncionarioController::class, 'storeHorarios'])->name('funcionario.horarios.store');
Route::get('/funcionario/configuracoes', [FuncionarioController::class, 'configuracoes'])->name('funcionario.configuracoes');
Route::post('/funcionario/configuracoes', [FuncionarioController::class, 'updateConfiguracoes'])->name('funcionario.configuracoes.update');

// Página pública de agendamento (com dados reais)
Route::get('/agendar/{slug?}', function ($slug = null) {
    // Se não informar slug, pega a primeira (comportamento legado) ou tenta pelo auth
    if (!$slug) {
        $barbearia = \App\Models\Barbearia::first();
    } else {
        $barbearia = \App\Models\Barbearia::where('slug', $slug)->first();
    }

    if (!$barbearia) abort(404);

    $funcionarioId = request('funcionario');
    $exclusivo     = !is_null($funcionarioId);

    if ($exclusivo) {
        $preselected   = \App\Models\Profissional::where('barbearia_id', $barbearia->id)->find($funcionarioId);
        $profissionais = $preselected ? collect([$preselected]) : collect();
    } else {
        $preselected   = null;
        $profissionais = \App\Models\Profissional::where('barbearia_id', $barbearia->id)
            ->where('ativo', true)
            ->where('aceita_agendamento_online', true)->get();
    }

    $servicos  = \App\Models\Servico::where('barbearia_id', $barbearia->id)->where('ativo', true)->get();
    $produtos  = \App\Models\Produto::where('barbearia_id', $barbearia->id)->where('ativo', true)->get();

    return view('booking.index', compact('profissionais', 'servicos', 'produtos', 'preselected', 'exclusivo', 'barbearia'));
})->name('booking');
Route::post('/agendar', [AgendamentoController::class, 'store'])->name('booking.store');

// Redireciona antigo slug para novo padrão se necessário
Route::get('/b/{slug}', function ($slug) {
    return redirect()->route('booking', ['slug' => $slug]);
});

// Rota temporária — adiciona coluna foto à tabela users — REMOVER após executar uma vez
Route::get('/panel/run-migration-user-foto', function () {
    if (!auth()->check()) abort(403);
    if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'foto')) {
        \Illuminate\Support\Facades\Schema::table('users', function ($table) {
            $table->text('foto')->nullable();
        });
        return response()->json(['status' => 'ok', 'message' => 'Coluna foto adicionada à tabela users.']);
    }
    return response()->json(['status' => 'already_exists', 'message' => 'Coluna foto já existe.']);
});

// Rota temporária de criação de agendamento via token — REMOVER após validação
Route::get('/teste-booking', function (\Illuminate\Http\Request $request) {
    if ($request->get('token') !== 'glow2026') abort(403);

    $barbearia    = \App\Models\Barbearia::first();
    if (!$barbearia) return response()->json(['error' => 'Nenhuma barbearia encontrada'], 404);

    $profissional = \App\Models\Profissional::where('barbearia_id', $barbearia->id)
        ->where('nome', 'like', '%João%')->first()
        ?? \App\Models\Profissional::where('barbearia_id', $barbearia->id)->where('ativo', true)->first()
        ?? \App\Models\Profissional::create([
            'barbearia_id'              => $barbearia->id,
            'nome'                      => 'João Barbeiro',
            'ativo'                     => true,
            'aceita_agendamento_online' => true,
            'comissao_percentual'       => 40,
            'codigo_acesso'             => strtoupper(substr(md5(uniqid()), 0, 6)),
        ]);

    $servico = \App\Models\Servico::where('barbearia_id', $barbearia->id)->where('ativo', true)->first()
        ?? \App\Models\Servico::create([
            'barbearia_id'      => $barbearia->id,
            'nome'              => 'Corte de Cabelo',
            'preco'             => 35.00,
            'duracao_minutos'   => 30,
            'ativo'             => true,
            'disponivel_online' => true,
        ]);

    $hora      = \Carbon\Carbon::now()->setTimezone('America/Sao_Paulo')->setTime(14, 0, 0);
    $dataFim   = (clone $hora)->addMinutes($servico->duracao_minutos ?? 30);

    $agendamento = \App\Models\Agendamento::create([
        'barbearia_id'     => $barbearia->id,
        'profissional_id'  => $profissional->id,
        'servico_id'       => $servico->id,
        'cliente_nome'     => 'Maria da Silva',
        'cliente_telefone' => '11988887777',
        'data_inicio'      => $hora,
        'data_fim'         => $dataFim,
        'preco'            => $servico->preco ?? 35,
        'status'           => 'confirmado',
        'agendado_online'  => true,
        'descricao'        => 'Agendamento via link de teste.',
    ]);

    return response()->json([
        'success'           => true,
        'agendamento_id'    => $agendamento->id,
        'cliente'           => $agendamento->cliente_nome,
        'profissional'      => $profissional->nome,
        'servico'           => $servico->nome,
        'data_inicio'       => $hora->toDateTimeString(),
        'booking_url'       => url('/agendar/' . $barbearia->slug . '?funcionario=' . $profissional->id),
        'calendario_url'    => url('/panel/agendamentos'),
    ]);
});

// Webhooks e endpoints chamados externamente (fora de /api/ para evitar conflito com o runtime do Vercel)
Route::get('/webhooks/check-vip', [AgendamentoController::class, 'checkVip'])->name('api.check-vip');
Route::post('/webhooks/google-calendar/sync', [GoogleCalendarSyncController::class, 'store'])->name('api.google-calendar.sync');
Route::post('/webhooks/google-calendar/event-id', [GoogleCalendarSyncController::class, 'storeEventId'])->name('api.google-calendar.event-id');




Route::prefix('panel')->name('panel.')->middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notificações
    Route::get('/notificacoes', [\App\Http\Controllers\NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::post('/notificacoes/{id}/read', [\App\Http\Controllers\NotificacaoController::class, 'markAsRead'])->name('notificacoes.read');
    Route::post('/notificacoes/read-all', [\App\Http\Controllers\NotificacaoController::class, 'markAllAsRead'])->name('notificacoes.readAll');
    Route::delete('/notificacoes/{id}', [\App\Http\Controllers\NotificacaoController::class, 'destroy'])->name('notificacoes.destroy');

    // Agendamentos
    Route::get('/agendamentos', [AgendamentoController::class, 'index'])->name('agendamentos');
    Route::post('/agendamentos', [AgendamentoController::class, 'storePanel'])->name('agendamentos.store');
    Route::get('/agendamentos/eventos', [AgendamentoController::class, 'eventosJson'])->name('agendamentos.eventos');
    Route::patch('/agendamentos/{agendamento}/status', [AgendamentoController::class, 'updateStatus'])->name('agendamentos.status');

    // Rota temporária de info — remover após validação
    Route::get('/agendamentos/info-teste', function () {
        $barbeariaId  = auth()->user()->barbearia_id;
        $barbearia    = \App\Models\Barbearia::find($barbeariaId);
        $profissional = \App\Models\Profissional::where('barbearia_id', $barbeariaId)->first();
        $servico      = \App\Models\Servico::where('barbearia_id', $barbeariaId)->first();
        return response()->json([
            'barbearia_id'     => $barbeariaId,
            'barbearia_slug'   => $barbearia?->slug,
            'profissional_id'  => $profissional?->id,
            'profissional_nome'=> $profissional?->nome,
            'servico_id'       => $servico?->id,
            'servico_nome'     => $servico?->nome,
            'booking_url'      => url('/agendar/' . $barbearia?->slug . '?funcionario=' . $profissional?->id),
        ]);
    });

    // Rota temporária de seed — remover após validação
    Route::get('/agendamentos/seed-teste', function () {
        $barbeariaId = auth()->user()->barbearia_id;

        // Garante que existe ao menos um profissional
        $profissional = \App\Models\Profissional::where('barbearia_id', $barbeariaId)->first()
            ?? \App\Models\Profissional::create([
                'barbearia_id'              => $barbeariaId,
                'nome'                      => 'João Barbeiro',
                'ativo'                     => true,
                'aceita_agendamento_online' => true,
                'comissao_percentual'       => 40,
                'codigo_acesso'             => strtoupper(substr(md5(uniqid()), 0, 6)),
            ]);

        // Garante que existe ao menos um serviço
        $servico = \App\Models\Servico::where('barbearia_id', $barbeariaId)->first()
            ?? \App\Models\Servico::create([
                'barbearia_id'      => $barbeariaId,
                'nome'              => 'Corte de Cabelo',
                'preco'             => 35.00,
                'duracao_minutos'   => 30,
                'ativo'             => true,
                'disponivel_online' => true,
            ]);

        // Cria o agendamento para hoje às 10h
        $inicio = \Carbon\Carbon::now()->startOfDay()->addHours(10);
        $fim    = (clone $inicio)->addMinutes($servico->duracao_minutos ?? 30);

        \App\Models\Agendamento::create([
            'barbearia_id'     => $barbeariaId,
            'profissional_id'  => $profissional->id,
            'servico_id'       => $servico->id,
            'cliente_nome'     => 'Cliente Teste',
            'cliente_telefone' => '11999999999',
            'data_inicio'      => $inicio,
            'data_fim'         => $fim,
            'preco'            => $servico->preco ?? 35,
            'status'           => 'confirmado',
            'agendado_online'  => false,
            'descricao'        => 'Agendamento de teste.',
        ]);

        return redirect('/panel/agendamentos');
    })->name('agendamentos.seed-teste');

    // Agendamentos Recorrentes
    Route::get('/agendamentos-recorrentes', [AgendamentoRecorrenteController::class, 'index'])->name('agendamentos-recorrentes');
    Route::post('/agendamentos-recorrentes', [AgendamentoRecorrenteController::class, 'store'])->name('agendamentos-recorrentes.store');
    Route::patch('/agendamentos-recorrentes/{recorrente}/toggle', [AgendamentoRecorrenteController::class, 'toggle'])->name('agendamentos-recorrentes.toggle');
    Route::delete('/agendamentos-recorrentes/{recorrente}', [AgendamentoRecorrenteController::class, 'destroy'])->name('agendamentos-recorrentes.destroy');

    // Clube VIP (Assinaturas)
    Route::prefix('assinaturas')->name('assinaturas.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AssinaturaController::class, 'index'])->name('index');
        Route::post('/planos', [\App\Http\Controllers\AssinaturaController::class, 'storePlano'])->name('planos.store');
        Route::patch('/planos/{plano}/toggle', [\App\Http\Controllers\AssinaturaController::class, 'togglePlano'])->name('planos.toggle');
        Route::delete('/planos/{plano}', [\App\Http\Controllers\AssinaturaController::class, 'destroyPlano'])->name('planos.destroy');
        
        Route::post('/', [\App\Http\Controllers\AssinaturaController::class, 'storeAssinatura'])->name('store');
        Route::patch('/{assinatura}/toggle', [\App\Http\Controllers\AssinaturaController::class, 'toggleAssinatura'])->name('toggle');
        Route::delete('/{assinatura}', [\App\Http\Controllers\AssinaturaController::class, 'destroyAssinatura'])->name('destroy');
    });

    // Comandas
    Route::get('/comandas', [ComandaController::class, 'index'])->name('comandas');
    Route::post('/comandas', [ComandaController::class, 'store'])->name('comandas.store');
    Route::get('/comandas/{comanda}', [ComandaController::class, 'show'])->name('comandas.show');
    Route::post('/comandas/{comanda}/itens', [ComandaController::class, 'addItem'])->name('comandas.itens.store');
    Route::delete('/comandas/{comanda}/itens/{item}', [ComandaController::class, 'removeItem'])->name('comandas.itens.destroy');
    Route::post('/comandas/{comanda}/fechar', [ComandaController::class, 'close'])->name('comandas.fechar');

    // Financeiro
    Route::get('/financeiro', [FinanceiroController::class, 'index'])->name('financeiro.index');

    // WhatsApp
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/mensagens', function () { return view('panel.whatsapp.mensagens'); })->name('mensagens');
        Route::get('/recarregar-saldo', function () { return view('panel.whatsapp.recarregar-saldo'); })->name('recarregar-saldo');
    });

    // Contas
    Route::prefix('contas')->name('contas.')->group(function () {
        Route::get('/', [ContaController::class, 'index'])->name('todas');
        Route::post('/', [ContaController::class, 'store'])->name('store');
        Route::patch('/{conta}/pagar', [ContaController::class, 'pagar'])->name('pagar');
        Route::delete('/{conta}', [ContaController::class, 'destroy'])->name('destroy');
    });

    // Relatórios
    Route::get('/relatorios', [\App\Http\Controllers\RelatorioController::class, 'index'])->name('relatorios.index');


    // CRUD pages
    Route::get('/profissionais', [ProfissionalController::class, 'index'])->name('profissionais');
    Route::post('/profissionais', [ProfissionalController::class, 'store'])->name('profissionais.store');
    Route::put('/profissionais/{profissional}', [ProfissionalController::class, 'update'])->name('profissionais.update');
    Route::delete('/profissionais/{profissional}', [ProfissionalController::class, 'destroy'])->name('profissionais.destroy');
    Route::get('/profissionais/adicionar', [ProfissionalController::class, 'index'])->name('profissionais.adicionar');
    Route::post('/profissionais/{profissional}/gerar-codigo', [ProfissionalController::class, 'gerarCodigo'])->name('profissionais.gerar-codigo');


    Route::get('/servicos', [ServicoController::class, 'index'])->name('servicos');
    Route::post('/servicos', [ServicoController::class, 'store'])->name('servicos.store');
    Route::put('/servicos/{servico}', [ServicoController::class, 'update'])->name('servicos.update');
    Route::delete('/servicos/{servico}', [ServicoController::class, 'destroy'])->name('servicos.destroy');
    Route::get('/servicos/adicionar', [ServicoController::class, 'index'])->name('servicos.adicionar');
    Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos');
    Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');
    Route::get('/produtos/adicionar', [ProdutoController::class, 'index'])->name('produtos.adicionar');
    Route::get('/expedientes', [ExpedienteController::class, 'index'])->name('expedientes');
    Route::post('/expedientes', [ExpedienteController::class, 'store'])->name('expedientes.store');
    Route::get('/expedientes/adicionar', [ExpedienteController::class, 'index'])->name('expedientes.adicionar');
    Route::get('/bloquear-horarios', [BloqueioController::class, 'index'])->name('bloquear-horarios');
    Route::post('/bloquear-horarios', [BloqueioController::class, 'store'])->name('bloquear-horarios.store');
    Route::delete('/bloquear-horarios/{bloqueio}', [BloqueioController::class, 'destroy'])->name('bloquear-horarios.destroy');
    Route::get('/logs', [LogController::class, 'index'])->name('logs');
    Route::get('/logs/exportar', [LogController::class, 'export'])->name('logs.export');

    // Configurações
    Route::prefix('configuracoes')->name('configuracoes.')->group(function () {
        Route::get('/sistema', function () { return view('panel.configuracoes.sistema'); })->name('sistema');
        Route::get('/barbearia', function () { return view('panel.configuracoes.barbearia'); })->name('barbearia');
        Route::get('/agendamento', function () { return view('panel.configuracoes.agendamento'); })->name('agendamento');
        Route::get('/conta', function () { return view('panel.configuracoes.conta'); })->name('conta');
        Route::post('/conta/avatar', function (\Illuminate\Http\Request $request) {
            $request->validate(['foto_base64' => 'required|string|max:500000']);
            $foto = $request->foto_base64;
            if (!str_starts_with($foto, 'data:image/')) abort(422);
            auth()->user()->update(['foto' => $foto]);
            return back()->with('success', 'Avatar atualizado com sucesso!');
        })->name('conta.avatar');
    });

    Route::get('/meu-plano', function () { return view('panel.meu-plano'); })->name('meu-plano');
});

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('panel.dashboard')
        : redirect()->route('login');
});
