<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Configuracao;
use App\Models\Eleitor;
use App\Services\Admin\ListaUsuarioTelaPermissaoService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compartilha com todas as views
        $config = Configuracao::first();
        View::share('configuracao', $config);

        View::composer('*', function ($view) {
            $view->with('permissoesService', app(ListaUsuarioTelaPermissaoService::class));
            if (session()->has('eleitor_id')) {
                $eleitor = Eleitor::find(session('eleitor_id'));
                $view->with('eleitorLogado', $eleitor);
            }
        });
    }
}
