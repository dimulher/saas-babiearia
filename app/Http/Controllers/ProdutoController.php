<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Barbearia;
use App\Services\LogAtividadeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutoController
{
    public function index()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $produtos = Produto::where('barbearia_id', $barbeariaId)->latest()->get();
        return view('panel.produtos', compact('produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco_venda' => 'required|numeric|min:0',
            'preco_custo' => 'nullable|numeric|min:0',
            'estoque_atual' => 'required|integer',
            'estoque_minimo' => 'required|integer',
            'unidade' => 'nullable|string|max:10',
            'codigo' => 'nullable|string|max:50',
            'descricao' => 'nullable|string',
            'imagem' => 'nullable|image|max:5120',
        ]);

        $barbeariaId = auth()->user()->barbearia_id;

        $produto = Produto::create([
            'barbearia_id' => $barbeariaId,
            'nome' => $request->nome,
            'preco_venda' => $request->preco_venda,
            'preco_custo' => $request->preco_custo ?? 0,
            'estoque_atual' => $request->estoque_atual,
            'estoque_minimo' => $request->estoque_minimo,
            'unidade' => $request->unidade ?? 'un',
            'codigo' => $request->codigo,
            'descricao' => $request->descricao,
            'imagem' => $request->hasFile('imagem') ? $request->file('imagem')->store('produtos', 'public') : null,
            'ativo' => true,
        ]);

        LogAtividadeService::log('produto_criado', "Produto '{$produto->nome}' cadastrado.", 'Produto', $produto->id, null, $produto->toArray());

        return redirect()->route('panel.produtos')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco_venda' => 'required|numeric|min:0',
            'preco_custo' => 'nullable|numeric|min:0',
            'estoque_atual' => 'required|integer',
            'estoque_minimo' => 'required|integer',
            'unidade' => 'nullable|string|max:10',
            'codigo' => 'nullable|string|max:50',
            'descricao' => 'nullable|string',
            'imagem' => 'nullable|image|max:5120',
        ]);

        $dadosAntigos = $produto->toArray();

        $dados = $request->except('imagem');

        if ($request->hasFile('imagem')) {
            if ($produto->imagem && !str_starts_with($produto->imagem, 'http')) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $dados['imagem'] = $request->file('imagem')->store('produtos', 'public');
        }

        $produto->update($dados);

        LogAtividadeService::log('produto_atualizado', "Produto '{$produto->nome}' atualizado.", 'Produto', $produto->id, $dadosAntigos, $produto->toArray());

        return redirect()->route('panel.produtos')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $nome = $produto->nome;
        $id = $produto->id;
        $dados = $produto->toArray();
        $produto->delete();

        LogAtividadeService::log('produto_excluido', "Produto '{$nome}' removido.", 'Produto', $id, $dados, null);

        return redirect()->route('panel.produtos')->with('success', 'Produto excluído com sucesso!');
    }
}
