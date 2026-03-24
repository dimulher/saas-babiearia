@extends('layouts.app')
@section('title', 'Financeiro - Visão Geral')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">Financeiro — Visão Geral</h1>

    <!-- Cards resumo -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
            <p class="text-sm text-green-600 font-medium">Receitas do Mês</p>
            <p class="text-3xl font-black text-green-700 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <p class="text-sm text-red-600 font-medium">Despesas do Mês</p>
            <p class="text-3xl font-black text-red-700 mt-1">R$ 0,00</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <p class="text-sm text-blue-600 font-medium">Lucro do Mês</p>
            <p class="text-3xl font-black text-blue-700 mt-1">R$ 0,00</p>
        </div>
    </div>

    <!-- Gráfico placeholder -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Fluxo de Caixa</h2>
        <div class="h-48 flex items-center justify-center text-gray-400 bg-gray-50 rounded-xl">
            <p class="text-sm">Gráfico de fluxo de caixa (sem dados ainda)</p>
        </div>
    </div>

</div>
@endsection
