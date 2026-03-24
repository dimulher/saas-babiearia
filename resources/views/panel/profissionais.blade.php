@extends('layouts.app')
@section('title', 'Profissionais')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-lg font-semibold text-gray-800">Profissionais</h1>
        <a href="/panel/profissionais/adicionar" class="bg-gray-900 hover:bg-gray-800 text-white text-sm px-4 py-2 rounded-lg font-medium">ADICIONAR</a>
    </div>

    <div class="flex items-center justify-between mb-4">
        <input type="text" placeholder="Pesquisar" class="border border-gray-200 rounded-lg px-3 py-2 text-sm w-60 focus:outline-none focus:ring-2 focus:ring-indigo-300">
        <div class="flex items-center gap-2">
            <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white">
                <option>Colunas</option>
            </select>
            <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white">
                <option>10</option><option>25</option><option>50</option>
            </select>
        </div>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-2">Nome</th>
                <th class="py-3 px-2"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" class="py-12 text-center text-gray-400 text-sm">Nenhum resultado encontrado. Tente ampliar a sua pesquisa.</td>
            </tr>
        </tbody>
    </table>
    <p class="text-xs text-gray-400 mt-3">Exibindo 0 resultados</p>
</div>
@endsection
