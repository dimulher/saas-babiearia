@extends('layouts.app')
@section('title', 'Agendamentos Recorrentes')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Agendamentos Recorrentes</h1>
        <button class="flex items-center gap-2 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800">
            <i class="fa-solid fa-plus text-xs"></i> Novo Recorrente
        </button>
    </div>

    <p class="text-sm text-gray-500">Gerencie clientes com agendamentos periódicos fixos.</p>

    <!-- Filtros -->
    <div class="bg-white rounded-2xl border border-gray-200 p-4 flex flex-wrap gap-3">
        <input type="text" placeholder="Pesquisar por cliente..." class="border border-gray-200 rounded-xl px-3 py-2 text-sm w-60 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todos os profissionais</option>
        </select>
        <select class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option>Todos os status</option>
            <option>Ativo</option>
            <option>Inativo</option>
        </select>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Cliente</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Profissional</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Serviço</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Periodicidade</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Próximo</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Status</th>
                    <th class="py-3 px-4 w-24"></th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="7" class="py-12 text-center text-gray-400 text-sm">Nenhum agendamento recorrente cadastrado.</td></tr>
            </tbody>
        </table>
    </div>

</div>
@endsection
