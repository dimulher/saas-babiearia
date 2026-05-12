<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $barbeariaId = Auth::user()->barbearia_id;

        $query = Cliente::where('barbearia_id', $barbeariaId);

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'ilike', "%{$busca}%")
                  ->orWhere('telefone', 'ilike', "%{$busca}%")
                  ->orWhere('email', 'ilike', "%{$busca}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $clientes = $query->orderBy('nome')->paginate(25);

        return view('panel.clientes', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'     => 'required|string|max:100',
            'telefone' => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:100',
            'tipo'     => 'in:normal,vip,assinante',
        ]);

        Cliente::create([
            'barbearia_id'   => Auth::user()->barbearia_id,
            'nome'           => $request->nome,
            'telefone'       => $request->telefone,
            'email'          => $request->email,
            'whatsapp'       => $request->whatsapp,
            'data_nascimento'=> $request->data_nascimento,
            'tipo'           => $request->tipo ?? 'normal',
            'observacoes'    => $request->observacoes,
            'ativo'          => true,
        ]);

        return back()->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::where('barbearia_id', Auth::user()->barbearia_id)->findOrFail($id);

        $request->validate([
            'nome'  => 'required|string|max:100',
            'tipo'  => 'in:normal,vip,assinante',
        ]);

        $cliente->update($request->only('nome','telefone','email','whatsapp','data_nascimento','tipo','observacoes','ativo'));

        return back()->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $cliente = Cliente::where('barbearia_id', Auth::user()->barbearia_id)->findOrFail($id);
        $cliente->delete();

        return back()->with('success', 'Cliente removido.');
    }
}
