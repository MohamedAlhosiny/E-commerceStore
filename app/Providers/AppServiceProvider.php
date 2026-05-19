<?php

namespace App\Providers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\userRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this -> app ->bind( UserRepositoryInterface::class ,
        userRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
