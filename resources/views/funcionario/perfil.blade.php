@extends('funcionario.layout')
@section('title', 'Meu Perfil')

@section('content')
<div class="space-y-4">

    <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
        <div class="w-20 h-20 bg-[#E2C28A] rounded-full flex items-center justify-center text-[#0A1B3D] text-2xl font-black mx-auto mb-3">
            {{ $user->initials }}
        </div>
        <h2 class="text-xl font-bold text-gray-900">{{ $user->nome }}</h2>
        <p class="text-sm text-gray-500">{{ $user->email }}</p>
        <span class="inline-block mt-2 bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
            {{ ucfirst($user->role) }}
        </span>
    </div>

    @if($profissional)
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <h3 class="font-semibold text-gray-800 mb-3 text-sm flex items-center gap-2">
            <i class="fa-solid fa-scissors text-[#E2C28A]"></i> Dados Profissionais
        </h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Barbearia</span>
                <span class="font-medium text-gray-800">{{ $user->barbearia->nome ?? '—' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Comissão</span>
                <span class="font-bold text-green-600">{{ $profissional->comissao_percentual }}%</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Agendamento Online</span>
                <span class="{{ $profissional->aceita_agendamento_online ? 'text-green-600' : 'text-red-500' }} font-medium">
                    {{ $profissional->aceita_agendamento_online ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-500">Telefone</span>
                <span class="font-medium text-gray-800">{{ $profissional->telefone ?? '—' }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <h3 class="font-semibold text-gray-800 mb-3 text-sm flex items-center gap-2">
            <i class="fa-solid fa-lock text-[#E2C28A]"></i> Conta
        </h3>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 rounded-lg text-sm transition-colors">
                <i class="fa-solid fa-right-from-bracket"></i> Sair da conta
            </button>
        </form>
    </div>

</div>
@endsection
