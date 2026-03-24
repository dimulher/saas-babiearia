@extends('layouts.app')
@section('title', 'Agendamentos')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Agendamentos</h1>
        <button onclick="document.getElementById('modal-novo-agendamento').classList.remove('hidden')"
            class="flex items-center gap-2 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Agendamento
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3">
        <input type="date" value="{{ now()->format('Y-m-d') }}"
            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todos profissionais</option>
        </select>
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">Todos os status</option>
            <option value="pendente">Pendente</option>
            <option value="confirmado">Confirmado</option>
            <option value="concluido">Concluído</option>
            <option value="cancelado">Cancelado</option>
            <option value="faltou">Faltou</option>
        </select>
    </div>

    <!-- Calendário semanal + lista -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">

        <!-- Navegação semanal -->
        <div class="flex items-center justify-between mb-6">
            <button class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="flex gap-2">
                @php $inicioSemana = now()->startOfWeek(); @endphp
                @for($i = 0; $i < 7; $i++)
                    @php $dia = $inicioSemana->copy()->addDays($i); @endphp
                    <div class="flex flex-col items-center w-14 py-2 px-1 rounded-xl cursor-pointer {{ $dia->isToday() ? 'bg-indigo-600 text-white' : 'hover:bg-gray-50 text-gray-600' }}">
                        <span class="text-xs font-medium">{{ strtoupper($dia->locale('pt_BR')->isoFormat('ddd')) }}</span>
                        <span class="text-lg font-bold">{{ $dia->day }}</span>
                    </div>
                @endfor
            </div>
            <button class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>

        <!-- Estado vazio -->
        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p class="text-base font-medium text-gray-500">Nenhum agendamento para este dia</p>
            <p class="text-sm text-gray-400 mt-1">Clique em "Novo Agendamento" para começar</p>
        </div>
    </div>

</div>

<!-- Modal Novo Agendamento -->
<div id="modal-novo-agendamento" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-gray-900">Novo Agendamento</h2>
            <button onclick="document.getElementById('modal-novo-agendamento').classList.add('hidden')"
                class="p-1 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                <input type="text" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Nome do cliente">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Profissional</label>
                <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option>Selecione um profissional</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Serviço</label>
                <select class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option>Selecione um serviço</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                    <input type="date" value="{{ now()->format('Y-m-d') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Horário</label>
                    <input type="time" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                <textarea rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Opcional..."></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-novo-agendamento').classList.add('hidden')"
                    class="flex-1 border border-gray-200 text-gray-700 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-2.5 rounded-xl text-sm font-medium hover:bg-indigo-700">
                    Salvar Agendamento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
