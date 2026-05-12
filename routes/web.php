<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\ProfissionalController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\BloqueioController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AgendamentoRecorrenteController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\ComandaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\FuncionarioController;
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

// Rotas do Funcionário
Route::get('/funcionario/login', [FuncionarioController::class, 'showLogin'])->name('funcionario.login');
Route::post('/funcionario/login', [FuncionarioController::class, 'login'])->name('funcionario.login.post');
Route::get('/funcionario/dashboard', [FuncionarioController::class, 'dashboard'])->name('funcionario.dashboard');
Route::post('/funcionario/agendamentos/{agendamento}/finalizar', [FuncionarioController::class, 'finalizar'])->name('funcionario.agendamento.finalizar');
Route::post('/funcionario/logout', [FuncionarioController::class, 'logout'])->name('funcionario.logout');

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

// API para verificação de VIP no agendamento
Route::get('/api/check-vip', [AgendamentoController::class, 'checkVip'])->name('api.check-vip');



Route::prefix('panel')->name('panel.')->middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $hoje      = \Carbon\Carbon::today();
        $inicioMes = $hoje->copy()->startOfMonth();
        $fimMes    = $hoje->copy()->endOfMonth();
        
        $barbeariaId = auth()->user()->barbearia_id;
        $barbearia   = auth()->user()->barbearia;

        // Métricas de agendamentos
        $agendamentosHoje      = \App\Models\Agendamento::where('barbearia_id', $barbeariaId)->whereDate('data_inicio', $hoje)->count();
        $agendamentosMes       = \App\Models\Agendamento::where('barbearia_id', $barbeariaId)->whereBetween('data_inicio', [$inicioMes, $fimMes])->count();
        $agendamentosPendentes = \App\Models\Agendamento::where('barbearia_id', $barbeariaId)->where('status', 'agendado')->whereDate('data_inicio', '>=', $hoje)->count();

        // Faturamento
        $faturamentoHoje = \App\Models\Agendamento::where('barbearia_id', $barbeariaId)->whereDate('data_inicio', $hoje)
            ->where('status', 'concluido')->sum('preco');
        $faturamentoMes  = \App\Models\Agendamento::where('barbearia_id', $barbeariaId)->whereBetween('data_inicio', [$inicioMes, $fimMes])
            ->where('status', 'concluido')->sum('preco');
        $ticketMedio = $agendamentosMes > 0 ? $faturamentoMes / $agendamentosMes : 0;

        // Totais
        $clientesAtivos  = \App\Models\Cliente::where('barbearia_id', $barbeariaId)->count();
        $profissionais   = \App\Models\Profissional::where('barbearia_id', $barbeariaId)->count();
        $servicos        = \App\Models\Servico::where('barbearia_id', $barbeariaId)->where('ativo', true)->count();
        $profissionaisList = \App\Models\Profissional::where('barbearia_id', $barbeariaId)->where('ativo', true)
            ->where('aceita_agendamento_online', true)->get();

        $selectedDate = request('date') ? \Carbon\Carbon::parse(request('date')) : $hoje;
        $agendamentos = \App\Models\Agendamento::where('barbearia_id', $barbeariaId)
            ->with(['servico', 'profissional'])
            ->whereDate('data_inicio', $selectedDate)
            ->orderBy('data_inicio')
            ->get();

        return view('panel.dashboard', compact(
            'agendamentosHoje', 'agendamentosMes', 'agendamentosPendentes',
            'faturamentoHoje', 'faturamentoMes', 'ticketMedio',
            'clientesAtivos', 'profissionais', 'servicos',
            'profissionaisList', 'agendamentos', 'selectedDate', 'barbearia'
        ));
    })->name('dashboard');

    // Notificações
    Route::get('/notificacoes', [\App\Http\Controllers\NotificacaoController::class, 'index'])->name('notificacoes.index');
    Route::post('/notificacoes/{id}/read', [\App\Http\Controllers\NotificacaoController::class, 'markAsRead'])->name('notificacoes.read');
    Route::post('/notificacoes/read-all', [\App\Http\Controllers\NotificacaoController::class, 'markAllAsRead'])->name('notificacoes.readAll');
    Route::delete('/notificacoes/{id}', [\App\Http\Controllers\NotificacaoController::class, 'destroy'])->name('notificacoes.destroy');

    // Agendamentos
    Route::get('/agendamentos', [AgendamentoController::class, 'index'])->name('agendamentos');

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
    });

    Route::get('/meu-plano', function () { return view('panel.meu-plano'); })->name('meu-plano');
});

Route::get('/', function () {
    return redirect()->route('panel.dashboard');
});
