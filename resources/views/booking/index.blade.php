<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar — GlowSystem</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }

        .hero-cover {
            background: linear-gradient(135deg, #1e0a3c 0%, #2d1060 40%, #4c1d95 70%, #1a0533 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-cover::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(139,92,246,0.25) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(167,139,250,0.15) 0%, transparent 50%);
        }
        .hero-cover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(to bottom, transparent, #030712);
        }

        .profile-avatar-ring {
            background: linear-gradient(135deg, #7c3aed, #a78bfa, #7c3aed);
            padding: 3px;
            border-radius: 28px;
        }
        .profile-avatar-inner {
            background: #030712;
            border-radius: 25px;
            overflow: hidden;
        }

        .step-bar-track {
            background: rgba(255,255,255,0.06);
            border-radius: 99px;
            overflow: hidden;
        }
        .step-bar-fill {
            background: linear-gradient(90deg, #7c3aed, #a78bfa);
            border-radius: 99px;
            transition: width 0.5s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 0 0 12px rgba(139,92,246,0.5);
        }

        .step-dot {
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        }
        .step-dot.active {
            box-shadow: 0 0 0 4px rgba(139,92,246,0.25), 0 0 16px rgba(139,92,246,0.5);
        }

        .card-service {
            background: rgba(17,24,39,0.8);
            border: 1px solid rgba(255,255,255,0.06);
            transition: all 0.25s ease;
        }
        .card-service:hover {
            border-color: rgba(139,92,246,0.6);
            background: rgba(139,92,246,0.06);
            transform: translateY(-1px);
            box-shadow: 0 8px 32px rgba(139,92,246,0.12);
        }

        .sticky-nav {
            backdrop-filter: blur(20px);
            background: rgba(3,7,18,0.85);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
    </style>
</head>
<body class="bg-[#030712] min-h-screen text-white" x-data="booking()">

    <!-- ═══════════════ HERO HEADER ═══════════════ -->
    <div class="hero-cover h-44 relative">
        <!-- Decorative orbs -->
        <div class="absolute top-4 left-8 w-32 h-32 rounded-full bg-violet-600/10 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-8 right-12 w-24 h-24 rounded-full bg-purple-400/10 blur-2xl pointer-events-none"></div>
    </div>

    <!-- Profile Card (sobreposição ao hero) -->
    <div class="max-w-lg mx-auto px-4 relative" style="margin-top: -56px; z-index: 10;">
        <div class="flex flex-col items-center text-center">

            <!-- Avatar do estabelecimento -->
            <div class="profile-avatar-ring shadow-[0_8px_32px_rgba(124,58,237,0.4)] mb-4">
                <div class="profile-avatar-inner w-24 h-24 flex items-center justify-center">
                    @if(!empty($barbearia?->logo))
                        <img src="{{ Storage::url($barbearia->logo) }}"
                             alt="{{ $barbearia->nome }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-violet-700 to-purple-900 flex items-center justify-center">
                            <span class="text-3xl font-black text-white select-none">
                                {{ strtoupper(substr($barbearia?->nome ?? 'G', 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Nome e status -->
            <h1 class="text-2xl font-black tracking-tight text-white uppercase leading-none">
                {{ $barbearia?->nome ?? 'GlowSystem' }}
            </h1>
            @if(!empty($barbearia?->descricao))
                <p class="text-sm text-gray-400 font-medium mt-1 max-w-xs">{{ $barbearia->descricao }}</p>
            @endif
            <div class="flex items-center gap-2 mt-3 px-4 py-1.5 bg-green-500/10 border border-green-500/20 rounded-full">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse flex-shrink-0"></span>
                <span class="text-xs text-green-400 font-bold uppercase tracking-widest">Aberto · Aceitando reservas</span>
            </div>
        </div>
    </div>

    <!-- ═══════════════ STICKY PROGRESS BAR ═══════════════ -->
    <div class="sticky top-0 z-50 sticky-nav mt-8">

        <!-- Step indicator compacto -->
        <div class="max-w-lg mx-auto px-4 py-3">
            <div class="flex items-center gap-3">
                <!-- Dots -->
                <div class="flex items-center gap-2">
                    <template x-for="i in 5" :key="i">
                        <div class="step-dot rounded-full transition-all"
                             :class="i < etapa ? 'w-2 h-2 bg-violet-500' :
                                     i === etapa ? 'w-6 h-2 bg-violet-500 active' :
                                     'w-2 h-2 bg-gray-700'">
                        </div>
                    </template>
                </div>
                <!-- Label da etapa atual -->
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1" x-text="[
                    '', 'Escolha o serviço', 'Profissional', 'Data & Hora', 'Adicionais', 'Confirmar'
                ][etapa]"></span>
                <!-- Passo X/5 -->
                <span class="ml-auto text-xs font-black text-violet-400" x-text="etapa + '/5'"></span>
            </div>
            <!-- Barra de progresso -->
            <div class="step-bar-track h-0.5 mt-2">
                <div class="step-bar-fill h-full" :style="`width: ${(etapa / 5) * 100}%`"></div>
            </div>
        </div>
    </div>

    <!-- ═══════════════ CONTEÚDO PRINCIPAL ═══════════════ -->
    <div class="max-w-lg mx-auto px-4 py-6">

        <!-- Etapa 1: Selecionar serviço -->
        <div x-show="etapa === 1" x-transition.opacity class="space-y-3">
            <div class="mb-6">
                <p class="text-xs font-bold text-violet-400 uppercase tracking-widest mb-1">Passo 1 de 5</p>
                <h2 class="text-2xl font-black text-white uppercase tracking-tight">Escolha o serviço</h2>
            </div>

            @forelse($servicos as $s)
            <button @click="servico_id = {{ $s->id }}; servico_nome = '{{ $s->nome }}'; avancarDeServico()"
                class="card-service w-full flex items-center gap-4 p-5 rounded-[24px] group">
                <!-- Ícone do serviço -->
                <div class="w-12 h-12 rounded-2xl bg-violet-900/40 border border-violet-700/40 flex items-center justify-center flex-shrink-0 group-hover:bg-violet-600/30 transition-colors">
                    <i class="fa-solid fa-scissors text-violet-400 text-sm"></i>
                </div>
                <div class="text-left flex-1">
                    <p class="font-bold text-white text-base group-hover:text-violet-300 transition-colors">{{ $s->nome }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 font-medium"><i class="fa-regular fa-clock mr-1"></i>{{ $s->duracao_minutos }} min</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="font-black text-white text-lg">R$ {{ number_format($s->preco, 2, ',', '.') }}</p>
                    <div class="w-7 h-7 rounded-full bg-white/5 group-hover:bg-violet-600 flex items-center justify-center ml-auto mt-1.5 transition-colors">
                        <i class="fa-solid fa-chevron-right text-[10px] text-gray-500 group-hover:text-white"></i>
                    </div>
                </div>
            </button>
            @empty
            <div class="text-center py-12 rounded-[24px] border border-dashed border-gray-800">
                <i class="fa-solid fa-calendar-xmark text-gray-700 text-3xl mb-3"></i>
                <p class="text-gray-500 font-medium">Nenhum serviço disponível no momento.</p>
            </div>
            @endforelse
        </div>

        <!-- Etapa 2: Selecionar profissional -->
        <div x-show="etapa === 2" x-transition.opacity class="space-y-3" style="display: none;">
            <div class="flex items-center gap-3 mb-6">
                <button @click="etapa = 1"
                    class="w-9 h-9 flex items-center justify-center bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-colors flex-shrink-0">
                    <i class="fa-solid fa-arrow-left text-gray-400 text-sm"></i>
                </button>
                <div>
                    <p class="text-xs font-bold text-violet-400 uppercase tracking-widest">Passo 2 de 5</p>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tight leading-none">Profissional</h2>
                </div>
            </div>

            @if(!$preselected)
            <button @click="profissional_id = ''; profissional_nome = 'Qualquer profissional'; proximaEtapa(3)"
                class="card-service w-full flex items-center gap-4 p-5 rounded-[24px] group">
                <div class="w-12 h-12 rounded-2xl bg-gray-800/80 border border-white/10 flex items-center justify-center flex-shrink-0 group-hover:bg-violet-600/20 transition-colors">
                    <i class="fa-solid fa-shuffle text-gray-400 group-hover:text-violet-400"></i>
                </div>
                <div class="text-left flex-1">
                    <p class="font-bold text-white text-base group-hover:text-violet-300 transition-colors">Qualquer profissional</p>
                    <p class="text-xs text-gray-500 mt-0.5 font-medium">Primeiro disponível</p>
                </div>
                <div class="w-7 h-7 rounded-full bg-white/5 group-hover:bg-violet-600 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-500 group-hover:text-white"></i>
                </div>
            </button>
            @endif

            @foreach($profissionais as $p)
            <button @click="profissional_id = {{ $p->id }}; profissional_nome = '{{ $p->nome }}'; proximaEtapa(3)"
                class="card-service w-full flex items-center gap-4 p-5 rounded-[24px] group">
                <div class="w-12 h-12 rounded-2xl bg-violet-900/40 border border-violet-700/40 flex items-center justify-center font-black text-violet-400 text-lg flex-shrink-0 group-hover:bg-violet-600/30 transition-colors">
                    {{ $p->initials }}
                </div>
                <div class="text-left flex-1">
                    <p class="font-bold text-white text-base group-hover:text-violet-300 transition-colors">
                        {{ $p->nome }}
                        @if($preselected && $preselected->id === $p->id)
                            <span class="ml-2 px-2 py-0.5 bg-violet-600 text-white text-[10px] uppercase font-black rounded-md">Sugerido</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5 font-medium">Especialista</p>
                </div>
                <div class="w-7 h-7 rounded-full bg-white/5 group-hover:bg-violet-600 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-500 group-hover:text-white"></i>
                </div>
            </button>
            @endforeach
        </div>

        <!-- Etapa 3: Selecionar data e hora -->
        <div x-show="etapa === 3" x-transition.opacity class="space-y-6" style="display: none;">
            <div class="flex items-center gap-3 mb-6">
                <button @click="voltarDeDateHora()"
                    class="w-9 h-9 flex items-center justify-center bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-colors flex-shrink-0">
                    <i class="fa-solid fa-arrow-left text-gray-400 text-sm"></i>
                </button>
                <div>
                    <p class="text-xs font-bold text-violet-400 uppercase tracking-widest" x-text="exclusivo ? 'Passo 2 de 4' : 'Passo 3 de 5'"></p>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tight leading-none">Data &amp; Hora</h2>
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Selecione a data</label>
                <input type="date" x-model="data" min="{{ now()->format('Y-m-d') }}"
                    class="w-full bg-gray-900 border border-gray-800 rounded-[20px] px-5 py-4 text-white font-bold focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all">
            </div>

            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Horários disponíveis</label>
                <div class="grid grid-cols-4 gap-3">
                    @foreach(['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30', '17:00', '17:30', '18:00', '18:30', '19:00'] as $h)
                    <button @click="hora = '{{ $h }}'"
                        :class="hora === '{{ $h }}' ? 'bg-violet-600 border-violet-500 text-white shadow-[0_0_15px_rgba(139,92,246,0.4)]' : 'bg-gray-900 border-gray-800 text-gray-300 hover:border-violet-500 hover:text-white'"
                        class="border rounded-[16px] py-3 text-sm font-black transition-all">
                        {{ $h }}
                    </button>
                    @endforeach
                </div>
            </div>

            <button @click="if(hora) proximaEtapa({{ $produtos->count() > 0 ? 4 : 5 }})" :class="hora ? 'bg-white text-gray-950 hover:bg-gray-100' : 'bg-gray-800 text-gray-500 cursor-not-allowed'"
                class="w-full py-4 rounded-[20px] text-sm font-black uppercase tracking-widest transition-colors mt-4">
                Continuar
            </button>
        </div>

        <!-- Etapa 4: Adicionais (Produtos) -->
        @if($produtos->count() > 0)
        <div x-show="etapa === 4" x-transition.opacity class="space-y-6" style="display: none;">
            <div class="flex items-center gap-3 mb-6">
                <button @click="etapa = 3"
                    class="w-9 h-9 flex items-center justify-center bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-colors flex-shrink-0">
                    <i class="fa-solid fa-arrow-left text-gray-400 text-sm"></i>
                </button>
                <div>
                    <p class="text-xs font-bold text-violet-400 uppercase tracking-widest">Passo 4 de 5</p>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tight leading-none">Adicionais</h2>
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-1">Catálogo de Produtos</label>
                <p class="text-sm text-gray-500 font-medium mb-4 ml-1">Deseja garantir algum produto? Ele estará separado para você após o serviço.</p>
                
                <div class="space-y-3">
                    @foreach($produtos as $p)
                    <label class="flex items-center justify-between p-4 bg-gray-900 border border-gray-800 rounded-[20px] cursor-pointer hover:border-violet-500 transition-all"
                           :class="produtos_ids.includes({{ $p->id }}) ? 'border-violet-500 bg-violet-900/10' : ''">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center border border-gray-700">
                                <i class="fa-solid fa-box text-gray-400"></i>
                            </div>
                            <div>
                                <p class="font-bold text-white text-base">{{ $p->nome }}</p>
                                <p class="font-black text-violet-400 mt-0.5">R$ {{ number_format($p->preco, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        <input type="checkbox" value="{{ $p->id }}" x-model="produtos_ids" class="w-5 h-5 rounded border-gray-700 text-violet-600 focus:ring-violet-600 bg-gray-800">
                    </label>
                    @endforeach
                </div>
            </div>

            <button @click="proximaEtapa(5)" class="w-full py-4 bg-white text-gray-950 hover:bg-gray-100 rounded-[20px] text-sm font-black uppercase tracking-widest transition-colors mt-4">
                Ir para Confirmação
            </button>
        </div>
        @endif

        <!-- Etapa 5: Confirmação -->
        <div x-show="etapa === 5" x-transition.opacity class="space-y-6" style="display: none;">
            <div class="flex items-center gap-3 mb-6">
                <button @click="etapa = {{ $produtos->count() > 0 ? 4 : 3 }}"
                    class="w-9 h-9 flex items-center justify-center bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl transition-colors flex-shrink-0">
                    <i class="fa-solid fa-arrow-left text-gray-400 text-sm"></i>
                </button>
                <div>
                    <p class="text-xs font-bold text-violet-400 uppercase tracking-widest">Passo 5 de 5</p>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tight leading-none">Confirmar Reserva</h2>
                </div>
            </div>

            <!-- Resumo -->
            <div class="bg-gray-900 border border-gray-800 rounded-[24px] p-6 space-y-4">
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest border-b border-gray-800 pb-3 mb-4">Resumo do Pedido</h3>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400 font-medium">Serviço</span>
                    <span class="text-sm font-bold text-white" x-text="servico_nome"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400 font-medium">Profissional</span>
                    <span class="text-sm font-bold text-white" x-text="profissional_nome"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400 font-medium">Data</span>
                    <span class="text-sm font-bold text-white" x-text="data.split('-').reverse().join('/')"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400 font-medium">Horário</span>
                    <span class="text-sm font-bold text-white" x-text="hora"></span>
                </div>
                
                <div x-show="produtos_ids.length > 0" class="pt-3 border-t border-gray-800 mt-3">
                    <div class="flex justify-between items-start">
                        <span class="text-sm text-gray-400 font-medium">Produtos</span>
                        <span class="text-sm font-bold text-violet-400 text-right" x-text="produtos_ids.length + ' item(ns)'"></span>
                    </div>
                </div>
            </div>

            <!-- Dados do cliente -->
            <div class="space-y-4">
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest ml-1">Seus Dados</h3>
                
                <div>
                    <input type="text" x-model="nomeCliente"
                        class="w-full bg-gray-900 border border-gray-800 rounded-[20px] px-5 py-4 text-white font-bold focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all placeholder-gray-600"
                        placeholder="Nome completo">
                </div>
                <div>
                    <input type="tel" x-model="telefone" @input="checkVipStatus"
                        class="w-full bg-gray-900 border border-gray-800 rounded-[20px] px-5 py-4 text-white font-bold focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all placeholder-gray-600"
                        placeholder="WhatsApp (com DDD)">
                </div>

                <!-- Toggle Sou VIP -->
                <div class="flex items-center gap-3 bg-gray-900/50 border border-gray-800 rounded-[20px] p-4">
                    <input type="checkbox" id="is_vip_checkbox" x-model="isVipChecked" @change="checkVipStatus" class="w-5 h-5 rounded border-gray-700 text-violet-600 focus:ring-violet-600 bg-gray-900 cursor-pointer">
                    <label for="is_vip_checkbox" class="text-sm font-bold text-gray-300 cursor-pointer flex-1">Sou Cliente VIP</label>
                </div>

                <!-- Feedback VIP -->
                <div x-show="isVipChecked && isVipVerificado" x-transition class="bg-violet-900/20 border border-violet-500/30 rounded-[20px] p-4 flex items-start gap-3">
                    <i class="fa-solid fa-crown text-violet-400 mt-1"></i>
                    <div>
                        <p class="text-sm font-bold text-violet-400 uppercase tracking-widest" x-text="'Plano ' + vipPlan + ' Ativado!'"></p>
                        <p class="text-[10px] font-medium text-gray-400 mt-0.5">Seus benefícios VIP serão aplicados automaticamente nesta reserva.</p>
                    </div>
                </div>

                <!-- Aviso VIP Inválido -->
                <div x-show="isVipChecked && !isVipVerificado && telefone.length >= 14" x-transition class="bg-red-900/10 border border-red-500/20 rounded-[20px] p-4 flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-red-400 mt-1"></i>
                    <div>
                        <p class="text-xs font-bold text-red-400">Assinatura não encontrada</p>
                        <p class="text-[10px] font-medium text-gray-500 mt-0.5">Verifique se o WhatsApp digitado é o mesmo cadastrado no seu Clube VIP.</p>
                    </div>
                </div>

                <div>
                    <textarea x-model="descricao" rows="2" placeholder="Deixe uma observação para o profissional (opcional)"
                        class="w-full bg-gray-900 border border-gray-800 rounded-[20px] px-5 py-4 text-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all resize-none placeholder-gray-600"></textarea>
                </div>
            </div>

            <button @click="confirmar()" :disabled="isLoading || !nomeCliente || !telefone"
                class="w-full bg-violet-600 hover:bg-violet-700 py-5 rounded-[20px] text-sm font-black uppercase tracking-widest text-white transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-[0_0_20px_rgba(139,92,246,0.3)] mt-6">
                <i class="fa-solid fa-check mr-2" x-show="!isLoading"></i>
                <i class="fa-solid fa-spinner fa-spin mr-2" x-show="isLoading" style="display: none;"></i>
                <span x-text="isLoading ? 'Processando...' : 'Confirmar Reserva'"></span>
            </button>
        </div>

        <!-- Etapa 6: Sucesso -->
        <div x-show="etapa === 6" x-transition.opacity class="text-center py-16 space-y-6" style="display: none;">
            <!-- Ícone animado -->
            <div class="relative mx-auto w-28 h-28">
                <div class="absolute inset-0 bg-green-500/10 rounded-full animate-ping opacity-30"></div>
                <div class="relative w-28 h-28 bg-green-500/10 border border-green-500/30 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-check text-green-400 text-4xl"></i>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-green-400 uppercase tracking-widest mb-2">Tudo certo!</p>
                <h2 class="text-3xl font-black text-white uppercase tracking-tight">Reserva Confirmada!</h2>
                <p class="text-gray-400 font-medium mt-2">Você receberá os detalhes no seu WhatsApp.</p>
            </div>

            <div class="bg-white/[0.03] border border-white/10 rounded-[24px] p-6 text-left space-y-3 mx-auto">
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest border-b border-white/10 pb-3 mb-3">Resumo</h3>
                <div class="flex justify-between items-center"><span class="text-sm text-gray-400">Serviço</span><span class="text-sm font-bold text-white" x-text="servico_nome"></span></div>
                <div class="flex justify-between items-center"><span class="text-sm text-gray-400">Profissional</span><span class="text-sm font-bold text-white" x-text="profissional_nome"></span></div>
                <div class="flex justify-between items-center"><span class="text-sm text-gray-400">Data</span><span class="text-sm font-bold text-white" x-text="data.split('-').reverse().join('/')"></span></div>
                <div class="flex justify-between items-center"><span class="text-sm text-gray-400">Horário</span><span class="text-sm font-bold text-violet-400" x-text="hora"></span></div>
            </div>

            <button @click="window.location.reload()"
                class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-violet-600/10 border border-violet-500/30 hover:bg-violet-600/20 rounded-full text-xs font-black text-violet-400 uppercase tracking-widest transition-all">
                Fazer nova reserva <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>

    </div>

    <script>
        const exclusivo = {{ $exclusivo ? 'true' : 'false' }};

        function booking() {
            return {
                // Modo exclusivo: link de profissional → começa no serviço, pula etapa 2
                etapa: 1,
                exclusivo: exclusivo,
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
                isVipChecked: false,
                isVipVerificado: false,
                vipPlan: '',
                isLoading: false,

                init() {
                    this.etapa = 1;
                },

                // Se exclusivo: serviço → data/hora (pula profissional)
                // Se normal:    serviço → profissional → data/hora
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

                checkVipStatus() {
                    let val = this.telefone.replace(/\D/g, '').substring(0, 11);
                    this.telefone = val.replace(/^(\d{2})(\d)/g, '($1) $2').replace(/(\d)(\d{4})$/, '$1-$2');

                    if (this.isVipChecked && val.length >= 10) {
                        fetch(`/api/check-vip?barbearia_id=${this.barbearia_id}&telefone=${encodeURIComponent(this.telefone)}&t=${Date.now()}`, { cache: 'no-store' })
                            .then(res => res.json())
                            .then(data => {
                                if (data.isVip) {
                                    this.isVipVerificado = true;
                                    this.vipPlan = data.plano;
                                } else {
                                    this.isVipVerificado = false;
                                    this.vipPlan = '';
                                }
                            });
                    } else {
                        this.isVipVerificado = false;
                        this.vipPlan = '';
                    }
                },

                confirmar() {
                    if(!this.nomeCliente || !this.telefone) {
                        alert('Preencha seu nome e WhatsApp.');
                        return;
                    }

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
                    .then(response => response.json())
                    .then(data => {
                        this.isLoading = false;
                        if(data.success) {
                            this.proximaEtapa(6);
                        } else {
                            alert('Erro ao agendar. Tente novamente. Detalhes: ' + JSON.stringify(data.errors || data.message));
                        }
                    })
                    .catch(error => {
                        this.isLoading = false;
                        alert('Erro ao agendar. Verifique a conexão.');
                        console.error('Error:', error);
                    });
                }
            }
        }
    </script>

</body>
</html>
