@extends('layouts.app')
@section('title', 'Gerenciar Assinatura')

@section('content')
<div class="space-y-6 max-w-2xl">

    <h1 class="text-2xl font-bold text-white">Gerenciar Assinatura</h1>

    <!-- Plano atual -->
    <div class="bg-gray-900/50 border border-gray-700 rounded-2xl p-6 space-y-4">
        <div class="flex items-start justify-between">
            <div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 mb-2">Ativo</span>
                <h2 class="text-xl font-bold text-white">Plano Gratuito</h2>
                <p class="text-sm text-gray-500 mt-1">Renovação automática desabilitada</p>
            </div>
            <span class="text-2xl font-bold text-white">R$ 0<span class="text-sm font-normal text-gray-500">/mês</span></span>
        </div>
        <div class="flex gap-3 pt-2">
            <a href="/panel/assinaturas/planos" class="flex-1 bg-green-500 text-white py-2.5 rounded-xl text-sm font-semibold text-center hover:bg-indigo-700">
                Fazer Upgrade
            </a>
        </div>
    </div>

    <!-- Histórico de faturas -->
    <div class="bg-gray-900/50 border border-gray-700 rounded-2xl p-6">
        <h2 class="text-base font-semibold text-white mb-4">Histórico de Faturas</h2>
        <div class="text-center py-8 text-gray-400 text-sm">Nenhuma fatura disponível.</div>
    </div>

    <!-- Forma de pagamento -->
    <div class="bg-gray-900/50 border border-gray-700 rounded-2xl p-6">
        <h2 class="text-base font-semibold text-white mb-4">Forma de Pagamento</h2>
        <div class="flex items-center gap-4 p-4 bg-gray-800/50 rounded-xl">
            <i class="fa-solid fa-credit-card text-gray-400 text-xl"></i>
            <div>
                <p class="text-sm font-medium text-gray-300">Nenhum cartão cadastrado</p>
                <p class="text-xs text-gray-500">Adicione um cartão para assinar um plano pago</p>
            </div>
            <button class="ml-auto text-sm text-green-500 font-medium hover:text-green-700">Adicionar</button>
        </div>
    </div>

</div>
@endsection
