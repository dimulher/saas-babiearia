@extends('layouts.app')
@section('title', 'Comandas')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Comandas</h1>
        <button class="flex items-center gap-2 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nova Comanda
        </button>
    </div>

    <!-- Tabs status -->
    <div class="flex gap-2" x-data="{ tab: 'abertas' }">
        <button @click="tab = 'abertas'" :class="tab === 'abertas' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
            class="px-4 py-2 rounded-xl text-sm font-medium transition-colors">Abertas</button>
        <button @click="tab = 'fechadas'" :class="tab === 'fechadas' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
            class="px-4 py-2 rounded-xl text-sm font-medium transition-colors">Fechadas</button>
    </div>

    <!-- Lista vazia -->
    <div class="bg-white rounded-2xl border border-gray-200 p-12 flex flex-col items-center justify-center text-gray-400">
        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <p class="text-base font-medium text-gray-500">Nenhuma comanda aberta</p>
        <p class="text-sm text-gray-400 mt-1">As comandas abertas aparecerão aqui</p>
    </div>

</div>
@endsection
