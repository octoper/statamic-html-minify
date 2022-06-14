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

        if ($response instanceof StreamedResponse || $response instanceof JsonResponse) {
            return $next($request);
        }

        if ($response instanceof Response && $this->isValidHTMLResponse($response)) {
            $html = (new HtmlMinify($response->getContent()))->minifiedHtml();

            return $response->setContent($html);
        }

        return $next($request);
    }

    protected function isValidHTMLResponse($response)
    {
        $contentType = $response->headers->get('Content-Type');

        return stripos($contentType, 'text/html') !== false;
    }
}
