<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AttemptService;
use App\Services\RangeService;
use App\Services\ShuffleService;
use App\Services\QuestionNavigationService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AttemptService::class, function ($app) {
            return new AttemptService();
        });
        
        $this->app->singleton(RangeService::class, function ($app) {
            return new RangeService();
        });
        
        $this->app->singleton(ShuffleService::class, function ($app) {
            return new ShuffleService();
        });
        
        $this->app->singleton(QuestionNavigationService::class, function ($app) {
            return new QuestionNavigationService(
                $app->make(RangeService::class),
                $app->make(\App\Services\ScoringService::class)
            );
        });
    }

    public function boot()
    {
        //
    }
}