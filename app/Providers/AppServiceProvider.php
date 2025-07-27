<?php

namespace App\Providers;

use App\Http\Controllers\Home\Repositories\DeputadoRepository;
use App\Http\Controllers\Home\Repositories\DeputadoRepositoryInterface;
use Illuminate\Support\ServiceProvider;

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
        app()->bind(
            DeputadoRepositoryInterface::class,
            DeputadoRepository::class
        );
    }
}
