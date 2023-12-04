<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\Website;

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
        Paginator::useBootstrapFive();
        
        $totalHourlydailyWebsites = Website::where('status',0)->whereIn('get_image_hourly',['H','D'])->count();
        view()->share('totalHourlydailyWebsites', $totalHourlydailyWebsites);
    }
}
