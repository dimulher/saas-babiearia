@extends('layouts.funcionario')
@section('title', 'Agendamentos')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Agendamentos</h1>
            <p class="text-sm text-gray-400 font-medium">Todos os seus horários marcados.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 text-green-400 font-medium flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-check shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Filtros --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 flex flex-wrap items-center gap-3">
        {{-- Período --}}
        <form action="{{ route('funcionario.agendamentos') }}" method="GET" class="flex bg-[#0B0F19] p-1 rounded-xl border border-gray-800">
            <input type="hidden" name="status" value="{{ $status }}">
            @foreach(['hoje'=>'Hoje','semana'=>'Semana','mes'=>'Mês','todos'=>'Todos'] as $val => $label)
            <button type="submit" name="filtro" value="{{ $val }}"
                class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all {{ $filtro === $val ? 'bg-green-500 text-white' : 'text-gray-400 hover:text-white' }}">
                {{ $label }}
            </button>
            @endforeach
        </form>

        {{-- Status --}}
        <form action="{{ route('funcionario.agendamentos') }}" method="GET" class="flex flex-wrap gap-2 ml-auto">
            <input type="hidden" name="filtro" value="{{ $filtro }}">
            <button type="submit" name="status" value=""
                class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all {{ $status === '' ? 'bg-gray-700 text-white border-gray-600' : 'border-gray-800 text-gray-500 hover:border-gray-600 hover:text-white' }}">
                Todos
            </button>
            @foreach([
                'pendente'   => ['text-amber-400',   'border-amber-800/50',   'bg-amber-900/30'],
                'confirmado' => ['text-blue-400',     'border-blue-800/50',    'bg-blue-900/30'],
                'concluido'  => ['text-emerald-400',  'border-emerald-800/50', 'bg-emerald-900/30'],
                'cancelado'  => ['text-rose-400',     'border-rose-800/50',    'bg-rose-900/30'],
                'faltou'     => ['text-gray-500',     'border-gray-700',       'bg-gray-800'],
            ] as $s => [$tc, $bc, $bg])
            <button type="submit" name="status" value="{{ $s }}"
                class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border transition-all {{ $status === $s ? "$bg $tc $bc" : "border-gray-800 text-gray-500 hover:$bc hover:$tc" }}">
                {{ ['pendente'=>'Pendente','confirmado'=>'Confirmado','concluido'=>'Concluído','cancelado'=>'Cancelado','faltou'=>'Faltou'][$s] }}
            </button>
            @endforeach
        </form>
    </div>

    {{-- Lista --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
        @if($agendamentos->isEmpty())
            <div class="flex flex-col items-center justify-center py-16">
                <i class="fa-regular fa-calendar-xmark text-4xl text-gray-700 mb-4"></i>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Nenhum agendamento</p>
                <p class="text-xs text-gray-600 mt-1">Nenhum horário marcado para este período.</p>
            </div>
        @else
            <div class="divide-y divide-gray-800/50">
                @foreach($agendamentos as $ag)
                    <div class="flex items-center gap-3 sm:gap-4 px-4 py-3.5 hover:bg-gray-900/40 transition-colors">
                        {{-- Data/Hora --}}
                        <div class="text-center shrink-0 w-14">
                            <p class="text-[9px] font-black text-green-400 uppercase tracking-widest">
                                {{ \Carbon\Carbon::parse($ag->data_inicio)->translatedFormat('D') }}
                            </p>
                            <p class="text-sm font-black text-white">
                                {{ \Carbon\Carbon::parse($ag->data_inicio)->format('d/m') }}
                            </p>
                            <p class="text-[10px] font-bold text-gray-500">
                                {{ \Carbon\Carbon::parse($ag->data_inicio)->format('H:i') }}
                            </p>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-white truncate">{{ $ag->cliente_nome ?? 'Cliente' }}</p>
                            <p class="text-[10px] text-gray-500 truncate flex items-center gap-1.5 mt-0.5">
                                <i class="fa-solid fa-scissors text-[8px]"></i>
                                {{ $ag->servico?->nome ?? '—' }}
                                @if($ag->cliente_telefone)
                                    <span class="text-gray-700">·</span>
                                    <i class="fa-brands fa-whatsapp text-green-600 text-[8px]"></i>
                                    {{ $ag->cliente_telefone }}
                                @endif
                            </p>
                        </div>

                        {{-- Status + Ação --}}
                        <div class="shrink-0 flex flex-col items-end gap-2">
                            <span class="text-[9px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full
                                {{ $ag->status === 'pendente'   ? 'bg-amber-900/30 text-amber-400' : '' }}
                                {{ $ag->status === 'confirmado' ? 'bg-blue-900/30 text-blue-400' : '' }}
                                {{ $ag->status === 'concluido'  ? 'bg-emerald-900/30 text-emerald-400' : '' }}
                                {{ $ag->status === 'cancelado'  ? 'bg-rose-900/30 text-rose-400' : '' }}
                                {{ $ag->status === 'faltou'     ? 'bg-gray-800 text-gray-500' : '' }}">
                                {{ ['pendente'=>'Pendente','confirmado'=>'Confirmado','concluido'=>'Concluído','cancelado'=>'Cancelado','faltou'=>'Faltou'][$ag->status] ?? $ag->status }}
                            </span>

                            @if(in_array($ag->status, ['pendente','confirmado']))
                                <form action="{{ route('funcionario.agendamento.finalizar', $ag->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg bg-green-500/10 hover:bg-green-500 text-green-400 hover:text-white border border-green-500/20 hover:border-green-500 transition-all flex items-center gap-1">
                                        <i class="fa-solid fa-check-double text-[8px]"></i>Finalizar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-4 py-3 border-t border-gray-800/50 bg-gray-900/30">
                <p class="text-[10px] text-gray-600 font-bold uppercase tracking-widest">
                    {{ $agendamentos->count() }} agendamento(s) encontrado(s)
                </p>
            </div>
        @endif
    </div>

</div>
@endsection
