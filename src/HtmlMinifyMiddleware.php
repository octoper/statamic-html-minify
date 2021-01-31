<?php

namespace Octoper\HtmlMinify;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        $html = (new HtmlMinify($response->getContent()))->minifiedHtml();

        return $response->setContent($html);
    }
}
