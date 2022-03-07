<?php

namespace Lofty\User;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //

        // $this->app->make('Lofty\User\UserController');
        // $this->app->singleton(UserController::class, function(){
        //     return new UserController();
        // });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
