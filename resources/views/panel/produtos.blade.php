@extends('layouts.app')
@section('title', 'Produtos & Estoque')

@section('content')
<div class="space-y-6" x-data="{
    showModal: false,
    editMode: false,
    imagemPreview: null,
    product: { id: '', nome: '', preco_venda: '', preco_custo: '', estoque_atual: '', estoque_minimo: '', unidade: 'un', codigo: '', descricao: '', imagem_url: '' },
    openModal(prod = null) {
        if (prod) { this.editMode = true; this.product = { ...prod }; }
        else { this.editMode = false; this.product = { id: '', nome: '', preco_venda: '', preco_custo: '', estoque_atual: '', estoque_minimo: '', unidade: 'un', codigo: '', descricao: '', imagem_url: '' }; }
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
            <h1 class="text-2xl font-bold text-white tracking-tight">Produtos & Estoque</h1>
            <p class="text-sm text-gray-400 font-medium">Controle seu inventário e margens de lucro de forma integrada.</p>
        </div>
        <button @click="openModal()" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-5 py-3 rounded-xl transition-all shadow-lg shadow-green-900/30 uppercase tracking-wider">
            <i class="fa-solid fa-box-open text-xs"></i> Novo Produto
        </button>
    </div>

    <div class="bg-[#111827] border border-gray-800/50 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-gray-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
                <input type="text" placeholder="Pesquisar no inventário..."
                    class="block w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Normal</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Crítico</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/50 text-gray-500 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-6 py-4">Produto</th>
                        <th class="px-6 py-4">Preço Venda</th>
                        <th class="px-6 py-4 text-center">Estoque</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($produtos as $produto)
                        <tr class="hover:bg-gray-900/40 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    @if($produto->imagem_url)
                                        <img src="{{ $produto->imagem_url }}" alt="{{ $produto->nome }}" class="w-11 h-11 rounded-xl object-cover border border-gray-800 group-hover:border-green-500/50 transition-all">
                                    @else
                                        <div class="w-11 h-11 rounded-xl bg-green-900/30 flex items-center justify-center text-green-400 border border-green-800/50 group-hover:bg-green-500 group-hover:text-white transition-all">
                                            <i class="fa-solid fa-bottle-droplet text-xs"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold text-white group-hover:text-green-400 transition-colors text-sm">{{ $produto->nome }}</div>
                                        @if($produto->codigo)
                                            <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">SKU: {{ $produto->codigo }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 font-bold text-white">R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border
                                    {{ $produto->estoque_baixo ? 'bg-rose-900/30 text-rose-400 border-rose-800/50 animate-pulse' : 'bg-emerald-900/30 text-emerald-400 border-emerald-800/50' }}">
                                    {{ $produto->estoque_atual }} {{ $produto->unidade }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right space-x-3">
                                <button @click="openModal({{ json_encode($produto) }})" class="text-green-400 hover:text-green-300 text-[10px] font-bold uppercase tracking-widest transition-colors">Editar</button>
                                <form action="{{ route('panel.produtos.destroy', $produto->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Excluir este produto?')" class="text-gray-600 hover:text-rose-500 transition-colors">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <i class="fa-solid fa-box-archive text-3xl text-gray-700 mb-3 block"></i>
                                <p class="text-sm font-bold text-white">Nenhum produto em estoque</p>
                                <p class="text-xs text-gray-500 mt-1">Cadastre seus produtos para controle de vendas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($produtos->count() > 0)
            <div class="px-6 py-3 border-t border-gray-800/50">
                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">{{ $produtos->count() }} itens catalogados</p>
            </div>
        @endif
    </div>

    <!-- Modal Novo/Editar Produto -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
        <div x-transition.opacity class="fixed inset-0 bg-black/70 backdrop-blur-sm" @click="showModal = false"></div>
        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-[#111827] rounded-2xl shadow-2xl w-full max-w-lg border border-gray-800 z-10 max-h-[90vh] overflow-y-auto">
            <form :action="editMode ? `/panel/produtos/${product.id}` : '/panel/produtos'" method="POST" enctype="multipart/form-data">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                <div class="px-7 pt-7 pb-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-green-900/30 flex items-center justify-center text-green-400 border border-green-800/50">
                                <i class="fa-solid fa-box text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white" x-text="editMode ? 'Editar Produto' : 'Novo Produto'"></h3>
                                <p class="text-xs text-gray-500 mt-0.5">Gestão de inventário e precificação.</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false" class="w-9 h-9 flex items-center justify-center hover:bg-gray-800 text-gray-500 hover:text-white rounded-xl transition-all">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Foto do Produto</label>
                            <label class="flex items-center gap-4 p-4 bg-gray-900 border border-gray-700 border-dashed rounded-xl cursor-pointer hover:border-green-500 transition-all">
                                <template x-if="imagemPreview || product.imagem_url">
                                    <img :src="imagemPreview || product.imagem_url" class="w-14 h-14 rounded-xl object-cover border border-gray-700">
                                </template>
                                <template x-if="!imagemPreview && !product.imagem_url">
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
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Nome do Item</label>
                            <input type="text" name="nome" x-model="product.nome" required placeholder="Ex: Shampoo Pós-Química 500ml"
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Preço Venda (R$)</label>
                                <input type="number" step="0.01" name="preco_venda" x-model="product.preco_venda" required
                                    class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Preço Custo (R$)</label>
                                <input type="number" step="0.01" name="preco_custo" x-model="product.preco_custo"
                                    class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Estoque Atual</label>
                                <input type="number" name="estoque_atual" x-model="product.estoque_atual" required
                                    class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Mínimo</label>
                                <input type="number" name="estoque_minimo" x-model="product.estoque_minimo" required
                                    class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Unidade</label>
                                <input type="text" name="unidade" x-model="product.unidade" placeholder="un, kg, ml..."
                                    class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">SKU/Código</label>
                                <input type="text" name="codigo" x-model="product.codigo" placeholder="Código único"
                                    class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest ml-1">Notas</label>
                            <textarea name="descricao" x-model="product.descricao" rows="2" placeholder="Informações adicionais..."
                                class="block w-full px-4 py-3.5 bg-gray-900 border border-gray-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-green-500/50 focus:border-green-600 transition-all outline-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-900/60 border-t border-gray-800 px-7 py-5 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all" x-text="editMode ? 'Salvar' : 'Adicionar ao Estoque'"></button>
                    <button type="button" @click="showModal = false" class="px-8 py-3 bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
