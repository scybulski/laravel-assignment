<?php

namespace App\Providers;

use App\Facades\AuthFacade;
use App\Facades\Contracts\AuthContract;
use App\Factories\AuthAdapterFactory;
use App\Factories\Contracts\AuthAdapterFactoryContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(abstract: AuthContract::class, concrete: AuthFacade::class);
        $this->app->singleton(abstract: AuthAdapterFactoryContract::class, concrete: AuthAdapterFactory::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
