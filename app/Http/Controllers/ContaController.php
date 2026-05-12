<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Barbearia;
use App\Services\LogAtividadeService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ContaController
{
    public function index(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $query = Conta::where('barbearia_id', $barbeariaId);

        if ($request->has('tipo') && $request->tipo != 'todas') {
            if ($request->tipo == 'parceladas') {
                $query->where('total_parcelas', '>', 1);
            } elseif ($request->tipo == 'recorrentes') {
                $query->where('recorrencia', '!=', 'nenhuma');
            }
        }

        $contas = $query->latest('vencimento')->get();
        
        return view('panel.contas.index', compact('contas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'tipo' => 'required|in:receita,despesa',
            'vencimento' => 'required|date',
            'categoria' => 'nullable|string',
            'recorrencia' => 'nullable|in:nenhuma,semanal,mensal,anual',
            'total_parcelas' => 'nullable|integer|min:1',
        ]);

        $barbeariaId = auth()->user()->barbearia_id;

        $conta = Conta::create([
            'barbearia_id' => $barbeariaId,
            'descricao' => $request->descricao,
            'valor' => $request->valor,
            'tipo' => $request->tipo,
            'vencimento' => $request->vencimento,
            'categoria' => $request->categoria,
            'recorrencia' => $request->recorrencia ?? 'nenhuma',
            'total_parcelas' => $request->total_parcelas ?? 1,
            'status' => 'pendente',
        ]);

        LogAtividadeService::log('conta_criada', "Nova conta cadastrada: {$conta->descricao} (R$ {$conta->valor})", 'Conta', $conta->id);

        return redirect()->route('panel.contas.todas')->with('success', 'Conta cadastrada com sucesso!');
    }

    public function pagar(Conta $conta)
    {
        $conta->update([
            'status' => 'pago',
            'pago_em' => now(),
        ]);

        LogAtividadeService::log('conta_paga', "Conta marcada como paga: {$conta->descricao}", 'Conta', $conta->id);

        return back()->with('success', 'Conta marcada como paga!');
    }

    public function destroy(Conta $conta)
    {
        $descricao = $conta->descricao;
        $conta->delete();

        LogAtividadeService::log('conta_excluida', "Conta excluída: {$descricao}", 'Conta');

        return redirect()->route('panel.contas.todas')->with('success', 'Conta excluída com sucesso!');
    }
}
