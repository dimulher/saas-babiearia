@extends('layouts.app')
@section('title', 'Meu Plano')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">Meu Plano</h1>

    <!-- Plano atual -->
    <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-6 flex items-center justify-between">
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 mb-2">Plano Atual</span>
            <h2 class="text-2xl font-black text-indigo-900">Gratuito</h2>
            <p class="text-sm text-indigo-600 mt-1">Recursos básicos para começar</p>
        </div>
        <div class="text-right">
            <p class="text-3xl font-black text-indigo-900">R$ 0</p>
            <p class="text-xs text-indigo-600">/mês</p>
        </div>
    </div>

    <!-- Planos disponíveis -->
    <h2 class="text-lg font-bold text-gray-900">Fazer upgrade</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Básico</h3>
                <p class="text-3xl font-black text-gray-900 mt-1">R$ 49<span class="text-sm font-normal text-gray-500">/mês</span></p>
            </div>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Agendamentos ilimitados</li>
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Até 3 profissionais</li>
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Link de agendamento</li>
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> WhatsApp automático</li>
            </ul>
            <button class="w-full border border-indigo-600 text-indigo-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-50">Assinar Básico</button>
        </div>

        <div class="bg-indigo-600 border border-indigo-600 rounded-2xl p-6 space-y-4 relative">
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full">Mais popular</span>
            <div>
                <h3 class="text-lg font-bold text-white">Profissional</h3>
                <p class="text-3xl font-black text-white mt-1">R$ 89<span class="text-sm font-normal text-indigo-200">/mês</span></p>
            </div>
            <ul class="space-y-2 text-sm text-indigo-100">
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Agendamentos ilimitados</li>
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Profissionais ilimitados</li>
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> WhatsApp automático</li>
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Relatórios completos</li>
            </ul>
            <button class="w-full bg-white text-indigo-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-50">Assinar Profissional</button>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Premium</h3>
                <p class="text-3xl font-black text-gray-900 mt-1">R$ 149<span class="text-sm font-normal text-gray-500">/mês</span></p>
            </div>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Tudo do Profissional</li>
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Múltiplas unidades</li>
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Suporte prioritário</li>
                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> API de integração</li>
            </ul>
            <button class="w-full border border-indigo-600 text-indigo-600 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-50">Assinar Premium</button>
        </div>

    </div>

</div>
@endsection
