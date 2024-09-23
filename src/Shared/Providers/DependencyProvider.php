<?php

namespace Pupadevs\Laramain\Shared\Providers;

use Illuminate\Support\ServiceProvider;

class DependencyProvider extends ServiceProvider{


    public function register(){


       // $this->app->bind(UserReadRepositoryInterface::class, UserReadRepository::class);
    }

    public function boot(){

    }
}