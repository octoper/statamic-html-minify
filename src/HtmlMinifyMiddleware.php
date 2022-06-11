<?php

namespace Octoper\HtmlMinify;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Statamic\Support\Arr;
use Statamic\Support\Str;
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
        /** @var $response \Illuminate\Http\Response */
        $response = $next($request);

        if ($response instanceof StreamedResponse || $response instanceof JsonResponse) {
            return $next($request);
        }

        foreach (config("html-minify.excludedContentTypes") as $type) {
            if ($response->headers->contains('content-type', $type)) {
                return $next($request);
            }
        }
        if (Arr::ha())

        $html = (new HtmlMinify($response->getContent()))->minifiedHtml();

        return $response->setContent($html);
    }
}
