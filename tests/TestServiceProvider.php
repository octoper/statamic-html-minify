<?php

namespace Octoper\HtmlMinify\Tests;

use Illuminate\Support\Facades\Route;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class TestServiceProvider extends AddonServiceProvider
{
    public function boot()
    {
        parent::boot();

        Statamic::booted(function () {
            $this->registerWebRoutes(function () {
                Route::get('/test-minify', function () {
                    return file_get_contents('test-unminified.html');
                });
            });
        });
    }
}
