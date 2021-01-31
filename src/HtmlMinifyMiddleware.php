<?php

namespace Octoper\HtmlMinify;

use Illuminate\Http\Request;
use voku\helper\HtmlMin;

class HtmlMinifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $response = $next($request);
        
        if ($response instanceof StreamedResponse) {
            return $next($request);
        }

        $config_prefix = 'html-minify';

        $html = (new HtmlMin())
            ->doOptimizeViaHtmlDomParser(
                config("{$config_prefix}.optimizeViaHtmlDomParser")
            )
            ->doRemoveComments(
                config("{$config_prefix}.removeComments")
            )
            ->doSumUpWhitespace(
                config("{$config_prefix}.sumUpWhitespace")
            )
            ->doSortCssClassNames(
                config("{$config_prefix}.sortCssClassNames")
            )
            ->doSortHtmlAttributes(
                config("{$config_prefix}.sortHtmlAttributes")
            )
            ->doRemoveWhitespaceAroundTags(
                config("{$config_prefix}.removeWhitespaceAroundTags")
            )
            ->doRemoveSpacesBetweenTags(
                config("{$config_prefix}.removeSpacesBetweenTags")
            )
            ->doRemoveOmittedQuotes(
                config("{$config_prefix}.removeOmittedQuotes")
            )
            ->doRemoveOmittedHtmlTags(
                config("{$config_prefix}.removeOmittedHtmlTags")
            )
            ->minify($response->getContent());

        return $response->setContent($html);
    }
}
