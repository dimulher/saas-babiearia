<a href="/panel/dashboard" class="sidebar-item {{ request()->is('panel/dashboard') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-house w-4 text-gray-400"></i> Dashboard
</a>

<a href="/panel/comandas" class="sidebar-item {{ request()->is('panel/comandas*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-receipt w-4 text-gray-400"></i> Comandas
</a>

<a href="/panel/agendamentos" class="sidebar-item {{ request()->is('panel/agendamentos') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-calendar-days w-4 text-gray-400"></i> Agendamentos
</a>

<a href="/panel/financeiro" class="sidebar-item {{ request()->is('panel/financeiro*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-chart-line w-4 text-gray-400"></i> Financeiro
</a>

{{-- WhatsApp --}}
<div x-data="{ open: {{ request()->is('panel/whatsapp*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-400">
        <span class="flex items-center gap-2.5"><i class="fa-brands fa-whatsapp w-4 text-green-500"></i> WhatsApp</span>
        <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
        <a href="/panel/whatsapp/mensagens" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/whatsapp/mensagens') ? 'active' : '' }}"><i class="fa-solid fa-bell w-3 text-gray-400"></i> Mensagens e configurações</a>
        <a href="/panel/whatsapp/recarregar-saldo" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/whatsapp/recarregar-saldo') ? 'active' : '' }}"><i class="fa-solid fa-plus w-3 text-gray-400"></i> Recarregar saldo</a>
    </div>
</div>

<a href="/panel/contas" class="sidebar-item {{ request()->is('panel/contas*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-coins w-4 text-gray-400"></i> Contas
</a>

<a href="/panel/assinaturas" class="sidebar-item {{ request()->is('panel/assinaturas*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-crown w-4 text-gray-400"></i> Clube VIP
</a>

<a href="/panel/relatorios" class="sidebar-item {{ request()->is('panel/relatorios*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-chart-pie w-4 text-gray-400"></i> Relatórios
</a>

<div class="border-t border-gray-100 my-2"></div>

<a href="/panel/profissionais" class="sidebar-item {{ request()->is('panel/profissionais*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-user-group w-4 text-gray-400"></i> Profissionais
</a>

<a href="/panel/servicos" class="sidebar-item {{ request()->is('panel/servicos*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-magic-wand-sparkles w-4 text-gray-400"></i> Serviços
</a>
<a href="/panel/produtos" class="sidebar-item {{ request()->is('panel/produtos*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-box w-4 text-gray-400"></i> Produtos
</a>
<a href="/panel/expedientes" class="sidebar-item {{ request()->is('panel/expedientes*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-clock w-4 text-gray-400"></i> Expedientes
</a>
<a href="/panel/bloquear-horarios" class="sidebar-item {{ request()->is('panel/bloquear-horarios*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-calendar-xmark w-4 text-gray-400"></i> Bloquear Horários
</a>
<a href="/panel/logs" class="sidebar-item {{ request()->is('panel/logs*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-clock-rotate-left w-4 text-gray-400"></i> Logs de Atividades
</a>

{{-- Configurações --}}
<div x-data="{ open: {{ request()->is('panel/configuracoes*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-400">
        <span class="flex items-center gap-2.5"><i class="fa-solid fa-gear w-4 text-gray-400"></i> Configurações</span>
        <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
        <a href="/panel/configuracoes/sistema" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/configuracoes/sistema') ? 'active' : '' }}"><i class="fa-solid fa-sliders w-3 text-gray-400"></i> Config. do sistema</a>
        <a href="/panel/configuracoes/barbearia" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/configuracoes/barbearia') ? 'active' : '' }}"><i class="fa-solid fa-store w-3 text-gray-400"></i> Config. do estabelecimento</a>
        <a href="/panel/configuracoes/agendamento" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/configuracoes/agendamento') ? 'active' : '' }}"><i class="fa-solid fa-calendar-check w-3 text-gray-400"></i> Config. de agendamento</a>
        <a href="/panel/configuracoes/conta" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-400 text-xs {{ request()->is('panel/configuracoes/conta') ? 'active' : '' }}"><i class="fa-solid fa-user-gear w-3 text-gray-400"></i> Config. da conta</a>
    </div>
</div>

<a href="/panel/meu-plano" class="sidebar-item {{ request()->is('panel/meu-plano') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-400">
    <i class="fa-solid fa-table-list w-4 text-gray-400"></i> Meu plano
</a>
