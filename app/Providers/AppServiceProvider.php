<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Garante que $errors sempre existe nas views, mesmo quando a sessão
        // falha em ambiente serverless e ShareErrorsFromSession não injeta a variável
        View::composer('*', function ($view) {
            if (!array_key_exists('errors', $view->getData())) {
                $view->with('errors', new ViewErrorBag);
            }
        });
    }
}
