<?php

namespace Octoper\HtmlMinify\Tests;

use function Spatie\Snapshots\assertMatchesSnapshot;

uses(TestCase::class);

it('can minify html', function () {
    $minifiedHtml = $this->get('/html-minify/test')->getContent();

    assertMatchesSnapshot($minifiedHtml);
});
