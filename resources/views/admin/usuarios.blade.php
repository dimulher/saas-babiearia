@extends('admin.layout')
@section('title', 'Usuários')
@section('subtitle', 'Todos os usuários da plataforma')

@section('content')
<div class="space-y-6">

    {{-- Filtros --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="busca" value="{{ request('busca') }}"
                placeholder="Buscar por nome ou email..."
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos os perfis</option>
                <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                <option value="gerente"  {{ request('role') === 'gerente'  ? 'selected' : '' }}>Gerente</option>
                <option value="barbeiro" {{ request('role') === 'barbeiro' ? 'selected' : '' }}>Barbeiro</option>
            </select>
            <button type="submit" class="bg-[#0A1B3D] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#122550]">
                <i class="fa-solid fa-search mr-1"></i> Filtrar
            </button>
            @if(request()->hasAny(['busca','role']))
            <a href="{{ route('admin.usuarios') }}" class="text-sm text-gray-400 hover:text-gray-600">Limpar</a>
            @endif
        </form>
    </div>

    {{-- Cards resumo --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $totais['admin'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Admins / Donos</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $totais['gerente'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Gerentes</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $totais['barbeiro'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Barbeiros</p>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-users text-[#E2C28A]"></i>
                Usuários
                <span class="ml-2 bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded-full">
                    {{ $usuarios->total() }}
                </span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Usuário</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Barbearia</th>
                        <th class="text-left text-xs font-semibold text-gray-500 uppercase py-3 px-4">Perfil</th>
                        <th class="text-center text-xs font-semibold text-gray-500 uppercase py-3 px-4">Cadastro</th>
                        <th class="text-right text-xs font-semibold text-gray-500 uppercase py-3 px-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#E2C28A] rounded-full flex items-center justify-center text-[#0A1B3D] text-xs font-bold shrink-0">
                                    {{ $u->initials }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $u->nome }}</p>
                                    <p class="text-xs text-gray-400">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-gray-700">{{ $u->barbearia?->nome ?? '—' }}</p>
                            @if($u->barbearia)
                            <p class="text-xs text-gray-400">{{ $u->barbearia->cidade }}/{{ $u->barbearia->estado }}</p>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $corRole = match($u->role) {
                                    'admin'    => 'bg-blue-100 text-blue-700',
                                    'gerente'  => 'bg-purple-100 text-purple-700',
                                    'barbeiro' => 'bg-green-100 text-green-700',
                                    default    => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $corRole }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center text-xs text-gray-400">
                            {{ $u->created_at->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if($u->barbearia)
                            <a href="{{ route('admin.impersonar', $u->barbearia->id) }}"
                               class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 font-medium"
                               onclick="return confirm('Acessar painel de {{ addslashes($u->barbearia->nome) }}?')">
                                <i class="fa-solid fa-right-to-bracket"></i> Acessar painel
                            </a>
                            @else
                            <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-users text-3xl mb-2 block text-gray-300"></i>
                            Nenhum usuário encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usuarios->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $usuarios->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
