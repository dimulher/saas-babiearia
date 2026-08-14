@extends('layouts.app')
@section('title', 'Agendamentos')

@section('content')
<div x-data="agendamentosCalendar()" x-init="init()">

    <style>
        #glow-calendar {
            --fc-border-color: #1a2030;
            --fc-page-bg-color: transparent;
            --fc-neutral-bg-color: #0d1117;
            --fc-neutral-text-color: #9ca3af;
            --fc-today-bg-color: rgba(22,163,74,0.05);
            --fc-now-indicator-color: #4ade80;
            --fc-non-business-color: rgba(0,0,0,0.08);
            --fc-list-event-hover-bg-color: #1f2937;
        }
        #glow-calendar .fc-toolbar { flex-wrap: wrap; gap: 6px; margin-bottom: 16px; }
        #glow-calendar .fc-toolbar-title { color: #fff; font-family: 'Outfit',sans-serif; font-weight: 800; font-size: 1rem; }
        #glow-calendar .fc-button {
            background: #1f2937 !important; border: 1px solid #374151 !important; color: #d1d5db !important;
            font-family: 'Outfit',sans-serif !important; font-weight: 700 !important; font-size: 9px !important;
            text-transform: uppercase !important; letter-spacing: 0.08em !important;
            padding: 5px 12px !important; border-radius: 8px !important; box-shadow: none !important; transition: all .15s !important;
        }
        #glow-calendar .fc-button:hover { background: #374151 !important; color: #fff !important; }
        #glow-calendar .fc-button-primary:not(:disabled):active,
        #glow-calendar .fc-button-primary:not(:disabled).fc-button-active {
            background: #16a34a !important; border-color: #15803d !important; color: #fff !important;
        }
        #glow-calendar .fc-button-group .fc-button { border-radius: 0 !important; }
        #glow-calendar .fc-button-group .fc-button:first-child { border-radius: 8px 0 0 8px !important; }
        #glow-calendar .fc-button-group .fc-button:last-child  { border-radius: 0 8px 8px 0 !important; }

        /* Cabeçalhos de coluna */
        #glow-calendar .fc-col-header-cell { background: #0d1117; border-bottom: 1px solid #1a2030 !important; }
        #glow-calendar .fc-col-header-cell-cushion {
            color: #6b7280; font-size: 9px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .1em; text-decoration: none; padding: 10px 4px;
        }
        #glow-calendar .fc-day-today .fc-col-header-cell-cushion { color: #4ade80 !important; }

        /* Linhas de hora — bem mais sutis */
        #glow-calendar .fc-timegrid-slot { height: 3.5rem !important; }
        #glow-calendar .fc-timegrid-slot-minor { border-top: none !important; }
        #glow-calendar .fc-timegrid-slot-label { border-right: none !important; }
        #glow-calendar .fc-timegrid-slot-label-cushion {
            color: #374151; font-size: 9px; font-weight: 700; padding-right: 10px;
        }

        /* Borda da grade bem suave */
        #glow-calendar td, #glow-calendar th { border-color: #1a2030 !important; }
        #glow-calendar .fc-scrollgrid { border: none !important; }
        #glow-calendar .fc-scrollgrid-section > td { border: none !important; }

        /* Eventos */
        #glow-calendar .fc-event { border-radius: 8px !important; border: none !important; cursor: pointer; font-family: 'Outfit',sans-serif !important; font-size: 11px !important; font-weight: 700 !important; }
        #glow-calendar .fc-event:hover { opacity: .82; transform: scale(1.01); transition: .1s; }
        #glow-calendar .fc-timegrid-event { border-radius: 8px !important; padding: 3px 6px; }
        #glow-calendar .fc-event-title { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Indicador de agora */
        #glow-calendar .fc-timegrid-now-indicator-line { border-color: #4ade80 !important; }
        #glow-calendar .fc-timegrid-now-indicator-arrow { border-color: #4ade80 !important; }

        /* Day grid (mês) */
        #glow-calendar .fc-daygrid-day-number { color: #9ca3af; font-weight: 700; font-size: 12px; text-decoration: none; }
        #glow-calendar .fc-day-today .fc-daygrid-day-number { color: #4ade80 !important; }

        /* Lista */
        #glow-calendar .fc-list-day-cushion { background: #0d1117 !important; }
        #glow-calendar .fc-list-day-text, #glow-calendar .fc-list-day-side-text { color: #9ca3af; font-size: 10px; font-weight: 700; text-transform: uppercase; text-decoration: none; }
        #glow-calendar .fc-list-event-title a { color: #f3f4f6; font-weight: 700; text-decoration: none; }
        #glow-calendar .fc-list-event-time { color: #6b7280; font-size: 10px; font-weight: 700; }
        #glow-calendar .fc-list-empty-cushion { color: #6b7280; font-size: 12px; }

        @media (max-width: 640px) {
            #glow-calendar .fc-toolbar { justify-content: space-between; }
            #glow-calendar .fc-toolbar-chunk:nth-child(3) { display: none; }
            #glow-calendar .fc-toolbar-title { font-size: .85rem; }
        }
    </style>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Agendamentos</h1>
            <p class="text-sm text-gray-400 font-medium">Gerencie e visualize os horários do seu estabelecimento.</p>
        </div>
    </div>

    {{-- Barra de filtros --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 mb-4 space-y-3">

        {{-- Filtros --}}
        <div class="flex flex-wrap items-center gap-2">

            {{-- Busca por nome do cliente --}}
            <div class="flex items-center gap-2 bg-[#0B0F19] px-3 py-2.5 rounded-xl border border-gray-800 flex-1 min-w-[160px]"
                :class="filtroBusca ? 'border-green-700/50' : ''">
                <i class="fa-solid fa-magnifying-glass text-gray-500 text-xs shrink-0"></i>
                <input type="text" x-model="filtroBusca" @input.debounce.400ms="refetchEvents()"
                    placeholder="Buscar cliente..."
                    class="bg-transparent border-none p-0 text-xs font-medium text-gray-300 placeholder-gray-600 focus:ring-0 w-full min-w-0">
                <button x-show="filtroBusca" @click="filtroBusca='';refetchEvents()"
                    class="text-gray-600 hover:text-gray-400 shrink-0 transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            {{-- Data --}}
            <div class="flex items-center gap-2 bg-[#0B0F19] px-3 py-2.5 rounded-xl border border-gray-800 transition-colors"
                :class="filtroData ? 'border-green-700/50' : ''">
                <i class="fa-regular fa-calendar text-green-500 text-xs shrink-0"></i>
                <input type="date" x-model="filtroData" @change="navegarParaData()"
                    title="Ir para data"
                    class="bg-transparent border-none p-0 text-xs font-bold text-gray-300 focus:ring-0 cursor-pointer w-28">
                <button x-show="filtroData" @click="filtroData='';voltarSemana()"
                    class="text-gray-600 hover:text-gray-400 shrink-0 transition-colors">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            {{-- Profissional --}}
            <div class="flex items-center gap-2 bg-[#0B0F19] px-3 py-2.5 rounded-xl border border-gray-800 transition-colors"
                :class="profissionalId ? 'border-green-700/50' : ''">
                <i class="fa-solid fa-user-tie text-green-500 text-xs shrink-0"></i>
                <select x-model="profissionalId" @change="refetchEvents()"
                    class="bg-transparent border-none p-0 text-xs font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-4 cursor-pointer">
                    <option value="">Todos profissionais</option>
                    @foreach($profissionais as $prof)
                        <option value="{{ $prof->id }}">{{ $prof->nome }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Serviço --}}
            <div class="flex items-center gap-2 bg-[#0B0F19] px-3 py-2.5 rounded-xl border border-gray-800 transition-colors"
                :class="filtroServico ? 'border-green-700/50' : ''">
                <i class="fa-solid fa-scissors text-green-500 text-xs shrink-0"></i>
                <select x-model="filtroServico" @change="refetchEvents()"
                    class="bg-transparent border-none p-0 text-xs font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-4 cursor-pointer">
                    <option value="">Todos serviços</option>
                    @foreach($servicos as $svc)
                        <option value="{{ $svc->id }}">{{ $svc->nome }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Produto (exibe só se existirem produtos) --}}
            @if($produtos->isNotEmpty())
            <div class="flex items-center gap-2 bg-[#0B0F19] px-3 py-2.5 rounded-xl border border-gray-800 transition-colors"
                :class="filtroProduto ? 'border-green-700/50' : ''">
                <i class="fa-solid fa-tag text-green-500 text-xs shrink-0"></i>
                <select x-model="filtroProduto" @change="refetchEvents()"
                    class="bg-transparent border-none p-0 text-xs font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-4 cursor-pointer">
                    <option value="">Todos produtos</option>
                    @foreach($produtos as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->nome }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Botão limpar (aparece quando algum filtro está ativo) --}}
            <button x-show="filtroBusca || profissionalId || filtroServico || filtroProduto || filtroData"
                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                @click="limparFiltros()"
                class="flex items-center gap-1.5 px-3 py-2.5 bg-rose-900/20 hover:bg-rose-800/30 border border-rose-800/40 rounded-xl text-rose-400 text-[9px] font-bold uppercase tracking-widest transition-all shrink-0">
                <i class="fa-solid fa-xmark text-xs"></i>Limpar
            </button>
        </div>

        {{-- Legenda de status --}}
        <div class="flex items-center gap-3 flex-wrap pt-0.5">
            <span class="flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-widest text-amber-400">
                <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>Pendente
            </span>
            <span class="flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-widest text-blue-400">
                <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>Confirmado
            </span>
            <span class="flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-widest text-emerald-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>Concluído
            </span>
            <span class="flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-widest text-rose-400">
                <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span>Cancelado
            </span>
            <span class="flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-widest text-blue-300">
                <i class="fa-brands fa-google text-[9px]"></i>Google Cal
            </span>
        </div>
    </div>

    {{-- Calendário --}}
    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl p-4 sm:p-5">
        <div id="glow-calendar"></div>
    </div>

    {{-- TOOLTIP DE HOVER --}}
    <div x-show="ttVisible" x-cloak
        :style="`position:fixed;left:${ttX}px;top:${ttY}px;z-index:9998;pointer-events:none`"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="w-64 bg-[#0d1117] border border-gray-700/80 rounded-2xl shadow-2xl overflow-hidden">

        <template x-if="ttData">
            <div>
                {{-- Cabeçalho com cor do status --}}
                <div class="px-4 pt-4 pb-3 border-b border-gray-800/60 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                        :style="`background:${statusColor(ttData.status)}22`">
                        <template x-if="ttData.tipo === 'agendamento'">
                            <i class="fa-solid fa-scissors text-xs" :style="`color:${statusColor(ttData.status)}`"></i>
                        </template>
                        <template x-if="ttData.tipo === 'google_calendar'">
                            <i class="fa-brands fa-google text-blue-400 text-xs"></i>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-black text-white leading-tight truncate" x-text="ttData.title"></p>
                        <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-full border mt-1"
                            :class="statusClass(ttData.status)"
                            x-text="ttData.tipo === 'google_calendar' ? 'Google Calendar' : statusLabel(ttData.status)"></span>
                    </div>
                </div>

                {{-- Detalhes --}}
                <div class="px-4 py-3 space-y-2">
                    {{-- Horário --}}
                    <div class="flex items-center gap-2.5">
                        <i class="fa-regular fa-clock text-green-500 text-xs w-3.5 text-center shrink-0"></i>
                        <span class="text-xs text-gray-300 font-medium" x-text="ttFormatHorario(ttData.start, ttData.end)"></span>
                    </div>

                    {{-- Profissional (só agendamentos) --}}
                    <div x-show="ttData.tipo === 'agendamento' && ttData.profissional" class="flex items-center gap-2.5">
                        <i class="fa-solid fa-user-tie text-green-500 text-xs w-3.5 text-center shrink-0"></i>
                        <span class="text-xs text-gray-300 font-medium truncate" x-text="ttData.profissional"></span>
                    </div>

                    {{-- Serviço --}}
                    <div x-show="ttData.tipo === 'agendamento' && ttData.servico" class="flex items-center gap-2.5">
                        <i class="fa-solid fa-scissors text-green-500 text-xs w-3.5 text-center shrink-0"></i>
                        <span class="text-xs text-gray-300 font-medium truncate" x-text="ttData.servico"></span>
                    </div>

                    {{-- Preço --}}
                    <div x-show="ttData.tipo === 'agendamento' && ttData.preco" class="flex items-center gap-2.5">
                        <i class="fa-solid fa-tag text-emerald-500 text-xs w-3.5 text-center shrink-0"></i>
                        <span class="text-xs font-black text-emerald-400" x-text="ttData.preco"></span>
                    </div>
                </div>

                {{-- Rodapé --}}
                <div class="px-4 pb-3">
                    <p class="text-[9px] text-gray-600 font-bold uppercase tracking-widest">Clique para ver detalhes</p>
                </div>
            </div>
        </template>
    </div>

    {{-- MODAL NOVO AGENDAMENTO --}}
    <div x-show="novoOpen" x-cloak class="fixed inset-0 z-[70] overflow-y-auto" aria-modal="true">
        <div class="flex items-end sm:items-center justify-center min-h-screen">

            <div @click="fecharNovo()"
                x-show="novoOpen"
                x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm"></div>

            <div x-show="novoOpen"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-6 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-6 sm:scale-95"
                class="relative w-full sm:max-w-lg bg-[#111827] border border-gray-800 rounded-t-3xl sm:rounded-3xl shadow-2xl z-10 overflow-hidden">

                {{-- Cabeçalho do modal --}}
                <div class="px-6 pt-6 pb-4 border-b border-gray-800/70 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-green-500/15 flex items-center justify-center">
                            <i class="fa-solid fa-calendar-plus text-green-400 text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-white tracking-tight">Novo Agendamento</h3>
                            <p class="text-[10px] text-gray-500 font-medium">Preencha os dados do cliente</p>
                        </div>
                    </div>
                    <button @click="fecharNovo()"
                        class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-white hover:bg-gray-800 rounded-xl transition-all">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                {{-- Formulário --}}
                <form @submit.prevent="salvarAgendamento()" class="px-6 py-5 space-y-4 max-h-[80vh] overflow-y-auto">

                    {{-- Alerta de erro --}}
                    <div x-show="novoErro" class="flex items-center gap-2 bg-rose-500/10 border border-rose-500/30 rounded-xl px-4 py-3 text-rose-400 text-xs font-medium">
                        <i class="fa-solid fa-triangle-exclamation shrink-0"></i>
                        <span x-text="novoErro"></span>
                    </div>

                    {{-- Cliente --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1.5 block">Nome do Cliente <span class="text-rose-400">*</span></label>
                            <input type="text" x-model="novoForm.nome_cliente" required placeholder="Ex: João Silva"
                                class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white text-sm font-medium placeholder-gray-600 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1.5 block">Telefone / WhatsApp</label>
                            <div class="relative">
                                <i class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-green-600 text-sm pointer-events-none"></i>
                                <input type="tel" x-model="novoForm.telefone" placeholder="(00) 00000-0000"
                                    class="w-full bg-gray-900 border border-gray-800 rounded-xl pl-11 pr-4 py-3 text-white text-sm font-medium placeholder-gray-600 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1.5 block">Status</label>
                            <select x-model="novoForm.status"
                                class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white text-sm font-medium focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                                <option value="confirmado">Confirmado</option>
                                <option value="pendente">Pendente</option>
                            </select>
                        </div>
                    </div>

                    {{-- Profissional + Serviço --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1.5 block">Profissional <span class="text-rose-400">*</span></label>
                            <select x-model="novoForm.profissional_id" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white text-sm font-medium focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                                <option value="">Selecione...</option>
                                @foreach($profissionais as $prof)
                                    <option value="{{ $prof->id }}">{{ $prof->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1.5 block">Serviço <span class="text-rose-400">*</span></label>
                            <select x-model="novoForm.servico_id" required
                                class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white text-sm font-medium focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                                <option value="">Selecione...</option>
                                @foreach($servicos as $svc)
                                    <option value="{{ $svc->id }}" data-preco="{{ number_format($svc->preco, 2, ',', '.') }}" data-duracao="{{ $svc->duracao_minutos }}">
                                        {{ $svc->nome }} — R$ {{ number_format($svc->preco, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Data + Hora --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1.5 block">Data <span class="text-rose-400">*</span></label>
                            <input type="date" x-model="novoForm.data" required
                                :min="hoje"
                                class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white text-sm font-medium focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1.5 block">Horário <span class="text-rose-400">*</span></label>
                            <input type="time" x-model="novoForm.hora" required step="1800"
                                class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white text-sm font-medium focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all">
                        </div>
                    </div>

                    {{-- Observações --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-1.5 block">Observações</label>
                        <textarea x-model="novoForm.descricao" rows="2" placeholder="Informações adicionais..."
                            class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white text-sm font-medium placeholder-gray-600 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500/20 transition-all resize-none"></textarea>
                    </div>

                    {{-- Botões --}}
                    <div class="flex items-center gap-3 pt-1">
                        <button type="button" @click="fecharNovo()"
                            class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="novoSalvando"
                            class="flex-1 py-3 bg-green-500 hover:bg-green-600 disabled:opacity-60 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-green-900/20">
                            <span x-show="!novoSalvando"><i class="fa-solid fa-check mr-1.5"></i>Salvar</span>
                            <span x-show="novoSalvando"><i class="fa-solid fa-spinner animate-spin mr-1.5"></i>Salvando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal de detalhes --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[70] overflow-y-auto" aria-modal="true">
        <div class="flex items-end sm:items-center justify-center min-h-screen">

            <div @click="fecharModal()"
                x-show="modalOpen"
                x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm"></div>

            <div x-show="modalOpen"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-6 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-6 sm:scale-95"
                class="relative w-full sm:max-w-md bg-[#111827] border border-gray-800 rounded-t-3xl sm:rounded-3xl shadow-2xl z-10 overflow-hidden">

                <template x-if="eventoSelecionado">
                    <div>
                        {{-- Topo --}}
                        <div class="px-6 pt-6 pb-5 border-b border-gray-800/70">
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
                                    :style="eventoSelecionado.tipo === 'agendamento'
                                        ? 'background:' + statusBg(eventoSelecionado.status) + '26;'
                                        : 'background:#1e3a8a40;border:1px solid #1e3a8a80'">
                                    <template x-if="eventoSelecionado.tipo === 'agendamento'">
                                        <i class="fa-solid fa-scissors text-base" :style="'color:' + statusBg(eventoSelecionado.status)"></i>
                                    </template>
                                    <template x-if="eventoSelecionado.tipo === 'google_calendar'">
                                        <i class="fa-brands fa-google text-blue-400 text-base"></i>
                                    </template>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-extrabold text-white uppercase tracking-tight truncate" x-text="eventoSelecionado.title"></h3>
                                    <div class="mt-1.5">
                                        <span x-show="eventoSelecionado.tipo === 'agendamento'"
                                            class="inline-flex items-center gap-1 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border"
                                            :class="statusClass(eventoSelecionado.status)"
                                            x-text="statusLabel(eventoSelecionado.status)"></span>
                                        <span x-show="eventoSelecionado.tipo === 'google_calendar'"
                                            class="inline-flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-full border border-blue-800/50 text-blue-400 bg-blue-900/20">
                                            <i class="fa-brands fa-google text-[9px]"></i>Google Calendar
                                        </span>
                                    </div>
                                </div>
                                <button @click="fecharModal()"
                                    class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-white hover:bg-gray-800 rounded-xl transition-all shrink-0">
                                    <i class="fa-solid fa-xmark text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Detalhes --}}
                        <div class="px-6 py-5 space-y-3">
                            <div class="flex items-center gap-3">
                                <i class="fa-regular fa-clock text-green-500 w-4 text-center text-sm shrink-0"></i>
                                <span class="text-gray-300 text-sm font-medium" x-text="formatarHorario(eventoSelecionado.start, eventoSelecionado.end, eventoSelecionado.allDay)"></span>
                            </div>
                            <div x-show="eventoSelecionado.tipo === 'agendamento'" class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-scissors text-green-500 w-4 text-center text-sm shrink-0"></i>
                                    <span class="text-gray-300 text-sm flex-1" x-text="eventoSelecionado.servico"></span>
                                    <span class="text-emerald-400 font-bold text-sm" x-text="eventoSelecionado.preco"></span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-user-tie text-green-500 w-4 text-center text-sm shrink-0"></i>
                                    <span class="text-gray-300 text-sm" x-text="eventoSelecionado.profissional"></span>
                                </div>
                                <div x-show="eventoSelecionado.telefone" class="flex items-center gap-3">
                                    <i class="fa-solid fa-phone text-green-500 w-4 text-center text-sm shrink-0"></i>
                                    <a :href="'https://wa.me/55' + eventoSelecionado.telefone.replace(/\D/g,'')"
                                        target="_blank"
                                        class="text-gray-300 text-sm hover:text-green-400 transition-colors"
                                        x-text="eventoSelecionado.telefone"></a>
                                </div>
                                <div x-show="eventoSelecionado.descricao" class="flex items-start gap-3">
                                    <i class="fa-solid fa-note-sticky text-green-500 w-4 text-center text-sm shrink-0 mt-0.5"></i>
                                    <p class="text-gray-400 text-xs leading-relaxed" x-text="eventoSelecionado.descricao"></p>
                                </div>
                            </div>
                            <div x-show="eventoSelecionado.tipo === 'google_calendar' && eventoSelecionado.descricao" class="flex items-start gap-3">
                                <i class="fa-solid fa-note-sticky text-blue-400 w-4 text-center text-sm shrink-0 mt-0.5"></i>
                                <p class="text-gray-400 text-xs leading-relaxed" x-text="eventoSelecionado.descricao"></p>
                            </div>
                        </div>

                        {{-- Ações de status --}}
                        <div x-show="eventoSelecionado.tipo === 'agendamento'" class="px-6 pb-6 pt-0">
                            <p class="text-[9px] font-bold uppercase tracking-widest text-gray-500 mb-3">Atualizar Status</p>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    x-show="['pendente','cancelado','faltou'].includes(eventoSelecionado.status)"
                                    @click="atualizarStatus('confirmado')"
                                    :disabled="atualizandoStatus"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-500 disabled:opacity-50 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all">
                                    <i class="fa-solid fa-check mr-1"></i>Confirmar
                                </button>
                                <button
                                    x-show="eventoSelecionado.status !== 'concluido' && eventoSelecionado.status !== 'cancelado'"
                                    @click="atualizarStatus('cancelado')"
                                    :disabled="atualizandoStatus"
                                    class="px-4 py-2 bg-rose-900/50 hover:bg-rose-800/70 disabled:opacity-50 text-rose-300 rounded-xl text-[10px] font-bold uppercase tracking-widest border border-rose-800/50 transition-all">
                                    <i class="fa-solid fa-xmark mr-1"></i>Cancelar
                                </button>
                                <button
                                    x-show="['pendente','confirmado'].includes(eventoSelecionado.status)"
                                    @click="atualizarStatus('faltou')"
                                    :disabled="atualizandoStatus"
                                    class="px-4 py-2 bg-gray-800 hover:bg-gray-700 disabled:opacity-50 text-gray-400 rounded-xl text-[10px] font-bold uppercase tracking-widest border border-gray-700 transition-all">
                                    <i class="fa-solid fa-user-slash mr-1"></i>Faltou
                                </button>
                                <button
                                    x-show="['cancelado','faltou'].includes(eventoSelecionado.status)"
                                    @click="atualizarStatus('pendente')"
                                    :disabled="atualizandoStatus"
                                    class="px-4 py-2 bg-gray-800 hover:bg-gray-700 disabled:opacity-50 text-gray-400 rounded-xl text-[10px] font-bold uppercase tracking-widest border border-gray-700 transition-all">
                                    <i class="fa-solid fa-rotate-left mr-1"></i>Reabrir
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
function agendamentosCalendar() {
    return {
        profissionalId: '',
        filtroServico: '',
        filtroProduto: '',
        filtroBusca: '',
        filtroData: '',
        modalOpen: false,
        eventoSelecionado: null,
        atualizandoStatus: false,
        calendar: null,

        // Tooltip de hover
        ttVisible: false,
        ttData: null,
        ttX: 0,
        ttY: 0,

        // Novo agendamento
        novoOpen: false,
        novoSalvando: false,
        novoErro: '',
        novoForm: { nome_cliente: '', telefone: '', profissional_id: '', servico_id: '', data: '', hora: '', status: 'confirmado', descricao: '' },
        hoje: new Date().toISOString().split('T')[0],

        init() {
            this.$nextTick(() => {
                this.initCalendar();
                if (new URLSearchParams(window.location.search).get('novo') === '1') {
                    this.abrirNovo();
                }
            });
            window.addEventListener('glow:novo-agendamento', () => this.abrirNovo());
        },

        initCalendar() {
            const el = document.getElementById('glow-calendar');
            if (!el) return;
            if (!window.FullCalendar) { setTimeout(() => this.initCalendar(), 80); return; }
            if (this.calendar) { this.calendar.destroy(); this.calendar = null; }

            const self = this;

            this.calendar = new FullCalendar.Calendar(el, {
                locale: 'pt-br',
                initialView: window.innerWidth < 768 ? 'timeGridDay' : 'timeGridWeek',
                headerToolbar: {
                    left:   'prev,next today',
                    center: 'title',
                    right:  'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                },
                buttonText: { today:'Hoje', month:'Mês', week:'Semana', day:'Dia', listWeek:'Lista' },
                height: 'auto',
                aspectRatio: window.innerWidth < 768 ? 1 : 1.8,

                /* ─── Menos linhas: slots de 1h, sem all-day ─── */
                slotMinTime:  '08:00:00',
                slotMaxTime:  '21:00:00',
                slotDuration: '01:00:00',   /* 1 linha por hora = 13 linhas no total */
                slotLabelInterval: '01:00',
                allDaySlot: false,
                /* ─────────────────────────────────────────────── */

                nowIndicator: false,
                firstDay: 0,
                eventSources: [{
                    url: '/panel/agendamentos/eventos',
                    extraParams: () => ({
                        profissional_id: self.profissionalId,
                        servico_id:      self.filtroServico,
                        produto_id:      self.filtroProduto,
                        busca:           self.filtroBusca,
                    }),
                    failure() { console.error('Erro ao buscar eventos.'); },
                }],
                eventClick(info) {
                    info.jsEvent.preventDefault();
                    self.ttVisible = false;
                    self.abrirModal(info.event);
                },

                eventMouseEnter(info) {
                    const e   = info.jsEvent;
                    const ttW = 260;
                    const ttH = 200;
                    let x = e.clientX + 18;
                    let y = e.clientY - 20;
                    if (x + ttW > window.innerWidth)  x = e.clientX - ttW - 10;
                    if (y + ttH > window.innerHeight) y = window.innerHeight - ttH - 8;
                    self.ttX = x;
                    self.ttY = Math.max(8, y);
                    self.ttData = {
                        title:        info.event.title,
                        start:        info.event.start,
                        end:          info.event.end,
                        allDay:       info.event.allDay,
                        tipo:         info.event.extendedProps.tipo,
                        status:       info.event.extendedProps.status,
                        servico:      info.event.extendedProps.servico,
                        profissional: info.event.extendedProps.profissional,
                        preco:        info.event.extendedProps.preco,
                    };
                    self.ttVisible = true;
                },

                eventMouseLeave() {
                    self.ttVisible = false;
                },
                eventDidMount(info) {
                    if (info.event.extendedProps.tipo === 'google_calendar') {
                        const titleEl = info.el.querySelector('.fc-event-title, .fc-list-event-title');
                        if (titleEl) {
                            const icon = document.createElement('i');
                            icon.className = 'fa-brands fa-google';
                            icon.style.cssText = 'font-size:9px;opacity:.8;margin-right:4px';
                            titleEl.prepend(icon);
                        }
                    }
                },
                noEventsContent: {
                    html: '<div style="padding:40px 0;text-align:center"><i class="fa-regular fa-calendar-xmark" style="font-size:2.5rem;color:#374151;display:block;margin-bottom:12px"></i><p style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.1em">Sem agendamentos neste período</p></div>'
                },
            });

            this.calendar.render();
        },

        refetchEvents() {
            if (this.calendar) this.calendar.refetchEvents();
        },

        navegarParaData() {
            if (!this.filtroData || !this.calendar) return;
            this.calendar.gotoDate(this.filtroData);
            this.calendar.changeView('timeGridDay');
        },

        voltarSemana() {
            if (!this.calendar) return;
            this.calendar.changeView(window.innerWidth < 768 ? 'timeGridDay' : 'timeGridWeek');
            this.calendar.today();
        },

        limparFiltros() {
            this.filtroBusca    = '';
            this.profissionalId = '';
            this.filtroServico  = '';
            this.filtroProduto  = '';
            this.filtroData     = '';
            this.voltarSemana();
            this.$nextTick(() => this.refetchEvents());
        },

        abrirModal(event) {
            this.eventoSelecionado = {
                id:     event.id,
                title:  event.title,
                start:  event.start,
                end:    event.end,
                allDay: event.allDay,
                ...event.extendedProps,
            };
            this.modalOpen = true;
        },

        fecharModal() { this.modalOpen = false; },

        abrirNovo() {
            this.novoForm = { nome_cliente: '', telefone: '', profissional_id: '', servico_id: '', data: this.hoje, hora: '09:00', status: 'confirmado', descricao: '' };
            this.novoErro = '';
            this.novoOpen = true;
        },

        fecharNovo() { this.novoOpen = false; },

        async salvarAgendamento() {
            this.novoErro = '';
            this.novoSalvando = true;
            try {
                const resp = await fetch('/panel/agendamentos', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.novoForm),
                });
                const data = await resp.json();
                if (resp.ok && data.success) {
                    this.novoOpen = false;
                    if (this.calendar) this.calendar.refetchEvents();
                } else {
                    const erros = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Erro ao salvar.');
                    this.novoErro = erros;
                }
            } catch(e) {
                this.novoErro = 'Erro de conexão. Tente novamente.';
            } finally {
                this.novoSalvando = false;
            }
        },

        async atualizarStatus(novoStatus) {
            if (!this.eventoSelecionado || this.atualizandoStatus) return;
            this.atualizandoStatus = true;
            try {
                const resp = await fetch(`/panel/agendamentos/${this.eventoSelecionado.agendamento_id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ status: novoStatus }),
                });
                const data = await resp.json();
                if (data.success) {
                    this.eventoSelecionado.status = novoStatus;
                    this.modalOpen = false;
                    this.calendar.refetchEvents();
                }
            } catch(e) {
                console.error('Erro ao atualizar status:', e);
            } finally {
                this.atualizandoStatus = false;
            }
        },

        formatarHorario(start, end, allDay) {
            if (allDay) return 'Dia inteiro';
            const opt = { hour: '2-digit', minute: '2-digit' };
            const dia = start ? new Date(start).toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' }) : '';
            const s   = start ? new Date(start).toLocaleTimeString('pt-BR', opt) : '';
            const e   = end   ? new Date(end).toLocaleTimeString('pt-BR', opt)   : '';
            return dia + (s ? ' · ' + s + (e ? ' – ' + e : '') : '');
        },

        ttFormatHorario(start, end) {
            if (!start) return '';
            const opt  = { hour: '2-digit', minute: '2-digit' };
            const dia  = new Date(start).toLocaleDateString('pt-BR', { weekday: 'long', day: 'numeric', month: 'long' });
            const ini  = new Date(start).toLocaleTimeString('pt-BR', opt);
            const fim  = end ? new Date(end).toLocaleTimeString('pt-BR', opt) : '';
            return dia + ' · ' + ini + (fim ? ' – ' + fim : '');
        },

        statusLabel(s) {
            return ({pendente:'Pendente',confirmado:'Confirmado',concluido:'Concluído',cancelado:'Cancelado',faltou:'Faltou'})[s] || s;
        },
        statusClass(s) {
            return ({
                pendente:   'bg-amber-900/30 text-amber-400 border-amber-800/50',
                confirmado: 'bg-blue-900/30 text-blue-400 border-blue-800/50',
                concluido:  'bg-emerald-900/30 text-emerald-400 border-emerald-800/50',
                cancelado:  'bg-rose-900/30 text-rose-400 border-rose-800/50',
                faltou:     'bg-gray-800/50 text-gray-500 border-gray-700/50',
            })[s] || '';
        },
        statusBg(s) {
            return ({pendente:'#d97706',confirmado:'#2563eb',concluido:'#059669',cancelado:'#e11d48',faltou:'#6b7280'})[s] || '#6b7280';
        },
    };
}
</script>
@endpush
@endsection
