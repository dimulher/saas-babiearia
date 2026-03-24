@extends('layouts.app')
@section('title', 'Logs de Atividades')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Logs de Atividades</h1>
        <button class="flex items-center gap-2 border border-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50">
            <i class="fa-solid fa-download text-xs"></i> Exportar
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3">
        <input type="text" placeholder="Pesquisar por ação ou usuário..." class="border border-gray-200 rounded-xl px-3 py-2 text-sm w-72 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todas as ações</option>
            <option>Agendamento criado</option>
            <option>Agendamento cancelado</option>
            <option>Cliente cadastrado</option>
            <option>Serviço alterado</option>
            <option>Login realizado</option>
        </select>
        <input type="date" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Data/Hora</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Usuário</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Ação</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Detalhes</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">IP</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="5" class="py-12 text-center text-gray-400 text-sm">Nenhuma atividade registrada ainda.</td></tr>
            </tbody>
        </table>
    </div>
    <p class="text-xs text-gray-400">Exibindo 0 resultados</p>

</div>
@endsection
