<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerOrigin();
        $this->registerTimeZone();
        $this->registerAi();
    }

    public function registerOrigin(): void
    {
        if (! $this->app->environment('local')) {
            URL::forceRootUrl(
                config('app.url')
            );

            URL::useOrigin(
                config('app.url')
            );

            URL::useAssetOrigin(
                config('app.url')
            );
        }
    }

    public function registerTimeZone(): void
    {
        Config::set('app.timezone', 'Asia/Shanghai');
    }

    public function registerAi(): void
    {
        Config::set('ai.providers.siliconflow', [
            'driver' => 'groq',
            'url' => config('services.siliconflow.base_url'),
            'key' => config('services.siliconflow.api_key'),
        ]);

        Config::set('ai.providers.siliconflow_openai', [
            'driver' => 'openai',
            'url' => config('services.siliconflow.base_url'),
            'key' => config('services.siliconflow.api_key'),
        ]);
    }

    public function boot(): void
    {
        Authenticate::redirectUsing(function () {
            return route('auth.render');
        });
    }
}
