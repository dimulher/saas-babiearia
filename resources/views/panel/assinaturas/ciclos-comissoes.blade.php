@extends('layouts.app')
@section('title', 'Ciclos de Comissões')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-white">Ciclos de Comissões</h1>
        <button class="flex items-center gap-2 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800">
            <i class="fa-solid fa-plus text-xs"></i> Novo Ciclo
        </button>
    </div>

    <p class="text-sm text-gray-500">Defina os ciclos de pagamento de comissão para seus profissionais.</p>

    <!-- Tabela -->
    <div class="bg-gray-900/50 rounded-2xl border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-700 bg-gray-800/50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Nome do Ciclo</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Periodicidade</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Profissionais</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Status</th>
                    <th class="py-3 px-4 w-24"></th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="5" class="py-12 text-center text-gray-400 text-sm">Nenhum ciclo cadastrado. Clique em "Novo Ciclo" para começar.</td></tr>
            </tbody>
        </table>
        </div>
    </div>

</div>
@endsection
