<?php

namespace ConfrariaWeb\ImportAgendor\Providers;

use ConfrariaWeb\ImportAgendor\Services\ImportAgendorService;
use Illuminate\Support\ServiceProvider;

class ImportAgendorServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Views', 'importagendor');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ImportAgendorService', function () {
            return new ImportAgendorService();
        });
    }

}
