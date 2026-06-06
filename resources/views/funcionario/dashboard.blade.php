<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Funcionário — GlowSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.05); }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#0B0F19] min-h-screen text-white pb-20">

    <div class="fixed top-0 inset-x-0 h-80 bg-gradient-to-b from-green-900/20 to-transparent pointer-events-none"></div>

    <!-- Header -->
    <header class="border-b border-gray-800/80 bg-[#0B0F19]/90 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center font-black text-lg shadow-lg shadow-green-900/40 text-white">
                    {{ $profissional->initials }}
                </div>
                <div>
                    <h1 class="font-bold text-base leading-tight">{{ $profissional->nome }}</h1>
                    <p class="text-xs text-gray-400">Área do Funcionário</p>
                </div>
            </div>

            <form method="POST" action="{{ route('funcionario.logout') }}">
                @csrf
                <button type="submit" class="w-9 h-9 rounded-xl bg-gray-800 hover:bg-red-500/20 text-gray-400 hover:text-red-400 flex items-center justify-center transition-colors" title="Sair">
                    <i class="fa-solid fa-power-off text-sm"></i>
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-7 space-y-6" x-data="{ linkCopied: false }">

        <!-- Welcome & Link Card -->
        <div class="bg-gradient-to-br from-green-800 to-emerald-900 rounded-2xl p-7 shadow-xl shadow-green-900/30 relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>

            <h2 class="text-xl font-black mb-2">Olá, {{ explode(' ', $profissional->nome)[0] }}!</h2>
            <p class="text-green-100/80 text-sm mb-5 max-w-md">Compartilhe seu link exclusivo com seus clientes para que eles agendem diretamente com você.</p>

            <div class="bg-black/20 rounded-xl p-2 flex items-center gap-2 max-w-xl border border-white/10">
                <div class="flex-1 truncate px-3 text-sm text-green-100 font-mono">
                    {{ $profissional->link_agendamento }}
                </div>
                <button
                    @click="navigator.clipboard.writeText('{{ $profissional->link_agendamento }}'); linkCopied = true; setTimeout(() => linkCopied = false, 2000)"
                    class="bg-white text-green-900 font-bold text-xs uppercase px-4 py-2.5 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-2 shrink-0">
                    <i class="fa-solid fa-copy" x-show="!linkCopied"></i>
                    <i class="fa-solid fa-check text-green-600" x-show="linkCopied" x-cloak></i>
                    <span x-text="linkCopied ? 'Copiado!' : 'Copiar Link'"></span>
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 text-green-400 font-medium flex items-center gap-3">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Agenda -->
        <div>
            <div class="flex items-end justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold">Sua Agenda</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Gerencie seus horários</p>
                </div>

                <form action="{{ route('funcionario.dashboard') }}" method="GET" class="flex bg-gray-900 p-1 rounded-xl border border-gray-800">
                    <button type="submit" name="filtro" value="hoje" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ $filtro === 'hoje' ? 'bg-green-500 text-white' : 'text-gray-400 hover:text-white' }}">Hoje</button>
                    <button type="submit" name="filtro" value="semana" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ $filtro === 'semana' ? 'bg-green-500 text-white' : 'text-gray-400 hover:text-white' }}">Semana</button>
                    <button type="submit" name="filtro" value="todos" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all {{ $filtro === 'todos' ? 'bg-green-500 text-white' : 'text-gray-400 hover:text-white' }}">Todos</button>
                </form>
            </div>

            @if($agendamentos->isEmpty())
                <div class="glass rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-600 text-2xl">
                        <i class="fa-regular fa-calendar-xmark"></i>
                    </div>
                    <h4 class="text-base font-bold text-white mb-2">Nenhum agendamento</h4>
                    <p class="text-gray-400 text-sm">Você não tem horários marcados para este período.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($agendamentos as $agendamento)
                        <div class="glass rounded-2xl p-5 flex flex-col sm:flex-row gap-5 hover:border-green-500/20 transition-colors" x-data="{}">
                            <div class="flex sm:flex-col items-center justify-between sm:justify-center sm:w-20 shrink-0 sm:border-r border-gray-800 sm:pr-4">
                                <div class="text-center">
                                    <div class="text-sm text-green-400 font-bold uppercase">{{ \Carbon\Carbon::parse($agendamento->data_inicio)->translatedFormat('D') }}</div>
                                    <div class="text-xl font-black">{{ \Carbon\Carbon::parse($agendamento->data_inicio)->format('d/m') }}</div>
                                </div>
                                <div class="bg-gray-900 rounded-lg px-3 py-1 text-sm font-bold text-gray-300 mt-1.5">
                                    {{ \Carbon\Carbon::parse($agendamento->data_inicio)->format('H:i') }}
                                </div>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <h4 class="font-bold text-base">{{ $agendamento->cliente_nome ?? 'Cliente' }}</h4>
                                        <p class="text-sm text-gray-400"><i class="fa-brands fa-whatsapp mr-1 text-green-500"></i> {{ $agendamento->cliente_telefone ?? 'N/A' }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                        {{ $agendamento->status === 'pendente' ? 'bg-amber-500/10 text-amber-400' : '' }}
                                        {{ $agendamento->status === 'confirmado' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                        {{ $agendamento->status === 'concluido' ? 'bg-green-500/10 text-green-400' : '' }}
                                        {{ $agendamento->status === 'cancelado' ? 'bg-red-500/10 text-red-400' : '' }}
                                        {{ $agendamento->status === 'faltou' ? 'bg-gray-500/10 text-gray-400' : '' }}">
                                        {{ $agendamento->status }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 mt-3 flex-wrap">
                                    <div class="bg-gray-800 rounded-lg px-3 py-1.5 flex items-center gap-2 text-sm">
                                        <i class="fa-solid fa-scissors text-gray-500 text-xs"></i>
                                        <span class="font-medium">{{ $agendamento->servico->nome ?? 'Serviço Excluído' }}</span>
                                    </div>
                                    <div class="bg-gray-800 rounded-lg px-3 py-1.5 flex items-center gap-2 text-sm text-green-400">
                                        <i class="fa-solid fa-dollar-sign text-green-500/60 text-xs"></i>
                                        <span class="font-bold">R$ {{ number_format($agendamento->preco, 2, ',', '.') }}</span>
                                    </div>
                                </div>

                                @if($agendamento->produtos_solicitados || $agendamento->descricao)
                                    <div class="mt-3 p-3 bg-gray-900/50 rounded-xl border border-gray-800 space-y-1.5">
                                        @if($agendamento->produtos_solicitados)
                                            <p class="text-sm"><span class="text-gray-400 text-xs uppercase font-bold tracking-wider mr-2">Produtos:</span> {{ $agendamento->produtos_solicitados }}</p>
                                        @endif
                                        @if($agendamento->descricao)
                                            <p class="text-sm"><span class="text-gray-400 text-xs uppercase font-bold tracking-wider mr-2">Obs:</span> {{ $agendamento->descricao }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if($agendamento->status === 'pendente' || $agendamento->status === 'confirmado')
                                    <div class="mt-4 pt-4 border-t border-gray-800/50 flex justify-end">
                                        <form action="{{ route('funcionario.agendamento.finalizar', $agendamento->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-green-500/10 hover:bg-green-500 text-green-400 hover:text-white px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 transition-all border border-green-500/20 hover:border-green-500">
                                                <i class="fa-solid fa-check-double"></i> Finalizar Atendimento
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

</body>
</html>
