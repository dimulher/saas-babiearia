<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use App\Models\Barbearia;
use App\Services\LogAtividadeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'imagem' => 'nullable|image|max:5120',
        ]);

        $barbeariaId = auth()->user()->barbearia_id;

        $servico = Servico::create([
            'barbearia_id' => $barbeariaId,
            'nome' => $request->nome,
            'preco' => $request->preco,
            'duracao_minutos' => $request->duracao_minutos,
            'cor' => $request->cor ?? '#16a34a',
            'descricao' => $request->descricao,
            'imagem' => $request->hasFile('imagem') ? $request->file('imagem')->store('servicos', 'public') : null,
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
            'imagem' => 'nullable|image|max:5120',
        ]);

        $data = $request->except('imagem');
        $data['disponivel_online'] = $request->has('disponivel_online');

        if ($request->hasFile('imagem')) {
            if ($servico->imagem && !str_starts_with($servico->imagem, 'http')) {
                Storage::disk('public')->delete($servico->imagem);
            }
            $data['imagem'] = $request->file('imagem')->store('servicos', 'public');
        }

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
