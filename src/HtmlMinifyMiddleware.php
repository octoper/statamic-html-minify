<?php

namespace Octoper\HtmlMinify;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        if ($response instanceof Response && $response->headers->contains('Content-Type', 'text/html')) {
            $html = (new HtmlMinify($response->getContent()))->minifiedHtml();

            return $response->setContent($html);
        }

        return $next($request);
    }
}
