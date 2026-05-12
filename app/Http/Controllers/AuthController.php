<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Barbearia;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redireciona por role
            $role = Auth::user()->role;
            if ($role === 'barbeiro') {
                return redirect()->route('funcionario.dashboard');
            }
            return redirect()->intended(route('panel.dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'E-mail ou senha incorretos.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $tiposValidos = array_keys(config('estabelecimento.tipos', []));

        $request->validate([
            'tipo'           => 'required|in:' . implode(',', $tiposValidos),
            'barbearia_nome' => 'required|string|max:100',
            'nome'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:6|confirmed',
        ], [
            'tipo.required'           => 'Selecione o tipo do seu negócio.',
            'barbearia_nome.required' => 'O nome do estabelecimento é obrigatório.',
            'nome.required'           => 'Seu nome é obrigatório.',
            'email.required'          => 'O e-mail é obrigatório.',
            'email.unique'            => 'Este e-mail já está cadastrado.',
            'password.required'       => 'A senha é obrigatória.',
            'password.min'            => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed'      => 'As senhas não coincidem.',
        ]);

        // Cria o estabelecimento
        $slug = Str::slug($request->barbearia_nome) . '-' . Str::random(5);
        $barbearia = Barbearia::create([
            'nome'  => $request->barbearia_nome,
            'tipo'  => $request->tipo,
            'slug'  => strtolower($slug),
            'email' => $request->email,
            'ativo' => true,
            'plano' => 'gratuito',
        ]);

        // Cria o usuário admin
        $user = User::create([
            'barbearia_id' => $barbearia->id,
            'nome'         => $request->nome,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => 'admin',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('panel.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
