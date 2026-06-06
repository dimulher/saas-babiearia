@extends('layouts.app')
@section('title', 'Equipe')

@section('content')
<div class="space-y-6" x-data="{
    showModal: {{ $errors->any() ? 'true' : 'false' }},
    editMode: false,
    professional: {
        id: '', nome: '', email: '', telefone: '', comissao_percentual: '0', aceita_agendamento_online: true
    },
    openModal(prof = null) {
        if (prof) {
            this.editMode = true;
            this.professional = { ...prof };
        } else {
            this.editMode = false;
            this.professional = { id: '', nome: '', email: '', telefone: '', comissao_percentual: '0', aceita_agendamento_online: true };
        }
        this.showModal = true;
    },
    showDeleteModal: false,
    profToDelete: null,
    confirmDelete(prof) { this.profToDelete = prof; this.showDeleteModal = true; },
    showCodeModal: false,
    profToGenerate: null,
    confirmCode(prof) { this.profToGenerate = prof; this.showCodeModal = true; }
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Gestão da Equipe</h1>
            <p class="text-sm text-gray-400 font-medium">Gerencie os especialistas e suas configurações de atendimento.</p>
        </div>
        <button @click="openModal()" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-5 py-3 rounded-xl transition-all shadow-lg shadow-green-900/30 uppercase tracking-wider">
            <i class="fa-solid fa-user-plus text-xs"></i> Novo Integrante
        </button>
    </div>

    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-gray-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" placeholder="Pesquisar integrantes..."
                    class="block w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
            </div>
            <div class="flex items-center gap-2 bg-gray-900 px-4 py-2 rounded-xl border border-gray-800">
                <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Equipe Ativa</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/50 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Especialista</th>
                        <th class="px-6 py-4">Contato</th>
                        <th class="px-6 py-4 text-center">Comissão</th>
                        <th class="px-6 py-4 text-center">Agenda Online</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($profissionais as $profissional)
                        <tr class="hover:bg-gray-900/40 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-12 h-12 rounded-xl bg-green-900/30 flex items-center justify-center text-green-400 font-bold text-sm border border-green-800/50 group-hover:bg-green-500 group-hover:text-white group-hover:border-green-500 transition-all">
                                            {{ $profissional->initials }}
                                        </div>
                                        @if($profissional->is_online)
                                            <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-[#111827] rounded-full"></div>
                                        @else
                                            <div class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-gray-700 border-2 border-[#111827] rounded-full"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-white group-hover:text-green-400 transition-colors text-sm">{{ $profissional->nome }}</div>
                                        @if($profissional->is_online)
                                            <div class="text-[9px] text-emerald-500 font-bold tracking-widest uppercase mt-0.5">Online Agora</div>
                                        @elseif($profissional->ultimo_login_at)
                                            <div class="text-[9px] text-gray-600 font-bold tracking-widest uppercase mt-0.5">Visto em {{ \Carbon\Carbon::parse($profissional->ultimo_login_at)->format('d/m/y H:i') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-xs font-medium text-gray-300">{{ $profissional->telefone ?? '—' }}</div>
                                <div class="text-[10px] text-gray-500 mt-0.5">{{ $profissional->email ?? 'Sem e-mail' }}</div>
                                @if($profissional->codigo_acesso)
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 bg-green-900/30 rounded text-[10px] font-bold text-green-400 tracking-[0.2em] border border-green-800/50 uppercase">
                                        <i class="fa-solid fa-key text-[8px]"></i> {{ $profissional->codigo_acesso }}
                                    </div>
                                @else
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 bg-gray-900/50 rounded text-[9px] font-bold text-gray-500 tracking-widest border border-gray-800 uppercase">
                                        Sem acesso
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center font-bold text-white text-base tracking-tight">
                                {{ number_format($profissional->comissao_percentual, 0) }}%
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($profissional->aceita_agendamento_online && $profissional->is_online)
                                    <span class="px-3 py-1 bg-emerald-900/30 text-emerald-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-emerald-800/50">Visível</span>
                                @elseif($profissional->aceita_agendamento_online && !$profissional->is_online)
                                    <span class="px-3 py-1 bg-gray-900/50 text-gray-500 rounded-full text-[9px] font-bold uppercase tracking-widest border border-gray-800">Offline</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-900/30 text-rose-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-rose-800/50">Privado</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-end gap-3">
                                    <button type="button" @click='confirmCode(@json($profissional))' class="text-green-400 hover:text-green-300 text-[10px] font-bold uppercase tracking-widest transition-colors">
                                        Gerar Código
                                    </button>
                                    <button type="button" @click='openModal(@json($profissional))' class="text-gray-400 hover:text-white text-[10px] font-bold uppercase tracking-widest transition-colors">Editar</button>
                                    <button type="button" @click='confirmDelete(@json($profissional))' class="text-gray-600 hover:text-rose-500 transition-colors">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-gray-900/50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fa-solid fa-user-group text-2xl text-gray-600"></i>
                                </div>
                                <p class="text-sm font-bold text-white">Nenhum integrante cadastrado</p>
                                <p class="text-xs text-gray-500 mt-1">Clique em "Novo Integrante" para começar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Novo/Editar Profissional -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div x-transition.opacity class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showModal = false"></div>
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-[#111827] rounded-2xl shadow-2xl w-full max-w-lg border border-gray-800 z-10">
            <form :action="editMode ? `/panel/profissionais/${professional.id}` : '/panel/profissionais'" method="POST">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                <div class="px-7 pt-7 pb-6">
                    <div class="flex items-center justify-between mb-7">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-green-900/30 flex items-center justify-center text-green-400 border border-green-800/50">
                                <i class="fa-solid fa-user-tie text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white" x-text="editMode ? 'Editar Cadastro' : 'Novo Integrante'"></h3>
                                <p class="text-xs text-gray-500 mt-0.5">Configure os dados do especialista.</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false" class="w-9 h-9 flex items-center justify-center hover:bg-gray-800 text-gray-500 hover:text-white rounded-xl transition-all">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Nome Completo</label>
                            <input type="text" name="nome" x-model="professional.nome" required placeholder="Ex: Lucas Gabriel"
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            @error('nome') <p class="text-[10px] text-red-400 font-bold uppercase tracking-tight mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">E-mail</label>
                            <input type="email" name="email" x-model="professional.email" required placeholder="lucas@exemplo.com"
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            @error('email') <p class="text-[10px] text-red-400 font-bold uppercase tracking-tight mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">WhatsApp</label>
                            <input type="tel" name="telefone" x-model="professional.telefone" required placeholder="(00) 00000-0000"
                                @input="professional.telefone = $event.target.value.replace(/\D/g, '').substring(0, 11).replace(/^(\d{2})(\d)/g, '($1) $2').replace(/(\d)(\d{4})$/, '$1-$2')"
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            @error('telefone') <p class="text-[10px] text-red-400 font-bold uppercase tracking-tight mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900/60 border-t border-gray-800 px-7 py-5 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-green-900/20" x-text="editMode ? 'Salvar Alterações' : 'Criar Cadastro'"></button>
                    <button type="button" @click="showModal = false" class="px-8 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Exclusão -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4">
        <div x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showDeleteModal = false"></div>
        <div x-show="showDeleteModal" x-transition.scale.origin.bottom
             class="relative bg-[#111827] rounded-2xl shadow-2xl w-full max-w-sm border border-gray-800 p-7 z-10">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-full bg-rose-900/30 flex items-center justify-center text-rose-500 mb-5 border border-rose-800/50">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Remover Profissional</h3>
                <p class="text-sm text-gray-400 mb-7">Tem certeza que deseja remover <span class="text-white font-bold" x-text="profToDelete?.nome"></span>? Esta ação não poderá ser desfeita.</p>
                <div class="flex items-center gap-3 w-full">
                    <button type="button" @click="showDeleteModal = false" class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Cancelar</button>
                    <form :action="`/panel/profissionais/${profToDelete?.id}`" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Remover</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Código -->
    <div x-show="showCodeModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto flex items-center justify-center p-4">
        <div x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showCodeModal = false"></div>
        <div x-show="showCodeModal" x-transition.scale.origin.bottom
             class="relative bg-[#111827] rounded-2xl shadow-2xl w-full max-w-sm border border-gray-800 p-7 z-10">
            <div class="flex flex-col items-center text-center">
                <div class="w-14 h-14 rounded-full bg-green-900/30 flex items-center justify-center text-green-400 mb-5 border border-green-800/50">
                    <i class="fa-solid fa-key text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Código de Acesso</h3>
                <p class="text-sm text-gray-400 mb-7">Deseja gerar um novo código de acesso para <span class="text-white font-bold" x-text="profToGenerate?.nome"></span>? O código anterior será invalidado.</p>
                <div class="flex items-center gap-3 w-full">
                    <button type="button" @click="showCodeModal = false" class="flex-1 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Cancelar</button>
                    <form :action="`/panel/profissionais/${profToGenerate?.id}/gerar-codigo`" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-green-500 hover:bg-green-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-green-900/20">Gerar Agora</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
