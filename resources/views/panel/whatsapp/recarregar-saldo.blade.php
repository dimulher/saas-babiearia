@extends('layouts.app')
@section('title', 'Recarregar Saldo Business - GlowSystem')

@section('content')
<div class="space-y-8 max-w-2xl mx-auto">

    <div class="text-center sm:text-left">
        <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Créditos de Mensageria</h1>
        <p class="text-sm text-gray-400 font-medium">Gerencie seu saldo para automações de WhatsApp.</p>
    </div>

    <!-- Saldo atual -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-8 flex items-center justify-between relative overflow-hidden shadow-sm">
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-900/30 rounded-full -mr-16 -mt-16 opacity-50"></div>
        <div class="relative z-10">
            <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-widest mb-1">Saldo Atual Disponível</p>
            <div class="flex items-baseline gap-2">
                <span class="text-xl font-bold text-emerald-500 tracking-tighter italic">R$</span>
                <p class="text-5xl font-bold text-white tracking-tighter italic">0,00</p>
            </div>
            <p class="text-[9px] text-gray-400 font-bold mt-2 uppercase tracking-widest">Válido para envios ilimitados até o fim do saldo.</p>
        </div>
        <div class="relative z-10 hidden sm:block">
            <div class="w-20 h-20 bg-emerald-900/30 rounded-3xl flex items-center justify-center text-emerald-600 border border-emerald-800 shadow-sm">
                <i class="fa-brands fa-whatsapp text-4xl"></i>
            </div>
        </div>
    </div>

    <!-- Opções de recarga -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-8 space-y-8 shadow-sm">
        <div>
            <h2 class="text-xs font-bold text-white uppercase tracking-widest mb-1">Selecionar Valor de Recarga</h2>
            <p class="text-[10px] text-gray-400 font-medium">Escolha um dos pacotes pré-definidos para agilizar seu processo.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" x-data="{ valor: 10 }">
            @foreach([10, 20, 50, 100, 200, 500] as $v)
            <button @click="valor = {{ $v }}"
                :class="valor === {{ $v }} ? 'bg-violet-600 border-violet-600 text-white shadow-lg shadow-violet-900/20' : 'bg-gray-800/50 border-gray-800 text-gray-500 hover:border-violet-200'"
                class="border-2 rounded-2xl py-4 text-xs font-bold transition-all active:scale-95 italic">
                R$ {{ number_format($v, 0, ',', '.') }}
            </button>
            @endforeach

            <div class="col-span-full mt-4">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Outro valor específico</label>
                <div class="relative group">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 group-focus-within:text-violet-600 transition-colors italic">R$</span>
                    <input type="number" min="5" class="w-full bg-gray-800/50 border border-gray-800 rounded-2xl pl-12 pr-6 py-4 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all italic text-gray-300" placeholder="0,00">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50">
            <h3 class="text-xs font-bold text-white uppercase tracking-widest mb-4">Meio de Pagamento</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ metodo: 'pix' }">
                <label @click="metodo = 'pix'" class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all active:scale-95" :class="metodo === 'pix' ? 'border-violet-600 bg-violet-50/50' : 'border-gray-50 bg-gray-900/80 hover:border-gray-700'">
                    <div class="w-10 h-10 rounded-xl bg-gray-900/50 flex items-center justify-center text-violet-600 shadow-sm">
                        <i class="fa-solid fa-qrcode text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-white uppercase tracking-widest">PIX</p>
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-tighter">Liberação Instantânea</p>
                    </div>
                    <input type="radio" name="metodo" value="pix" x-model="metodo" class="hidden">
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors" :class="metodo === 'pix' ? 'border-violet-600 bg-violet-600' : 'border-gray-300'">
                        <div class="w-2 h-2 rounded-full bg-gray-900/50" x-show="metodo === 'pix'"></div>
                    </div>
                </label>

                <label @click="metodo = 'cartao'" class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all active:scale-95" :class="metodo === 'cartao' ? 'border-violet-600 bg-violet-50/50' : 'border-gray-50 bg-gray-900/80 hover:border-gray-700'">
                    <div class="w-10 h-10 rounded-xl bg-gray-900/50 flex items-center justify-center text-violet-600 shadow-sm">
                        <i class="fa-solid fa-credit-card text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-white uppercase tracking-widest">Cartão</p>
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-tighter">Crédito em até 1h</p>
                    </div>
                    <input type="radio" name="metodo" value="cartao" x-model="metodo" class="hidden">
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors" :class="metodo === 'cartao' ? 'border-violet-600 bg-violet-600' : 'border-gray-300'">
                        <div class="w-2 h-2 rounded-full bg-gray-900/50" x-show="metodo === 'cartao'"></div>
                    </div>
                </label>
            </div>
        </div>

        <button class="w-full bg-violet-600 text-white py-4 rounded-2xl text-[11px] font-bold uppercase tracking-widest hover:bg-violet-700 transition-all shadow-xl shadow-violet-900/20 flex items-center justify-center gap-3">
            Finalizar Recarga <i class="fa-solid fa-arrow-right-long"></i>
        </button>
    </div>

    <!-- Histórico de recargas -->
    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-8 shadow-sm">
        <h2 class="text-xs font-bold text-white uppercase tracking-widest mb-6">Histórico Recente</h2>
        <div class="flex flex-col items-center justify-center py-12 text-center opacity-40">
            <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mb-4 border border-gray-800">
                <i class="fa-solid fa-receipt text-2xl text-gray-300"></i>
            </div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sem movimentações registradas</p>
        </div>
    </div>

</div>
@endsection
