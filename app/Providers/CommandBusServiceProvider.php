<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\CommandBus;
use App\Services\CommandBus as CommandBusImplementation;

class CommandBusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBus::class, function ($app) {
            return new CommandBusImplementation($app);
        });
    }
} 