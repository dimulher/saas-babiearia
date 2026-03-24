<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar — Barbearia do Gabriel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Figtree', sans-serif; }</style>
</head>
<body class="bg-gray-950 min-h-screen text-white">

    <!-- Header da barbearia -->
    <div class="bg-gray-900 border-b border-gray-800">
        <div class="max-w-lg mx-auto px-4 py-5 flex items-center gap-4">
            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-2xl font-black">B</div>
            <div>
                <h1 class="text-lg font-bold text-white">Barbearia do Gabriel</h1>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                    <p class="text-xs text-gray-400">Aberto agora · <span class="text-green-400">Aceitando agendamentos</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo principal -->
    <div class="max-w-lg mx-auto px-4 py-6" x-data="booking()">

        <!-- Etapas -->
        <div class="flex items-center gap-2 mb-6">
            @foreach(['Serviço', 'Profissional', 'Data e Hora', 'Confirmação'] as $i => $step)
            <div class="flex items-center gap-2 flex-1">
                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                    :class="{{ $i + 1 }} <= etapa ? 'bg-indigo-600 text-white' : 'bg-gray-700 text-gray-400'">
                    {{ $i + 1 }}
                </div>
                <span class="text-xs hidden sm:block" :class="{{ $i + 1 }} <= etapa ? 'text-white' : 'text-gray-500'">{{ $step }}</span>
                @if($i < 3)
                <div class="h-px flex-1 bg-gray-700 ml-2"></div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Etapa 1: Selecionar serviço -->
        <div x-show="etapa === 1" class="space-y-3">
            <h2 class="text-xl font-bold text-white mb-4">Escolha o serviço</h2>

            @php
            $servicos = [
                ['nome' => 'Corte de Cabelo', 'tempo' => '30 min', 'preco' => 'R$ 35,00'],
                ['nome' => 'Barba', 'tempo' => '20 min', 'preco' => 'R$ 25,00'],
                ['nome' => 'Corte + Barba', 'tempo' => '50 min', 'preco' => 'R$ 55,00'],
                ['nome' => 'Sobrancelha', 'tempo' => '10 min', 'preco' => 'R$ 15,00'],
                ['nome' => 'Hidratação', 'tempo' => '40 min', 'preco' => 'R$ 45,00'],
                ['nome' => 'Pigmentação', 'tempo' => '60 min', 'preco' => 'R$ 80,00'],
            ];
            @endphp

            @foreach($servicos as $s)
            <button @click="servico = '{{ $s['nome'] }}'; etapa = 2"
                class="w-full flex items-center justify-between p-4 bg-gray-800 hover:bg-gray-700 border border-gray-700 hover:border-indigo-500 rounded-2xl transition-all group">
                <div class="text-left">
                    <p class="font-semibold text-white group-hover:text-indigo-300">{{ $s['nome'] }}</p>
                    <p class="text-sm text-gray-400 mt-0.5"><i class="fa-regular fa-clock text-xs mr-1"></i>{{ $s['tempo'] }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-white">{{ $s['preco'] }}</p>
                    <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-indigo-400 mt-1"></i>
                </div>
            </button>
            @endforeach
        </div>

        <!-- Etapa 2: Selecionar profissional -->
        <div x-show="etapa === 2" class="space-y-3">
            <div class="flex items-center gap-3 mb-4">
                <button @click="etapa = 1" class="p-2 hover:bg-gray-800 rounded-xl">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </button>
                <h2 class="text-xl font-bold text-white">Escolha o profissional</h2>
            </div>

            <button @click="profissional = 'Qualquer'; etapa = 3"
                class="w-full flex items-center gap-4 p-4 bg-gray-800 hover:bg-gray-700 border border-gray-700 hover:border-indigo-500 rounded-2xl transition-all group">
                <div class="w-12 h-12 bg-gray-600 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-shuffle text-gray-300"></i>
                </div>
                <div class="text-left">
                    <p class="font-semibold text-white">Qualquer profissional</p>
                    <p class="text-sm text-gray-400">Primeiro disponível</p>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-indigo-400 ml-auto"></i>
            </button>

            @foreach(['Gabriel', 'Lucas', 'Rafael'] as $p)
            <button @click="profissional = '{{ $p }}'; etapa = 3"
                class="w-full flex items-center gap-4 p-4 bg-gray-800 hover:bg-gray-700 border border-gray-700 hover:border-indigo-500 rounded-2xl transition-all group">
                <div class="w-12 h-12 bg-indigo-700 rounded-xl flex items-center justify-center font-bold text-white text-lg">
                    {{ substr($p, 0, 1) }}
                </div>
                <div class="text-left">
                    <p class="font-semibold text-white">{{ $p }}</p>
                    <p class="text-sm text-gray-400">Profissional</p>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-gray-500 group-hover:text-indigo-400 ml-auto"></i>
            </button>
            @endforeach
        </div>

        <!-- Etapa 3: Selecionar data e hora -->
        <div x-show="etapa === 3" class="space-y-4">
            <div class="flex items-center gap-3 mb-4">
                <button @click="etapa = 2" class="p-2 hover:bg-gray-800 rounded-xl">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </button>
                <h2 class="text-xl font-bold text-white">Data e horário</h2>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Selecione a data</label>
                <input type="date" x-model="data" min="{{ now()->format('Y-m-d') }}"
                    class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-3">Horários disponíveis</label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach(['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30'] as $h)
                    <button @click="hora = '{{ $h }}'"
                        :class="hora === '{{ $h }}' ? 'bg-indigo-600 border-indigo-500 text-white' : 'bg-gray-800 border-gray-700 text-gray-300 hover:border-indigo-500'"
                        class="border rounded-xl py-2 text-sm font-medium transition-colors">
                        {{ $h }}
                    </button>
                    @endforeach
                </div>
            </div>

            <button @click="if(hora) etapa = 4" :class="hora ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-700 cursor-not-allowed'"
                class="w-full py-3.5 rounded-2xl text-sm font-bold text-white transition-colors mt-2">
                Continuar
            </button>
        </div>

        <!-- Etapa 4: Confirmação -->
        <div x-show="etapa === 4" class="space-y-4">
            <div class="flex items-center gap-3 mb-4">
                <button @click="etapa = 3" class="p-2 hover:bg-gray-800 rounded-xl">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </button>
                <h2 class="text-xl font-bold text-white">Confirme o agendamento</h2>
            </div>

            <!-- Resumo -->
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Serviço</span>
                    <span class="text-sm font-semibold text-white" x-text="servico"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Profissional</span>
                    <span class="text-sm font-semibold text-white" x-text="profissional"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Data</span>
                    <span class="text-sm font-semibold text-white" x-text="data"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Horário</span>
                    <span class="text-sm font-semibold text-white" x-text="hora"></span>
                </div>
            </div>

            <!-- Dados do cliente -->
            <div class="space-y-3">
                <div>
                    <label class="block text-sm text-gray-400 mb-1.5">Seu nome</label>
                    <input type="text" x-model="nomeCliente"
                        class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Digite seu nome">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1.5">WhatsApp</label>
                    <input type="tel" x-model="telefone"
                        class="w-full bg-gray-800 border border-gray-700 rounded-2xl px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="(00) 00000-0000">
                </div>
            </div>

            <button @click="confirmar()"
                class="w-full bg-indigo-600 hover:bg-indigo-700 py-4 rounded-2xl text-sm font-bold text-white transition-colors">
                <i class="fa-solid fa-check mr-2"></i>Confirmar Agendamento
            </button>
        </div>

        <!-- Etapa 5: Sucesso -->
        <div x-show="etapa === 5" class="text-center py-12 space-y-4">
            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto">
                <i class="fa-solid fa-check text-green-400 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-white">Agendamento confirmado!</h2>
            <p class="text-gray-400 text-sm">Você receberá uma confirmação pelo WhatsApp em breve.</p>
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5 text-left space-y-2 mt-4">
                <div class="flex justify-between"><span class="text-gray-400 text-sm">Serviço</span><span class="text-white text-sm font-medium" x-text="servico"></span></div>
                <div class="flex justify-between"><span class="text-gray-400 text-sm">Profissional</span><span class="text-white text-sm font-medium" x-text="profissional"></span></div>
                <div class="flex justify-between"><span class="text-gray-400 text-sm">Data</span><span class="text-white text-sm font-medium" x-text="data"></span></div>
                <div class="flex justify-between"><span class="text-gray-400 text-sm">Horário</span><span class="text-white text-sm font-medium" x-text="hora"></span></div>
            </div>
            <button @click="etapa = 1; servico = ''; profissional = ''; hora = ''; nomeCliente = ''; telefone = ''"
                class="mt-4 text-sm text-indigo-400 hover:text-indigo-300 font-medium">
                Fazer outro agendamento
            </button>
        </div>

    </div>

    <script>
        function booking() {
            return {
                etapa: 1,
                servico: '',
                profissional: '',
                data: '{{ now()->format("Y-m-d") }}',
                hora: '',
                nomeCliente: '',
                telefone: '',
                confirmar() {
                    if (!this.nomeCliente || !this.telefone) {
                        alert('Preencha seu nome e WhatsApp para confirmar.');
                        return;
                    }
                    this.etapa = 5;
                }
            }
        }
    </script>

</body>
</html>
