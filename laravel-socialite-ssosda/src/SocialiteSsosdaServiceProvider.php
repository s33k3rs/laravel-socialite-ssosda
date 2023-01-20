<?php

namespace KominfoSidoarjo\SocialiteSsosda;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;

class SocialiteSsosdaServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('cognito', function () use ($socialite) {
            $config = config('services.cognito');

            return $socialite->buildProvider(SocialiteSsosdaProvider::class, $config);
        });
    }
}
