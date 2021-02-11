<?php

namespace Octoper\HtmlMinify;

use Illuminate\Support\Facades\Config;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;

class HtmlMinifyServiceProvider extends AddonServiceProvider
{
    protected $middlewareGroups = [
        'web' => [
            HtmlMinifyMiddleware::class,
        ],
    ];

    protected $routes = [
        'cp' => __DIR__ . '/../routes/cp.php',
    ];

    public function boot(): void
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'html-minify');

        $this->publishes([
            __DIR__.'/../config/html-minify.php' => config_path('html-minify.php'),
        ], 'Statamic HTML Minify config file');

        $this->bootNavigation();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/html-minify.php',
            'html-minify'
        );
    }

    private function bootNavigation(): void
    {
        Nav::extend(function ($nav) {
            $nav->create('HTML Minify')
                ->icon('<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>')
                ->section('Tools')
                ->route('html-minify.settings');
//                ->children([
//                    $nav->item(__('oh-dear::lang.uptime'))->route('oh-dear.uptime'),
//                    $nav->item(__('oh-dear::lang.broken_links'))->route('oh-dear.broken-links'),
//                    $nav->item(__('oh-dear::lang.mixed_content'))->route('oh-dear.mixed-content'),
//                    $nav->item(__('oh-dear::lang.certificate_health'))->route('oh-dear.certificate-health'),
//                ]);
        });
    }
}
