<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use App\Models\Barbearia;
use App\Services\LogAtividadeService;
use Illuminate\Http\Request;

class ServicoController
{
    public function index()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $servicos = Servico::where('barbearia_id', $barbeariaId)->latest()->get();
        return view('panel.servicos', compact('servicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'duracao_minutos' => 'required|integer|min:1',
            'cor' => 'nullable|string|max:7',
            'descricao' => 'nullable|string',
            'disponivel_online' => 'nullable|boolean',
        ]);

        $barbeariaId = auth()->user()->barbearia_id;

        $servico = Servico::create([
            'barbearia_id' => $barbeariaId,
            'nome' => $request->nome,
            'preco' => $request->preco,
            'duracao_minutos' => $request->duracao_minutos,
            'cor' => $request->cor ?? '#6366f1',
            'descricao' => $request->descricao,
            'disponivel_online' => $request->has('disponivel_online'),
            'ativo' => true,
        ]);

        LogAtividadeService::log('servico_criado', "Serviço '{$servico->nome}' cadastrado.", 'Servico', $servico->id, null, $servico->toArray());

        return redirect()->route('panel.servicos')->with('success', 'Serviço cadastrado com sucesso!');
    }

    public function update(Request $request, Servico $servico)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'duracao_minutos' => 'required|integer|min:1',
            'cor' => 'nullable|string|max:7',
            'descricao' => 'nullable|string',
            'disponivel_online' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['disponivel_online'] = $request->has('disponivel_online');

        $dadosAntigos = $servico->toArray();
        $servico->update($data);

        LogAtividadeService::log('servico_atualizado', "Serviço '{$servico->nome}' atualizado.", 'Servico', $servico->id, $dadosAntigos, $servico->toArray());

        return redirect()->route('panel.servicos')->with('success', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Servico $servico)
    {
        $nome = $servico->nome;
        $id = $servico->id;
        $dados = $servico->toArray();
        $servico->delete();

        LogAtividadeService::log('servico_excluido', "Serviço '{$nome}' removido.", 'Servico', $id, $dados, null);

        return redirect()->route('panel.servicos')->with('success', 'Serviço excluído com sucesso!');
    }
}
