<?php

namespace App\Providers;

use App\User;
use App\Level;
use App\Lesson;
use App\Observers\UserObserver;
use App\Observers\LevelObserver;
use App\Observers\LessonObserver;
use Illuminate\Support\Facades\Schema;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        User::observe(UserObserver::class);
        Level::observe(LevelObserver::class);
        Lesson::observe(LessonObserver::class);
    }
}
