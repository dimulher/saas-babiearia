@extends('funcionario.layout')
@section('title', 'Minhas Comissões')

@section('content')
<div class="space-y-4">

    <div class="bg-[#0A1B3D] rounded-xl p-4 text-white">
        <p class="text-sm text-blue-300">{{ now()->isoFormat('MMMM [de] YYYY') }}</p>
        <h2 class="text-xl font-bold mt-1">Minhas Comissões</h2>
        @if($profissional)
        <p class="text-sm text-blue-200 mt-1">{{ $profissional->comissao_percentual }}% por atendimento concluído</p>
        @endif
    </div>

    {{-- Total do mês --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 text-center">
        <p class="text-sm text-gray-500 mb-1">Total do Mês</p>
        <p class="text-4xl font-black text-[#0A1B3D]">R$ {{ number_format($totalComissao, 2, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $agendamentos->count() }} atendimentos concluídos</p>
    </div>

    {{-- Lista --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <h3 class="font-semibold text-gray-800 mb-3 text-sm">Atendimentos Concluídos</h3>

        @forelse($agendamentos as $ag)
        <div class="flex items-center justify-between py-2.5 border-b border-gray-100 last:border-0">
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $ag->servico->nome }}</p>
                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($ag->data_inicio)->format('d/m/Y H:i') }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">R$ {{ number_format($ag->preco, 2, ',', '.') }}</p>
                @if($profissional)
                <p class="text-sm font-bold text-green-600">
                    + R$ {{ number_format($ag->preco * ($profissional->comissao_percentual / 100), 2, ',', '.') }}
                </p>
                @endif
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center py-8 text-gray-400">
            <i class="fa-solid fa-coins text-3xl mb-2 text-gray-300"></i>
            <p class="text-sm">Nenhum atendimento concluído este mês</p>
        </div>
        @endforelse
    </div>

</div>
@endsection
