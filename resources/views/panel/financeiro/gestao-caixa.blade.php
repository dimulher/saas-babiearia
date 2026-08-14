@extends('layouts.app')
@section('title', 'Gestão de Caixa')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Gestão de Caixa</h1>
            <p class="text-xs text-gray-400 mt-0.5">{{ now()->isoFormat('MMMM [de] YYYY') }}</p>
        </div>
        <a href="{{ route('panel.contas.store') }}" onclick="event.preventDefault(); document.getElementById('modal-entrada').classList.remove('hidden')"
            class="bg-[#0A1B3D] hover:bg-[#122550] text-white text-sm px-4 py-2 rounded-lg font-medium flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Lançamento
        </a>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <p class="text-xs text-gray-500 font-medium">Saldo do Mês</p>
            <p class="text-2xl font-black {{ $saldo >= 0 ? 'text-gray-900' : 'text-red-600' }} mt-1">
                R$ {{ number_format($saldo, 2, ',', '.') }}
            </p>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-xl p-5">
            <p class="text-xs text-green-600 font-medium">Entradas (pagas)</p>
            <p class="text-2xl font-black text-green-700 mt-1">R$ {{ number_format($entradas, 2, ',', '.') }}</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-xl p-5">
            <p class="text-xs text-red-600 font-medium">Saídas (pagas)</p>
            <p class="text-2xl font-black text-red-700 mt-1">R$ {{ number_format($saidas, 2, ',', '.') }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
            <p class="text-xs text-blue-600 font-medium">Movimentações</p>
            <p class="text-2xl font-black text-blue-700 mt-1">{{ $movimentacoes->total() }}</p>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Descrição</th>
                    <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Vencimento</th>
                    <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Status</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">Valor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimentacoes as $c)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $c->tipo === 'receita' ? 'bg-green-100' : 'bg-red-100' }}">
                                <i class="fa-solid {{ $c->tipo === 'receita' ? 'fa-arrow-down text-green-600' : 'fa-arrow-up text-red-600' }} text-xs"></i>
                            </div>
                            <p class="font-medium text-gray-800">{{ $c->descricao }}</p>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-center text-xs text-gray-500">
                        {{ \Carbon\Carbon::parse($c->vencimento)->format('d/m/Y') }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $c->status === 'pago' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($c->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right font-semibold {{ $c->tipo === 'receita' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $c->tipo === 'receita' ? '+' : '-' }} R$ {{ number_format($c->valor, 2, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-12 text-center text-gray-400">Nenhuma movimentação este mês.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        @if($movimentacoes->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $movimentacoes->links() }}</div>
        @endif
    </div>
</div>
@endsection
