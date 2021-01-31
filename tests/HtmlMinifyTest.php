<?php

namespace Octoper\HtmlMinify\Tests;


use Illuminate\Routing\Router;
use Octoper\HtmlMinify\HtmlMinifyMiddleware;

class HtmlMinifyTest extends TestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set('html-minify.optimizeViaHtmlDomParser', true);
    }

    /** @test */
    public function check_if_can_minify_html()
    {
//        $response = $this->;
//
//        dd($response);
    }
}
