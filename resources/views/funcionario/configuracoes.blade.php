@extends('layouts.funcionario')
@section('title', 'Configurações')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <h1 class="text-xl font-black text-white uppercase tracking-tight">Configurações</h1>
        <p class="text-sm text-gray-500 mt-0.5">Gerencie seus dados e preferências pessoais.</p>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 rounded-2xl px-4 py-3 text-green-400 text-sm font-medium">
        <i class="fa-solid fa-circle-check shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- ── PERFIL ──────────────────────────────────── --}}
    <div class="bg-[#111827] border border-gray-800/60 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800/60 flex items-center gap-2">
            <i class="fa-solid fa-user text-green-500 text-xs"></i>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Meu Perfil</p>
        </div>

        <div class="p-6 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl overflow-hidden border border-green-800/40 shrink-0">
                @if($profissional->foto)
                    <img src="{{ $profissional->foto }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-green-900/30 flex items-center justify-center text-green-400 text-2xl font-black select-none uppercase">
                        {{ $profissional->initials }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-lg font-black text-white tracking-tight">{{ $profissional->nome }}</p>
                <p class="text-xs text-green-400 font-bold uppercase tracking-wider mt-0.5">Funcionário</p>
                <p class="text-xs text-gray-500 mt-1">{{ $profissional->barbearia->nome ?? '—' }}</p>
            </div>
        </div>

        <div class="divide-y divide-gray-800/50 border-t border-gray-800/60">
            <div class="grid grid-cols-2 px-6 py-3.5">
                <span class="text-sm text-gray-500">Comissão</span>
                <span class="text-sm font-bold text-emerald-400 text-right">{{ $profissional->comissao_percentual }}%</span>
            </div>
            <div class="grid grid-cols-2 px-6 py-3.5">
                <span class="text-sm text-gray-500">Agendamento Online</span>
                <span class="text-sm font-bold text-right {{ $profissional->aceita_agendamento_online ? 'text-green-400' : 'text-gray-600' }}">
                    {{ $profissional->aceita_agendamento_online ? 'Ativo' : 'Inativo' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── FOTO DE PERFIL ──────────────────────────── --}}
    <div class="bg-[#111827] border border-gray-800/60 rounded-2xl overflow-hidden"
         x-data="{
             hasNewPhoto: false,
             newPhotoSrc: '',
             fotoBase64: '',
             loading: false,
             processarFoto(event) {
                 const file = event.target.files[0];
                 if (!file) return;
                 this.loading = true;
                 const reader = new FileReader();
                 reader.onload = (e) => {
                     const img = new Image();
                     img.onload = () => {
                         const MAX = 200;
                         let w = img.width, h = img.height;
                         if (w > h) { if (w > MAX) { h = Math.round(h * MAX / w); w = MAX; } }
                         else { if (h > MAX) { w = Math.round(w * MAX / h); h = MAX; } }
                         const canvas = document.createElement('canvas');
                         canvas.width = w; canvas.height = h;
                         canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                         const b64 = canvas.toDataURL('image/jpeg', 0.75);
                         this.newPhotoSrc = b64;
                         this.fotoBase64 = b64;
                         this.hasNewPhoto = true;
                         this.loading = false;
                     };
                     img.src = e.target.result;
                 };
                 reader.readAsDataURL(file);
             }
         }">
        <div class="px-6 py-4 border-b border-gray-800/60 flex items-center gap-2">
            <i class="fa-solid fa-camera text-green-500 text-xs"></i>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Foto de Perfil</p>
        </div>
        <form action="{{ route('funcionario.configuracoes.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="foto_base64" :value="fotoBase64">
            <div class="flex items-center gap-5">
                <div class="relative shrink-0">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-dashed border-gray-700 hover:border-green-500 transition-colors cursor-pointer flex items-center justify-center bg-gray-900/50"
                         @click="$refs.fotoInput.click()">
                        <div x-show="!hasNewPhoto" class="w-full h-full">
                            @if($profissional->foto)
                                <img src="{{ $profissional->foto }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-green-400 text-2xl font-black select-none uppercase">
                                    {{ $profissional->initials }}
                                </div>
                            @endif
                        </div>
                        <img x-show="hasNewPhoto" :src="newPhotoSrc" class="w-full h-full object-cover" style="display:none">
                    </div>
                    <div class="absolute -bottom-1.5 -right-1.5 w-7 h-7 bg-green-500 rounded-full flex items-center justify-center shadow-lg pointer-events-none">
                        <i class="fa-solid fa-camera text-white text-[10px]"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white">Sua foto de perfil</p>
                    <p class="text-xs text-gray-500 mt-1">Aparece no calendário, na equipe e na página de agendamento. Use uma foto quadrada para melhor resultado.</p>
                    <input type="file" x-ref="fotoInput" accept="image/*" class="hidden" @change="processarFoto($event)">
                    <button type="button" @click="$refs.fotoInput.click()"
                        class="mt-2 text-xs text-green-400 hover:text-green-300 font-bold">
                        <i class="fa-solid fa-upload text-[10px] mr-1"></i> Escolher foto
                    </button>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    :disabled="!hasNewPhoto || loading"
                    class="px-6 py-3 bg-green-500 hover:bg-green-600 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-green-900/20">
                    <i x-show="!loading" class="fa-solid fa-floppy-disk mr-1.5"></i>
                    <i x-show="loading" x-cloak class="fa-solid fa-spinner fa-spin mr-1.5"></i>
                    Salvar Foto
                </button>
            </div>
        </form>
    </div>

    {{-- ── TELEFONE ─────────────────────────────────── --}}
    <div class="bg-[#111827] border border-gray-800/60 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800/60 flex items-center gap-2">
            <i class="fa-brands fa-whatsapp text-green-500 text-xs"></i>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Telefone / WhatsApp</p>
        </div>
        <form action="{{ route('funcionario.configuracoes.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <p class="text-xs text-gray-500">Seu número de contato visível para clientes e para o proprietário.</p>
            <div class="relative">
                <i class="fa-brands fa-whatsapp absolute left-4 top-1/2 -translate-y-1/2 text-green-600 text-sm pointer-events-none"></i>
                <input type="tel" name="telefone"
                    value="{{ old('telefone', $profissional->telefone) }}"
                    placeholder="(00) 00000-0000"
                    class="w-full bg-gray-900 border border-gray-800 rounded-2xl pl-11 pr-4 py-4 text-white font-medium text-sm placeholder-gray-600 focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600/15 transition-all">
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-green-900/20">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Salvar
                </button>
            </div>
        </form>
    </div>

    {{-- ── CÓDIGO DE ACESSO ─────────────────────────── --}}
    <div class="bg-[#111827] border border-gray-800/60 rounded-2xl overflow-hidden" x-data="{ copiado: false }">
        <div class="px-6 py-4 border-b border-gray-800/60 flex items-center gap-2">
            <i class="fa-solid fa-key text-amber-500 text-xs"></i>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Código de Acesso</p>
        </div>
        <div class="p-6 space-y-3">
            <p class="text-xs text-gray-500">Use este código para fazer login neste painel. Guarde-o com segurança — somente o proprietário pode alterá-lo.</p>
            <div class="flex items-center gap-3 bg-gray-900 border border-gray-800 rounded-2xl px-5 py-4">
                <span class="flex-1 font-mono font-black text-xl text-white tracking-[.3em] select-all">
                    {{ $profissional->codigo_acesso }}
                </span>
                <button type="button"
                    @click="navigator.clipboard.writeText('{{ $profissional->codigo_acesso }}'); copiado = true; setTimeout(() => copiado = false, 2000)"
                    class="shrink-0 w-9 h-9 flex items-center justify-center rounded-xl bg-gray-800 hover:bg-green-500 transition-all text-gray-400 hover:text-white">
                    <i class="fa-regular fa-copy text-sm" x-show="!copiado"></i>
                    <i class="fa-solid fa-check text-sm text-white" x-show="copiado" x-cloak></i>
                </button>
            </div>
            <p class="text-[11px] text-amber-500/70 flex items-center gap-1.5">
                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                Não compartilhe seu código com outras pessoas.
            </p>
        </div>
    </div>

    {{-- ── LINK DE AGENDAMENTO ──────────────────────── --}}
    <div class="bg-[#111827] border border-gray-800/60 rounded-2xl overflow-hidden" x-data="{ linkCopiado: false }">
        <div class="px-6 py-4 border-b border-gray-800/60 flex items-center gap-2">
            <i class="fa-solid fa-link text-green-500 text-xs"></i>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Link de Agendamento</p>
        </div>
        <div class="p-6 space-y-3">
            <p class="text-xs text-gray-500">Compartilhe este link com seus clientes para que eles agendem direto com você.</p>
            <div class="flex items-center gap-3 bg-gray-900 border border-gray-800 rounded-2xl px-5 py-4">
                <i class="fa-solid fa-user-tie text-green-500 text-xs shrink-0"></i>
                <span class="flex-1 text-xs text-gray-400 font-mono truncate">{{ $profissional->link_agendamento }}</span>
                <button type="button"
                    @click="navigator.clipboard.writeText('{{ $profissional->link_agendamento }}'); linkCopiado = true; setTimeout(() => linkCopiado = false, 2000)"
                    class="shrink-0 w-9 h-9 flex items-center justify-center rounded-xl bg-gray-800 hover:bg-green-500 transition-all text-gray-400 hover:text-white">
                    <i class="fa-regular fa-copy text-sm" x-show="!linkCopiado"></i>
                    <i class="fa-solid fa-check text-sm text-white" x-show="linkCopiado" x-cloak></i>
                </button>
            </div>
            <a href="{{ $profissional->link_agendamento }}" target="_blank"
               class="flex items-center justify-center gap-2 w-full py-3 bg-green-500/10 hover:bg-green-500 border border-green-500/30 hover:border-green-500 text-green-400 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                Ver página de agendamento
            </a>
        </div>
    </div>

    {{-- ── SAIR DA CONTA ────────────────────────────── --}}
    <div class="bg-[#111827] border border-red-900/20 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-red-900/20 flex items-center gap-2">
            <i class="fa-solid fa-right-from-bracket text-red-400 text-xs"></i>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Sessão</p>
        </div>
        <div class="p-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-bold text-white">Sair da conta</p>
                <p class="text-xs text-gray-500 mt-0.5">Encerra a sessão neste dispositivo.</p>
            </div>
            <form method="POST" action="{{ route('funcionario.logout') }}">
                @csrf
                <button type="submit"
                    class="px-5 py-3 bg-red-500/10 hover:bg-red-500 border border-red-500/30 hover:border-red-500 text-red-400 hover:text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all">
                    <i class="fa-solid fa-right-from-bracket mr-1.5"></i> Sair
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
