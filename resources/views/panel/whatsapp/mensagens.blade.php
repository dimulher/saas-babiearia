@extends('layouts.app')
@section('title', 'WhatsApp Business - GlowSystem')

@section('content')
<div class="space-y-8" x-data="{ tab: 'mensagens' }">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">WhatsApp Business</h1>
            <p class="text-sm text-gray-400 font-medium">Automatize o relacionamento com seus clientes via WhatsApp.</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="flex items-center gap-2 px-4 py-2 bg-rose-50 border border-rose-100 rounded-2xl text-[10px] text-rose-600 font-bold uppercase tracking-widest shadow-sm">
                <span class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></span>
                Desconectado
            </span>
            <button class="bg-emerald-600 text-white px-6 py-3 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-900/30 flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-sm"></i> Conectar Agora
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="overflow-x-auto no-scrollbar -mx-4 sm:mx-0 px-4 sm:px-0">
        <div class="flex gap-2 p-1 bg-gray-800 rounded-[20px] border border-gray-700/50 w-max min-w-full">
            <button @click="tab = 'mensagens'"
                :class="tab === 'mensagens' ? 'bg-gray-900/50 text-green-500 shadow-sm' : 'text-gray-500 hover:text-gray-300'"
                class="px-6 py-2.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap">
                Fluxos Automáticos
            </button>
            <button @click="tab = 'configuracao'"
                :class="tab === 'configuracao' ? 'bg-gray-900/50 text-green-500 shadow-sm' : 'text-gray-500 hover:text-gray-300'"
                class="px-6 py-2.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest transition-all whitespace-nowrap">
                Painel de Conexão
            </button>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="w-full">
        
        <!-- Mensagens automáticas -->
        <div x-show="tab === 'mensagens'" x-cloak x-transition:enter class="space-y-6">

            @php
            $mensagens = [
                ['titulo' => 'Confirmação de Agendamento', 'icon' => 'fa-calendar-check', 'desc' => 'Disparado instantaneamente após a reserva.', 'ativa' => true],
                ['titulo' => 'Lembrete Estratégico (24h)', 'icon' => 'fa-clock', 'desc' => 'Reduza faltas enviando um alerta um dia antes.', 'ativa' => true],
                ['titulo' => 'Check-in Imediato (1h)', 'icon' => 'fa-bolt', 'desc' => 'Alerta final para preparo do cliente.', 'ativa' => false],
                ['titulo' => 'Aviso de Cancelamento', 'icon' => 'fa-calendar-xmark', 'desc' => 'Confirmação de que o horário foi liberado.', 'ativa' => true],
                ['titulo' => 'NPS / Avaliação Pós-Venda', 'icon' => 'fa-star', 'desc' => 'Colete feedbacks após a conclusão do serviço.', 'ativa' => false],
            ];
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($mensagens as $msg)
                <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-6 transition-all hover:border-violet-200 group" x-data="{ ativa: {{ $msg['ativa'] ? 'true' : 'false' }}, editando: false }">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-green-900/30 flex items-center justify-center text-green-500 border border-green-800 group-hover:bg-green-500 group-hover:text-white transition-all shadow-sm">
                                <i class="fa-solid {{ $msg['icon'] }} text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-white uppercase tracking-widest">{{ $msg['titulo'] }}</h3>
                                <p class="text-[10px] text-gray-400 font-medium mt-1">{{ $msg['desc'] }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-3">
                            <button @click="ativa = !ativa" :class="ativa ? 'bg-emerald-500' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-all shadow-inner">
                                <span :class="ativa ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-gray-900/50 transition-transform shadow-sm"></span>
                            </button>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-800/50">
                        <span class="text-[10px] font-bold uppercase tracking-widest" :class="ativa ? 'text-emerald-500' : 'text-gray-300'">
                            {{ $msg['ativa'] ? 'Fluxo Ativo' : 'Pausado' }}
                        </span>
                        <button @click="editando = !editando" class="text-[10px] font-bold text-green-500 hover:text-green-800 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square"></i> <span x-text="editando ? 'Fechar Editor' : 'Customizar Script'"></span>
                        </button>
                    </div>

                    <div x-show="editando" x-collapse class="mt-6 space-y-4">
                        <div class="relative">
                            <textarea rows="5" class="w-full bg-gray-800/50 border border-gray-800 rounded-2xl px-5 py-4 text-sm font-medium focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none leading-relaxed italic text-gray-400" placeholder="Olá {nome}, seu agendamento foi confirmado..."></textarea>
                            <div class="absolute bottom-4 right-4 flex gap-2">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-bounce"></div>
                            </div>
                        </div>
                        
                        <div class="bg-violet-50/50 p-4 rounded-2xl border border-green-800">
                            <p class="text-[9px] font-bold text-green-400 uppercase tracking-widest mb-3">Tags Dinâmicas Disponíveis:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['{cliente}', '{data}', '{hora}', '{profissional}', '{servico}'] as $v)
                                <span class="text-[9px] bg-gray-900/50 text-green-500 px-3 py-1 rounded-lg font-bold border border-green-800 shadow-sm cursor-copy hover:border-green-300 transition-colors">{{ $v }}</span>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="flex justify-end pt-2">
                            <button class="bg-gray-900 text-white px-6 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all shadow-lg">Atualizar Script</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Configuração -->
        <div x-show="tab === 'configuracao'" x-cloak x-transition:enter class="flex justify-center py-10">
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-2xl border border-gray-800 p-10 space-y-8 max-w-lg w-full text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-500"></div>
                
                <div>
                    <div class="w-20 h-20 bg-emerald-900/30 rounded-3xl flex items-center justify-center mx-auto mb-6 text-emerald-600 shadow-sm border border-emerald-800">
                        <i class="fa-brands fa-whatsapp text-4xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-white uppercase tracking-tight mb-2">Sincronização de Dispositivo</h2>
                    <p class="text-xs text-gray-400 font-medium">Abra o WhatsApp no seu celular, acesse Aparelhos Conectados e escaneie o código abaixo.</p>
                </div>

                <div class="relative group">
                    <div class="aspect-square bg-gray-800/50 rounded-[40px] flex flex-col items-center justify-center border-4 border-dashed border-gray-800 p-8 group-hover:border-emerald-200 transition-all">
                        <div class="w-full h-full bg-gray-900 rounded-[32px] shadow-inner flex flex-col items-center justify-center text-gray-200 gap-4 opacity-50">
                            <i class="fa-solid fa-qrcode text-6xl"></i>
                            <p class="text-[9px] font-bold uppercase tracking-widest">Aguardando geração...</p>
                        </div>
                    </div>
                    
                    <!-- Overlay de carregamento sim -->
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-900/50/60 backdrop-blur-[2px] rounded-[40px] opacity-0 group-hover:opacity-100 transition-opacity">
                         <button class="bg-emerald-600 text-white px-8 py-4 rounded-2xl text-[11px] font-bold uppercase tracking-widest shadow-2xl shadow-emerald-900/30 hover:scale-105 active:scale-95 transition-all">
                            Gerar Novo Token
                         </button>
                    </div>
                </div>

                <div class="pt-4 space-y-4">
                    <div class="flex items-center gap-3 justify-center text-gray-400">
                         <i class="fa-solid fa-shield-halved text-xs text-emerald-500"></i>
                         <p class="text-[10px] font-bold uppercase tracking-widest">Conexão Criptografada ponta-a-ponta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
