<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Barbearia;
use App\Models\User;

class ConfiguracaoController extends Controller
{
    public function barbearia()
    {
        $barbearia = Auth::user()->barbearia;
        return view('panel.configuracoes.barbearia', compact('barbearia'));
    }

    public function salvarBarbearia(Request $request)
    {
        $barbearia = Auth::user()->barbearia;

        $tiposValidos = array_keys(config('estabelecimento.tipos', []));

        $request->validate([
            'nome'     => 'required|string|max:100',
            'tipo'     => 'required|in:' . implode(',', $tiposValidos),
            'telefone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:200',
            'cidade'   => 'nullable|string|max:80',
            'estado'   => 'nullable|string|max:2',
            'cep'      => 'nullable|string|max:10',
            'instagram'=> 'nullable|string|max:100',
            'descricao'=> 'nullable|string|max:500',
        ]);

        $barbearia->update($request->only(
            'nome','tipo','telefone','whatsapp','endereco','cidade','estado','cep','instagram','descricao'
        ));

        return back()->with('success', 'Dados do estabelecimento atualizados!');
    }

    public function conta()
    {
        $user = Auth::user();
        return view('panel.configuracoes.conta', compact('user'));
    }

    public function salvarConta(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nome'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,'.$user->id,
            'telefone' => 'nullable|string|max:20',
        ]);

        $user->update($request->only('nome','email','telefone'));

        if ($request->filled('nova_senha')) {
            $request->validate([
                'senha_atual'           => 'required',
                'nova_senha'            => 'min:6|confirmed',
            ]);

            if (!Hash::check($request->senha_atual, $user->password)) {
                return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
            }

            $user->update(['password' => Hash::make($request->nova_senha)]);
        }

        return back()->with('success', 'Conta atualizada com sucesso!');
    }
}
