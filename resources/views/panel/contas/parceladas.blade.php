@extends('layouts.app')
@section('title', 'Contas Parceladas')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Contas</h1>
        <button class="flex items-center gap-2 bg-gray-900 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-800">
            <i class="fa-solid fa-plus text-xs"></i> Nova Conta
        </button>
    </div>

    <!-- Tabs -->
    <div class="overflow-x-auto no-scrollbar -mx-4 sm:mx-0 px-4 sm:px-0">
        <div class="flex gap-1 bg-gray-100 rounded-xl p-1 w-max min-w-full">
            <a href="/panel/contas" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Todas</a>
            <a href="/panel/contas/parceladas" class="px-4 py-2 rounded-lg text-sm font-medium bg-white text-gray-900 shadow-sm whitespace-nowrap">Parceladas</a>
            <a href="/panel/contas/recorrentes" class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 whitespace-nowrap">Recorrentes</a>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Descrição</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Parcelas</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Próximo Venc.</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Status</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Valor Parcela</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-4">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="6" class="py-12 text-center text-gray-400 text-sm">Nenhuma conta parcelada cadastrada.</td></tr>
            </tbody>
        </table>
        </div>
    </div>

</div>
@endsection
