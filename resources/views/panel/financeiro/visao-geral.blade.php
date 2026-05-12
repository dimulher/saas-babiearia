@extends('layouts.app')
@section('title', 'Financeiro — Visão Geral')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Visão Geral Financeira</h1>
            <p class="text-xs text-gray-400 mt-0.5">{{ now()->isoFormat('MMMM [de] YYYY') }}</p>
        </div>
        <a href="{{ route('panel.contas.todas') }}" class="text-sm text-blue-600 hover:underline">
            Ver todas as contas →
        </a>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-green-50 border border-green-100 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-green-600 font-medium">Receitas do Mês</p>
                <i class="fa-solid fa-arrow-trend-up text-green-400"></i>
            </div>
            <p class="text-3xl font-black text-green-700">R$ {{ number_format($receitas, 2, ',', '.') }}</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-red-600 font-medium">Despesas do Mês</p>
                <i class="fa-solid fa-arrow-trend-down text-red-400"></i>
            </div>
            <p class="text-3xl font-black text-red-700">R$ {{ number_format($despesas, 2, ',', '.') }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-sm text-blue-600 font-medium">Lucro do Mês</p>
                <i class="fa-solid fa-scale-balanced text-blue-400"></i>
            </div>
            <p class="text-3xl font-black {{ $lucro >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                R$ {{ number_format($lucro, 2, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Alertas --}}
    @if($contasVencidas > 0)
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
        <p class="text-sm text-red-700">
            Você tem <strong>{{ $contasVencidas }} conta(s) vencida(s)</strong>.
            <a href="{{ route('panel.contas.todas') }}?status=pendente" class="underline font-medium">Ver agora →</a>
        </p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Gráfico de barras (últimos 6 meses) --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-bar text-[#E2C28A]"></i> Fluxo dos Últimos 6 Meses
            </h2>
            <div class="flex items-end gap-2 h-36">
                @foreach($grafico as $g)
                @php
                    $maxVal = max(collect($grafico)->max('receitas'), collect($grafico)->max('despesas'), 1);
                    $hR = max(4, round(($g['receitas'] / $maxVal) * 100));
                    $hD = max(4, round(($g['despesas'] / $maxVal) * 100));
                @endphp
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full flex gap-0.5 items-end" style="height: 100px">
                        <div class="flex-1 bg-green-400 rounded-t" style="height: {{ $hR }}%"
                             title="Receitas: R$ {{ number_format($g['receitas'],2,',','.') }}"></div>
                        <div class="flex-1 bg-red-400 rounded-t" style="height: {{ $hD }}%"
                             title="Despesas: R$ {{ number_format($g['despesas'],2,',','.') }}"></div>
                    </div>
                    <span class="text-xs text-gray-400">{{ $g['mes'] }}</span>
                </div>
                @endforeach
            </div>
            <div class="flex items-center gap-4 mt-3">
                <span class="flex items-center gap-1 text-xs text-gray-500"><span class="w-3 h-3 bg-green-400 rounded-sm inline-block"></span> Receitas</span>
                <span class="flex items-center gap-1 text-xs text-gray-500"><span class="w-3 h-3 bg-red-400 rounded-sm inline-block"></span> Despesas</span>
            </div>
        </div>

        {{-- Últimas movimentações --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-[#E2C28A]"></i> Últimas Movimentações
            </h2>
            <div class="space-y-0">
                @forelse($ultimasContas as $c)
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $c->tipo === 'receita' ? 'bg-green-100' : 'bg-red-100' }}">
                            <i class="fa-solid {{ $c->tipo === 'receita' ? 'fa-arrow-down text-green-600' : 'fa-arrow-up text-red-600' }} text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ Str::limit($c->descricao, 30) }}</p>
                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($c->vencimento)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold {{ $c->tipo === 'receita' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $c->tipo === 'receita' ? '+' : '-' }} R$ {{ number_format($c->valor, 2, ',', '.') }}
                    </span>
                </div>
                @empty
                <p class="text-sm text-gray-400 py-6 text-center">Nenhuma movimentação este mês.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
