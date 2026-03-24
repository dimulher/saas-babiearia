<?php

use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::post('/login', function () { return redirect('/panel/dashboard'); })->name('login.post');
Route::post('/register', function () { return redirect('/panel/dashboard'); })->name('register.post');
Route::post('/logout', function () { return redirect('/login'); })->name('logout');

// Página pública de agendamento
Route::get('/agendar/{slug?}', function () { return view('booking.index'); })->name('booking');

Route::prefix('panel')->name('panel.')->group(function () {

    Route::get('/dashboard', function () {
        return view('panel.dashboard');
    })->name('dashboard');

    // Agendamentos
    Route::get('/agendamentos', function () {
        return view('panel.agendamentos');
    })->name('agendamentos');

    Route::get('/agendamentos/recorrentes', function () {
        return view('panel.agendamentos-recorrentes');
    })->name('agendamentos.recorrentes');

    Route::get('/agendamentos-recorrentes', function () {
        return view('panel.agendamentos-recorrentes');
    })->name('agendamentos-recorrentes');

    // Comandas
    Route::get('/comandas', function () {
        return view('panel.comandas');
    })->name('comandas');

    // Financeiro
    Route::prefix('financeiro')->name('financeiro.')->group(function () {
        Route::get('/visao-geral', function () { return view('panel.financeiro.visao-geral'); })->name('visao-geral');
        Route::get('/gestao-caixa', function () { return view('panel.financeiro.gestao-caixa'); })->name('gestao-caixa');
        Route::get('/relatorios', function () { return view('panel.financeiro.relatorios'); })->name('relatorios');
        Route::get('/extrato', function () { return view('panel.financeiro.extrato'); })->name('extrato');
        Route::get('/saude-financeira', function () { return view('panel.financeiro.saude-financeira'); })->name('saude-financeira');
    });

    // WhatsApp
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/mensagens', function () { return view('panel.whatsapp.mensagens'); })->name('mensagens');
        Route::get('/recarregar-saldo', function () { return view('panel.whatsapp.recarregar-saldo'); })->name('recarregar-saldo');
    });

    // Contas
    Route::prefix('contas')->name('contas.')->group(function () {
        Route::get('/', function () { return view('panel.contas.index'); })->name('todas');
        Route::get('/parceladas', function () { return view('panel.contas.parceladas'); })->name('parceladas');
        Route::get('/recorrentes', function () { return view('panel.contas.recorrentes'); })->name('recorrentes');
    });

    // Relatórios
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/servicos', function () { return view('panel.relatorios.servicos'); })->name('servicos');
        Route::get('/clientes', function () { return view('panel.relatorios.clientes'); })->name('clientes');
        Route::get('/profissionais', function () { return view('panel.relatorios.profissionais'); })->name('profissionais');
    });

    // Assinaturas
    Route::prefix('assinaturas')->name('assinaturas.')->group(function () {
        Route::get('/planos', function () { return view('panel.assinaturas.planos'); })->name('planos');
        Route::get('/gerenciar', function () { return view('panel.assinaturas.gerenciar'); })->name('gerenciar');
        Route::get('/ciclos-comissoes', function () { return view('panel.assinaturas.ciclos-comissoes'); })->name('ciclos-comissoes');
        Route::get('/configuracoes', function () { return view('panel.assinaturas.configuracoes'); })->name('configuracoes');
    });

    // CRUD pages
    Route::get('/profissionais', function () { return view('panel.profissionais'); })->name('profissionais');
    Route::get('/profissionais/adicionar', function () { return view('panel.profissionais'); })->name('profissionais.adicionar');
    Route::get('/clientes', function () { return view('panel.clientes'); })->name('clientes');
    Route::get('/clientes/adicionar', function () { return view('panel.clientes'); })->name('clientes.adicionar');
    Route::get('/servicos', function () { return view('panel.servicos'); })->name('servicos');
    Route::get('/servicos/adicionar', function () { return view('panel.servicos'); })->name('servicos.adicionar');
    Route::get('/produtos', function () { return view('panel.produtos'); })->name('produtos');
    Route::get('/produtos/adicionar', function () { return view('panel.produtos'); })->name('produtos.adicionar');
    Route::get('/expedientes', function () { return view('panel.expedientes'); })->name('expedientes');
    Route::get('/expedientes/adicionar', function () { return view('panel.expedientes'); })->name('expedientes.adicionar');
    Route::get('/bloquear-horarios', function () { return view('panel.bloquear-horarios'); })->name('bloquear-horarios');
    Route::get('/logs', function () { return view('panel.logs'); })->name('logs');

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
