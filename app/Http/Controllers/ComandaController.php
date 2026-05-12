<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comanda;
use App\Models\ComandaItem;
use App\Models\Profissional;
use App\Models\Servico;
use App\Models\Produto;

class ComandaController
{
    public function index()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $comandasAbertas = Comanda::where('barbearia_id', $barbeariaId)->with('profissional')->where('status', 'aberta')->latest()->get();
        $comandasFechadas = Comanda::where('barbearia_id', $barbeariaId)->with('profissional')->where('status', 'fechada')->latest()->get();
        $profissionais = Profissional::where('barbearia_id', $barbeariaId)->where('ativo', true)->get();

        return view('panel.comandas', compact('comandasAbertas', 'comandasFechadas', 'profissionais'));
    }

    public function store(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $request->validate([
            'cliente_nome' => 'required|string|max:255',
            'profissional_id' => 'required|exists:profissionais,id',
            'observacoes' => 'nullable|string'
        ]);

        $profissional = Profissional::where('barbearia_id', $barbeariaId)->findOrFail($request->profissional_id);

        Comanda::create([
            'barbearia_id' => $barbeariaId,
            'profissional_id' => $profissional->id,
            'cliente_nome' => $request->cliente_nome,
            'status' => 'aberta',
            'observacoes' => $request->observacoes,
            'subtotal' => 0,
            'desconto' => 0,
            'total' => 0,
        ]);

        return redirect()->route('panel.comandas')->with('success', 'Comanda criada com sucesso!');
    }

    public function show(Comanda $comanda)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        if ($comanda->barbearia_id !== $barbeariaId) abort(403);

        $comanda->load(['itens.servico', 'itens.produto', 'profissional']);
        $servicos = Servico::where('barbearia_id', $barbeariaId)->where('ativo', true)->get();
        $produtos = Produto::where('barbearia_id', $barbeariaId)->where('ativo', true)->get();

        return view('panel.comanda-detalhes', compact('comanda', 'servicos', 'produtos'));
    }

    public function addItem(Request $request, Comanda $comanda)
    {
        $request->validate([
            'tipo' => 'required|in:servico,produto',
            'item_id' => 'required|integer',
            'quantidade' => 'required|integer|min:1'
        ]);

        $descricao = '';
        $precoUnitario = 0;

        if ($request->tipo === 'servico') {
            $servico = Servico::findOrFail($request->item_id);
            $descricao = $servico->nome;
            $precoUnitario = $servico->preco;
        } else {
            $produto = Produto::findOrFail($request->item_id);
            $descricao = $produto->nome;
            $precoUnitario = $produto->preco_venda;
        }

        $subtotal = $precoUnitario * $request->quantidade;

        ComandaItem::create([
            'comanda_id' => $comanda->id,
            'servico_id' => $request->tipo === 'servico' ? $request->item_id : null,
            'produto_id' => $request->tipo === 'produto' ? $request->item_id : null,
            'descricao' => $descricao,
            'quantidade' => $request->quantidade,
            'preco_unitario' => $precoUnitario,
            'subtotal' => $subtotal
        ]);

        $comanda->calcularTotal();

        return redirect()->back()->with('success', 'Item adicionado!');
    }

    public function removeItem(Comanda $comanda, ComandaItem $item)
    {
        if ($item->comanda_id !== $comanda->id) abort(403);
        
        $item->delete();
        $comanda->calcularTotal();

        return redirect()->back()->with('success', 'Item removido!');
    }

    public function close(Request $request, Comanda $comanda)
    {
        $request->validate([
            'forma_pagamento' => 'required|in:dinheiro,cartao_credito,cartao_debito,pix,outro'
        ]);

        $comanda->update([
            'status' => 'fechada',
            'forma_pagamento' => $request->forma_pagamento,
            'fechada_em' => now()
        ]);

        return redirect()->route('panel.comandas')->with('success', 'Comanda fechada com sucesso!');
    }
}
