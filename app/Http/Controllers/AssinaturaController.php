<?php

namespace App\Http\Controllers;

use App\Models\Assinatura;
use App\Models\Plano;
use App\Models\Cliente;
use Illuminate\Http\Request;

class AssinaturaController
{
    public function index()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $planos = Plano::where('barbearia_id', $barbeariaId)->withCount(['assinaturas' => function($q) {
            $q->where('status', 'ativo');
        }])->get();

        $assinaturas = Assinatura::where('barbearia_id', $barbeariaId)->with(['cliente', 'plano'])->latest()->get();
        $clientes = Cliente::where('barbearia_id', $barbeariaId)->orderBy('nome')->get();

        // Calcular MRR (Monthly Recurring Revenue)
        $mrr = 0;
        foreach ($assinaturas as $ass) {
            if ($ass->status === 'ativo' && $ass->plano) {
                $mrr += $ass->plano->valor_mensal;
            }
        }

        $assinantesAtivos = $assinaturas->where('status', 'ativo')->count();
        $ticketMedio = $assinantesAtivos > 0 ? $mrr / $assinantesAtivos : 0;

        return view('panel.assinaturas.index', compact(
            'planos', 'assinaturas', 'clientes', 'mrr', 'assinantesAtivos', 'ticketMedio'
        ));
    }

    public function storePlano(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'valor_mensal' => 'required|numeric|min:0',
            'recursos' => 'nullable|string',
        ]);

        $recursosArray = [];
        if (!empty($data['recursos'])) {
            $recursosArray = array_values(array_filter(array_map('trim', explode("\n", $data['recursos']))));
        }

        Plano::create([
            'barbearia_id' => $barbeariaId,
            'nome' => $data['nome'],
            'valor_mensal' => $data['valor_mensal'],
            'recursos' => $recursosArray,
            'ativo' => true,
        ]);

        return back()->with('success', 'Plano criado com sucesso!');
    }

    public function togglePlano(Plano $plano)
    {
        if ($plano->barbearia_id !== auth()->user()->barbearia_id) abort(403);
        $plano->update(['ativo' => !$plano->ativo]);
        return back()->with('success', 'Status do plano atualizado.');
    }

    public function destroyPlano(Plano $plano)
    {
        if ($plano->barbearia_id !== auth()->user()->barbearia_id) abort(403);
        if ($plano->assinaturas()->count() > 0) {
            return back()->withErrors(['Não é possível excluir um plano que possui assinantes.']);
        }
        $plano->delete();
        return back()->with('success', 'Plano excluído.');
    }

    public function storeAssinatura(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $data = $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'novo_cliente_nome' => 'nullable|string|max:255',
            'novo_cliente_telefone' => 'nullable|string|max:255',
            'plano_id' => 'required|exists:planos,id',
            'dia_vencimento' => 'required|integer|min:1|max:31',
        ]);

        if (empty($data['cliente_id']) && empty($data['novo_cliente_nome'])) {
            return back()->withErrors(['Você deve selecionar um cliente existente ou cadastrar um novo.']);
        }

        $clienteId = $data['cliente_id'];

        if ($clienteId) {
            $cliente = Cliente::where('barbearia_id', $barbeariaId)->findOrFail($clienteId);
        }

        // Cria o cliente se for um novo
        if (!empty($data['novo_cliente_nome'])) {
            $cliente = Cliente::create([
                'barbearia_id' => $barbeariaId,
                'nome' => $data['novo_cliente_nome'],
                'telefone' => $data['novo_cliente_telefone'] ?? '',
                'tipo' => 'vip',
            ]);
            $clienteId = $cliente->id;
        }

        Assinatura::create([
            'barbearia_id' => $barbeariaId,
            'cliente_id' => $clienteId,
            'plano_id' => $data['plano_id'],
            'dia_vencimento' => $data['dia_vencimento'],
            'status' => 'ativo',
            'data_inicio' => today(),
        ]);

        return back()->with('success', 'Assinatura criada com sucesso!');
    }

    public function toggleAssinatura(Assinatura $assinatura)
    {
        $assinatura->update([
            'status' => $assinatura->status === 'ativo' ? 'pausado' : 'ativo'
        ]);
        return back()->with('success', 'Status da assinatura atualizado.');
    }

    public function destroyAssinatura(Assinatura $assinatura)
    {
        $assinatura->delete();
        return back()->with('success', 'Assinatura cancelada/removida.');
    }
}
