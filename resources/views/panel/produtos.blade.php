@extends('layouts.app')
@section('title', 'Produtos & Estoque')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false,
    product: {
        id: '',
        nome: '',
        preco_venda: '',
        preco_custo: '',
        estoque_atual: '',
        estoque_minimo: '',
        unidade: 'un',
        codigo: '',
        descricao: ''
    },
    openModal(prod = null) {
        if (prod) {
            this.editMode = true;
            this.product = { ...prod };
        } else {
            this.editMode = false;
            this.product = {
                id: '',
                nome: '',
                preco_venda: '',
                preco_custo: '',
                estoque_atual: '',
                estoque_minimo: '',
                unidade: 'un',
                codigo: '',
                descricao: ''
            };
        }
        this.showModal = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Produtos & Estoque</h1>
            <p class="text-sm text-gray-400 font-medium">Controle seu inventÃ¡rio e margens de lucro de forma integrada.</p>
        </div>
        <button @click="openModal()" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest shadow-xl shadow-violet-200">
            <i class="fa-solid fa-box-open text-xs"></i> Novo Produto
        </button>
    </div>

    <!-- Lista de Produtos -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden border border-gray-800 rounded-[32px]">
        <div class="p-8 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-900/30">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" placeholder="Pesquisar no inventÃ¡rio..." class="block w-full pl-10 pr-4 py-3 bg-gray-900/50 border-gray-800 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-violet-500 transition-all outline-none">
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full shadow-sm shadow-emerald-200"></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Normal</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 bg-rose-500 rounded-full animate-pulse shadow-sm shadow-rose-200"></div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">CrÃ­tico</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-900/80 text-gray-400 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Produto</th>
                        <th class="px-8 py-5">PreÃ§o Venda</th>
                        <th class="px-8 py-5 text-center">NÃ­vel de Estoque</th>
                        <th class="px-8 py-5 text-right">AÃ§Ãµes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($produtos as $produto)
                        <tr class="hover:bg-gray-900/80 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-violet-900/30 flex items-center justify-center text-violet-600 border border-violet-800 group-hover:bg-violet-600 group-hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-bottle-droplet text-xs"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-white group-hover:text-violet-600 transition-colors uppercase tracking-tight">{{ $produto->nome }}</div>
                                        @if($produto->codigo)
                                            <div class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5 opacity-60">SKU: {{ $produto->codigo }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="font-bold text-white text-base italic tracking-tighter">R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border
                                    {{ $produto->estoque_baixo ? 'bg-rose-50 text-rose-600 border-rose-100 animate-pulse' : 'bg-emerald-900/30 text-emerald-600 border-emerald-800' }}">
                                    {{ $produto->estoque_atual }} {{ $produto->unidade }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right space-x-3">
                                <button @click="openModal({{ json_encode($produto) }})" class="text-violet-600 hover:text-violet-800 text-[10px] font-bold uppercase tracking-widest transition-colors">Editar</button>
                                <form action="{{ route('panel.produtos.destroy', $produto->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este produto?')" class="text-gray-300 hover:text-rose-600 transition-colors">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-gray-400">
                                <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-box-archive text-3xl text-gray-200"></i>
                                </div>
                                <p class="text-base font-bold text-white uppercase tracking-widest">Nenhum produto em estoque</p>
                                <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">Comece cadastrando seus produtos para controle de vendas e estoque.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($produtos->count() > 0)
            <div class="px-8 py-4 bg-gray-900/80 border-t border-gray-800">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest opacity-60">Total de {{ $produtos->count() }} itens catalogados no sistema</p>
            </div>
        @endif
    </div>

    <!-- Modal Novo/Editar Produto -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">
                <form :action="editMode ? `/panel/produtos/${product.id}` : '/panel/produtos'" method="POST">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="bg-gray-900/50 px-8 pt-10 pb-8">
                        <div class="flex items-center justify-between mb-10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-violet-900/30 rounded-2xl flex items-center justify-center text-violet-600 shadow-sm border border-violet-800">
                                    <i class="fa-solid fa-box text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white uppercase tracking-tight" x-text="editMode ? 'Editar Cadastro' : 'Novo Produto'"></h3>
                                    <p class="text-xs text-gray-400 font-medium mt-0.5">GestÃ£o tÃ©cnica de inventÃ¡rio e precificaÃ§Ã£o.</p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false" class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 text-gray-400 rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nome do Item *</label>
                                <input type="text" name="nome" x-model="product.nome" required placeholder="Ex: Shampoo PÃ³s-QuÃ­mica 500ml" class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">PreÃ§o de Venda (R$) *</label>
                                    <input type="number" step="0.01" name="preco_venda" x-model="product.preco_venda" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">PreÃ§o de Custo (R$)</label>
                                    <input type="number" step="0.01" name="preco_custo" x-model="product.preco_custo" class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Estoque DisponÃ­vel *</label>
                                    <input type="number" name="estoque_atual" x-model="product.estoque_atual" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Aviso Estoque MÃ­nimo *</label>
                                    <input type="number" name="estoque_minimo" x-model="product.estoque_minimo" required class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Unidade de Medida</label>
                                    <input type="text" name="unidade" x-model="product.unidade" placeholder="un, kg, ml..." class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">CÃ³digo de Barras / SKU</label>
                                    <input type="text" name="codigo" x-model="product.codigo" placeholder="ID Ãºnico do produto" class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Notas Adicionais</label>
                                <textarea name="descricao" x-model="product.descricao" rows="3" placeholder="InformaÃ§Ãµes do fornecedor ou detalhes tÃ©cnicos..." class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-900/80 px-8 py-8 sm:flex sm:flex-row-reverse gap-4">
                        <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-xl shadow-violet-900/20 px-10 py-4 bg-violet-600 text-[10px] font-bold text-white uppercase tracking-widest hover:bg-violet-700 transition-all sm:w-auto italic" x-text="editMode ? 'Salvar AlteraÃ§Ãµes' : 'Adicionar ao Estoque'"></button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-gray-700 px-10 py-4 bg-gray-900/50 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800/50 hover:text-gray-400 transition-all sm:mt-0 sm:w-auto">Descartar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
