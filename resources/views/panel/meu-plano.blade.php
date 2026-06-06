@extends('layouts.app')
@section('title', 'Assinatura e Planos - GlowSystem')

@section('content')
<div class="space-y-10 max-w-6xl mx-auto">

    <div class="text-center sm:text-left">
        <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Assinatura e Planos</h1>
        <p class="text-sm text-gray-400 font-medium">Potencialize seu negócio com as ferramentas certas.</p>
    </div>

    <!-- Plano atual -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-8 flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden shadow-sm">
        <div class="absolute top-0 right-0 w-40 h-40 bg-green-900/30 rounded-full -mr-20 -mt-20 opacity-50"></div>
        <div class="relative z-10 text-center sm:text-left">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-widest bg-violet-100 text-green-500 mb-3 border border-violet-200/50">Status: Ativo</span>
            <h2 class="text-3xl font-bold text-white uppercase tracking-tighter leading-none italic">Plano <span class="text-green-500">Free Experience</span></h2>
            <p class="text-[10px] text-gray-400 font-bold mt-2 uppercase tracking-widest">Recursos essenciais para pequenos estabelecimentos.</p>
        </div>
        <div class="relative z-10 text-center sm:text-right">
            <p class="text-5xl font-bold text-white tracking-tighter italic">R$ 0</p>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Investimento mensal</p>
        </div>
    </div>

    <!-- Planos disponíveis -->
    <div>
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-8 ml-1">Evolua sua operação</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Básico -->
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-8 space-y-8 flex flex-col hover:border-violet-200 transition-all shadow-sm">
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-widest">Essential</h3>
                    <div class="flex items-baseline gap-1 mt-4">
                        <span class="text-sm font-bold text-gray-400 italic">R$</span>
                        <p class="text-4xl font-bold text-white tracking-tighter italic">49</p>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">/mês</span>
                    </div>
                </div>
                <ul class="space-y-4 flex-1">
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-400">
                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> Agendamentos ilimitados
                    </li>
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-400">
                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> Até 3 profissionais
                    </li>
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-400">
                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> Link de agendamento personalizado
                    </li>
                    <li class="flex items-center gap-3 text-[10px] font-bold text-gray-300 line-through italic">
                        <i class="fa-solid fa-xmark text-rose-300"></i> WhatsApp automático
                    </li>
                </ul>
                <button class="w-full border-2 border-gray-800 text-white py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:border-green-500 hover:text-green-500 transition-all italic">Upgrade para Essential</button>
            </div>

            <!-- Profissional (Destaque) -->
            <div class="bg-gray-900 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border-none p-8 space-y-8 flex flex-col relative shadow-2xl scale-105 z-10 overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-500 rounded-full -mr-16 -mt-16 opacity-30 blur-3xl"></div>
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-green-500 text-white text-[9px] font-bold uppercase tracking-widest px-6 py-2 rounded-full shadow-lg">Mais Vendido</span>
                
                <div>
                    <h3 class="text-xs font-bold text-green-400 uppercase tracking-widest">Business Elite</h3>
                    <div class="flex items-baseline gap-1 mt-4">
                        <span class="text-sm font-bold text-green-400 italic">R$</span>
                        <p class="text-4xl font-bold text-white tracking-tighter italic">89</p>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">/mês</span>
                    </div>
                </div>
                <ul class="space-y-4 flex-1">
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-200">
                        <i class="fa-solid fa-star text-green-400 text-[10px]"></i> Profissionais Ilimitados
                    </li>
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-200">
                        <i class="fa-solid fa-star text-green-400 text-[10px]"></i> WhatsApp Automático Integrado
                    </li>
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-200">
                        <i class="fa-solid fa-star text-green-400 text-[10px]"></i> Relatórios Avançados (BI)
                    </li>
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-200">
                        <i class="fa-solid fa-star text-green-400 text-[10px]"></i> Gestão de Comissões Complexa
                    </li>
                </ul>
                <button class="w-full bg-green-500 text-white py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-green-600 transition-all shadow-xl shadow-green-900/50 italic">Contratar Business Elite</button>
            </div>

            <!-- Premium -->
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-8 space-y-8 flex flex-col hover:border-violet-200 transition-all shadow-sm">
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-widest">Multi-Unit Pro</h3>
                    <div class="flex items-baseline gap-1 mt-4">
                        <span class="text-sm font-bold text-gray-400 italic">R$</span>
                        <p class="text-4xl font-bold text-white tracking-tighter italic">149</p>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">/mês</span>
                    </div>
                </div>
                <ul class="space-y-4 flex-1">
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-400">
                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> Tudo do Business Elite
                    </li>
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-400">
                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> Gestão Multi-Unidades (Filiais)
                    </li>
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-400">
                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> Suporte Concierge Prioritário
                    </li>
                    <li class="flex items-center gap-3 text-xs font-bold text-gray-400">
                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i> Integração via API Pública
                    </li>
                </ul>
                <button class="w-full border-2 border-gray-800 text-white py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:border-green-500 hover:text-green-500 transition-all italic">Upgrade para Multi-Unit</button>
            </div>

        </div>
    </div>

</div>
@endsection
