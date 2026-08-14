@extends('layouts.app')
@section('title', 'Perfil do Estabelecimento - GlowSystem')

@section('content')
<div class="space-y-8 max-w-2xl mx-auto">

    <div class="text-center sm:text-left">
        <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Perfil do Estabelecimento</h1>
        <p class="text-sm text-gray-400 font-medium">Configure a identidade visual e informações de contato da sua unidade.</p>
    </div>

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-500/10 border border-green-500/30 rounded-2xl px-4 py-3 text-green-400 text-sm font-medium">
        <i class="fa-solid fa-circle-check shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif

    <form id="barbearia-form" action="{{ route('panel.configuracoes.barbearia.update') }}" method="POST">
        @csrf
        <input type="hidden" name="logo_base64" id="logo-b64">

        <div class="bg-gray-900/50 bg-[#111827] border border-gray-800/50 rounded-3xl shadow-sm border border-gray-800 p-10 space-y-10 shadow-sm rounded-[32px]">

            {{-- Logo --}}
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <div class="relative shrink-0">
                    <div id="logo-clickable"
                         class="w-32 h-32 bg-gray-800/50 rounded-[40px] flex items-center justify-center border-4 border-dashed border-gray-700 cursor-pointer hover:border-green-500 transition-all overflow-hidden group">
                        @if(!empty($barbearia->logo))
                            <img id="logo-preview" src="{{ $barbearia->logo }}" class="w-full h-full object-cover">
                            <div id="logo-placeholder" class="hidden w-full h-full bg-gradient-to-br from-green-600 to-emerald-900 flex items-center justify-center">
                                <span class="text-4xl font-black text-white select-none">{{ strtoupper(substr($barbearia->nome ?? 'G', 0, 1)) }}</span>
                            </div>
                        @else
                            <img id="logo-preview" src="" class="w-full h-full object-cover hidden">
                            <div id="logo-placeholder" class="w-full h-full flex items-center justify-center flex-col gap-2">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-500 group-hover:text-green-400 transition-colors"></i>
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center shadow-lg pointer-events-none">
                        <i class="fa-solid fa-camera text-white text-xs"></i>
                    </div>
                </div>

                <input type="file" id="logo-file" accept="image/*" class="hidden">

                <div class="text-center sm:text-left">
                    <h3 class="text-xs font-bold text-white uppercase tracking-widest">Logo ou Identidade</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2 opacity-60">Sugerido: Quadrado (1:1), PNG ou JPG.</p>
                    <button type="button" onclick="document.getElementById('logo-file').click()"
                        class="mt-4 text-[10px] font-bold text-green-500 uppercase tracking-widest hover:text-green-400 flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-camera"></i> Escolher imagem
                    </button>
                    <p id="logo-nome-arquivo" class="mt-2 text-[10px] text-gray-500 hidden"></p>
                </div>
            </div>

            <div class="border-t border-gray-800/50 pt-10 grid grid-cols-1 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nome Fantasia do Estabelecimento</label>
                    <input type="text" name="nome" value="{{ old('nome', $barbearia->nome) }}" placeholder="Ex: Glow Beauty & Style"
                        class="block w-full px-6 py-4 bg-gray-800/50 border border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Telefone Principal</label>
                        <input type="text" name="telefone" value="{{ old('telefone', $barbearia->telefone) }}" placeholder="(00) 0000-0000"
                            class="block w-full px-6 py-4 bg-gray-800/50 border border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">WhatsApp Business</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $barbearia->whatsapp) }}" placeholder="(00) 00000-0000"
                            class="block w-full px-6 py-4 bg-gray-800/50 border border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Endereço Completo</label>
                    <input type="text" name="endereco" value="{{ old('endereco', $barbearia->endereco) }}" placeholder="Rua, Número, Bairro"
                        class="block w-full px-6 py-4 bg-gray-800/50 border border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="sm:col-span-2 space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Cidade</label>
                        <input type="text" name="cidade" value="{{ old('cidade', $barbearia->cidade) }}"
                            class="block w-full px-6 py-4 bg-gray-800/50 border border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">UF</label>
                        <input type="text" name="estado" maxlength="2" value="{{ old('estado', $barbearia->estado) }}" placeholder="SP"
                            class="block w-full px-6 py-4 bg-gray-800/50 border border-gray-800 rounded-2xl text-sm font-bold text-white text-center focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none uppercase">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Instagram (@usuario)</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-6 flex items-center text-green-400 font-bold italic">@</span>
                        <input type="text" name="instagram" value="{{ old('instagram', $barbearia->instagram) }}" placeholder="seu-negocio"
                            class="block w-full pl-12 pr-6 py-4 bg-gray-800/50 border border-gray-800 rounded-2xl text-sm font-bold text-white focus:ring-2 focus:ring-green-500 focus:bg-gray-900/50 transition-all outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-800/50">
                <button type="submit"
                    class="w-full sm:w-auto bg-green-500 text-white px-12 py-4 rounded-2xl text-[11px] font-bold uppercase tracking-widest hover:bg-green-600 transition-all shadow-xl shadow-green-900/20 active:scale-95">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Salvar Alterações
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('logo-clickable').addEventListener('click', function () {
    document.getElementById('logo-file').click();
});

document.getElementById('logo-file').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const img = new Image();
        img.onload = function () {
            const MAX = 400;
            let w = img.width, h = img.height;
            if (w > h) { if (w > MAX) { h = Math.round(h * MAX / w); w = MAX; } }
            else        { if (h > MAX) { w = Math.round(w * MAX / h); h = MAX; } }
            const canvas = document.createElement('canvas');
            canvas.width = w; canvas.height = h;
            canvas.getContext('2d').drawImage(img, 0, 0, w, h);
            const b64 = canvas.toDataURL('image/jpeg', 0.82);

            document.getElementById('logo-b64').value = b64;

            const preview = document.getElementById('logo-preview');
            preview.src = b64;
            preview.classList.remove('hidden');

            const placeholder = document.getElementById('logo-placeholder');
            if (placeholder) placeholder.classList.add('hidden');

            const nomeArq = document.getElementById('logo-nome-arquivo');
            nomeArq.textContent = file.name;
            nomeArq.classList.remove('hidden');
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
