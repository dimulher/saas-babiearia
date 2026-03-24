@extends('layouts.guest')
@section('title', 'Entrar')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

    <!-- Logo -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-black tracking-tight text-gray-900">
            BARBER<span class="text-indigo-600">SAAS</span>
        </h1>
        <p class="mt-2 text-sm text-gray-500">O melhor sistema para barbearias</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-400 @enderror"
                placeholder="seu@email.com">
            @error('email')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
            <input type="password" id="password" name="password" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="••••••••">
            @error('password')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
                <span class="text-sm text-gray-600">Lembrar-me</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                Esqueceu sua senha?
            </a>
        </div>

        <button type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
            Entrar
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Não tem cadastro?
        <a href="{{ route('register') }}" class="text-indigo-600 font-medium hover:text-indigo-800">Cadastrar grátis</a>
    </p>
</div>
@endsection
