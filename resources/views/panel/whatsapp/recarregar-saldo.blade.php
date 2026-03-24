@extends('layouts.app')
@section('title', 'Recarregar Saldo WhatsApp')

@section('content')
<div class="space-y-6 max-w-2xl">

    <h1 class="text-2xl font-bold text-gray-900">Recarregar Saldo</h1>

    <!-- Saldo atual -->
    <div class="bg-green-50 border border-green-100 rounded-2xl p-6 flex items-center justify-between">
        <div>
            <p class="text-sm text-green-600 font-medium">Saldo Disponível</p>
            <p class="text-4xl font-black text-green-700 mt-1">R$ 0,00</p>
            <p class="text-xs text-green-500 mt-1">Usado para envio de mensagens automáticas via WhatsApp</p>
        </div>
        <i class="fa-brands fa-whatsapp text-6xl text-green-200"></i>
    </div>

    <!-- Opções de recarga -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="text-base font-semibold text-gray-900">Escolha o valor da recarga</h2>
        <div class="grid grid-cols-3 gap-3" x-data="{ valor: 10 }">
            @foreach([10, 20, 50, 100, 200, 500] as $v)
            <button @click="valor = {{ $v }}"
                :class="valor === {{ $v }} ? 'border-indigo-600 bg-indigo-50 text-indigo-700 font-semibold' : 'border-gray-200 text-gray-700 hover:border-gray-300'"
                class="border-2 rounded-xl py-3 text-sm transition-colors">
                R$ {{ number_format($v, 2, ',', '.') }}
            </button>
            @endforeach

            <div class="col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Ou digite um valor personalizado</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">R$</span>
                    <input type="number" min="5" class="w-full border border-gray-200 rounded-xl pl-8 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0,00">
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Forma de Pagamento</h3>
            <div class="space-y-2" x-data="{ metodo: 'pix' }">
                <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer" :class="metodo === 'pix' ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200'">
                    <input type="radio" name="metodo" value="pix" x-model="metodo" class="text-indigo-600">
                    <i class="fa-solid fa-qrcode text-gray-600"></i>
                    <span class="text-sm text-gray-700 font-medium">PIX (instantâneo)</span>
                </label>
                <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer" :class="metodo === 'cartao' ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200'">
                    <input type="radio" name="metodo" value="cartao" x-model="metodo" class="text-indigo-600">
                    <i class="fa-solid fa-credit-card text-gray-600"></i>
                    <span class="text-sm text-gray-700 font-medium">Cartão de Crédito</span>
                </label>
            </div>
        </div>

        <button class="w-full bg-indigo-600 text-white py-3 rounded-xl text-sm font-semibold hover:bg-indigo-700">
            Recarregar Agora
        </button>
    </div>

    <!-- Histórico de recargas -->
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Histórico de Recargas</h2>
        <div class="text-center py-8 text-gray-400 text-sm">Nenhuma recarga realizada ainda.</div>
    </div>

</div>
@endsection
