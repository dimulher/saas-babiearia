@extends('admin.layout')
@section('title', 'Barbearias')
@section('subtitle', 'Todas as barbearias cadastradas na plataforma')

@section('content')
<div class="space-y-6">

    {{-- Filtros --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="busca" value="{{ request('busca') }}"
                placeholder="Buscar por nome ou email..."
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="plano" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos os planos</option>
                <option value="gratuito"      {{ request('plano') === 'gratuito'      ? 'selected' : '' }}>Gratuito</option>
                <option value="basico"        {{ request('plano') === 'basico'        ? 'selected' : '' }}>Básico</option>
                <option value="profissional"  {{ request('plano') === 'profissional'  ? 'selected' : '' }}>Profissional</option>
                <option value="premium"       {{ request('plano') === 'premium'       ? 'selected' : '' }}>Premium</option>
            </select>
            <select name="ativo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos os status</option>
                <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativas</option>
                <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativas</option>
            </select>
            <button type="submit" class="bg-[#0A1B3D] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#122550]">
                <i class="fa-solid fa-search mr-1"></i> Filtrar
            </button>
            @if(request()->hasAny(['busca','plano','ativo']))
            <a href="{{ route('admin.barbearias') }}" class="text-sm text-gray-400 hover:text-gray-600">Limpar</a>
            @endif
        </form>
    </div>

    {{-- Tabela --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-store text-[#E2C28A]"></i>
                Barbearias
                <span class="ml-2 bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded-full">
                    {{ $barbearias->total() }}
                </span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Barbearia</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Localização</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Plano</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Usuários</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Agendamentos</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Clientes</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Status</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Cadastro</th>
                        <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barbearias as $b)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-[#0A1B3D] rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($b->nome, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $b->nome }}</p>
                                    <p class="text-xs text-gray-400">{{ $b->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-600">
                            {{ $b->cidade ?? '—' }}{{ $b->estado ? '/'.$b->estado : '' }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $corPlano = match($b->plano) {
                                    'premium'       => 'bg-yellow-100 text-yellow-700',
                                    'profissional'  => 'bg-purple-100 text-purple-700',
                                    'basico'        => 'bg-blue-100 text-blue-700',
                                    default         => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $corPlano }}">
                                {{ ucfirst($b->plano) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center text-gray-600">{{ $b->users_count }}</td>
                        <td class="py-3 px-4 text-center text-gray-600">{{ $b->agendamentos_count }}</td>
                        <td class="py-3 px-4 text-center text-gray-600">{{ $b->clientes_count }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center gap-1 text-xs {{ $b->ativo ? 'text-green-600' : 'text-red-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $b->ativo ? 'bg-green-500' : 'bg-red-400' }}"></span>
                                {{ $b->ativo ? 'Ativa' : 'Inativa' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center text-xs text-gray-400">
                            {{ $b->created_at->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.impersonar', $b->id) }}"
                               class="inline-flex items-center gap-1 text-xs bg-[#0A1B3D] text-white px-3 py-1.5 rounded-lg hover:bg-[#122550] transition-colors"
                               onclick="return confirm('Acessar painel de {{ addslashes($b->nome) }}?')">
                                <i class="fa-solid fa-right-to-bracket"></i> Acessar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-store text-3xl mb-2 block text-gray-300"></i>
                            Nenhuma barbearia encontrada.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($barbearias->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $barbearias->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
