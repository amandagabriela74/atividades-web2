<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Book::class => \App\Policies\BookPolicy::class,
        Publisher::class => \App\Policies\PublisherPolicy::class,
        Category::class => \App\Policies\CategoryPolicy::class,
        Author::class => \App\Policies\AuthorPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
