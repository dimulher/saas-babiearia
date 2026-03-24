@extends('layouts.app')
@section('title', 'Planos de Assinatura')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">Planos de Assinatura</h1>

    <!-- Toggle mensal/anual -->
    <div class="flex items-center justify-center" x-data="{ anual: false }">
        <div class="flex items-center gap-4 bg-white border border-gray-200 rounded-2xl p-1.5">
            <button @click="anual = false" :class="!anual ? 'bg-gray-900 text-white' : 'text-gray-600'" class="px-5 py-2 rounded-xl text-sm font-medium transition-colors">Mensal</button>
            <button @click="anual = true" :class="anual ? 'bg-gray-900 text-white' : 'text-gray-600'" class="px-5 py-2 rounded-xl text-sm font-medium transition-colors">
                Anual <span class="ml-1 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">-20%</span>
            </button>
        </div>
    </div>

    <!-- Cards de planos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto" x-data="{ anual: false }">

        <!-- Gratuito -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-5">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Gratuito</h3>
                <p class="text-sm text-gray-500 mt-1">Para quem está começando</p>
                <p class="text-4xl font-black text-gray-900 mt-3">R$ 0<span class="text-base font-normal text-gray-500">/mês</span></p>
            </div>
            <ul class="space-y-2.5 text-sm">
                <li class="flex items-start gap-2 text-gray-600"><i class="fa-solid fa-check text-green-500 mt-0.5"></i> Até 50 agendamentos/mês</li>
                <li class="flex items-start gap-2 text-gray-600"><i class="fa-solid fa-check text-green-500 mt-0.5"></i> 1 profissional</li>
                <li class="flex items-start gap-2 text-gray-600"><i class="fa-solid fa-check text-green-500 mt-0.5"></i> Link de agendamento</li>
                <li class="flex items-start gap-2 text-gray-400"><i class="fa-solid fa-xmark text-red-400 mt-0.5"></i> WhatsApp automático</li>
                <li class="flex items-start gap-2 text-gray-400"><i class="fa-solid fa-xmark text-red-400 mt-0.5"></i> Relatórios avançados</li>
            </ul>
            <button class="w-full border-2 border-gray-200 text-gray-500 py-2.5 rounded-xl text-sm font-semibold cursor-default">
                Plano atual
            </button>
        </div>

        <!-- Profissional (destaque) -->
        <div class="bg-indigo-600 border border-indigo-600 rounded-2xl p-6 space-y-5 relative">
            <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-xs font-bold px-4 py-1.5 rounded-full whitespace-nowrap">Mais popular</span>
            <div>
                <h3 class="text-lg font-bold text-white">Profissional</h3>
                <p class="text-sm text-indigo-200 mt-1">Para barbearias em crescimento</p>
                <p class="text-4xl font-black text-white mt-3">R$ 89<span class="text-base font-normal text-indigo-200">/mês</span></p>
            </div>
            <ul class="space-y-2.5 text-sm">
                <li class="flex items-start gap-2 text-indigo-100"><i class="fa-solid fa-check text-green-300 mt-0.5"></i> Agendamentos ilimitados</li>
                <li class="flex items-start gap-2 text-indigo-100"><i class="fa-solid fa-check text-green-300 mt-0.5"></i> Profissionais ilimitados</li>
                <li class="flex items-start gap-2 text-indigo-100"><i class="fa-solid fa-check text-green-300 mt-0.5"></i> WhatsApp automático</li>
                <li class="flex items-start gap-2 text-indigo-100"><i class="fa-solid fa-check text-green-300 mt-0.5"></i> Relatórios completos</li>
                <li class="flex items-start gap-2 text-indigo-100"><i class="fa-solid fa-check text-green-300 mt-0.5"></i> Comandas e Financeiro</li>
            </ul>
            <button class="w-full bg-white text-indigo-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-50">
                Assinar Profissional
            </button>
        </div>

        <!-- Premium -->
        <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-5">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Premium</h3>
                <p class="text-sm text-gray-500 mt-1">Para redes e grandes volumes</p>
                <p class="text-4xl font-black text-gray-900 mt-3">R$ 149<span class="text-base font-normal text-gray-500">/mês</span></p>
            </div>
            <ul class="space-y-2.5 text-sm">
                <li class="flex items-start gap-2 text-gray-600"><i class="fa-solid fa-check text-green-500 mt-0.5"></i> Tudo do Profissional</li>
                <li class="flex items-start gap-2 text-gray-600"><i class="fa-solid fa-check text-green-500 mt-0.5"></i> Múltiplas unidades</li>
                <li class="flex items-start gap-2 text-gray-600"><i class="fa-solid fa-check text-green-500 mt-0.5"></i> API de integração</li>
                <li class="flex items-start gap-2 text-gray-600"><i class="fa-solid fa-check text-green-500 mt-0.5"></i> Suporte prioritário</li>
                <li class="flex items-start gap-2 text-gray-600"><i class="fa-solid fa-check text-green-500 mt-0.5"></i> Domínio personalizado</li>
            </ul>
            <button class="w-full border-2 border-indigo-600 text-indigo-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-50">
                Assinar Premium
            </button>
        </div>

    </div>

</div>
@endsection
