<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(UrlGenerator $url)
    {
        if (env('APP_ENV') === 'production') {
            $url->forceScheme('https');
        }
    }
}