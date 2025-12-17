<?php

namespace NomanIsmail\MediaPanel;

use Illuminate\Support\ServiceProvider;
use NomanIsmail\MediaPanel\Repositories\MediaRepositoryInterface;
use NomanIsmail\MediaPanel\Repositories\MediaRepository;
use NomanIsmail\MediaPanel\Services\MediaService;

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

        // Register media service as singleton
        $this->app->singleton(MediaService::class, function ($app) {
            return new MediaService($app->make(MediaRepositoryInterface::class));
        });

        // Merge config - handle both package and published config
        $configPath = $this->getConfigPath();
        if ($configPath && file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'media');
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $configPath = $this->getConfigPath();
        if ($configPath && file_exists($configPath)) {
            $this->publishes([
                $configPath => config_path('media.php'),
            ], 'mediapanel-config');
        }

        // Publish migrations
        $migrationsPath = __DIR__ . '/Database/Migrations';
        if (is_dir($migrationsPath)) {
            $this->publishes([
                $migrationsPath => database_path('migrations'),
            ], 'mediapanel-migrations');
        }

        // Publish views
        $viewsPath = __DIR__ . '/resources/views';
        if (is_dir($viewsPath)) {
            $this->publishes([
                $viewsPath => resource_path('views/vendor/mediapanel'),
            ], 'mediapanel-views');
        }

        // Publish assets
        $assetsPath = __DIR__ . '/resources/assets';
        if (is_dir($assetsPath)) {
            $this->publishes([
                $assetsPath . '/mediapanel.js' => public_path('vendor/mediapanel/mediapanel.js'),
                $assetsPath . '/mediapanel.css' => public_path('vendor/mediapanel/mediapanel.css'),
            ], 'mediapanel-assets');
        }

        // Load routes
        $routesPath = __DIR__ . '/routes/web.php';
        if (file_exists($routesPath)) {
            $this->loadRoutesFrom($routesPath);
        }

        // Load views
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'mediapanel');
        }
    }

    /**
     * Get config file path.
     *
     * @return string|null
     */
    protected function getConfigPath(): ?string
    {
        // Check multiple possible paths
        $paths = [
            __DIR__ . '/../../config/media.php',           // Package root
            __DIR__ . '/../../../config/media.php',        // Alternative structure
            base_path('vendor/nomanismail/mediapanel/config/media.php'), // Installed package
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
