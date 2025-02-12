<?php

namespace App\Providers;

use App\Interfaces\Repositories\IJugadorRepository;
use App\Interfaces\Repositories\IPartidaRepository;
use App\Interfaces\Repositories\ITorneoRepository;
use App\Repositories\JugadorRepository;
use App\Repositories\PartidaRepository;
use App\Repositories\TorneoRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ITorneoRepository::class, TorneoRepository::class);
        $this->app->bind(IJugadorRepository::class, JugadorRepository::class);
        $this->app->bind(IPartidaRepository::class, PartidaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
