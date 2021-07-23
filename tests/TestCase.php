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
                'id' => 'octoper/statamic-html-minify',
                'namespace' => 'Octoper\\StatamicHtmlMinify',
            ]
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
            $app['config']->set("statamic.$config", require(__DIR__ . "/../vendor/statamic/cms/config/{$config}.php"));
        }

        $app['config']->set('statamic.users.repository', 'file');
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('html-minify.optimizeViaHtmlDomParser', true);

        // Workaround for registering routes for tests
        Statamic::booted(function () {
            Statamic::pushWebRoutes(function () {
                Route::namespace('\\Octoper\\HtmlMinify\\\Http\\Controllers')->group(function () {
                    Route::get('/html-minify/test', function () {
                        return <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <p>Hello</p>
</body>
</html>
HTML;
                    })->middleware(HtmlMinifyMiddleware::class);
                });
            });
        });
    }
}
