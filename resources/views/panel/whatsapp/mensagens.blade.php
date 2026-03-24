@extends('layouts.app')
@section('title', 'WhatsApp - Mensagens')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">WhatsApp</h1>
        <div class="flex items-center gap-3">
            <span class="flex items-center gap-2 px-3 py-1.5 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600 font-medium">
                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                Desconectado
            </span>
            <button class="bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-green-700">
                Conectar WhatsApp
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ tab: 'mensagens' }" class="space-y-4">
        <div class="flex gap-2 border-b border-gray-200">
            <button @click="tab = 'mensagens'" :class="tab === 'mensagens' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2.5 text-sm font-medium transition-colors">Mensagens Automáticas</button>
            <button @click="tab = 'configuracao'" :class="tab === 'configuracao' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2.5 text-sm font-medium transition-colors">Configuração</button>
        </div>

        <!-- Mensagens automáticas -->
        <div x-show="tab === 'mensagens'" class="space-y-4">

            @php
            $mensagens = [
                ['titulo' => 'Confirmação de Agendamento', 'desc' => 'Enviada quando o agendamento é criado', 'ativa' => true],
                ['titulo' => 'Lembrete (24h antes)', 'desc' => 'Lembrete enviado 24 horas antes do horário', 'ativa' => true],
                ['titulo' => 'Lembrete (1h antes)', 'desc' => 'Lembrete enviado 1 hora antes do horário', 'ativa' => false],
                ['titulo' => 'Cancelamento', 'desc' => 'Enviada quando o agendamento é cancelado', 'ativa' => true],
                ['titulo' => 'Avaliação pós-atendimento', 'desc' => 'Enviada após o atendimento concluído', 'ativa' => false],
            ];
            @endphp

            @foreach($mensagens as $msg)
            <div class="bg-white rounded-2xl border border-gray-200 p-5" x-data="{ ativa: {{ $msg['ativa'] ? 'true' : 'false' }}, editando: false }">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $msg['titulo'] }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $msg['desc'] }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="editando = !editando" class="text-xs text-indigo-600 font-medium hover:text-indigo-800">
                            <span x-text="editando ? 'Fechar' : 'Editar'"></span>
                        </button>
                        <button @click="ativa = !ativa" :class="ativa ? 'bg-green-500' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                            <span :class="ativa ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                        </button>
                    </div>
                </div>
                <div x-show="editando" class="mt-4 pt-4 border-t border-gray-100">
                    <textarea rows="4" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono" placeholder="Olá {nome}, seu agendamento foi confirmado para {data} às {hora} com {profissional}.&#10;&#10;Aguardamos você!"></textarea>
                    <div class="flex gap-2 mt-3">
                        <span class="text-xs text-gray-500">Variáveis: </span>
                        @foreach(['{nome}', '{data}', '{hora}', '{profissional}', '{servico}'] as $v)
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-mono">{{ $v }}</span>
                        @endforeach
                    </div>
                    <button class="mt-3 bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700">Salvar mensagem</button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Configuração -->
        <div x-show="tab === 'configuracao'" class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5 max-w-lg">
            <div>
                <h2 class="text-base font-semibold text-gray-900 mb-1">Conectar WhatsApp</h2>
                <p class="text-sm text-gray-500">Escaneie o QR Code com seu WhatsApp para conectar</p>
            </div>
            <div class="flex items-center justify-center bg-gray-50 rounded-2xl h-56 border-2 border-dashed border-gray-300">
                <div class="text-center">
                    <i class="fa-brands fa-whatsapp text-5xl text-gray-300 mb-3"></i>
                    <p class="text-sm text-gray-400">QR Code aparecerá aqui ao conectar</p>
                </div>
            </div>
            <button class="w-full bg-green-600 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-green-700">
                Gerar QR Code
            </button>
        </div>
    </div>

</div>
@endsection
