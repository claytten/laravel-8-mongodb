<?php

namespace App\Providers;

use App\Models\Kendaraan;
use App\Observers\KendaraanObserver;
use Illuminate\Foundation\AliasLoader;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Loader Alias
        $loader = AliasLoader::getInstance();

        // SANCTUM CUSTOM PERSONAL-ACCESS-TOKEN
        $loader->alias(\Laravel\Sanctum\PersonalAccessToken::class, \App\Models\Sanctum\PersonalAccessToken::class);

        // apply observer for default status value
        // because we use mongodb, we can't use schema default value
        Kendaraan::observe(KendaraanObserver::class);
    }
}
