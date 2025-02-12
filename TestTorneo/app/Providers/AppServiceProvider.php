<?php

namespace App\Providers;

use App\Interfaces\Repositories\IJugadorFemeninoRepository;
use App\Interfaces\Repositories\IJugadorMasculinoRepository;
use App\Interfaces\Repositories\IPartidaRepository;
use App\Interfaces\Repositories\ITorneoRepository;
use App\Repositories\JugadorFemeninoRepository;
use App\Repositories\JugadorMasculinoRepository;
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
        $this->app->bind(IPartidaRepository::class, PartidaRepository::class);
        $this->app->bind(IJugadorMasculinoRepository::class, JugadorMasculinoRepository::class);
        $this->app->bind(IJugadorFemeninoRepository::class, JugadorFemeninoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
