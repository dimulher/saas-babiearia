{{-- ── Dashboard ── --}}
<div class="px-3 py-1">
    <a href="/panel/dashboard" class="sidebar-item {{ request()->is('panel/dashboard') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-house w-4 text-center"></i> Visão Geral
    </a>
</div>

{{-- ── Agendamentos ── --}}
<div class="sidebar-section-label">Agendamentos</div>
<div class="px-3 space-y-0.5">
    <a href="/panel/agendamentos" class="sidebar-item {{ request()->is('panel/agendamentos') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-calendar-days w-4 text-center"></i> Agendamentos
    </a>
    <a href="/panel/comandas" class="sidebar-item {{ request()->is('panel/comandas*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-receipt w-4 text-center"></i> Comandas
    </a>
    <a href="/panel/bloquear-horarios" class="sidebar-item {{ request()->is('panel/bloquear-horarios*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-calendar-xmark w-4 text-center"></i> Horários Bloqueados
    </a>
</div>

{{-- ── Financeiro ── --}}
<div class="sidebar-section-label">Financeiro</div>
<div class="px-3 space-y-0.5">
    <a href="/panel/financeiro" class="sidebar-item {{ request()->is('panel/financeiro*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-chart-line w-4 text-center"></i> Financeiro
    </a>
    <a href="/panel/contas" class="sidebar-item {{ request()->is('panel/contas*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-coins w-4 text-center"></i> Contas
    </a>
    <a href="/panel/relatorios" class="sidebar-item {{ request()->is('panel/relatorios*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-chart-pie w-4 text-center"></i> Relatórios
    </a>
</div>

{{-- ── Clube de Assinatura ── --}}
<div class="sidebar-section-label">Clube de Assinatura</div>
<div class="px-3 space-y-0.5">
    <a href="/panel/assinaturas" class="sidebar-item {{ request()->is('panel/assinaturas*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-crown w-4 text-center"></i> Clube VIP
    </a>
</div>

{{-- ── WhatsApp ── --}}
<div class="sidebar-section-label">WhatsApp</div>
<div class="px-3 space-y-0.5">
    <div x-data="{ open: {{ request()->is('panel/whatsapp*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-400 text-sm">
            <span class="flex items-center gap-2.5"><i class="fa-brands fa-whatsapp w-4 text-center text-green-500"></i> WhatsApp</span>
            <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
            <a href="/panel/whatsapp/mensagens" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/whatsapp/mensagens') ? 'active' : '' }}">
                <i class="fa-solid fa-bell w-3"></i> Mensagens e config.
            </a>
            <a href="/panel/whatsapp/recarregar-saldo" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/whatsapp/recarregar-saldo') ? 'active' : '' }}">
                <i class="fa-solid fa-plus w-3"></i> Recarregar saldo
            </a>
        </div>
    </div>
</div>

{{-- ── Configurações ── --}}
<div class="sidebar-section-label">Configurações</div>
<div class="px-3 space-y-0.5">
    <a href="/panel/profissionais" class="sidebar-item {{ request()->is('panel/profissionais*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-user-group w-4 text-center"></i> Profissionais
    </a>
    <a href="/panel/servicos" class="sidebar-item {{ request()->is('panel/servicos*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-magic-wand-sparkles w-4 text-center"></i> Serviços
    </a>
    <a href="/panel/produtos" class="sidebar-item {{ request()->is('panel/produtos*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-box w-4 text-center"></i> Produtos
    </a>
    <a href="/panel/expedientes" class="sidebar-item {{ request()->is('panel/expedientes*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-clock w-4 text-center"></i> Horários
    </a>
    <div x-data="{ open: {{ request()->is('panel/configuracoes*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-400 text-sm">
            <span class="flex items-center gap-2.5"><i class="fa-solid fa-gear w-4 text-center"></i> Configurações</span>
            <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
            <a href="/panel/configuracoes/sistema" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/configuracoes/sistema') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders w-3"></i> Sistema
            </a>
            <a href="/panel/configuracoes/barbearia" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/configuracoes/barbearia') ? 'active' : '' }}">
                <i class="fa-solid fa-store w-3"></i> Estabelecimento
            </a>
            <a href="/panel/configuracoes/agendamento" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/configuracoes/agendamento') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check w-3"></i> Agendamento
            </a>
            <a href="/panel/configuracoes/conta" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/configuracoes/conta') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear w-3"></i> Minha Conta
            </a>
        </div>
    </div>
    <a href="/panel/logs" class="sidebar-item {{ request()->is('panel/logs*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i> Logs
    </a>
</div>

{{-- ── Meu Plano ── --}}
<div class="sidebar-section-label">Meu Plano</div>
<div class="px-3 pb-3">
    <a href="/panel/meu-plano" class="sidebar-item {{ request()->is('panel/meu-plano') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-table-list w-4 text-center"></i> Meu Plano
    </a>
    <a href="/panel/meu-plano" class="sidebar-item {{ request()->is('panel/minha-assinatura') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400 text-sm">
        <i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i> Minha Assinatura
    </a>
</div>
