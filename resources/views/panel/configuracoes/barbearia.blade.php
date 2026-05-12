@extends('layouts.app')
@section('title', 'Perfil do Estabelecimento - GlowSystem')

@section('content')
<div class="space-y-8 max-w-2xl mx-auto">

    <div class="text-center sm:text-left">
        <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Perfil do Estabelecimento</h1>
        <p class="text-sm text-gray-400 font-medium">Configure a identidade visual e informações de contato da sua unidade.</p>
    </div>

    <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-10 space-y-10 shadow-sm rounded-[32px]">

        <!-- Logo -->
        <div class="flex flex-col sm:flex-row items-center gap-8">
            <div class="w-32 h-32 bg-gray-800/50 rounded-[40px] flex items-center justify-center border-4 border-dashed border-gray-800 cursor-pointer hover:bg-violet-900/30 hover:border-violet-200 transition-all group relative overflow-hidden">
                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-200 group-hover:text-violet-400 transition-colors"></i>
                <div class="absolute inset-0 bg-violet-600/0 group-hover:bg-violet-600/5 transition-all"></div>
            </div>
            <div class="text-center sm:text-left">
                <h3 class="text-xs font-bold text-white uppercase tracking-widest">Logo ou Identidade</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2 opacity-60">Sugerido: Quadrado (1:1), PNG ou JPG.</p>
                <button class="mt-4 text-[10px] font-bold text-violet-600 uppercase tracking-widest hover:text-violet-800 flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-camera"></i> Atualizar Imagem
                </button>
            </div>
        </div>

        <div class="border-t border-gray-50 pt-10 grid grid-cols-1 gap-8">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nome Fantasia do Estabelecimento</label>
                <input type="text" placeholder="Ex: Glow Beauty & Style"
                    class="block w-full px-6 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Telefone Principal</label>
                    <input type="text" placeholder="(00) 0000-0000"
                        class="block w-full px-6 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">WhatsApp Business</label>
                    <input type="text" placeholder="(00) 00000-0000"
                        class="block w-full px-6 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Endereço Completo</label>
                <input type="text" placeholder="Rua, Número, Bairro"
                    class="block w-full px-6 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="sm:col-span-2 space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cidade</label>
                    <input type="text"
                        class="block w-full px-6 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">UF</label>
                    <input type="text" maxlength="2" placeholder="SP"
                        class="block w-full px-6 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold text-center focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none uppercase">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Instagram (@usuario)</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-6 flex items-center text-violet-400 font-bold italic">@</span>
                    <input type="text" placeholder="seu-negocio"
                        class="block w-full pl-12 pr-6 py-4 bg-gray-800/50 border-gray-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-violet-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-gray-50">
            <button class="w-full sm:w-auto bg-violet-600 text-white px-12 py-4 rounded-2xl text-[11px] font-bold uppercase tracking-widest hover:bg-violet-700 transition-all shadow-xl shadow-violet-900/20 active:scale-95 italic">
                Preservar Alterações <i class="fa-solid fa-check ml-2"></i>
            </button>
        </div>
    </div>

</div>
@endsection
