<?php

namespace App\Providers;

use App\Repositories\Contracts\BlogRepository;
use App\Repositories\Contracts\CategoryRepository;
use App\Repositories\Contracts\CommentRepository;
use App\Repositories\Contracts\TagRepository;
use App\Repositories\Eloquent\BlogRepositoryEloquent;
use App\Repositories\Eloquent\CategoryRepositoryEloquent;
use App\Repositories\Eloquent\CommentRepositoryEloquent;
use App\Repositories\Eloquent\TagRepositoryEloquent;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Eloquent\UserRepositoryEloquent;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BlogRepository::class, BlogRepositoryEloquent::class);
        $this->app->bind(CategoryRepository::class, CategoryRepositoryEloquent::class);
        $this->app->bind(TagRepository::class, TagRepositoryEloquent::class);
        $this->app->bind(CommentRepository::class, CommentRepositoryEloquent::class);
                $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
