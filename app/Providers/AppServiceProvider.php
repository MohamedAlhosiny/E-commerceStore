<?php

namespace App\Providers;

use App\Interfaces\AdminRepositoryInterface;
use App\Interfaces\AdminServiceInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\AdminRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\UserRepository;
use App\Services\AdminService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(AdminServiceInterface::class, AdminService::class);
        $this->app->bind(CategoryRepositoryInterface::class , CategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
