<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ResponseWrapper
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
        } else {
            $content = $response->getContent();

            // Attempt to decode JSON content
            $data = json_decode($content, true);

            // If content isn't valid JSON, treat it as raw data
            if (json_last_error() !== JSON_ERROR_NONE) {
                $data = $content;
            }
        }

        return response()->json([
            'success' => $response->getStatusCode() >= 200 && $response->getStatusCode() < 300,
            ...$data,
        ], $response->getStatusCode())
            ->withHeaders($response->headers->all());
    }

}
