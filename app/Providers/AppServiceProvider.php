<?php

namespace App\Providers;

use App\Http\Resources\ProductResource;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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

        Model::preventLazyLoading(! app()->isProduction()); // Disable lazy loading in development, not in production. 

        ProductResource::withoutWrapping();

        Relation::enforceMorphMap([  // custom value for our polymorphic models
            'review' => Review::class,
            'user' => User::class,
        ]);
    }
}
