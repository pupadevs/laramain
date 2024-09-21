<?php

namespace VendorName\Laramain\Providers;

use Illuminate\Support\ServiceProvider;
use VendorName\Laramain\Console\InstallCommand;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/cqrs.php', 'cqrs');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([InstallCommand::class]);

            $this->publishes([
                __DIR__.'/../../config/cqrs.php' => config_path('cqrs.php'),
            ], 'config');
        }
    }
}
