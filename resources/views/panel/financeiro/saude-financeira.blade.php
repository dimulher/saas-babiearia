@extends('layouts.app')
@section('title', 'Saúde Financeira')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">Saúde Financeira</h1>

    <!-- Cards principais -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-500 font-medium">Receita Total</p>
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-arrow-trend-up text-green-600 text-xs"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-gray-900">R$ 0,00</p>
            <p class="text-xs text-gray-400 mt-1">Este mês</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-500 font-medium">Ticket Médio</p>
                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-receipt text-blue-600 text-xs"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-gray-900">R$ 0,00</p>
            <p class="text-xs text-gray-400 mt-1">Por agendamento</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-500 font-medium">Despesas</p>
                <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-arrow-trend-down text-red-600 text-xs"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-gray-900">R$ 0,00</p>
            <p class="text-xs text-gray-400 mt-1">Este mês</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-gray-500 font-medium">Margem de Lucro</p>
                <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-percent text-purple-600 text-xs"></i>
                </span>
            </div>
            <p class="text-2xl font-black text-gray-900">0%</p>
            <p class="text-xs text-gray-400 mt-1">Este mês</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Gráfico receita vs despesa -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Receita x Despesa (últimos 6 meses)</h2>
            <div class="h-48 flex items-center justify-center text-gray-400 bg-gray-50 rounded-xl">
                <p class="text-sm">Gráfico será exibido quando houver dados</p>
            </div>
        </div>

        <!-- Serviços mais rentáveis -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Serviços Mais Rentáveis</h2>
            <div class="flex flex-col items-center justify-center py-10 text-gray-400">
                <i class="fa-solid fa-scissors text-3xl mb-3"></i>
                <p class="text-sm">Nenhum dado disponível ainda</p>
            </div>
        </div>
    </div>

    <!-- Formas de pagamento -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Formas de Pagamento</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach(['Dinheiro', 'PIX', 'Cartão Débito', 'Cartão Crédito'] as $forma)
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-sm text-gray-500">{{ $forma }}</p>
                <p class="text-xl font-bold text-gray-900 mt-1">R$ 0,00</p>
                <p class="text-xs text-gray-400">0%</p>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
