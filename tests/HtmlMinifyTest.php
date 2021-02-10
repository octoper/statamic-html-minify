<?php

namespace Octoper\HtmlMinify\Tests;

use Illuminate\Support\Facades\Route;
use Octoper\HtmlMinify\HtmlMinifyMiddleware;
use Statamic\Statamic;
use function PHPUnit\Framework\assertEquals;

class HtmlMinifyTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
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

    /** @test */
    public function check_if_can_minify_html_with_middleware()
    {
        $response = $this->get('/html-minify/test');

        assertEquals(
            $response->content(),
            '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Title</title></head> <body><p>Hello</p></body></html>'
        );
    }
}
