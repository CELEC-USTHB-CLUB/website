<?php

namespace App\Providers;

use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\ServiceProvider;
use App\Actions\ExportEventRegistrationsAction;
use App\Actions\ExportTrainingRegisrationsAction;

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
        Voyager::addAction(ExportTrainingRegisrationsAction::class);
        Voyager::addAction(ExportEventRegistrationsAction::class);
    }
}
