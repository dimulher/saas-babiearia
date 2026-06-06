@extends('layouts.app')
@section('title', 'Regras de Agendamento - GlowSystem')

@section('content')
<div class="space-y-8 max-w-2xl mx-auto">

    <div class="text-center sm:text-left">
        <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Regras de Agendamento</h1>
        <p class="text-sm text-gray-400 font-medium">Defina a lógica e as restrições para as reservas dos seus clientes.</p>
    </div>

    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-8 space-y-8 shadow-sm">

        <div class="space-y-6">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Janela de Intervalo (Slot)</label>
                <select class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none appearance-none cursor-pointer italic">
                    <option>15 minutos</option>
                    <option selected>30 minutos</option>
                    <option>45 minutos</option>
                    <option>60 minutos</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Antecedência Máxima (Dias)</label>
                    <input type="number" value="30"
                        class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none italic">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Limite para Cancelamento (Horas)</label>
                    <input type="number" value="2"
                        class="block w-full px-5 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none italic">
                </div>
            </div>
        </div>

        <div class="space-y-4 pt-6 border-t border-gray-800/50">
            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-1">Workflow e Automação</h3>
            
            <label class="flex items-center justify-between p-4 bg-gray-900/80 rounded-2xl border border-gray-800 cursor-pointer hover:bg-green-900/30 transition-colors group" x-data="{ v: true }">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-900/50 flex items-center justify-center text-green-500 shadow-sm border border-gray-800 group-hover:border-violet-200">
                        <i class="fa-solid fa-bolt-lightning"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white uppercase tracking-widest">Aprovação Instantânea</p>
                        <p class="text-[10px] text-gray-400 font-medium">Confirmar automaticamente sem intervenção manual.</p>
                    </div>
                </div>
                <button type="button" @click="v = !v" :class="v ? 'bg-green-500' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-all shadow-inner">
                    <span :class="v ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-gray-900/50 transition-transform shadow-sm"></span>
                </button>
            </label>

            <label class="flex items-center justify-between p-4 bg-gray-900/80 rounded-2xl border border-gray-800 cursor-pointer hover:bg-green-900/30 transition-colors group" x-data="{ v: true }">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-900/50 flex items-center justify-center text-green-500 shadow-sm border border-gray-800 group-hover:border-violet-200">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white uppercase tracking-widest">Lembrete via E-mail</p>
                        <p class="text-[10px] text-gray-400 font-medium">Disparo automático 24 horas antes do evento.</p>
                    </div>
                </div>
                <button type="button" @click="v = !v" :class="v ? 'bg-green-500' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-all shadow-inner">
                    <span :class="v ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-gray-900/50 transition-transform shadow-sm"></span>
                </button>
            </label>

            <label class="flex items-center justify-between p-4 bg-gray-900/80 rounded-2xl border border-gray-800 cursor-pointer hover:bg-emerald-900/30 transition-colors group" x-data="{ v: false }">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gray-900/50 flex items-center justify-center text-emerald-500 shadow-sm border border-gray-800 group-hover:border-emerald-200">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-white uppercase tracking-widest">Lembrete via WhatsApp</p>
                        <p class="text-[10px] text-gray-400 font-medium">Requer integração Business ativa.</p>
                    </div>
                </div>
                <button type="button" @click="v = !v" :class="v ? 'bg-emerald-500' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-all shadow-inner">
                    <span :class="v ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-gray-900/50 transition-transform shadow-sm"></span>
                </button>
            </label>
        </div>

        <div class="pt-6 border-t border-gray-800/50">
            <button class="w-full sm:w-auto bg-green-500 text-white px-10 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-green-600 transition-all shadow-xl shadow-green-900/20 active:scale-95 italic">
                Consolidar Regras <i class="fa-solid fa-save ml-2"></i>
            </button>
        </div>
    </div>

</div>
@endsection
