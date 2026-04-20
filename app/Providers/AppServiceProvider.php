<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(
        \App\Contracts\BidServiceInterface::class,
        \App\Services\BidService::class
    );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // من اجل معرفة ما هي الكويري التي يتم تنفيذها 
          DB::listen(function ($query) {
        Log::channel('queries')->info('SQL QUERY', [
            'sql'      => $query->sql,
            'bindings' => $query->bindings,
            'time_ms'  => $query->time,
        ]);
    });
    }
}
