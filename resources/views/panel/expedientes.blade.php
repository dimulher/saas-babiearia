@extends('layouts.app')
@section('title', 'Expedientes')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-5">
        <h1 class="text-lg font-semibold text-gray-800">Expedientes</h1>
        <a href="/panel/expedientes/adicionar" class="bg-gray-900 hover:bg-gray-800 text-white text-sm px-4 py-2 rounded-lg font-medium">ADICIONAR</a>
    </div>
    <div class="flex items-center justify-between mb-4">
        <input type="text" placeholder="Pesquisar" class="border border-gray-200 rounded-lg px-3 py-2 text-sm w-60 focus:outline-none focus:ring-2 focus:ring-indigo-300">
        <div class="flex items-center gap-2">
            <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white"><option>Colunas</option></select>
            <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white"><option>10</option></select>
        </div>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide py-3 px-2">Nome</th>
                <th class="py-3 px-2 w-32"></th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-3 px-2 text-sm text-gray-700">Expediente Padrão — Barbearia do gabriel</td>
                <td class="py-3 px-2">
                    <div class="flex items-center gap-1 justify-end">
                        <button class="w-8 h-8 bg-gray-800 hover:bg-gray-700 text-white rounded-lg flex items-center justify-center"><i class="fa-solid fa-eye text-xs"></i></button>
                        <button class="w-8 h-8 bg-gray-800 hover:bg-gray-700 text-white rounded-lg flex items-center justify-center"><i class="fa-solid fa-pen text-xs"></i></button>
                        <button class="w-8 h-8 bg-gray-800 hover:bg-gray-700 text-white rounded-lg flex items-center justify-center"><i class="fa-solid fa-trash text-xs"></i></button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <p class="text-xs text-gray-400 mt-3">Exibindo 1 resultados</p>
</div>
@endsection
