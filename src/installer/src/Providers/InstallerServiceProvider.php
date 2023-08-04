<?php

declare(strict_types=1);

namespace Pandatask\installer\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider
 */
final class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    { //die(123);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'installer');

        $this->publishes([
            __DIR__.'/../config/short-url.php' => config_path('short-url.php'),
        ], 'pandatask-installer-config');

        $this->publishes([
            __DIR__ . '/../views', resource_path('views/vendor/installer'),
        ], 'pandatask-installer-views');
    }

    /**
     * Register services.
     */
    public function register(): void
    { //die(123);
        // Placeholder
    }
}
