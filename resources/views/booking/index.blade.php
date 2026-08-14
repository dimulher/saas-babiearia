<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $barbearia?->nome ?? 'Agendar' }} — Reservar horário</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root { --accent: #22c55e; --accent-dark: #16a34a; }
        * { box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Aurora hero background */
        .hero-aurora {
            background:
                radial-gradient(ellipse at 15% 60%, rgba(22,163,74,.30) 0%, transparent 48%),
                radial-gradient(ellipse at 85% 25%, rgba(16,185,129,.18) 0%, transparent 44%),
                radial-gradient(ellipse at 50% 95%, rgba(5,150,105,.14) 0%, transparent 40%),
                linear-gradient(170deg, #040d06 0%, #071510 40%, #0a1c12 70%, #060e0a 100%);
        }

        /* Subtle mesh overlay */
        .hero-mesh {
            background-image:
                linear-gradient(rgba(255,255,255,.018) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.018) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .glass-bar {
            background: rgba(6, 10, 17, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Service card */
        .svc-card {
            transition: background .18s ease, border-color .18s ease, transform .18s ease;
        }
        .svc-card:hover {
            background: rgba(22,163,74,.07);
            border-color: rgba(34,197,94,.40);
            transform: translateX(2px);
        }
        .svc-card:active { transform: scale(.99); }

        /* Prof list row */
        .prof-row { transition: background .15s ease; }
        .prof-row:hover { background: rgba(255,255,255,.04); }

        /* Time chips */
        .time-chip { transition: all .15s ease; }
        .time-chip:active { transform: scale(.93); }

        /* Sticky bottom selection bar */
        .sel-bar {
            background: rgba(6,10,17,.96);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        /* Step line transition */
        .step-connector { transition: background .4s ease; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 99px; }
    </style>
</head>
<body class="bg-[#070C12] min-h-screen text-white" x-data="booking()">

    {{-- ══════════════════════════════════════
         HERO  —  "aurora cover" style
    ══════════════════════════════════════ --}}
    <div class="hero-aurora relative overflow-hidden" style="min-height:260px;">

        {{-- Mesh overlay --}}
        <div class="hero-mesh absolute inset-0 pointer-events-none"></div>

        {{-- Light beam accents --}}
        <div class="absolute top-0 left-1/4 w-px h-full bg-gradient-to-b from-green-500/20 via-transparent to-transparent pointer-events-none"></div>
        <div class="absolute top-0 right-1/3 w-px h-3/4 bg-gradient-to-b from-emerald-400/10 via-transparent to-transparent pointer-events-none"></div>

        {{-- Content --}}
        <div class="relative max-w-xl mx-auto px-5 flex flex-col justify-end" style="min-height:260px;padding-bottom:32px;padding-top:40px;">

            {{-- Top-right: share --}}
            <div class="absolute top-5 right-5">
                <button type="button"
                    onclick="if(navigator.share)navigator.share({title:document.title,url:location.href});else navigator.clipboard?.writeText(location.href)"
                    class="w-9 h-9 rounded-xl bg-white/8 border border-white/10 flex items-center justify-center hover:bg-white/14 transition-colors">
                    <i class="fa-solid fa-share-nodes text-gray-400 text-sm"></i>
                </button>
            </div>

            {{-- Avatar + Name --}}
            <div class="flex items-end gap-4">
                {{-- Avatar: foto do profissional no link exclusivo, logo da barbearia nos demais --}}
                <div class="w-[72px] h-[72px] sm:w-20 sm:h-20 rounded-2xl overflow-hidden ring-2 ring-green-500/30 shadow-2xl shadow-black/60 shrink-0">
                    @if($exclusivo && $preselected)
                        @if($preselected->foto)
                            <img src="{{ $preselected->foto }}" alt="{{ $preselected->nome }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-600 to-emerald-900 flex items-center justify-center">
                                <span class="text-3xl font-black text-white select-none">{{ $preselected->initials }}</span>
                            </div>
                        @endif
                    @elseif(!empty($barbearia?->logo))
                        @if(str_starts_with($barbearia->logo, 'data:image/'))
                            <img src="{{ $barbearia->logo }}" alt="{{ $barbearia->nome }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ Storage::url($barbearia->logo) }}" alt="{{ $barbearia->nome }}" class="w-full h-full object-cover">
                        @endif
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-green-600 to-emerald-900 flex items-center justify-center">
                            <span class="text-3xl font-black text-white select-none">{{ strtoupper(substr($barbearia?->nome ?? 'G',0,1)) }}</span>
                        </div>
                    @endif
                </div>

                <div class="pb-1 flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center gap-1.5 text-[9px] font-black text-green-400 bg-green-400/10 border border-green-500/20 px-2.5 py-1 rounded-full uppercase tracking-[.12em]">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse shrink-0"></span>
                            Aberto · Aceitando reservas
                        </span>
                    </div>
                    @if($exclusivo && $preselected)
                        <h1 class="text-[22px] sm:text-2xl font-black text-white tracking-tight leading-none">
                            {{ $preselected->nome }}
                        </h1>
                        <p class="text-sm text-gray-400 mt-1.5 leading-snug">
                            <i class="fa-solid fa-scissors text-green-600 text-[10px] mr-1"></i>{{ $barbearia?->nome }}
                        </p>
                    @else
                        <h1 class="text-[22px] sm:text-2xl font-black text-white tracking-tight leading-none">
                            {{ $barbearia?->nome ?? 'GlowSystem' }}
                        </h1>
                        @if(!empty($barbearia?->descricao))
                        <p class="text-sm text-gray-400 mt-1.5 leading-snug line-clamp-1">{{ $barbearia->descricao }}</p>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Stats strip --}}
            <div class="flex items-center gap-5 mt-5 border-t border-white/6 pt-4">
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <div class="w-6 h-6 rounded-lg bg-green-500/12 flex items-center justify-center">
                        <i class="fa-solid fa-scissors text-green-500 text-[10px]"></i>
                    </div>
                    <span><strong class="text-white font-bold">{{ $servicos->count() }}</strong> {{ $servicos->count() === 1 ? 'serviço' : 'serviços' }}</span>
                </div>
                <div class="w-px h-4 bg-white/8"></div>
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <div class="w-6 h-6 rounded-lg bg-green-500/12 flex items-center justify-center">
                        <i class="fa-solid fa-user-tie text-green-500 text-[10px]"></i>
                    </div>
                    @if($exclusivo && $preselected)
                        <span class="text-white font-bold">Especialista exclusivo</span>
                    @else
                        <span><strong class="text-white font-bold">{{ $profissionais->count() }}</strong> {{ $profissionais->count() === 1 ? 'profissional' : 'profissionais' }}</span>
                    @endif
                </div>
                <div class="w-px h-4 bg-white/8"></div>
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <div class="w-6 h-6 rounded-lg bg-green-500/12 flex items-center justify-center">
                        <i class="fa-regular fa-calendar text-green-500 text-[10px]"></i>
                    </div>
                    <span>Agendamento online</span>
                </div>
            </div>
        </div>

        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-b from-transparent to-[#070C12] pointer-events-none"></div>
    </div>

    {{-- ══════════════════════════════════════
         STEPPER STICKY
    ══════════════════════════════════════ --}}
    <div class="sticky top-0 z-50 glass-bar border-b border-white/5" x-show="etapa < 6" x-cloak>
        <div class="max-w-xl mx-auto px-5 py-3.5">
            <div class="flex items-center">
                @foreach([1=>'Serviço',2=>'Profissional',3=>'Data',4=>'Adicionais',5=>'Confirmar'] as $n=>$label)
                <div class="flex items-center {{ $n < 5 ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center shrink-0">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-black transition-all duration-300"
                             :class="etapa > {{ $n }} ? 'bg-green-500 text-white' : etapa === {{ $n }} ? 'bg-green-500 text-white shadow-[0_0_0_4px_rgba(34,197,94,.18)]' : 'bg-gray-800 text-gray-600 border border-gray-700/60'">
                            <span x-show="etapa <= {{ $n }}">{{ $n }}</span>
                            <i class="fa-solid fa-check text-[10px]" x-show="etapa > {{ $n }}" x-cloak></i>
                        </div>
                        <span class="hidden sm:block text-[9px] font-bold uppercase tracking-wider mt-1 transition-colors"
                              :class="etapa === {{ $n }} ? 'text-green-400' : etapa > {{ $n }} ? 'text-green-700' : 'text-gray-700'">{{ $label }}</span>
                    </div>
                    @if($n < 5)
                    <div class="flex-1 h-px mx-1.5 step-connector"
                         :class="etapa > {{ $n }} ? 'bg-green-500' : 'bg-gray-800'"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         CONTEÚDO PRINCIPAL
    ══════════════════════════════════════ --}}
    <div class="max-w-xl mx-auto px-5 pt-6"
         :class="etapa > 1 && etapa < 6 ? 'pb-28' : 'pb-16'">

        {{-- ─── ETAPA 1: CATÁLOGO ─────────────────────────────── --}}
        <div x-show="etapa === 1"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Search --}}
            <div class="relative mb-5">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm pointer-events-none"></i>
                <input type="text" x-model="busca" placeholder="Buscar serviço..."
                    class="w-full bg-gray-900/70 border border-gray-800 rounded-2xl pl-11 pr-4 py-3.5 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-green-600 focus:bg-gray-900 transition-all">
                <button type="button" x-show="busca" @click="busca = ''"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-400 transition-colors" x-cloak>
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            {{-- Section label --}}
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Serviços disponíveis</p>
                <p class="text-[10px] text-gray-700">{{ $servicos->count() }} ao total</p>
            </div>

            {{-- Service list --}}
            <div class="space-y-2">
            @forelse($servicos as $s)
            <button type="button"
                data-nome="{{ mb_strtolower($s->nome . ' ' . ($s->descricao ?? '')) }}"
                x-show="!busca || $el.dataset.nome.indexOf(busca.toLowerCase()) !== -1"
                @click="servico_id = {{ $s->id }}; servico_nome = '{{ addslashes($s->nome) }}'; avancarDeServico()"
                class="svc-card w-full flex items-center gap-4 p-3.5 rounded-2xl border border-gray-800/70 bg-gray-900/40 text-left group">

                {{-- Thumbnail --}}
                <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 flex-none">
                    @if($s->imagem_url)
                        <img src="{{ $s->imagem_url }}" alt="{{ $s->nome }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center rounded-xl"
                             style="background:{{ $s->cor ?? '#16a34a' }}15; border:1px solid {{ $s->cor ?? '#16a34a' }}28;">
                            <i class="fa-solid fa-scissors text-xl" style="color:{{ $s->cor ?? '#4ade80' }}cc;"></i>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-white text-sm group-hover:text-green-300 transition-colors leading-snug truncate">{{ $s->nome }}</p>
                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                        <span class="inline-flex items-center gap-1 bg-gray-800/80 text-gray-400 text-[10px] font-bold px-2 py-0.5 rounded-full">
                            <i class="fa-regular fa-clock text-[9px]"></i> {{ $s->duracao_minutos }}min
                        </span>
                        @if($s->descricao)
                        <span class="text-[11px] text-gray-600 truncate max-w-[120px]">{{ Str::limit($s->descricao, 30) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Price --}}
                <div class="shrink-0 flex flex-col items-end gap-2.5">
                    <span class="font-black text-green-400 text-base whitespace-nowrap">R$ {{ number_format($s->preco, 2, ',', '.') }}</span>
                    <div class="w-7 h-7 rounded-full bg-gray-800 group-hover:bg-green-500 flex items-center justify-center transition-all">
                        <i class="fa-solid fa-chevron-right text-[10px] text-gray-500 group-hover:text-white transition-colors"></i>
                    </div>
                </div>
            </button>
            @empty
            <div class="text-center py-16 rounded-2xl border border-dashed border-gray-800/50">
                <i class="fa-solid fa-scissors text-gray-800 text-3xl mb-3 block"></i>
                <p class="text-gray-500 text-sm font-medium">Nenhum serviço cadastrado.</p>
            </div>
            @endforelse
            </div>
        </div>

        {{-- ─── ETAPA 2: PROFISSIONAIS ────────────────────────── --}}
        <div x-show="etapa === 2"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">

            <div class="flex items-center gap-3 mb-6">
                <button type="button" @click="etapa = 1"
                    class="w-9 h-9 flex items-center justify-center bg-gray-800 hover:bg-gray-700 rounded-xl transition-colors shrink-0">
                    <i class="fa-solid fa-arrow-left text-gray-300 text-sm"></i>
                </button>
                <div>
                    <h2 class="text-lg font-black text-white tracking-tight">Escolha o profissional</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Para <span class="text-green-400 font-semibold" x-text="servico_nome"></span></p>
                </div>
            </div>

            {{-- Lista de profissionais --}}
            <div class="rounded-2xl border border-gray-800/70 overflow-hidden divide-y divide-gray-800/60">

                @if(!$preselected)
                <button type="button"
                    @click="profissional_id = ''; profissional_nome = 'Qualquer profissional'; proximaEtapa(3)"
                    class="prof-row w-full flex items-center gap-4 px-4 py-4 text-left group">
                    <div class="w-11 h-11 rounded-full bg-gray-800 border border-gray-700/50 flex items-center justify-center shrink-0 group-hover:border-green-600/50 transition-colors">
                        <i class="fa-solid fa-shuffle text-gray-400 group-hover:text-green-400 transition-colors"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-white text-sm group-hover:text-green-300 transition-colors">Qualquer disponível</p>
                        <p class="text-[11px] text-gray-600 mt-0.5">Primeiro profissional livre</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-700 group-hover:text-green-500 transition-colors text-xs shrink-0"></i>
                </button>
                @endif

                @foreach($profissionais as $p)
                <button type="button"
                    @click="profissional_id = {{ $p->id }}; profissional_nome = '{{ addslashes($p->nome) }}'; proximaEtapa(3)"
                    class="prof-row w-full flex items-center gap-4 px-4 py-4 text-left group">
                    @if($p->foto)
                        <img src="{{ $p->foto }}" class="w-11 h-11 rounded-full object-cover border border-green-700/25 shrink-0 group-hover:border-green-500/40 transition-all">
                    @else
                        <div class="w-11 h-11 rounded-full bg-green-900/25 border border-green-700/25 flex items-center justify-center font-black text-green-400 text-base shrink-0 group-hover:bg-green-500/20 group-hover:border-green-500/40 transition-all">
                            {{ $p->initials }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="font-bold text-white text-sm group-hover:text-green-300 transition-colors">{{ $p->nome }}</p>
                        <p class="text-[11px] mt-0.5
                            @if($preselected && $preselected->id === $p->id) text-green-500 font-bold @else text-gray-600 @endif">
                            @if($preselected && $preselected->id === $p->id) Sugerido para este serviço @else Especialista @endif
                        </p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-700 group-hover:text-green-500 transition-colors text-xs shrink-0"></i>
                </button>
                @endforeach
            </div>
        </div>

        {{-- ─── ETAPA 3: DATA & HORA ──────────────────────────── --}}
        <div x-show="etapa === 3"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">

            <div class="flex items-center gap-3 mb-5">
                <button type="button" @click="voltarDeDateHora()"
                    class="w-9 h-9 flex items-center justify-center bg-gray-800 hover:bg-gray-700 rounded-xl transition-colors shrink-0">
                    <i class="fa-solid fa-arrow-left text-gray-300 text-sm"></i>
                </button>
                <div>
                    <h2 class="text-lg font-black text-white tracking-tight">Data & Horário</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Escolha quando quer ser atendido</p>
                </div>
            </div>

            <div class="space-y-5">
                {{-- Data --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-2">Data</label>
                    <input type="date" x-model="data" min="{{ now()->format('Y-m-d') }}"
                        class="w-full bg-gray-900/70 border border-gray-800 rounded-2xl px-5 py-4 text-white font-bold focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600/15 transition-all">
                </div>

                {{-- Horários --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-3">Horário disponível</label>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2">
                        @foreach(['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00'] as $h)
                        <button type="button" @click="hora = '{{ $h }}'"
                            :class="hora === '{{ $h }}' ? 'bg-green-500 border-green-500 text-white shadow-[0_0_12px_rgba(34,197,94,.35)]' : 'bg-gray-900/70 border-gray-800 text-gray-400 hover:border-green-700/50 hover:text-white'"
                            class="time-chip border rounded-xl py-2.5 text-[13px] font-bold transition-all">
                            {{ $h }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <button type="button"
                    @click="if(hora) proximaEtapa({{ $produtos->count() > 0 ? 4 : 5 }})"
                    :class="hora ? 'bg-green-500 hover:bg-green-600 cursor-pointer shadow-[0_4px_20px_rgba(34,197,94,.22)]' : 'bg-gray-800/60 text-gray-600 cursor-not-allowed'"
                    class="w-full py-4 rounded-2xl text-sm font-black uppercase tracking-widest text-white transition-all">
                    Continuar <i class="fa-solid fa-arrow-right ml-1.5"></i>
                </button>
            </div>
        </div>

        {{-- ─── ETAPA 4: ADICIONAIS ───────────────────────────── --}}
        @if($produtos->count() > 0)
        <div x-show="etapa === 4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">

            <div class="flex items-center gap-3 mb-6">
                <button type="button" @click="etapa = 3"
                    class="w-9 h-9 flex items-center justify-center bg-gray-800 hover:bg-gray-700 rounded-xl transition-colors shrink-0">
                    <i class="fa-solid fa-arrow-left text-gray-300 text-sm"></i>
                </button>
                <div>
                    <h2 class="text-lg font-black text-white tracking-tight">Adicionais</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Produtos para garantir no atendimento</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-5">
                @foreach($produtos as $p)
                <label class="relative flex flex-col bg-gray-900/50 border-2 rounded-2xl overflow-hidden cursor-pointer transition-all group"
                       :class="produtos_ids.includes('{{ $p->id }}') ? 'border-green-500 ring-2 ring-green-500/15' : 'border-gray-800 hover:border-green-700/50'">
                    <input type="checkbox" value="{{ $p->id }}" x-model="produtos_ids" class="sr-only">
                    <div class="absolute top-2.5 right-2.5 z-10 w-5 h-5 rounded-full flex items-center justify-center border-2 transition-all"
                         :class="produtos_ids.includes('{{ $p->id }}') ? 'bg-green-500 border-green-500' : 'bg-black/50 border-white/20'">
                        <i class="fa-solid fa-check text-[9px] text-white transition-opacity"
                           :class="produtos_ids.includes('{{ $p->id }}') ? 'opacity-100' : 'opacity-0'"></i>
                    </div>
                    <div class="aspect-square bg-gray-800 overflow-hidden">
                        @if($p->imagem_url)
                            <img src="{{ $p->imagem_url }}" alt="{{ $p->nome }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-700">
                                <i class="fa-solid fa-box text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="font-bold text-white text-xs leading-snug line-clamp-2">{{ $p->nome }}</p>
                        <p class="font-black text-green-400 text-sm mt-1.5">R$ {{ number_format($p->preco_venda, 2, ',', '.') }}</p>
                    </div>
                </label>
                @endforeach
            </div>

            <button type="button" @click="proximaEtapa(5)"
                class="w-full py-4 bg-green-500 hover:bg-green-600 text-white rounded-2xl text-sm font-black uppercase tracking-widest transition-all shadow-[0_4px_20px_rgba(34,197,94,.22)]">
                <span x-text="produtos_ids.length > 0 ? 'Continuar com ' + produtos_ids.length + ' produto(s)' : 'Continuar sem adicionais'"></span>
                <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </div>
        @endif

        {{-- ─── ETAPA 5: CONFIRMAÇÃO ──────────────────────────── --}}
        <div x-show="etapa === 5"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display:none;">

            <div class="flex items-center gap-3 mb-6">
                <button type="button" @click="etapa = {{ $produtos->count() > 0 ? 4 : 3 }}"
                    class="w-9 h-9 flex items-center justify-center bg-gray-800 hover:bg-gray-700 rounded-xl transition-colors shrink-0">
                    <i class="fa-solid fa-arrow-left text-gray-300 text-sm"></i>
                </button>
                <div>
                    <h2 class="text-lg font-black text-white tracking-tight">Confirmar reserva</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Último passo — preencha seus dados</p>
                </div>
            </div>

            {{-- Resumo --}}
            <div class="bg-gray-900/50 border border-gray-800/70 rounded-2xl overflow-hidden mb-5">
                <div class="px-5 py-3 border-b border-gray-800/60 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-green-600 text-xs"></i>
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Resumo da reserva</p>
                </div>
                <div class="divide-y divide-gray-800/50">
                    <div class="grid grid-cols-2 px-5 py-3.5">
                        <span class="text-sm text-gray-500">Serviço</span>
                        <span class="text-sm font-bold text-white text-right truncate" x-text="servico_nome"></span>
                    </div>
                    <div class="grid grid-cols-2 px-5 py-3.5">
                        <span class="text-sm text-gray-500">Profissional</span>
                        <span class="text-sm font-bold text-white text-right" x-text="profissional_nome"></span>
                    </div>
                    <div class="grid grid-cols-2 px-5 py-3.5">
                        <span class="text-sm text-gray-500">Data</span>
                        <span class="text-sm font-bold text-white text-right" x-text="data.split('-').reverse().join('/')"></span>
                    </div>
                    <div class="grid grid-cols-2 px-5 py-3.5">
                        <span class="text-sm text-gray-500">Horário</span>
                        <span class="text-sm font-bold text-green-400 text-right" x-text="hora"></span>
                    </div>
                    <div x-show="produtos_ids.length > 0" class="grid grid-cols-2 px-5 py-3.5">
                        <span class="text-sm text-gray-500">Adicionais</span>
                        <span class="text-sm font-bold text-green-400 text-right" x-text="produtos_ids.length + ' produto(s)'"></span>
                    </div>
                </div>
            </div>

            {{-- Campos --}}
            <div class="space-y-3">
                <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Seus dados</p>

                <input type="text" x-model="nomeCliente" placeholder="Nome completo"
                    class="w-full bg-gray-900/70 border border-gray-800 rounded-2xl px-5 py-4 text-white font-medium text-sm placeholder-gray-600 focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600/15 transition-all">

                <input type="tel" x-model="telefone" @input="formatTelefone" placeholder="WhatsApp com DDD"
                    class="w-full bg-gray-900/70 border border-gray-800 rounded-2xl px-5 py-4 text-white font-medium text-sm placeholder-gray-600 focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600/15 transition-all">

                {{-- Checkbox VIP --}}
                <label class="flex items-center gap-3.5 bg-gray-900/40 border border-gray-800 hover:border-gray-700 rounded-2xl p-4 cursor-pointer transition-all"
                       :class="isVipChecked ? 'border-amber-500/30 bg-amber-950/20' : ''">
                    <input type="checkbox" id="is_vip_checkbox" x-model="isVipChecked" @change="toggleVip"
                        class="w-5 h-5 rounded border-gray-700 text-green-500 focus:ring-green-500 bg-gray-800 cursor-pointer shrink-0">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-white">Sou Cliente VIP</p>
                        <p class="text-[11px] text-gray-500 mt-0.5">Informe seu CPF para aplicar os benefícios</p>
                    </div>
                    <i class="fa-solid fa-crown shrink-0 transition-colors"
                       :class="isVipChecked ? 'text-amber-400' : 'text-amber-500/30'"></i>
                </label>

                {{-- Campo CPF — aparece ao marcar VIP --}}
                <div x-show="isVipChecked" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
                    <div class="relative">
                        <i class="fa-solid fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-amber-500/50 text-sm pointer-events-none"></i>
                        <input type="text" x-model="cpf" @input="checkVipStatus" placeholder="CPF (000.000.000-00)"
                            maxlength="14"
                            class="w-full bg-gray-900/70 border rounded-2xl pl-11 pr-4 py-4 text-white font-medium text-sm placeholder-gray-600 focus:outline-none focus:ring-2 transition-all"
                            :class="isVipVerificado ? 'border-green-500 focus:border-green-500 focus:ring-green-500/15' : 'border-amber-800/50 focus:border-amber-500 focus:ring-amber-500/10'">
                    </div>

                    {{-- VIP confirmado --}}
                    <div x-show="isVipVerificado" x-transition
                         class="flex items-center gap-3 bg-green-950/60 border border-green-500/25 rounded-2xl p-4 mt-3">
                        <div class="w-8 h-8 bg-green-500/15 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-crown text-green-400 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-green-400" x-text="'Plano ' + vipPlan + ' ativo!'"></p>
                            <p class="text-xs text-gray-400 mt-0.5">Benefícios serão aplicados automaticamente.</p>
                        </div>
                    </div>

                    {{-- CPF não encontrado --}}
                    <div x-show="!isVipVerificado && cpf.length === 14" x-transition
                         class="flex items-start gap-3 bg-red-950/40 border border-red-500/20 rounded-2xl p-4 mt-3">
                        <i class="fa-solid fa-triangle-exclamation text-red-400 mt-0.5 shrink-0 text-sm"></i>
                        <div>
                            <p class="text-sm font-bold text-red-400">CPF não encontrado</p>
                            <p class="text-xs text-gray-500 mt-0.5">Verifique o CPF cadastrado no Clube VIP.</p>
                        </div>
                    </div>
                </div>

                <textarea x-model="descricao" rows="2" placeholder="Observação (opcional)"
                    class="w-full bg-gray-900/70 border border-gray-800 rounded-2xl px-5 py-4 text-white text-sm placeholder-gray-600 focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600/15 transition-all resize-none"></textarea>
            </div>

            <button type="button" @click="confirmar()" :disabled="isLoading || !nomeCliente || !telefone"
                class="w-full mt-5 bg-green-500 hover:bg-green-600 py-5 rounded-2xl text-sm font-black uppercase tracking-widest text-white transition-all disabled:opacity-40 disabled:cursor-not-allowed shadow-[0_0_24px_rgba(34,197,94,.22)]">
                <template x-if="!isLoading"><span><i class="fa-solid fa-calendar-check mr-2"></i>Confirmar Reserva</span></template>
                <template x-if="isLoading"><span><i class="fa-solid fa-spinner fa-spin mr-2"></i>Processando...</span></template>
            </button>
        </div>

        {{-- ─── ETAPA 6: SUCESSO ──────────────────────────────── --}}
        <div x-show="etapa === 6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="text-center py-10 space-y-6"
             style="display:none;">

            <div class="relative mx-auto w-24 h-24">
                <div class="absolute inset-0 bg-green-500/10 rounded-full animate-ping opacity-40"></div>
                <div class="relative w-24 h-24 bg-green-500/8 border border-green-500/25 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-check text-green-400 text-3xl"></i>
                </div>
            </div>

            <div>
                <p class="text-[10px] font-black text-green-400 uppercase tracking-widest mb-2">Reserva confirmada!</p>
                <h2 class="text-2xl font-black text-white">Até breve!</h2>
                <p class="text-sm text-gray-400 mt-2">Você receberá os detalhes no WhatsApp.</p>
            </div>

            <div class="bg-gray-900/50 border border-gray-800/70 rounded-2xl overflow-hidden text-left">
                <div class="divide-y divide-gray-800/50">
                    <div class="grid grid-cols-2 px-5 py-3.5"><span class="text-sm text-gray-500">Serviço</span><span class="text-sm font-bold text-white text-right truncate" x-text="servico_nome"></span></div>
                    <div class="grid grid-cols-2 px-5 py-3.5"><span class="text-sm text-gray-500">Profissional</span><span class="text-sm font-bold text-white text-right" x-text="profissional_nome"></span></div>
                    <div class="grid grid-cols-2 px-5 py-3.5"><span class="text-sm text-gray-500">Data</span><span class="text-sm font-bold text-white text-right" x-text="data.split('-').reverse().join('/')"></span></div>
                    <div class="grid grid-cols-2 px-5 py-3.5"><span class="text-sm text-gray-500">Horário</span><span class="text-sm font-bold text-green-400 text-right" x-text="hora"></span></div>
                </div>
            </div>

            <button type="button" @click="window.location.reload()"
                class="inline-flex items-center gap-2 px-6 py-3 bg-white/5 border border-white/8 hover:bg-white/10 rounded-full text-xs font-bold text-gray-400 uppercase tracking-widest transition-all">
                <i class="fa-solid fa-plus text-green-500 text-xs"></i> Nova reserva
            </button>
        </div>

    </div>{{-- /main --}}

    {{-- ══════════════════════════════════════
         BARRA STICKY DE SELEÇÃO (etapas 2-5)
    ══════════════════════════════════════ --}}
    <div x-show="etapa > 1 && etapa < 6" x-transition x-cloak
         class="sel-bar fixed bottom-0 left-0 right-0 z-40 border-t border-white/6 px-5 py-3.5">
        <div class="max-w-xl mx-auto flex items-center gap-3.5">
            <div class="w-9 h-9 rounded-xl bg-green-500/12 border border-green-500/18 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-scissors text-green-400 text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate leading-none" x-text="servico_nome"></p>
                <p class="text-[11px] text-gray-500 mt-0.5 truncate"
                   x-text="hora ? profissional_nome + ' · ' + hora : (profissional_nome || 'Definindo detalhes...')"></p>
            </div>
            <span class="text-[10px] font-black text-gray-600 uppercase tracking-wider shrink-0" x-text="'Etapa ' + etapa + ' / 5'"></span>
        </div>
    </div>

    <script>
        const exclusivo = {{ $exclusivo ? 'true' : 'false' }};

        function booking() {
            return {
                etapa: 1,
                exclusivo: exclusivo,
                busca: '',
                barbearia_id: '{{ $barbearia->id }}',
                servico_id: '',
                servico_nome: '',
                profissional_id: '{{ $preselected ? $preselected->id : "" }}',
                profissional_nome: '{{ $preselected ? $preselected->nome : "" }}',
                data: '{{ now()->format("Y-m-d") }}',
                hora: '',
                descricao: '',
                produtos_ids: [],
                nomeCliente: '',
                telefone: '',
                cpf: '',
                isVipChecked: false,
                isVipVerificado: false,
                vipPlan: '',
                isLoading: false,

                init() { this.etapa = 1; },

                avancarDeServico() {
                    this.proximaEtapa(this.exclusivo ? 3 : 2);
                },

                voltarDeDateHora() {
                    this.proximaEtapa(this.exclusivo ? 1 : 2);
                },

                proximaEtapa(prox) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    this.etapa = prox;
                },

                // Formata telefone sem disparar check VIP
                formatTelefone() {
                    let val = this.telefone.replace(/\D/g, '').substring(0, 11);
                    this.telefone = val.replace(/^(\d{2})(\d)/g, '($1) $2').replace(/(\d)(\d{4})$/, '$1-$2');
                },

                // Reseta o CPF/status ao desmarcar VIP
                toggleVip() {
                    if (!this.isVipChecked) {
                        this.cpf = '';
                        this.isVipVerificado = false;
                        this.vipPlan = '';
                    }
                },

                // Verifica VIP pelo CPF digitado
                checkVipStatus() {
                    // Formata CPF: 000.000.000-00
                    let raw = this.cpf.replace(/\D/g, '').substring(0, 11);
                    let fmt = raw
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    this.cpf = fmt;

                    if (raw.length === 11) {
                        fetch(`/webhooks/check-vip?barbearia_id=${this.barbearia_id}&cpf=${encodeURIComponent(this.cpf)}&telefone=${encodeURIComponent(this.telefone)}&t=${Date.now()}`, { cache: 'no-store' })
                            .then(r => r.json())
                            .then(d => { this.isVipVerificado = d.isVip; this.vipPlan = d.isVip ? d.plano : ''; });
                    } else {
                        this.isVipVerificado = false;
                        this.vipPlan = '';
                    }
                },

                confirmar() {
                    if (!this.nomeCliente || !this.telefone) { alert('Preencha seu nome e WhatsApp.'); return; }
                    this.isLoading = true;
                    fetch('/agendar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            nomeCliente: this.nomeCliente,
                            telefone: this.telefone,
                            cpf: this.cpf,
                            profissional_id: this.profissional_id,
                            servico_id: this.servico_id,
                            barbearia_id: this.barbearia_id,
                            data: this.data,
                            hora: this.hora,
                            descricao: this.descricao,
                            produtos_ids: this.produtos_ids,
                            is_vip: this.isVipVerificado
                        })
                    })
                    .then(r => r.json())
                    .then(d => {
                        this.isLoading = false;
                        if (d.success) { this.proximaEtapa(6); }
                        else { alert('Erro ao agendar. Tente novamente.\n' + JSON.stringify(d.errors || d.message)); }
                    })
                    .catch(() => { this.isLoading = false; alert('Erro na conexão. Tente novamente.'); });
                }
            }
        }
    </script>

</body>
</html>
