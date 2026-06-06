@extends('layouts.app')
@section('title', 'Relatório de Serviços')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Relatório de Serviços</h1>
            <p class="text-xs text-gray-400 mt-0.5">Desempenho por serviço no período</p>
        </div>
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

    {{-- Cards resumo --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-500 font-medium uppercase">Atendimentos</p>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-scissors text-blue-600 text-xs"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900">{{ $totalAtendimentos }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-500 font-medium uppercase">Receita Total</p>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-dollar-sign text-green-600 text-xs"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-green-700">R$ {{ number_format($receitaTotal, 2, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-500 font-medium uppercase">Ticket Médio</p>
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-green-600 text-xs"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-gray-900">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</p>
            @if($maisVendido !== '—')
            <p class="text-xs text-gray-400 mt-1">Mais vendido: <span class="text-gray-600 font-medium">{{ $maisVendido }}</span></p>
            @endif
        </div>
    </div>

    {{-- Tabela por serviço --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-bar text-[#E2C28A]"></i> Desempenho por Serviço
            </h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Serviço</th>
                    <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Qtd.</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">Receita</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">Ticket Médio</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">% Total</th>
                    <th class="py-3 px-4 w-32"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($porServico as $item)
                @php
                    $pct = $receitaTotal > 0 ? round(($item->receita / $receitaTotal) * 100) : 0;
                    $ticket = $item->qtd > 0 ? $item->receita / $item->qtd : 0;
                @endphp
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            @if($item->servico)
                            <div class="w-3 h-6 rounded-full shrink-0" style="background-color: {{ $item->servico->cor ?? '#6366f1' }}"></div>
                            <span class="font-medium text-gray-800">{{ $item->servico->nome }}</span>
                            @else
                            <span class="text-gray-400">Serviço removido</span>
                            @endif
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center font-semibold text-gray-700">{{ $item->qtd }}</td>
                    <td class="py-3 px-4 text-right font-semibold text-green-600">R$ {{ number_format($item->receita, 2, ',', '.') }}</td>
                    <td class="py-3 px-4 text-right text-gray-600">R$ {{ number_format($ticket, 2, ',', '.') }}</td>
                    <td class="py-3 px-4 text-right text-gray-500">{{ $pct }}%</td>
                    <td class="py-3 px-4">
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-[#0A1B3D] h-2 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-gray-400">
                        <i class="fa-solid fa-chart-bar text-3xl mb-2 block text-gray-300"></i>
                        Nenhum atendimento no período selecionado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
