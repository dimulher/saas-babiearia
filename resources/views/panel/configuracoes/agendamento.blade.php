@extends('layouts.app')
@section('title', 'Configurações de Agendamento')

@section('content')
<div class="space-y-6 max-w-2xl">

    <h1 class="text-2xl font-bold text-gray-900">Configurações de Agendamento</h1>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Intervalo entre agendamentos (minutos)</label>
            <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option>15 minutos</option>
                <option selected>30 minutos</option>
                <option>45 minutos</option>
                <option>60 minutos</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Agendamento antecipado máximo (dias)</label>
            <input type="number" value="30" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Cancelamento mínimo (horas de antecedência)</label>
            <input type="number" value="2" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="space-y-3">
            <label class="flex items-center justify-between cursor-pointer" x-data="{ v: true }">
                <div>
                    <p class="text-sm font-medium text-gray-900">Confirmação automática</p>
                    <p class="text-xs text-gray-500">Agendamentos aprovados automaticamente</p>
                </div>
                <button @click="v = !v" :class="v ? 'bg-indigo-600' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0">
                    <span :class="v ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </label>
            <label class="flex items-center justify-between cursor-pointer" x-data="{ v: true }">
                <div>
                    <p class="text-sm font-medium text-gray-900">Lembrete por e-mail</p>
                    <p class="text-xs text-gray-500">Envia e-mail de lembrete 24h antes</p>
                </div>
                <button @click="v = !v" :class="v ? 'bg-indigo-600' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0">
                    <span :class="v ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </label>
            <label class="flex items-center justify-between cursor-pointer" x-data="{ v: false }">
                <div>
                    <p class="text-sm font-medium text-gray-900">Lembrete por WhatsApp</p>
                    <p class="text-xs text-gray-500">Requer plano Profissional ou superior</p>
                </div>
                <button @click="v = !v" :class="v ? 'bg-indigo-600' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0">
                    <span :class="v ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                </button>
            </label>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <button class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700">
                Salvar configurações
            </button>
        </div>

    </div>

</div>
@endsection
