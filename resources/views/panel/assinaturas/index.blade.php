@extends('layouts.app')
@section('title', 'Clube de Assinaturas')

@section('content')
<div class="space-y-6" x-data="{ 
    showModalPlano: false,
    showModalAssinante: false,
    tab: 'planos' // 'planos' ou 'assinantes'
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Clube de Assinaturas</h1>
            <p class="text-sm text-gray-400 font-medium">Crie planos exclusivos e garanta receita recorrente para sua barbearia.</p>
        </div>
        <div class="flex gap-3">
            <button x-show="tab === 'planos'" @click="showModalPlano = true" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest shadow-xl shadow-emerald-900/30">
                <i class="fa-solid fa-plus text-xs"></i> Novo Plano
            </button>
            <button x-show="tab === 'assinantes'" @click="showModalAssinante = true" class="btn-premium flex items-center justify-center gap-2 text-white px-6 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest shadow-xl shadow-emerald-900/30" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fa-solid fa-user-plus text-xs"></i> Novo Assinante
            </button>
        </div>
    </div>

    <!-- Métricas (MRR) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-[#111827] border border-gray-800 rounded-3xl p-5 flex items-center gap-4">
            <div class="w-14 h-14 bg-emerald-900/30 text-emerald-400 rounded-2xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-hand-holding-dollar text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">MRR (Faturamento Recorrente)</p>
                <p class="text-2xl font-black text-white leading-none mt-1">R$ {{ number_format($mrr, 2, ',', '.') }}</p>
                <p class="text-[10px] text-emerald-500 font-bold mt-1">Garantido por mês</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-3xl p-5 flex items-center gap-4">
            <div class="w-14 h-14 bg-green-900/30 text-green-400 rounded-2xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Assinantes Ativos</p>
                <p class="text-2xl font-black text-white leading-none mt-1">{{ $assinantesAtivos }}</p>
                <p class="text-[10px] text-green-500 font-bold mt-1">No Clube VIP</p>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-3xl p-5 flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-900/30 text-blue-400 rounded-2xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-ticket text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Ticket Médio (Assinatura)</p>
                <p class="text-2xl font-black text-white leading-none mt-1">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</p>
                <p class="text-[10px] text-blue-500 font-bold mt-1">Por assinante</p>
            </div>
        </div>
    </div>

    <!-- Navegação (Tabs) -->
    <div class="overflow-x-auto no-scrollbar -mx-4 sm:mx-0 px-4 sm:px-0">
        <div class="flex gap-2 p-1 bg-[#111827] rounded-2xl border border-gray-800 w-max min-w-full">
            <button @click="tab='planos'" :class="tab==='planos' ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-500 hover:text-gray-300'" class="py-2.5 px-6 rounded-xl text-xs font-bold uppercase tracking-widest transition-all whitespace-nowrap">
                <i class="fa-solid fa-layer-group mr-2"></i> Planos Disponíveis
            </button>
            <button @click="tab='assinantes'" :class="tab==='assinantes' ? 'bg-gray-800 text-white shadow-sm' : 'text-gray-500 hover:text-gray-300'" class="py-2.5 px-6 rounded-xl text-xs font-bold uppercase tracking-widest transition-all whitespace-nowrap">
                <i class="fa-solid fa-users-viewfinder mr-2"></i> Gerenciar Assinantes
            </button>
        </div>
    </div>

    <!-- TAB: PLANOS -->
    <div x-show="tab === 'planos'" x-transition.opacity>
        @if($planos->isEmpty())
        <div class="bg-[#111827] border border-gray-800 border-dashed rounded-3xl p-12 text-center">
            <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-crown text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-bold text-white uppercase tracking-tight mb-2">Nenhum plano criado</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">Crie pacotes (como "VIP Mensal" ou "Corte Ilimitado") para os clientes assinarem e garantirem a receita da sua barbearia.</p>
            <button @click="showModalPlano = true" class="text-green-400 font-bold uppercase tracking-widest text-xs hover:text-green-300">
                Criar primeiro plano →
            </button>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($planos as $plano)
            <div class="relative bg-gradient-to-b from-[#1a112c] to-[#111827] border border-green-900/30 rounded-[32px] p-8 overflow-hidden group">
                <!-- Efeito glow fundo -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="relative z-10 flex justify-between items-start mb-6">
                    <div>
                        <span class="px-3 py-1 bg-green-900/40 text-green-400 text-[10px] font-black uppercase tracking-widest rounded-lg border border-green-800/50 mb-3 inline-block">Plano</span>
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">{{ $plano->nome }}</h3>
                    </div>
                    <form action="{{ route('panel.assinaturas.planos.destroy', $plano->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Excluir plano?')" class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-900/20 text-red-400 hover:bg-red-600 hover:text-white transition-colors">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
                
                <div class="mb-8">
                    <p class="text-3xl font-black text-white flex items-baseline gap-1">
                        <span class="text-sm text-gray-500">R$</span> {{ number_format($plano->valor_mensal, 2, ',', '.') }}
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">/mês</span>
                    </p>
                </div>

                <div class="space-y-3 mb-8 min-h-[120px]">
                    @if($plano->recursos)
                        @foreach($plano->recursos as $rec)
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5"></i>
                            <span class="text-sm font-medium text-gray-300">{{ $rec }}</span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-500 italic">Nenhum recurso listado.</p>
                    @endif
                </div>

                <div class="pt-6 border-t border-gray-800/50 flex items-center justify-between">
                    <div class="text-xs font-bold text-gray-400">
                        <i class="fa-solid fa-users mr-1"></i> {{ $plano->assinaturas_count }} assinantes
                    </div>
                    <form action="{{ route('panel.assinaturas.planos.toggle', $plano->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all {{ $plano->ativo ? 'bg-emerald-900/30 text-emerald-500 border border-emerald-800/50' : 'bg-gray-800 text-gray-500 border border-gray-700' }}">
                            {{ $plano->ativo ? 'Ativo' : 'Inativo' }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- TAB: ASSINANTES -->
    <div x-show="tab === 'assinantes'" x-transition.opacity style="display: none;">
        <div class="bg-[#111827] border border-gray-800 rounded-3xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-900/80 text-gray-400 uppercase text-[9px] font-bold tracking-widest">
                        <tr>
                            <th class="px-8 py-5">Cliente</th>
                            <th class="px-8 py-5">Plano Contratado</th>
                            <th class="px-8 py-5 text-center">Vencimento (Dia)</th>
                            <th class="px-8 py-5 text-center">Valor Mensal</th>
                            <th class="px-8 py-5 text-center">Status</th>
                            <th class="px-8 py-5 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @forelse($assinaturas as $ass)
                            <tr class="hover:bg-gray-900/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="font-bold text-white uppercase tracking-tight">{{ $ass->cliente->nome }}</div>
                                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">{{ $ass->cliente->telefone }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-crown text-green-500 text-xs"></i>
                                        <span class="font-bold text-gray-300">{{ $ass->plano->nome }}</span>
                                    </div>
                                    <div class="text-[9px] text-gray-500 uppercase tracking-widest mt-0.5">Desde {{ $ass->data_inicio->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-800 rounded-lg text-sm font-black text-white">
                                        {{ str_pad($ass->dia_vencimento, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="font-black text-emerald-400">R$ {{ number_format($ass->plano->valor_mensal, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <form action="{{ route('panel.assinaturas.toggle', $ass->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-4 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-widest border transition-all {{ $ass->status === 'ativo' ? 'bg-emerald-900/30 text-emerald-400 border-emerald-800/50' : 'bg-amber-900/30 text-amber-500 border-amber-800/50' }}">
                                            {{ $ass->status }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <form action="{{ route('panel.assinaturas.destroy', $ass->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Deseja cancelar esta assinatura? O cliente perderá os benefícios.')" class="text-gray-500 hover:text-red-400 transition-colors">
                                            <i class="fa-solid fa-xmark text-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-16 text-center">
                                    <i class="fa-regular fa-id-card text-4xl text-gray-700 mb-3"></i>
                                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Nenhum assinante cadastrado</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL NOVO PLANO -->
    <div x-show="showModalPlano" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showModalPlano" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showModalPlano = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModalPlano" class="inline-block align-bottom bg-[#111827] rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">
                <form action="{{ route('panel.assinaturas.planos.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-black text-white uppercase tracking-tight">Criar Plano VIP</h3>
                            <button type="button" @click="showModalPlano = false" class="text-gray-500 hover:text-white">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Nome do Plano</label>
                                <input type="text" name="nome" placeholder="Ex: Black Member" required class="w-full bg-gray-900 border border-gray-800 rounded-2xl px-5 py-4 text-white font-bold focus:outline-none focus:border-green-500 transition-colors">
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Valor Mensal (R$)</label>
                                <input type="number" step="0.01" name="valor_mensal" placeholder="120.00" required class="w-full bg-gray-900 border border-gray-800 rounded-2xl px-5 py-4 text-white font-bold focus:outline-none focus:border-green-500 transition-colors">
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Recursos / Benefícios (Um por linha)</label>
                                <textarea name="recursos" rows="4" placeholder="4 Cortes por mês&#10;10% de desconto em pomadas&#10;Café expresso grátis" class="w-full bg-gray-900 border border-gray-800 rounded-2xl px-5 py-4 text-white text-sm focus:outline-none focus:border-green-500 transition-colors"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-900/50 p-6 flex justify-end gap-3 border-t border-gray-800/50">
                        <button type="button" @click="showModalPlano = false" class="px-6 py-3 rounded-xl text-xs font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800 transition-colors">Cancelar</button>
                        <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold text-white bg-green-500 hover:bg-green-600 uppercase tracking-widest shadow-lg shadow-green-900/30 transition-all">Salvar Plano</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL NOVO ASSINANTE -->
    <div x-show="showModalAssinante" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-data="{ novoCliente: false }">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="showModalAssinante" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showModalAssinante = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModalAssinante" class="inline-block align-bottom bg-[#111827] rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-800">
                <form action="{{ route('panel.assinaturas.store') }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-black text-white uppercase tracking-tight">Nova Assinatura</h3>
                            <button type="button" @click="showModalAssinante = false" class="text-gray-500 hover:text-white">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <div class="space-y-5">
                            
                            <!-- Toggle Existente / Novo -->
                            <div class="flex bg-gray-900 rounded-xl p-1 border border-gray-800">
                                <button type="button" @click="novoCliente = false" :class="!novoCliente ? 'bg-gray-800 text-white' : 'text-gray-500 hover:text-gray-400'" class="flex-1 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all">Cliente Existente</button>
                                <button type="button" @click="novoCliente = true" :class="novoCliente ? 'bg-green-900/50 text-green-400' : 'text-gray-500 hover:text-gray-400'" class="flex-1 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all">Novo Cliente VIP</button>
                            </div>

                            <div x-show="!novoCliente">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Selecionar Cliente</label>
                                <select name="cliente_id" :required="!novoCliente" class="w-full bg-gray-900 border border-gray-800 rounded-2xl px-5 py-4 text-white font-bold focus:outline-none focus:border-emerald-500 transition-colors appearance-none">
                                    <option value="">Selecione o cliente</option>
                                    @foreach($clientes as $c)
                                        <option value="{{ $c->id }}">{{ $c->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="novoCliente" style="display: none;" class="space-y-4 p-4 bg-gray-900/50 border border-gray-800 rounded-2xl">
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Nome Completo</label>
                                    <input type="text" name="novo_cliente_nome" placeholder="Nome do novo cliente" :required="novoCliente" class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-green-500 transition-colors">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Telefone / WhatsApp</label>
                                    <input type="tel" name="novo_cliente_telefone" placeholder="(00) 00000-0000"
                                        x-data="{ telInput: '' }" x-model="telInput"
                                        @input="telInput = $event.target.value.replace(/\D/g, '').substring(0, 11).replace(/^(\d{2})(\d)/g, '($1) $2').replace(/(\d)(\d{4})$/, '$1-$2')"
                                        class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-green-500 transition-colors">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">CPF</label>
                                    <input type="text" name="novo_cliente_cpf" placeholder="000.000.000-00" maxlength="14"
                                        x-data="{ cpfInput: '' }" x-model="cpfInput"
                                        @input="let r = $event.target.value.replace(/\D/g,'').substring(0,11); cpfInput = r.replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d)/,'$1.$2').replace(/(\d{3})(\d{1,2})$/,'$1-$2')"
                                        class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 text-white font-bold focus:outline-none focus:border-green-500 transition-colors">
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Plano</label>
                                <select name="plano_id" required class="w-full bg-gray-900 border border-gray-800 rounded-2xl px-5 py-4 text-white font-bold focus:outline-none focus:border-emerald-500 transition-colors appearance-none">
                                    <option value="">Selecione o plano</option>
                                    @foreach($planos as $p)
                                        @if($p->ativo)
                                            <option value="{{ $p->id }}">{{ $p->nome }} - R$ {{ number_format($p->valor_mensal, 2, ',', '.') }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Dia do Vencimento (1 a 31)</label>
                                <input type="number" min="1" max="31" name="dia_vencimento" value="5" required class="w-full bg-gray-900 border border-gray-800 rounded-2xl px-5 py-4 text-white font-bold focus:outline-none focus:border-emerald-500 transition-colors">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-900/50 p-6 flex justify-end gap-3 border-t border-gray-800/50">
                        <button type="button" @click="showModalAssinante = false" class="px-6 py-3 rounded-xl text-xs font-bold text-gray-400 uppercase tracking-widest hover:bg-gray-800 transition-colors">Cancelar</button>
                        <button type="submit" class="px-6 py-3 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 uppercase tracking-widest shadow-lg shadow-emerald-900/30 transition-all">Assinar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
