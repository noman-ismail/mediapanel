<?php

namespace NomanIsmail\MediaPanel;

use Illuminate\Support\ServiceProvider;
use NomanIsmail\MediaPanel\Repositories\MediaRepositoryInterface;
use NomanIsmail\MediaPanel\Repositories\MediaRepository;

class MediaPanelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register repository binding
        $this->app->bind(
            MediaRepositoryInterface::class,
            MediaRepository::class
        );

        // Merge config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/media.php',
            'media'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/media.php' => config_path('media.php'),
        ], 'mediapanel-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/Database/Migrations' => database_path('migrations'),
        ], 'mediapanel-migrations');

        // Publish views
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/mediapanel'),
        ], 'mediapanel-views');

        // Publish assets
        $this->publishes([
            __DIR__ . '/resources/assets/mediapanel.js' => public_path('vendor/mediapanel/mediapanel.js'),
            __DIR__ . '/resources/assets/mediapanel.css' => public_path('vendor/mediapanel/mediapanel.css'),
        ], 'mediapanel-assets');

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'mediapanel');
    }
}
