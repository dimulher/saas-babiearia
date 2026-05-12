@extends('admin.layout')
@section('title', 'Dashboard CEO')
@section('subtitle', 'Visão geral de toda a plataforma')

@section('content')
<div class="space-y-6">

    {{-- Cards principais --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">Total Barbearias</p>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-store text-blue-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalBarbearias }}</p>
            <p class="text-xs text-green-600 mt-1"><i class="fa-solid fa-circle text-[8px]"></i> {{ $barberiasAtivas }} ativas</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">Total Usuários</p>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-users text-purple-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalUsuarios }}</p>
            <p class="text-xs text-gray-400 mt-1">donos + funcionários</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">Agendamentos</p>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check text-green-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalAgendamentos }}</p>
            <p class="text-xs text-gray-400 mt-1">na plataforma</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-gray-500">Planos Ativos</p>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-crown text-yellow-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $planos->sum() }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $planos->get('gratuito', 0) }} gratuitos</p>
        </div>
    </div>

    {{-- Planos breakdown + Barbearias recentes --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Distribuição de planos --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-[#E2C28A]"></i> Distribuição de Planos
            </h3>
            <div class="space-y-3">
                @php
                    $cores = ['gratuito'=>'gray','basico'=>'blue','profissional'=>'purple','premium'=>'yellow'];
                    $total = $planos->sum() ?: 1;
                @endphp
                @foreach(['gratuito','basico','profissional','premium'] as $plano)
                @php $qtd = $planos->get($plano, 0); $pct = round(($qtd/$total)*100); @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700 capitalize">{{ $plano }}</span>
                        <span class="text-gray-500">{{ $qtd }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-{{ $cores[$plano] }}-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Barbearias recentes --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-store text-[#E2C28A]"></i> Barbearias Recentes
                </h3>
                <a href="{{ route('admin.barbearias') }}" class="text-xs text-blue-600 hover:underline">Ver todas →</a>
            </div>
            <div class="space-y-3">
                @foreach($recentes as $b)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-[#0A1B3D] rounded-lg flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($b->nome, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $b->nome }}</p>
                            <p class="text-xs text-gray-400">{{ $b->cidade }}/{{ $b->estado }} · {{ $b->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $b->plano === 'gratuito' ? 'bg-gray-100 text-gray-600' :
                               ($b->plano === 'premium' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ ucfirst($b->plano) }}
                        </span>
                        <a href="{{ route('admin.impersonar', $b->id) }}"
                           class="text-xs text-indigo-600 hover:text-indigo-800 font-medium"
                           onclick="return confirm('Acessar como {{ $b->nome }}?')">
                            <i class="fa-solid fa-right-to-bracket"></i> Acessar
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tabela de todas as barbearias --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-list text-[#E2C28A]"></i> Todas as Barbearias
            </h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase py-2 px-2">Barbearia</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase py-2 px-2">Plano</th>
                    <th class="text-center text-xs font-semibold text-gray-500 uppercase py-2 px-2">Usuários</th>
                    <th class="text-center text-xs font-semibold text-gray-500 uppercase py-2 px-2">Agendamentos</th>
                    <th class="text-center text-xs font-semibold text-gray-500 uppercase py-2 px-2">Status</th>
                    <th class="text-right text-xs font-semibold text-gray-500 uppercase py-2 px-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barbearias as $b)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-[#0A1B3D] rounded-lg flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($b->nome, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $b->nome }}</p>
                                <p class="text-xs text-gray-400">{{ $b->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-2">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            {{ $b->plano === 'gratuito' ? 'bg-gray-100 text-gray-600' :
                               ($b->plano === 'premium' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ ucfirst($b->plano) }}
                        </span>
                    </td>
                    <td class="py-3 px-2 text-center text-gray-600">{{ $b->users_count }}</td>
                    <td class="py-3 px-2 text-center text-gray-600">{{ $b->agendamentos_count }}</td>
                    <td class="py-3 px-2 text-center">
                        <span class="w-2 h-2 rounded-full inline-block {{ $b->ativo ? 'bg-green-500' : 'bg-red-400' }}"></span>
                        <span class="text-xs text-gray-500 ml-1">{{ $b->ativo ? 'Ativa' : 'Inativa' }}</span>
                    </td>
                    <td class="py-3 px-2 text-right">
                        <a href="{{ route('admin.impersonar', $b->id) }}"
                           class="text-xs bg-[#0A1B3D] text-white px-3 py-1.5 rounded-lg hover:bg-[#122550]"
                           onclick="return confirm('Acessar painel de {{ $b->nome }}?')">
                            <i class="fa-solid fa-right-to-bracket mr-1"></i> Acessar
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400">Nenhuma barbearia cadastrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $barbearias->links() }}</div>
    </div>

</div>
@endsection
