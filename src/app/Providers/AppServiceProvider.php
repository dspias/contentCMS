<?php

namespace App\Providers;

use App\Models\Content;
use App\Models\Student;
use App\Observers\ContentObserver;
use App\Observers\StudentObserver;
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
        Student::observe(StudentObserver::class);
        Content::observe(ContentObserver::class);
    }
}
