<a href="/panel/dashboard" class="sidebar-item {{ request()->is('panel/dashboard') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-house w-4 text-gray-400"></i> Dashboard
</a>

<a href="/panel/comandas" class="sidebar-item {{ request()->is('panel/comandas*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-receipt w-4 text-gray-400"></i> Comandas
</a>

<a href="/panel/agendamentos" class="sidebar-item {{ request()->is('panel/agendamentos') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-calendar-days w-4 text-gray-400"></i> Agendamentos
</a>

{{-- Financeiro --}}
<div x-data="{ open: {{ request()->is('panel/financeiro*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-700">
        <span class="flex items-center gap-2.5"><i class="fa-solid fa-chart-line w-4 text-gray-400"></i> Financeiro</span>
        <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
        <a href="/panel/financeiro/visao-geral" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/financeiro/visao-geral') ? 'active' : '' }}"><i class="fa-solid fa-chart-pie w-3 text-gray-400"></i> Visão Geral</a>
        <a href="/panel/financeiro/gestao-caixa" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/financeiro/gestao-caixa') ? 'active' : '' }}"><i class="fa-solid fa-cash-register w-3 text-gray-400"></i> Gestão de Caixa</a>
        <a href="/panel/financeiro/relatorios" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/financeiro/relatorios') ? 'active' : '' }}"><i class="fa-solid fa-chart-bar w-3 text-gray-400"></i> Relatórios</a>
        <a href="/panel/financeiro/extrato" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/financeiro/extrato') ? 'active' : '' }}"><i class="fa-solid fa-file-lines w-3 text-gray-400"></i> Extrato</a>
        <a href="/panel/financeiro/saude-financeira" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/financeiro/saude-financeira') ? 'active' : '' }}"><i class="fa-solid fa-heart-pulse w-3 text-gray-400"></i> Saúde Financeira</a>
    </div>
</div>

{{-- WhatsApp --}}
<div x-data="{ open: {{ request()->is('panel/whatsapp*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-700">
        <span class="flex items-center gap-2.5"><i class="fa-brands fa-whatsapp w-4 text-green-500"></i> WhatsApp</span>
        <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
        <a href="/panel/whatsapp/mensagens" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/whatsapp/mensagens') ? 'active' : '' }}"><i class="fa-solid fa-bell w-3 text-gray-400"></i> Mensagens e configurações</a>
        <a href="/panel/whatsapp/recarregar-saldo" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/whatsapp/recarregar-saldo') ? 'active' : '' }}"><i class="fa-solid fa-plus w-3 text-gray-400"></i> Recarregar saldo</a>
    </div>
</div>

{{-- Contas --}}
<div x-data="{ open: {{ request()->is('panel/contas*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-700">
        <span class="flex items-center gap-2.5"><i class="fa-solid fa-coins w-4 text-gray-400"></i> Contas</span>
        <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
        <a href="/panel/contas" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/contas') && !request()->is('panel/contas/*') ? 'active' : '' }}"><i class="fa-solid fa-list w-3 text-gray-400"></i> Todas</a>
        <a href="/panel/contas/parceladas" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/contas/parceladas') ? 'active' : '' }}"><i class="fa-solid fa-layer-group w-3 text-gray-400"></i> Parceladas</a>
        <a href="/panel/contas/recorrentes" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/contas/recorrentes') ? 'active' : '' }}"><i class="fa-solid fa-arrows-rotate w-3 text-gray-400"></i> Recorrentes</a>
    </div>
</div>

<a href="/panel/agendamentos-recorrentes" class="sidebar-item {{ request()->is('panel/agendamentos-recorrentes') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-arrows-rotate w-4 text-gray-400"></i> <span class="text-xs">Agend. Recorrentes</span>
</a>

{{-- Relatórios --}}
<div x-data="{ open: {{ request()->is('panel/relatorios*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-700">
        <span class="flex items-center gap-2.5"><i class="fa-solid fa-chart-pie w-4 text-gray-400"></i> Relatórios</span>
        <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
        <a href="/panel/relatorios/servicos" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/relatorios/servicos') ? 'active' : '' }}"><i class="fa-solid fa-scissors w-3 text-gray-400"></i> Serviços</a>
        <a href="/panel/relatorios/clientes" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/relatorios/clientes') ? 'active' : '' }}"><i class="fa-solid fa-users w-3 text-gray-400"></i> Clientes</a>
        <a href="/panel/relatorios/profissionais" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/relatorios/profissionais') ? 'active' : '' }}"><i class="fa-solid fa-user-tie w-3 text-gray-400"></i> Profissionais</a>
    </div>
</div>

{{-- Assinaturas --}}
<div x-data="{ open: {{ request()->is('panel/assinaturas*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-700">
        <span class="flex items-center gap-2.5"><i class="fa-solid fa-user-check w-4 text-gray-400"></i> Assinaturas</span>
        <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
        <a href="/panel/assinaturas/planos" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/assinaturas/planos') ? 'active' : '' }}"><i class="fa-solid fa-table-list w-3 text-gray-400"></i> Planos</a>
        <a href="/panel/assinaturas/gerenciar" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/assinaturas/gerenciar') ? 'active' : '' }}"><i class="fa-solid fa-table-list w-3 text-gray-400"></i> Gerenciar</a>
        <a href="/panel/assinaturas/ciclos-comissoes" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/assinaturas/ciclos-comissoes') ? 'active' : '' }}"><i class="fa-solid fa-circle-notch w-3 text-gray-400"></i> Ciclos de Comissões</a>
        <a href="/panel/assinaturas/configuracoes" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/assinaturas/configuracoes') ? 'active' : '' }}"><i class="fa-solid fa-gear w-3 text-gray-400"></i> Config. de assinaturas</a>
    </div>
</div>

<div class="border-t border-gray-100 my-2"></div>

<a href="/panel/profissionais" class="sidebar-item {{ request()->is('panel/profissionais*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-user-group w-4 text-gray-400"></i> Profissionais
</a>
<a href="/panel/clientes" class="sidebar-item {{ request()->is('panel/clientes*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-users w-4 text-gray-400"></i> Clientes
</a>
<a href="/panel/servicos" class="sidebar-item {{ request()->is('panel/servicos*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-scissors w-4 text-gray-400"></i> Serviços
</a>
<a href="/panel/produtos" class="sidebar-item {{ request()->is('panel/produtos*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-box w-4 text-gray-400"></i> Produtos
</a>
<a href="/panel/expedientes" class="sidebar-item {{ request()->is('panel/expedientes*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-clock w-4 text-gray-400"></i> Expedientes
</a>
<a href="/panel/bloquear-horarios" class="sidebar-item {{ request()->is('panel/bloquear-horarios*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-calendar-xmark w-4 text-gray-400"></i> Bloquear Horários
</a>
<a href="/panel/logs" class="sidebar-item {{ request()->is('panel/logs*') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-clock-rotate-left w-4 text-gray-400"></i> Logs de Atividades
</a>

{{-- Configurações --}}
<div x-data="{ open: {{ request()->is('panel/configuracoes*') ? 'true' : 'false' }} }">
    <button @click="open = !open" class="sidebar-item w-full flex items-center justify-between px-3 py-2 text-gray-700">
        <span class="flex items-center gap-2.5"><i class="fa-solid fa-gear w-4 text-gray-400"></i> Configurações</span>
        <i class="fa-solid fa-chevron-down text-xs duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="sidebar-submenu pl-3 mt-1 space-y-0.5">
        <a href="/panel/configuracoes/sistema" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/configuracoes/sistema') ? 'active' : '' }}"><i class="fa-solid fa-sliders w-3 text-gray-400"></i> Config. do sistema</a>
        <a href="/panel/configuracoes/barbearia" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/configuracoes/barbearia') ? 'active' : '' }}"><i class="fa-solid fa-store w-3 text-gray-400"></i> Config. da barbearia</a>
        <a href="/panel/configuracoes/agendamento" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/configuracoes/agendamento') ? 'active' : '' }}"><i class="fa-solid fa-calendar-check w-3 text-gray-400"></i> Config. de agendamento</a>
        <a href="/panel/configuracoes/conta" class="sidebar-item flex items-center gap-2 px-3 py-1.5 text-gray-600 text-xs {{ request()->is('panel/configuracoes/conta') ? 'active' : '' }}"><i class="fa-solid fa-user-gear w-3 text-gray-400"></i> Config. da conta</a>
    </div>
</div>

<a href="/panel/meu-plano" class="sidebar-item {{ request()->is('panel/meu-plano') ? 'active' : '' }} flex items-center gap-2.5 px-3 py-2 text-gray-700">
    <i class="fa-solid fa-table-list w-4 text-gray-400"></i> Meu plano
</a>
<a href="/panel/contratar" class="flex items-center gap-2.5 px-3 py-2 text-indigo-600 font-medium text-sm hover:text-indigo-700">
    <i class="fa-solid fa-rocket w-4"></i> Contratar BarberSAAS
</a>
