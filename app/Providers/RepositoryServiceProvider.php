<?php

namespace App\Providers;

use App\Repositories\BlogPosts\BlogPostsRepository;
use App\Repositories\Interface\IBlogPosts;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IBlogPosts::class, BlogPostsRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
