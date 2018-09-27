<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\AttributeRepository::class, \App\Repositories\AttributeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CardRepository::class, \App\Repositories\CardRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CategoriesRepository::class, \App\Repositories\CategoriesRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CategoryRepository::class, \App\Repositories\CategoryRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ItemRepository::class, \App\Repositories\ItemRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\OrderRepository::class, \App\Repositories\OrderRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ProductRepository::class, \App\Repositories\ProductRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\StatusRepository::class, \App\Repositories\StatusRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\StageRepository::class, \App\Repositories\StageRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\TaskRepository::class, \App\Repositories\TaskRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\BillRepository::class, \App\Repositories\BillRepositoryEloquent::class);
        //:end-bindings:
    }
}
