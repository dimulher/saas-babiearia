@extends('layouts.app')
@section('title', 'Contas & Fluxo')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: {{ $errors->any() ? 'true' : 'false' }}
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Contas & Fluxo</h1>
            <p class="text-sm text-gray-400 font-medium">Gerencie suas receitas e despesas de forma estratégica.</p>
        </div>
        <button @click="showModal = true" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3 rounded-2xl text-sm font-bold uppercase tracking-widest shadow-xl shadow-emerald-900/30">
            <i class="fa-solid fa-plus text-xs"></i> Nova Conta
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm p-4 flex flex-wrap items-center gap-4 border border-gray-800 shadow-sm">
        <div class="relative flex-1 min-w-[200px]">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" placeholder="Pesquisar conta..." class="block w-full pl-10 pr-4 py-3 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
        </div>
        <div class="flex items-center gap-2 bg-gray-800/50 px-4 py-2 rounded-xl border border-gray-800">
            <i class="fa-solid fa-filter text-green-500 text-xs"></i>
            <select class="bg-transparent border-none p-0 text-[10px] font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-8">
                <option>Todos os status</option>
                <option>Pendente</option>
                <option>Pago</option>
            </select>
        </div>
        <div class="flex items-center gap-2 bg-gray-800/50 px-4 py-2 rounded-xl border border-gray-800">
            <i class="fa-solid fa-layer-group text-green-500 text-xs"></i>
            <select class="bg-transparent border-none p-0 text-[10px] font-bold uppercase tracking-widest focus:ring-0 text-gray-300 pr-8">
                <option>Todas as categorias</option>
                <option>Aluguel</option>
                <option>Energia</option>
                <option>Produtos</option>
            </select>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm shadow-sm overflow-hidden border border-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800/50 text-gray-400 uppercase text-[9px] font-bold tracking-widest">
                    <tr>
                        <th class="px-6 py-5">Descrição</th>
                        <th class="px-6 py-5">Categoria</th>
                        <th class="px-6 py-5 text-center">Vencimento</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-6 py-5 text-right">Valor</th>
                        <th class="px-6 py-5 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50">
                    @forelse($contas as $conta)
                        <tr class="hover:bg-gray-900/80 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white group-hover:text-green-500 transition-colors">{{ $conta->descricao }}</div>
                                <div class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $conta->tipo }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[11px] font-bold text-gray-400 bg-gray-800/50 px-3 py-1 rounded-full border border-gray-800">{{ $conta->categoria ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-xs font-bold {{ $conta->vencida ? 'text-rose-600' : 'text-gray-400' }}">
                                    {{ $conta->vencimento->format('d/m/Y') }}
                                </div>
                                @if($conta->pago_em)
                                    <div class="text-[9px] text-emerald-500 font-bold uppercase mt-1">Pago em {{ $conta->pago_em->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border
                                    {{ $conta->status == 'pago' ? 'bg-emerald-900/30 text-emerald-600 border-emerald-800' : ($conta->vencida ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-amber-50 text-amber-600 border-amber-100') }}">
                                    {{ $conta->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="font-bold text-sm {{ $conta->tipo == 'receita' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $conta->tipo == 'receita' ? '+' : '-' }} R$ {{ number_format($conta->valor, 2, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                @if($conta->status == 'pendente')
                                    <form action="{{ route('panel.contas.pagar', $conta->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 text-[10px] font-bold uppercase tracking-widest">Baixar</button>
                                    </form>
                                @endif
                                <form action="{{ route('panel.contas.destroy', $conta->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Deseja excluir esta conta?')" class="text-gray-300 hover:text-rose-600 transition-colors">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center text-gray-400">
                                <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fa-solid fa-file-invoice-dollar text-3xl text-gray-200"></i>
                                </div>
                                <p class="text-base font-bold text-white uppercase tracking-widest">Nenhuma conta cadastrada</p>
                                <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold tracking-widest">Organize suas finanças cadastrando sua primeira conta.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nova Conta -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-gray-900 rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">
                <form action="{{ route('panel.contas.store') }}" method="POST">
                    @csrf
                    <div class="bg-gray-900/50 px-6 pt-8 pb-6 sm:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-900/30 rounded-2xl flex items-center justify-center text-green-500 shadow-sm border border-green-800">
                                    <i class="fa-solid fa-receipt text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white uppercase tracking-tight">Lançamento Financeiro</h3>
                                    <p class="text-xs text-gray-400 font-medium mt-0.5">Registre uma nova entrada ou saída.</p>
                                </div>
                            </div>
                            <button type="button" @click="showModal = false" class="w-10 h-10 flex items-center justify-center hover:bg-gray-800 text-gray-400 rounded-xl transition-all">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Descrição</label>
                                <input type="text" name="descricao" required placeholder="Ex: Aluguel, Compra de Shampoos" class="block w-full px-4 py-3.5 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Valor (R$)</label>
                                    <input type="number" step="0.01" name="valor" required placeholder="0,00" class="block w-full px-4 py-3.5 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Tipo de Lançamento</label>
                                    <select name="tipo" required class="block w-full px-4 py-3.5 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                                        <option value="despesa">Despesa (Saída)</option>
                                        <option value="receita">Receita (Entrada)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Data de Vencimento</label>
                                    <input type="date" name="vencimento" value="{{ date('Y-m-d') }}" required class="block w-full px-4 py-3.5 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Categoria</label>
                                    <select name="categoria" class="block w-full px-4 py-3.5 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                                        <option value="">Selecione...</option>
                                        <option value="Aluguel">Aluguel</option>
                                        <option value="Salários">Salários</option>
                                        <option value="Produtos">Produtos</option>
                                        <option value="Infraestrutura">Infraestrutura</option>
                                        <option value="Outros">Outros</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Recorrência</label>
                                    <select name="recorrencia" class="block w-full px-4 py-3.5 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                                        <option value="nenhuma">Nenhuma</option>
                                        <option value="semanal">Semanal</option>
                                        <option value="mensal">Mensal</option>
                                        <option value="anual">Anual</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Qtd. Parcelas</label>
                                    <input type="number" name="total_parcelas" min="1" value="1" class="block w-full px-4 py-3.5 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-800/50 px-6 py-6 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-lg shadow-green-900/20 px-6 py-3 bg-green-500 text-xs font-bold text-white uppercase tracking-widest hover:bg-green-600 transition-all sm:w-auto">Lançar Conta</button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-2xl border border-gray-700 px-6 py-3 bg-gray-900/50 text-xs font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800/50 hover:text-gray-400 transition-all sm:mt-0 sm:w-auto">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
