@extends('layouts.app')
@section('title', 'Detalhes do Atendimento')

@section('content')
<div class="space-y-6" x-data="{ showCloseModal: false, formaPagamento: 'dinheiro' }">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('panel.comandas') }}" class="w-10 h-10 rounded-xl bg-gray-900/50 border border-gray-800 flex items-center justify-center text-gray-400 hover:text-violet-600 hover:border-violet-800 transition-all shadow-sm">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Comanda: {{ $comanda->cliente_nome }}</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-2 mt-0.5">
                    <i class="fa-solid fa-user-tie text-violet-500"></i>
                    Profissional: {{ $comanda->profissional->nome ?? 'Nenhum' }}
                </p>
            </div>
        </div>
        
        @if($comanda->status === 'aberta')
            <span class="bg-emerald-900/30 text-emerald-600 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border border-emerald-800 shadow-sm shadow-emerald-900/20 flex items-center gap-2 w-fit">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Atendimento Ativo
            </span>
        @else
            <span class="bg-gray-800/50 text-gray-400 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border border-gray-800 w-fit flex items-center gap-2">
                <i class="fa-solid fa-lock"></i>
                Finalizado
            </span>
        @endif
    </div>

    <!-- Layout Dividido -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Esquerda: Itens -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Lista de Itens -->
            <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden border border-gray-800">
                <div class="px-6 py-5 border-b border-gray-50 bg-gray-900/80 flex justify-between items-center">
                    <h2 class="text-xs font-bold text-white uppercase tracking-widest">Itens do Atendimento</h2>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $comanda->itens->count() }} Lançamentos</span>
                </div>
                
                @if($comanda->itens->isEmpty())
                    <div class="p-16 flex flex-col items-center justify-center text-gray-400">
                        <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                            <i class="fa-solid fa-receipt text-2xl text-gray-200"></i>
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nenhum item lançado ainda</p>
                    </div>
                @else
                    <ul class="divide-y divide-gray-50">
                        @foreach($comanda->itens as $item)
                            <li class="px-6 py-5 flex items-center justify-between hover:bg-gray-800/50 transition-colors group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-violet-900/30 flex items-center justify-center text-violet-600 border border-violet-800 group-hover:scale-110 transition-transform">
                                        @if($item->servico_id)
                                            <i class="fa-solid fa-sparkles text-xs"></i>
                                        @else
                                            <i class="fa-solid fa-box text-xs"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-white text-sm">{{ $item->descricao }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $item->quantidade }}x de R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <p class="font-bold text-white italic">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
                                    @if($comanda->status === 'aberta')
                                        <form action="{{ route('panel.comandas.itens.destroy', [$comanda->id, $item->id]) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-rose-600 transition-colors">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            
            <!-- Formulários de Adição (Apenas se Aberta) -->
            @if($comanda->status === 'aberta')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Add Serviço -->
                    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 border border-gray-800 shadow-sm">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-wand-magic-sparkles text-violet-500"></i>
                            Adicionar Procedimento
                        </h3>
                        <form action="{{ route('panel.comandas.itens.store', $comanda->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="tipo" value="servico">
                            <input type="hidden" name="quantidade" value="1">
                            <div>
                                <select name="item_id" required class="block w-full px-4 py-3 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                    <option value="">Selecione um serviço...</option>
                                    @foreach($servicos as $servico)
                                        <option value="{{ $servico->id }}">{{ $servico->nome }} - R$ {{ number_format($servico->preco, 2, ',', '.') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-violet-900/30 text-violet-600 hover:bg-violet-600 hover:text-white font-bold text-[10px] uppercase tracking-widest py-3.5 px-4 rounded-2xl transition-all border border-violet-800 shadow-sm shadow-violet-900/20">
                                Lançar Procedimento
                            </button>
                        </form>
                    </div>

                    <!-- Add Produto -->
                    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-6 border border-gray-800 shadow-sm">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-basket-shopping text-violet-500"></i>
                            Venda de Produto
                        </h3>
                        <form action="{{ route('panel.comandas.itens.store', $comanda->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="tipo" value="produto">
                            <div class="flex gap-3">
                                <select name="item_id" required class="flex-1 px-4 py-3 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                                    <option value="">Selecione...</option>
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->id }}">{{ $produto->nome }} - R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="quantidade" value="1" min="1" required class="w-20 px-4 py-3 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none text-center">
                            </div>
                            <button type="submit" class="w-full bg-violet-900/30 text-violet-600 hover:bg-violet-600 hover:text-white font-bold text-[10px] uppercase tracking-widest py-3.5 px-4 rounded-2xl transition-all border border-violet-800 shadow-sm shadow-violet-900/20">
                                Lançar Produto
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>

        <!-- Coluna Direita: Resumo e Pagamento -->
        <div class="space-y-6">
            <div class="bg-gray-900 rounded-[32px] p-8 text-white shadow-2xl relative overflow-hidden border border-gray-800">
                <div class="absolute top-0 right-0 -mr-12 -mt-12 w-48 h-48 rounded-full bg-violet-600 opacity-20 blur-3xl"></div>
                
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-8 flex items-center gap-2 relative">
                    <i class="fa-solid fa-wallet text-violet-400"></i> Resumo Financeiro
                </h3>
                
                <div class="space-y-4 mb-10 relative">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Subtotal</span>
                        <span class="font-bold text-gray-100 italic">R$ {{ number_format($comanda->subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Descontos</span>
                        <span class="font-bold text-rose-400 italic">R$ {{ number_format($comanda->desconto, 2, ',', '.') }}</span>
                    </div>
                    <div class="h-px bg-gray-800 my-6"></div>
                    <div class="flex justify-between items-end">
                        <span class="text-[10px] font-bold text-violet-400 uppercase tracking-widest">Total Líquido</span>
                        <span class="text-4xl font-bold text-white tracking-tighter">R$ {{ number_format($comanda->total, 2, ',', '.') }}</span>
                    </div>
                </div>

                @if($comanda->status === 'aberta')
                    <button @click="showCloseModal = true" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold text-[11px] uppercase tracking-widest py-4 px-6 rounded-2xl transition-all shadow-xl shadow-violet-900/50 flex items-center justify-center gap-3 active:scale-95 border border-violet-500 relative">
                        <i class="fa-solid fa-check-double text-xs"></i>
                        Receber Pagamento
                    </button>
                @else
                    <div class="bg-gray-800/50 rounded-2xl p-5 border border-gray-700 relative">
                        <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-2">Histórico de Transação</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-gray-200 capitalize">{{ str_replace('_', ' ', $comanda->forma_pagamento) }}</span>
                            <i class="fa-solid fa-credit-card text-violet-500"></i>
                        </div>
                        <p class="text-[9px] text-gray-500 mt-4 uppercase font-bold tracking-widest">Finalizada em {{ $comanda->fechada_em->format('d/m/Y H:i') }}</p>
                    </div>
                @endif
            </div>
            
            @if($comanda->observacoes)
                <div class="bg-violet-50/50 rounded-2xl p-6 border border-violet-800 shadow-sm">
                    <h4 class="text-[10px] font-bold text-violet-600 uppercase tracking-widest flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-circle-info"></i> Notas do Cliente
                    </h4>
                    <p class="text-gray-400 text-sm font-medium leading-relaxed italic">"{{ $comanda->observacoes }}"</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Pagamento -->
    <div x-show="showCloseModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCloseModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="showCloseModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showCloseModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-800">
                <form action="{{ route('panel.comandas.fechar', $comanda->id) }}" method="POST">
                    @csrf
                    <div class="bg-gray-900/50 px-6 pt-8 pb-6 sm:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-800">
                                    <i class="fa-solid fa-cash-register text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-white uppercase tracking-tight">Finalizar</h3>
                            </div>
                            <button type="button" @click="showCloseModal = false" class="text-gray-400 hover:text-gray-400">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        
                        <div class="bg-gray-800/50 p-6 rounded-[24px] border border-gray-800 mb-8 text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total à Receber</p>
                            <p class="text-4xl font-bold text-white tracking-tighter italic">R$ {{ number_format($comanda->total, 2, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 block mb-4">Escolha o Método</label>
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Dinheiro -->
                                <label class="relative flex cursor-pointer rounded-2xl border p-4 shadow-sm transition-all" :class="formaPagamento === 'dinheiro' ? 'border-violet-600 bg-violet-900/30 ring-1 ring-violet-600' : 'border-gray-800 bg-gray-800/50 hover:bg-gray-900/50'">
                                    <input type="radio" name="forma_pagamento" value="dinheiro" x-model="formaPagamento" class="sr-only">
                                    <div class="flex flex-col items-center justify-center w-full gap-2">
                                        <i class="fa-solid fa-money-bill-wave" :class="formaPagamento === 'dinheiro' ? 'text-violet-600' : 'text-gray-400'"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-widest" :class="formaPagamento === 'dinheiro' ? 'text-violet-900' : 'text-gray-500'">Dinheiro</span>
                                    </div>
                                </label>
                                <!-- PIX -->
                                <label class="relative flex cursor-pointer rounded-2xl border p-4 shadow-sm transition-all" :class="formaPagamento === 'pix' ? 'border-violet-600 bg-violet-900/30 ring-1 ring-violet-600' : 'border-gray-800 bg-gray-800/50 hover:bg-gray-900/50'">
                                    <input type="radio" name="forma_pagamento" value="pix" x-model="formaPagamento" class="sr-only">
                                    <div class="flex flex-col items-center justify-center w-full gap-2">
                                        <i class="fa-solid fa-pix" :class="formaPagamento === 'pix' ? 'text-violet-600' : 'text-gray-400'"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-widest" :class="formaPagamento === 'pix' ? 'text-violet-900' : 'text-gray-500'">PIX</span>
                                    </div>
                                </label>
                                <!-- Cartão de Crédito -->
                                <label class="relative flex cursor-pointer rounded-2xl border p-4 shadow-sm transition-all" :class="formaPagamento === 'cartao_credito' ? 'border-violet-600 bg-violet-900/30 ring-1 ring-violet-600' : 'border-gray-800 bg-gray-800/50 hover:bg-gray-900/50'">
                                    <input type="radio" name="forma_pagamento" value="cartao_credito" x-model="formaPagamento" class="sr-only">
                                    <div class="flex flex-col items-center justify-center w-full gap-2">
                                        <i class="fa-solid fa-credit-card" :class="formaPagamento === 'cartao_credito' ? 'text-violet-600' : 'text-gray-400'"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-widest" :class="formaPagamento === 'cartao_credito' ? 'text-violet-900' : 'text-gray-500'">Crédito</span>
                                    </div>
                                </label>
                                <!-- Cartão de Débito -->
                                <label class="relative flex cursor-pointer rounded-2xl border p-4 shadow-sm transition-all" :class="formaPagamento === 'cartao_debito' ? 'border-violet-600 bg-violet-900/30 ring-1 ring-violet-600' : 'border-gray-800 bg-gray-800/50 hover:bg-gray-900/50'">
                                    <input type="radio" name="forma_pagamento" value="cartao_debito" x-model="formaPagamento" class="sr-only">
                                    <div class="flex flex-col items-center justify-center w-full gap-2">
                                        <i class="fa-solid fa-card-credit-stack" :class="formaPagamento === 'cartao_debito' ? 'text-violet-600' : 'text-gray-400'"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-widest" :class="formaPagamento === 'cartao_debito' ? 'text-violet-900' : 'text-gray-500'">Débito</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="bg-gray-800/50 px-6 py-6 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-lg shadow-emerald-100 px-8 py-3.5 bg-emerald-600 text-[11px] font-bold text-white uppercase tracking-widest hover:bg-emerald-700 transition-all sm:w-auto">
                            Baixar Comanda
                        </button>
                        <button type="button" @click="showCloseModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-gray-700 px-8 py-3.5 bg-gray-900/50 text-[11px] font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800/50 transition-all sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
