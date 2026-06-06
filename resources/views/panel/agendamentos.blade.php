@extends('layouts.app')
@section('title', 'Agendamentos')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Agendamentos</h1>
            <p class="text-sm text-gray-400 font-medium">Organize os horários e especialistas do seu estabelecimento.</p>
        </div>
    </div>

    <!-- Filtros -->
    <form action="{{ route('panel.agendamentos') }}" method="GET" class="bg-[#111827] border border-gray-800/50 rounded-2xl p-5 flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2 bg-[#0B0F19] px-4 py-2.5 rounded-xl border border-gray-800">
            <i class="fa-solid fa-calendar text-green-500 text-xs"></i>
            <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                class="bg-transparent border-none p-0 text-xs font-bold uppercase tracking-widest focus:ring-0 text-gray-300">
        </div>
        <div class="flex items-center gap-2 bg-[#0B0F19] px-4 py-2.5 rounded-xl border border-gray-800">
            <i class="fa-solid fa-user-tie text-green-500 text-xs"></i>
            <select name="profissional_id" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-xs font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-8">
                <option value="">Todos profissionais</option>
                @foreach($profissionais as $prof)
                    <option value="{{ $prof->id }}" {{ $profissionalId == $prof->id ? 'selected' : '' }}>{{ $prof->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2 bg-[#0B0F19] px-4 py-2.5 rounded-xl border border-gray-800">
            <i class="fa-solid fa-filter text-green-500 text-xs"></i>
            <select name="status" onchange="this.form.submit()" class="bg-transparent border-none p-0 text-xs font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-8">
                <option value="">Todos os status</option>
                <option value="pendente" {{ $status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="confirmado" {{ $status == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                <option value="concluido" {{ $status == 'concluido' ? 'selected' : '' }}>Concluído</option>
                <option value="cancelado" {{ $status == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                <option value="faltou" {{ $status == 'faltou' ? 'selected' : '' }}>Faltou</option>
            </select>
        </div>
    </form>

    <!-- Calendário semanal + lista -->
    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-6">

        <!-- Navegação semanal -->
        <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-800/50">
            @php
                $dataSelecionada = \Carbon\Carbon::parse($date);
                $inicioSemana = $dataSelecionada->copy()->startOfWeek();
            @endphp
            <a href="{{ route('panel.agendamentos', ['date' => $inicioSemana->copy()->subWeek()->format('Y-m-d'), 'profissional_id' => $profissionalId, 'status' => $status]) }}"
               class="w-10 h-10 flex items-center justify-center hover:bg-green-900/30 hover:text-green-400 text-gray-500 rounded-xl transition-all">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <div class="flex gap-2 overflow-x-auto overflow-y-hidden py-2 -my-2 no-scrollbar items-center">
                @for($i = 0; $i < 7; $i++)
                    @php $dia = $inicioSemana->copy()->addDays($i); @endphp
                    <a href="{{ route('panel.agendamentos', ['date' => $dia->format('Y-m-d'), 'profissional_id' => $profissionalId, 'status' => $status]) }}"
                       class="flex flex-col items-center min-w-[58px] py-3 px-2 rounded-xl cursor-pointer transition-all
                       {{ $dia->isSameDay($dataSelecionada) ? 'bg-green-500 text-white shadow-lg shadow-green-900/30 scale-105' : 'hover:bg-gray-800 text-gray-500 border border-transparent' }}">
                        <span class="text-[9px] font-bold uppercase tracking-widest mb-1 opacity-80">{{ strtoupper($dia->locale('pt_BR')->isoFormat('ddd')) }}</span>
                        <span class="text-xl font-bold tracking-tight">{{ $dia->day }}</span>
                    </a>
                @endfor
            </div>
            <a href="{{ route('panel.agendamentos', ['date' => $inicioSemana->copy()->addWeek()->format('Y-m-d'), 'profissional_id' => $profissionalId, 'status' => $status]) }}"
               class="w-10 h-10 flex items-center justify-center hover:bg-green-900/30 hover:text-green-400 text-gray-500 rounded-xl transition-all">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </div>

        @if($agendamentos->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-gray-400 bg-gray-900/50 rounded-2xl border border-dashed border-gray-800">
            <i class="fa-regular fa-calendar-xmark text-5xl mb-4 text-gray-700"></i>
            <h3 class="text-base font-bold text-white uppercase tracking-tight">Sem agendamentos registrados</h3>
            <p class="text-[10px] text-gray-500 mt-2 uppercase font-bold tracking-widest">A agenda deste dia está livre.</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($agendamentos as $agendamento)
            <div class="bg-[#0B0F19] border border-gray-800 hover:border-green-800/40 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all group">
                <div class="flex items-center gap-5">
                    <div class="text-center w-14">
                        <div class="text-lg font-bold text-white tracking-tight">{{ \Carbon\Carbon::parse($agendamento->data_inicio)->format('H:i') }}</div>
                        <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">{{ \Carbon\Carbon::parse($agendamento->data_fim)->format('H:i') }}</div>
                    </div>
                    <div class="h-10 w-px bg-gray-800"></div>
                    <div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-tight group-hover:text-green-400 transition-colors">{{ $agendamento->cliente_nome ?? 'Cliente Não Informado' }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $agendamento->servico->nome ?? 'Serviço Excluído' }} - R$ {{ number_format($agendamento->preco, 2, ',', '.') }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="w-5 h-5 bg-green-900/30 rounded-full flex items-center justify-center text-green-400 text-[8px] font-bold uppercase border border-green-800/50">
                                {{ $agendamento->profissional->initials ?? '?' }}
                            </div>
                            <span class="text-[10px] text-gray-500 font-bold tracking-widest uppercase">{{ $agendamento->profissional->nome ?? 'Profissional Excluído' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if($agendamento->status == 'pendente')
                        <span class="px-3 py-1 bg-amber-900/30 text-amber-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-amber-800/50">Pendente</span>
                    @elseif($agendamento->status == 'confirmado')
                        <span class="px-3 py-1 bg-blue-900/30 text-blue-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-blue-800/50">Confirmado</span>
                    @elseif($agendamento->status == 'concluido')
                        <span class="px-3 py-1 bg-emerald-900/30 text-emerald-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-emerald-800/50">Concluído</span>
                    @elseif($agendamento->status == 'cancelado' || $agendamento->status == 'faltou')
                        <span class="px-3 py-1 bg-rose-900/30 text-rose-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-rose-800/50">{{ $agendamento->status }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection
