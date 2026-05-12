<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use App\Models\Barbearia;
use App\Services\LogAtividadeService;
use Illuminate\Http\Request;

class ProfissionalController
{
    public function index()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $profissionais = Profissional::where('barbearia_id', $barbeariaId)->latest()->get();
        return view('panel.profissionais', compact('profissionais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
        ]);

        $barbeariaId = auth()->user()->barbearia_id;

        do {
            $codigo = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (Profissional::where('codigo_acesso', $codigo)->exists());

        $profissional = Profissional::create([
            'barbearia_id' => $barbeariaId,
            'nome' => $request->nome,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'comissao_percentual' => 0,
            'aceita_agendamento_online' => true,
            'codigo_acesso' => $codigo,
            'ativo' => true,
        ]);

        LogAtividadeService::log('profissional_criado', "Profissional '{$profissional->nome}' cadastrado.", 'Profissional', $profissional->id, null, $profissional->toArray());

        return redirect()->route('panel.profissionais')->with('success', "Integrante cadastrado com sucesso! O código de acesso é: {$codigo}");
    }

    public function update(Request $request, Profissional $profissional)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
        ]);

        $dadosAntigos = $profissional->toArray();
        $profissional->update([
            'nome' => $request->nome,
            'email' => $request->email,
            'telefone' => $request->telefone,
        ]);

        LogAtividadeService::log('profissional_atualizado', "Profissional '{$profissional->nome}' atualizado.", 'Profissional', $profissional->id, $dadosAntigos, $profissional->toArray());

        return redirect()->route('panel.profissionais')->with('success', 'Profissional atualizado com sucesso!');
    }

    public function destroy(Profissional $profissional)
    {
        $nome = $profissional->nome;
        $id = $profissional->id;
        $dados = $profissional->toArray();
        $profissional->delete();

        LogAtividadeService::log('profissional_excluido', "Profissional '{$nome}' removido.", 'Profissional', $id, $dados, null);

        return redirect()->route('panel.profissionais')->with('success', 'Profissional excluído com sucesso!');
    }

    public function gerarCodigo(Profissional $profissional)
    {
        do {
            $codigo = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (Profissional::where('codigo_acesso', $codigo)->exists());

        $profissional->update(['codigo_acesso' => $codigo]);

        LogAtividadeService::log('codigo_gerado', "Código de acesso gerado para '{$profissional->nome}'.", 'Profissional', $profissional->id, null, ['codigo' => $codigo]);

        return redirect()->route('panel.profissionais')->with('success', "Código de acesso gerado para {$profissional->nome}: {$codigo}");
    }
}

