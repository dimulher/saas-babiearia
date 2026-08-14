@extends('layouts.app')
@section('title', 'Relatório de Clientes')

@section('content')
<div class="space-y-5">

    <div>
        <h1 class="text-lg font-semibold text-gray-800">Relatório de Clientes</h1>
        <p class="text-xs text-gray-400 mt-0.5">Análise da base de clientes no período</p>
    </div>

    {{-- Filtro de período --}}
    <form method="GET" class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl p-4">
        <label class="text-xs font-medium text-gray-600">Período:</label>
        <select name="mes" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            @foreach(range(1,12) as $m)
            <option value="{{ $m }}" {{ $m == $mes ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
            </option>
            @endforeach
        </select>
        <select name="ano" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            @foreach([now()->year - 1, now()->year] as $a)
            <option value="{{ $a }}" {{ $a == $ano ? 'selected' : '' }}>{{ $a }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-[#0A1B3D] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#122550]">
            <i class="fa-solid fa-filter mr-1"></i> Filtrar
        </button>
    </form>

    {{-- Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-500 font-medium uppercase">Total</p>
                <i class="fa-solid fa-users text-gray-400 text-sm"></i>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $totalClientes }}</p>
            <p class="text-xs text-gray-400 mt-1">clientes cadastrados</p>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-green-600 font-medium uppercase">Novos</p>
                <i class="fa-solid fa-user-plus text-green-400 text-sm"></i>
            </div>
            <p class="text-3xl font-black text-green-700">{{ $novosClientes }}</p>
            <p class="text-xs text-green-500 mt-1">no período</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-blue-600 font-medium uppercase">Recorrentes</p>
                <i class="fa-solid fa-rotate text-blue-400 text-sm"></i>
            </div>
            <p class="text-3xl font-black text-blue-700">{{ $recorrentes }}</p>
            <p class="text-xs text-blue-500 mt-1">vieram 2x ou mais</p>
        </div>
        <div class="bg-purple-50 border border-purple-100 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-green-600 font-medium uppercase">Retorno</p>
                <i class="fa-solid fa-chart-pie text-purple-400 text-sm"></i>
            </div>
            <p class="text-3xl font-black text-purple-700">{{ $taxaRetorno }}%</p>
            <p class="text-xs text-purple-500 mt-1">taxa de retorno</p>
        </div>
    </div>

    {{-- Top clientes --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-5 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-trophy text-[#E2C28A]"></i> Top Clientes do Período
            </h2>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">#</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Cliente</th>
                    <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Visitas</th>
                    <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Última Visita</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">Total Gasto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topClientes as $i => $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">
                        @if($i < 3)
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $i === 0 ? 'bg-yellow-100 text-yellow-700' : ($i === 1 ? 'bg-gray-200 text-gray-700' : 'bg-orange-100 text-orange-700') }}">
                            {{ $i + 1 }}
                        </span>
                        @else
                        <span class="text-gray-400 text-xs pl-1">{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-[#E2C28A] rounded-full flex items-center justify-center text-[#0A1B3D] text-xs font-bold shrink-0">
                                {{ strtoupper(substr($item->cliente->nome ?? 'C', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $item->cliente?->nome ?? 'Cliente removido' }}</p>
                                @if($item->cliente?->telefone)
                                <p class="text-xs text-gray-400">{{ $item->cliente->telefone }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center font-semibold text-gray-700">{{ $item->visitas }}</td>
                    <td class="py-3 px-4 text-center text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($item->ultima_visita)->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-4 text-right font-bold text-green-600">
                        R$ {{ number_format($item->total_gasto, 2, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-400">
                        <i class="fa-solid fa-users text-3xl mb-2 block text-gray-300"></i>
                        Nenhum atendimento no período selecionado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

</div>
@endsection
