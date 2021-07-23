<?php

namespace Octoper\HtmlMinify\Tests;

use function PHPUnit\Framework\assertEquals;

class HtmlMinifyTest extends TestCase
{
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
