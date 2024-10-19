<?php

namespace App\Providers;

use App\Nova\Metrics\CompletedAssignment;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use App\Nova\Metrics\PendingAssignment;
use App\Nova\Metrics\PendingStudentPayment;
use App\Nova\Metrics\PendingWorkProviderPayment;
use App\Nova\Metrics\PendingWriterPayment;
use App\Nova\Metrics\TotalRevenue;
use App\Nova\Metrics\UpcomingDeadline;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            UpcomingDeadline::make(),
            PendingAssignment::make(),
            CompletedAssignment::make(),
            PendingStudentPayment::make(),
            PendingWorkProviderPayment::make(),
            PendingWriterPayment::make(),
            TotalRevenue::make(),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
