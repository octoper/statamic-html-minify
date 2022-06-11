<?php

namespace Octoper\HtmlMinify\Tests;

use voku\helper\HtmlMin;
use function Spatie\Snapshots\assertMatchesSnapshot;

uses(TestCase::class);

it('can minify html', function () {
    $minifiedHtml = $this->get('/html-minify/test')->getContent();

    assertMatchesSnapshot($minifiedHtml);
});

it('can remove comments', function () {
    app()['config']->set('html-minify.removeComments', true);

    $minifiedHtml = $this->get('/html-minify/test/remove-comments')->getContent();

    assertMatchesSnapshot($minifiedHtml);
});
