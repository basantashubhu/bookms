<?php

namespace App\Providers;

use App\Repo\BookRepo;
use App\Repo\UserRepo;
use Illuminate\Support\Carbon;
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
        $this->app->singleton(BookRepo::class, function () {
            return new BookRepo();
        });
        $this->app->singleton(UserRepo::class, function () {
            return new UserRepo();
        });
    }
}
