<?php

namespace Octoper\HtmlMinify\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Octoper\HtmlMinify\HtmlMinifyMiddleware;
use Octoper\HtmlMinify\HtmlMinifyServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Statamic\Extend\Manifest;
use Statamic\Providers\StatamicServiceProvider;
use Statamic\Statamic;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            StatamicServiceProvider::class,
            HtmlMinifyServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(Manifest::class)->manifest = [
            'statamic-html-minify' => [
                'id'        => 'octoper/statamic-html-minify',
                'namespace' => 'Octoper\\StatamicHtmlMinify',
            ],
        ];
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $configs = [
            'assets', 'cp', 'forms', 'static_caching',
            'sites', 'stache', 'system', 'users',
        ];

        foreach ($configs as $config) {
            $app['config']->set("statamic.$config", require(__DIR__."/../vendor/statamic/cms/config/{$config}.php"));
        }

        $app['config']->set('statamic.users.repository', 'file');
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('html-minify.optimizeViaHtmlDomParser', true);

        // Workaround for registering routes for tests
        Statamic::booted(function () {
            Statamic::pushWebRoutes(function () {
                Route::namespace('\\Octoper\\HtmlMinify\\\Http\\Controllers')->group(function () {
                    Route::get('/html-minify/test', function () {
                        return response(file_get_contents(__DIR__.'/testPages/simpleMinify.html'), '200', [
                                'Content-Type' => 'text/html'
                            ]);
                    })->middleware(HtmlMinifyMiddleware::class);

                    Route::get('/html-minify/test/remove-comments', function () {
                        return response(file_get_contents(__DIR__.'/testPages/removeComments.html'), '200', [
                            'Content-Type' => 'text/html'
                        ]);
                    })->middleware(HtmlMinifyMiddleware::class);
                });
            });
        });
    }
}
