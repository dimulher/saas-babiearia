@extends('layouts.app')
@section('title', 'Serviços')

@section('content')
<div class="space-y-6" x-data="{
    showModal: {{ $errors->any() ? 'true' : 'false' }},
    editMode: false,
    imagemPreview: null,
    service: { id: '', nome: '', preco: '', duracao_minutos: '', cor: '#16a34a', descricao: '', disponivel_online: true, imagem_url: '' },
    openModal(serv = null) {
        if (serv) { this.editMode = true; this.service = { ...serv }; }
        else { this.editMode = false; this.service = { id: '', nome: '', preco: '', duracao_minutos: '', cor: '#16a34a', descricao: '', disponivel_online: true, imagem_url: '' }; }
        this.imagemPreview = null;
        this.showModal = true;
    },
    onImagemChange(event) {
        const file = event.target.files[0];
        if (file) { this.imagemPreview = URL.createObjectURL(file); }
    }
}">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Catálogo de Serviços</h1>
            <p class="text-sm text-gray-400 font-medium">Defina os procedimentos e valores oferecidos aos seus clientes.</p>
        </div>
        <button @click="openModal()" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-5 py-3 rounded-xl transition-all shadow-lg shadow-green-900/30 uppercase tracking-wider">
            <i class="fa-solid fa-plus text-xs"></i> Novo Serviço
        </button>
    </div>

    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-gray-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
                <input type="text" placeholder="Pesquisar procedimentos..."
                    class="block w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
            </div>
            <div class="flex items-center gap-2 bg-gray-900 px-4 py-2 rounded-xl border border-gray-800">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Serviços Ativos</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/50 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-3 sm:px-6 py-4">Procedimento</th>
                        <th class="px-3 sm:px-6 py-4">Preço</th>
                        <th class="px-3 sm:px-6 py-4 text-center">Duração</th>
                        <th class="px-3 sm:px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($servicos as $servico)
                        <tr class="hover:bg-gray-900/40 transition-colors group">
                            <td class="px-3 sm:px-6 py-5">
                                <div class="flex items-center gap-4">
                                    @if($servico->imagem_url)
                                        <img src="{{ $servico->imagem_url }}" alt="{{ $servico->nome }}" class="w-11 h-11 rounded-xl object-cover border border-gray-800 group-hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white shadow-sm group-hover:scale-105 transition-transform" style="background-color: {{ $servico->cor }}">
                                            <i class="fa-solid fa-sparkles text-xs"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-white group-hover:text-green-400 transition-colors text-sm">{{ $servico->nome }}</div>
                                        @if($servico->disponivel_online)
                                            <div class="text-[9px] text-emerald-400 font-bold uppercase tracking-widest mt-0.5 flex items-center gap-1">
                                                <i class="fa-solid fa-globe"></i> Visível Online
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-5 font-bold text-white">R$ {{ number_format($servico->preco, 2, ',', '.') }}</td>
                            <td class="px-3 sm:px-6 py-5 text-center">
                                <span class="px-3 py-1 bg-gray-800 text-gray-400 rounded-full text-[10px] font-bold uppercase tracking-widest border border-gray-700">
                                    {{ $servico->duracao_formatada }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-5 text-right space-x-3">
                                <button @click="openModal({{ json_encode($servico) }})" class="text-green-400 hover:text-green-300 text-[10px] font-bold uppercase tracking-widest transition-colors">Editar</button>
                                <form action="{{ route('panel.servicos.destroy', $servico->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Excluir este serviço?')" class="text-gray-600 hover:text-rose-500 transition-colors">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <i class="fa-solid fa-magic-wand-sparkles text-3xl text-gray-700 mb-3 block"></i>
                                <p class="text-sm font-bold text-white">Nenhum serviço cadastrado</p>
                                <p class="text-xs text-gray-500 mt-1">Cadastre seu primeiro procedimento.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Novo/Editar Serviço -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-end sm:items-center justify-center p-0 sm:p-4">
        <div x-transition.opacity class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showModal = false"></div>
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 translate-y-4"
             class="relative bg-[#111827] rounded-t-3xl sm:rounded-2xl shadow-2xl w-full sm:max-w-lg border border-gray-800 z-10">
            <form :action="editMode ? `/panel/servicos/${service.id}` : '/panel/servicos'" method="POST" enctype="multipart/form-data">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                <div class="px-5 sm:px-7 pt-6 sm:pt-7 pb-5 sm:pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-green-900/30 flex items-center justify-center text-green-400 border border-green-800/50">
                                <i class="fa-solid fa-magic-wand-sparkles text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white" x-text="editMode ? 'Editar Serviço' : 'Novo Serviço'"></h3>
                                <p class="text-xs text-gray-500 mt-0.5">Configure os detalhes do serviço.</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false" class="w-9 h-9 flex items-center justify-center hover:bg-gray-800 text-gray-500 hover:text-white rounded-xl transition-all">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Foto do Serviço</label>
                            <label class="flex items-center gap-4 p-4 bg-gray-900 border border-gray-700 border-dashed rounded-xl cursor-pointer hover:border-green-500 transition-all">
                                <template x-if="imagemPreview || service.imagem_url">
                                    <img :src="imagemPreview || service.imagem_url" class="w-14 h-14 rounded-xl object-cover border border-gray-700">
                                </template>
                                <template x-if="!imagemPreview && !service.imagem_url">
                                    <div class="w-14 h-14 rounded-xl bg-gray-800 flex items-center justify-center text-gray-500 border border-gray-700">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                </template>
                                <div>
                                    <p class="text-sm font-bold text-white">Selecionar imagem</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">PNG ou JPG, até 5MB.</p>
                                </div>
                                <input type="file" name="imagem" accept="image/*" class="hidden" @change="onImagemChange">
                            </label>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Nome do Procedimento</label>
                            <input type="text" name="nome" x-model="service.nome" required placeholder="Ex: Corte Feminino + Escova"
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Preço (R$)</label>
                                <input type="number" step="0.01" name="preco" x-model="service.preco" required
                                    class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Duração (min)</label>
                                <input type="number" name="duracao_minutos" x-model="service.duracao_minutos" required
                                    class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Cor no Calendário</label>
                            <div class="flex items-center gap-3">
                                <input type="color" name="cor" x-model="service.cor" class="w-12 h-12 rounded-xl border-none p-0 cursor-pointer overflow-hidden">
                                <span class="text-xs font-bold text-gray-500 uppercase" x-text="service.cor"></span>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Descrição</label>
                            <textarea name="descricao" x-model="service.descricao" rows="2" placeholder="O que está incluso neste serviço?..."
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none"></textarea>
                        </div>
                        <label class="flex items-center justify-between p-4 bg-gray-900/60 rounded-xl border border-gray-800 cursor-pointer hover:border-green-800/50 transition-colors">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-globe text-green-400"></i>
                                <div>
                                    <p class="text-xs font-bold text-white">Disponível Online</p>
                                    <p class="text-[10px] text-gray-500">Permite agendamento pelo link público.</p>
                                </div>
                            </div>
                            <div @click="service.disponivel_online = !service.disponivel_online" class="relative inline-flex h-6 w-11 items-center rounded-full transition-all" :class="service.disponivel_online ? 'bg-green-500' : 'bg-gray-700'">
                                <input type="checkbox" name="disponivel_online" x-model="service.disponivel_online" class="sr-only">
                                <span :class="service.disponivel_online ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="bg-gray-900/60 border-t border-gray-800 px-5 sm:px-7 py-4 sm:py-5 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all" x-text="editMode ? 'Salvar Alterações' : 'Criar Serviço'"></button>
                    <button type="button" @click="showModal = false" class="px-8 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
