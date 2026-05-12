@extends('layouts.app')
@section('title', 'Equipe')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ $errors->any() ? 'true' : 'false' }}, 
    editMode: false,
    professional: {
        id: '',
        nome: '',
        email: '',
        telefone: '',
        comissao_percentual: '0',
        aceita_agendamento_online: true
    },
    openModal(prof = null) {
        if (prof) {
            this.editMode = true;
            this.professional = { ...prof };
        } else {
            this.editMode = false;
            this.professional = {
                id: '',
                nome: '',
                email: '',
                telefone: '',
                comissao_percentual: '0',
                aceita_agendamento_online: true
            };
        }
        this.showModal = true;
    },
    showDeleteModal: false,
    profToDelete: null,
    confirmDelete(prof) {
        this.profToDelete = prof;
        this.showDeleteModal = true;
    },
    showCodeModal: false,
    profToGenerate: null,
    confirmCode(prof) {
        this.profToGenerate = prof;
        this.showCodeModal = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">GestÃ£o da Equipe</h1>
            <p class="text-sm text-gray-400 font-medium">Gerencie os especialistas e suas configuraÃ§Ãµes de atendimento.</p>
        </div>
        <button @click="openModal()" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest shadow-xl shadow-violet-900/20">
            <i class="fa-solid fa-user-plus text-xs"></i> Novo Integrante
        </button>
    </div>

    <!-- Lista de Profissionais -->
    <div class="bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-900/30">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" placeholder="Pesquisar integrantes da equipe..." class="block w-full pl-12 pr-4 py-3.5 bg-gray-900 border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-violet-500 focus:bg-gray-800 transition-all outline-none">
            </div>
            <div class="flex items-center gap-2 bg-gray-900 px-4 py-2 rounded-xl border border-gray-800">
                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Equipe Ativa</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/50 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Especialista</th>
                        <th class="px-8 py-5">Contato</th>
                        <th class="px-8 py-5 text-center">ComissÃ£o</th>
                        <th class="px-8 py-5 text-center">Agenda Online</th>
                        <th class="px-8 py-5 text-right">AÃ§Ãµes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($profissionais as $profissional)
                        <tr class="hover:bg-gray-900/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-14 h-14 rounded-2xl bg-violet-900/30 flex items-center justify-center text-violet-400 font-bold text-sm shadow-sm border border-violet-800 group-hover:bg-violet-600 group-hover:text-white group-hover:border-violet-500 transition-all">
                                            {{ $profissional->initials }}
                                        </div>
                                        @if($profissional->is_online)
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-gray-900 rounded-full shadow-md shadow-emerald-900/50" title="Online agora"></div>
                                        @else
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-gray-800/500 border-2 border-gray-900 rounded-full shadow-md" title="Offline"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-white group-hover:text-violet-400 transition-colors uppercase tracking-tight">{{ $profissional->nome }}</div>
                                        @if($profissional->is_online)
                                            <div class="text-[9px] text-emerald-500 font-bold tracking-widest uppercase mt-0.5">Online Agora</div>
                                        @elseif($profissional->ultimo_login_at)
                                            <div class="text-[9px] text-gray-500 font-bold tracking-widest uppercase mt-0.5 opacity-60">Visto em {{ \Carbon\Carbon::parse($profissional->ultimo_login_at)->format('d/m/y H:i') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-xs font-bold text-gray-300">{{ $profissional->telefone ?? '-' }}</div>
                                <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-0.5 opacity-60">{{ $profissional->email ?? 'Sem e-mail' }}</div>
                                @if($profissional->codigo_acesso)
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 bg-violet-900/30 rounded text-[10px] font-bold text-violet-400 tracking-[0.2em] border border-violet-800/50 uppercase">
                                        <i class="fa-solid fa-key text-[8px]"></i> {{ $profissional->codigo_acesso }}
                                    </div>
                                @else
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 bg-gray-900/50 rounded text-[9px] font-bold text-gray-500 tracking-widest border border-gray-800 uppercase">
                                        Sem acesso
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-center font-bold text-white text-base tracking-tighter italic">
                                {{ number_format($profissional->comissao_percentual, 0) }}%
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($profissional->aceita_agendamento_online && $profissional->is_online)
                                    <span class="px-4 py-1.5 bg-emerald-900/30 text-emerald-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-emerald-800 shadow-sm shadow-emerald-900/20">VisÃ­vel</span>
                                @elseif($profissional->aceita_agendamento_online && !$profissional->is_online)
                                    <span class="px-4 py-1.5 bg-gray-900/50 text-gray-500 rounded-full text-[9px] font-bold uppercase tracking-widest border border-gray-800 opacity-60">Offline</span>
                                @else
                                    <span class="px-4 py-1.5 bg-rose-900/30 text-rose-400 rounded-full text-[9px] font-bold uppercase tracking-widest border border-rose-800 opacity-60">Privado</span>
                                @endif
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-end gap-4">
                                    <button type="button" @click='confirmCode(@json($profissional))' class="text-indigo-400 hover:text-indigo-300 text-[10px] font-bold uppercase tracking-widest transition-colors" title="Gerar ou resetar cÃ³digo">
                                        Gerar CÃ³digo
                                    </button>
                                    <button type="button" @click='openModal(@json($profissional))' class="text-violet-400 hover:text-violet-300 text-[10px] font-bold uppercase tracking-widest transition-colors">Editar</button>
                                    <button type="button" @click='confirmDelete(@json($profissional))' class="text-gray-400 hover:text-rose-500 transition-colors">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center text-gray-500">
                                <div class="w-20 h-20 bg-gray-900/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-user-group text-3xl text-gray-200"></i>
                                </div>
                                <p class="text-base font-bold text-white uppercase tracking-widest">Nenhum integrante cadastrado</p>
                                <p class="text-[10px] text-gray-500 mt-2 uppercase font-bold tracking-widest">Inicie a gestÃ£o da sua equipe clicando em "Novo Integrante".</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Novo/Editar Profissional -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">
                <form :action="editMode ? `/panel/profissionais/${professional.id}` : '/panel/profissionais'" method="POST">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="px-8 pt-10 pb-8">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-violet-900/30 flex items-center justify-center text-violet-400 shadow-sm border border-violet-800">
                                    <i class="fa-solid fa-user-tie text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white uppercase tracking-tight" x-text="editMode ? 'Editar Cadastro' : 'Novo Integrante'"></h3>
                                    <p class="text-xs text-gray-500 font-medium mt-1">Configure os dados do especialista da sua equipe.</p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false" class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 text-gray-500 hover:text-white rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Nome Completo</label>
                                <input type="text" name="nome" x-model="professional.nome" required placeholder="Ex: Lucas Gabriel"
                                    class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-violet-500 focus:bg-gray-800 transition-all outline-none">
                                @error('nome') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">E-mail</label>
                                <input type="email" name="email" x-model="professional.email" required placeholder="lucas@exemplo.com"
                                    class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-violet-500 focus:bg-gray-800 transition-all outline-none">
                                @error('email') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">WhatsApp</label>
                                <input type="tel" name="telefone" x-model="professional.telefone" required placeholder="(00) 00000-0000"
                                    @input="professional.telefone = $event.target.value.replace(/\D/g, '').substring(0, 11).replace(/^(\d{2})(\d)/g, '($1) $2').replace(/(\d)(\d{4})$/, '$1-$2')"
                                    class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-violet-500 focus:bg-gray-800 transition-all outline-none">
                                @error('telefone') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-900 border-t border-gray-800 px-8 py-8 sm:flex sm:flex-row-reverse gap-4">
                        <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-violet-900/20 px-10 py-4 bg-violet-600 text-[10px] font-bold text-white uppercase tracking-widest hover:bg-violet-500 transition-all sm:w-auto italic" x-text="editMode ? 'Salvar AlteraÃ§Ãµes' : 'Criar Cadastro'"></button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-transparent px-10 py-4 bg-gray-800 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-700 hover:text-white transition-all sm:mt-0 sm:w-auto">Descartar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal ConfirmaÃ§Ã£o de ExclusÃ£o -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="showDeleteModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showDeleteModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full border border-gray-800 p-8">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-rose-900/30 flex items-center justify-center text-rose-500 mb-6 border border-rose-800">
                        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white uppercase tracking-tight mb-2">Remover Profissional</h3>
                    <p class="text-sm text-gray-400 mb-8">Tem certeza que deseja remover o integrante <span class="text-white font-bold" x-text="profToDelete?.nome"></span> da sua equipe? Esta aÃ§Ã£o nÃ£o poderÃ¡ ser desfeita e ele perderÃ¡ o acesso ao painel.</p>
                    
                    <div class="flex items-center gap-3 w-full">
                        <button type="button" @click="showDeleteModal = false" class="flex-1 py-3.5 bg-gray-800/50 hover:bg-gray-800 text-gray-400 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Cancelar</button>
                        <form :action="`/panel/profissionais/${profToDelete?.id}`" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-rose-900/30">Sim, Remover</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Gerar CÃ³digo -->
    <div x-show="showCodeModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCodeModal" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="showCodeModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showCodeModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full border border-gray-800 p-8">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-indigo-900/30 flex items-center justify-center text-indigo-400 mb-6 border border-indigo-800">
                        <i class="fa-solid fa-key text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white uppercase tracking-tight mb-2">CÃ³digo de Acesso</h3>
                    <p class="text-sm text-gray-400 mb-8">Deseja gerar um novo cÃ³digo de acesso para <span class="text-white font-bold" x-text="profToGenerate?.nome"></span>? Isso invalidarÃ¡ o cÃ³digo anterior se houver.</p>
                    
                    <div class="flex items-center gap-3 w-full">
                        <button type="button" @click="showCodeModal = false" class="flex-1 py-3.5 bg-gray-800/50 hover:bg-gray-800 text-gray-400 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Cancelar</button>
                        <form :action="`/panel/profissionais/${profToGenerate?.id}/gerar-codigo`" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-900/30">Gerar Agora</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
