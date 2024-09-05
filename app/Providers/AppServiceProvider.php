<?php

namespace App\Providers;

use App\Builders\Contracts\RetriableOperationServiceBuilderContract;
use App\Builders\RetriableOperationServiceBuilder;
use App\Facades\AuthFacade;
use App\Facades\Contracts\AuthContract;
use App\Facades\Contracts\MovieContract;
use App\Facades\MovieFacade;
use App\Factories\AuthAdapterFactory;
use App\Factories\Contracts\AuthAdapterFactoryContract;
use App\Factories\Contracts\MovieAdapterFactoryContract;
use App\Factories\MovieAdapterFactory;
use App\Iterators\Contracts\MovieAdaptersIteratorContract;
use App\Iterators\MovieAdaptersIterator;
use App\Services\Contracts\RetriableOperationContract;
use App\Services\RetriableOperationService;
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
        $this->app->singleton(abstract: MovieContract::class, concrete: MovieFacade::class);
        $this->app->singleton(abstract: MovieAdaptersIteratorContract::class, concrete: MovieAdaptersIterator::class);
        $this->app->singleton(abstract: MovieAdapterFactoryContract::class, concrete: MovieAdapterFactory::class);
        $this->app->bind(abstract: RetriableOperationContract::class, concrete: RetriableOperationService::class);
        $this->app->bind(abstract: RetriableOperationServiceBuilderContract::class, concrete: RetriableOperationServiceBuilder::class);
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
