@extends('layouts.app')
@section('title', 'Relatório de Profissionais')

@section('content')
<div class="space-y-5">

    <div>
        <h1 class="text-lg font-semibold text-gray-800">Relatório de Profissionais</h1>
        <p class="text-xs text-gray-400 mt-0.5">Desempenho e comissões por profissional</p>
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
                    <i class="fa-solid fa-calendar-check text-blue-600 text-xs"></i>
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
                <p class="text-xs text-gray-500 font-medium uppercase">Melhor do Período</p>
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-trophy text-yellow-600 text-xs"></i>
                </div>
            </div>
            <p class="text-lg font-bold text-gray-900 mt-1 truncate">{{ $melhorProfissional }}</p>
        </div>
    </div>

    {{-- Tabela por profissional --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-5 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-ranking-star text-[#E2C28A]"></i> Ranking de Profissionais
            </h2>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">#</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Profissional</th>
                    <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Atendimentos</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">Receita</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">Ticket Médio</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">Comissão</th>
                    <th class="py-3 px-4 w-28"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($porProfissional as $i => $item)
                @php
                    $ticket    = $item->atendimentos > 0 ? $item->receita / $item->atendimentos : 0;
                    $comissao  = $item->profissional ? ($item->receita * $item->profissional->comissao_percentual / 100) : 0;
                    $pctTotal  = $receitaTotal > 0 ? round(($item->receita / $receitaTotal) * 100) : 0;
                @endphp
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $i === 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $i + 1 }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-[#E2C28A] rounded-full flex items-center justify-center text-[#0A1B3D] text-xs font-bold shrink-0">
                                {{ strtoupper(substr($item->profissional->nome ?? 'P', 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $item->profissional?->nome ?? 'Profissional removido' }}</p>
                                @if($item->profissional)
                                <p class="text-xs text-gray-400">{{ $item->profissional->comissao_percentual }}% comissão</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center font-semibold text-gray-700">{{ $item->atendimentos }}</td>
                    <td class="py-3 px-4 text-right font-semibold text-green-600">R$ {{ number_format($item->receita, 2, ',', '.') }}</td>
                    <td class="py-3 px-4 text-right text-gray-600">R$ {{ number_format($ticket, 2, ',', '.') }}</td>
                    <td class="py-3 px-4 text-right font-bold text-[#0A1B3D]">R$ {{ number_format($comissao, 2, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-1">
                            <div class="flex-1 bg-gray-100 rounded-full h-2">
                                <div class="bg-[#E2C28A] h-2 rounded-full" style="width: {{ $pctTotal }}%"></div>
                            </div>
                            <span class="text-xs text-gray-400 w-8 text-right">{{ $pctTotal }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-400">
                        <i class="fa-solid fa-user-slash text-3xl mb-2 block text-gray-300"></i>
                        Nenhum atendimento no período selecionado.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($porProfissional->isNotEmpty())
            <tfoot>
                <tr class="border-t-2 border-gray-200 bg-gray-50">
                    <td colspan="3" class="py-3 px-4 text-xs font-bold text-gray-600 uppercase">Total</td>
                    <td class="py-3 px-4 text-right font-black text-green-700">R$ {{ number_format($receitaTotal, 2, ',', '.') }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
            @endif
        </table>
        </div>
    </div>

</div>
@endsection
