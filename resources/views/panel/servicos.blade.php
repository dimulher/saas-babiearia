@extends('layouts.app')
@section('title', 'ServiÃ§os')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ $errors->any() ? 'true' : 'false' }}, 
    editMode: false,
    service: {
        id: '',
        nome: '',
        preco: '',
        duracao_minutos: '',
        cor: '#7c3aed',
        descricao: '',
        disponivel_online: true
    },
    openModal(serv = null) {
        if (serv) {
            this.editMode = true;
            this.service = { ...serv };
        } else {
            this.editMode = false;
            this.service = {
                id: '',
                nome: '',
                preco: '',
                duracao_minutos: '',
                cor: '#7c3aed',
                descricao: '',
                disponivel_online: true
            };
        }
        this.showModal = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">CatÃ¡logo de ServiÃ§os</h1>
            <p class="text-sm text-gray-400 font-medium">Defina os procedimentos e valores oferecidos aos seus clientes.</p>
        </div>
        <button @click="openModal()" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest shadow-xl shadow-violet-200">
            <i class="fa-solid fa-plus text-xs"></i> Novo ServiÃ§o
        </button>
    </div>

    <!-- Lista de ServiÃ§os -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden border border-gray-800 rounded-[32px]">
        <div class="p-8 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-900/30">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" placeholder="Pesquisar procedimentos..." class="block w-full pl-12 pr-4 py-3.5 bg-gray-900/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
            </div>
            <div class="flex items-center gap-2 bg-gray-900/50 px-4 py-2 rounded-xl border border-gray-800">
                <div class="w-2.5 h-2.5 bg-violet-500 rounded-full"></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Procedimentos Ativos</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/80 text-gray-400 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Procedimento</th>
                        <th class="px-8 py-5">PreÃ§o Base</th>
                        <th class="px-8 py-5 text-center">DuraÃ§Ã£o</th>
                        <th class="px-8 py-5 text-right">GestÃ£o</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($servicos as $servico)
                        <tr class="hover:bg-gray-900/80 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-sm group-hover:scale-110 transition-transform" style="background-color: {{ $servico->cor }}">
                                        <i class="fa-solid fa-sparkles text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-white group-hover:text-violet-600 transition-colors uppercase tracking-tight">{{ $servico->nome }}</div>
                                        @if($servico->disponivel_online)
                                            <div class="text-[9px] text-emerald-600 font-bold uppercase tracking-widest mt-0.5 flex items-center gap-1 opacity-80">
                                                <i class="fa-solid fa-globe"></i> VisÃ­vel Online
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 font-bold text-white text-base italic tracking-tighter">R$ {{ number_format($servico->preco, 2, ',', '.') }}</td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-4 py-1.5 bg-gray-800/50 text-gray-500 rounded-full text-[9px] font-bold uppercase tracking-widest border border-gray-800 italic">
                                    {{ $servico->duracao_formatada }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right space-x-3">
                                <button @click="openModal({{ json_encode($servico) }})" class="text-violet-600 hover:text-violet-800 text-[10px] font-bold uppercase tracking-widest transition-colors">Editar</button>
                                <form action="{{ route('panel.servicos.destroy', $servico->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Excluir permanentemente este serviÃ§o?')" class="text-gray-300 hover:text-rose-600 transition-colors">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-24 text-center text-gray-400">
                                <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-magic-wand-sparkles text-3xl text-gray-200"></i>
                                </div>
                                <p class="text-base font-bold text-white uppercase tracking-widest">Nenhum serviÃ§o disponÃ­vel</p>
                                <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">Comece cadastrando seu primeiro procedimento.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Novo/Editar ServiÃ§o -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">
                <form :action="editMode ? `/panel/servicos/${service.id}` : '/panel/servicos'" method="POST">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="bg-gray-900/50 px-8 pt-10 pb-8">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-violet-900/30 flex items-center justify-center text-violet-600 shadow-sm border border-violet-800">
                                    <i class="fa-solid fa-magic-wand-sparkles text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white uppercase tracking-tight" x-text="editMode ? 'Editar Procedimento' : 'Novo Procedimento'"></h3>
                                    <p class="text-xs text-gray-400 font-medium mt-1">Configure os detalhes do serviÃ§o oferecido.</p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false" class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 text-gray-400 rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nome do Procedimento</label>
                                <input type="text" name="nome" x-model="service.nome" required placeholder="Ex: Corte Feminino + Escova"
                                    class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">PreÃ§o (R$)</label>
                                    <input type="number" step="0.01" name="preco" x-model="service.preco" required
                                        class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">DuraÃ§Ã£o (Minutos)</label>
                                    <input type="number" name="duracao_minutos" x-model="service.duracao_minutos" required
                                        class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cor no CalendÃ¡rio</label>
                                <div class="flex items-center gap-4">
                                    <input type="color" name="cor" x-model="service.cor" class="w-14 h-14 rounded-2xl border-none p-0 cursor-pointer overflow-hidden shadow-sm">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest italic" x-text="service.cor"></span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">DescriÃ§Ã£o Detalhada</label>
                                <textarea name="descricao" x-model="service.descricao" rows="2" placeholder="O que estÃ¡ incluso neste serviÃ§o?..."
                                    class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none"></textarea>
                            </div>

                            <label class="flex items-center justify-between p-5 bg-gray-900/80 rounded-2xl border border-gray-800 cursor-pointer hover:bg-violet-900/30 transition-colors group" x-data="{ online: service.disponivel_online }">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-900/50 flex items-center justify-center text-violet-600 shadow-sm border border-gray-800 group-hover:border-violet-200">
                                        <i class="fa-solid fa-globe"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-white uppercase tracking-widest">DisponÃ­vel Online</p>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Permitir agendamento pelo link pÃºblico.</p>
                                    </div>
                                </div>
                                <div @click="online = !online; service.disponivel_online = online" class="relative inline-flex h-6 w-11 items-center rounded-full transition-all shadow-inner" :class="online ? 'bg-violet-600' : 'bg-gray-200'">
                                    <input type="checkbox" name="disponivel_online" x-model="online" class="sr-only">
                                    <span :class="online ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-gray-900/50 transition-transform shadow-sm"></span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="bg-gray-900/80 px-8 py-8 sm:flex sm:flex-row-reverse gap-4">
                        <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-violet-900/20 px-10 py-4 bg-violet-600 text-[10px] font-bold text-white uppercase tracking-widest hover:bg-violet-700 transition-all sm:w-auto italic" x-text="editMode ? 'Salvar AlteraÃ§Ãµes' : 'Criar ServiÃ§o'"></button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-gray-700 px-10 py-4 bg-gray-900/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800/50 hover:text-gray-400 transition-all sm:mt-0 sm:w-auto">Descartar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
