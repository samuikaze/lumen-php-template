<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class RequestLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info($this->generateLogString($request));

        return $next($request);
    }

    /**
     * Generate log string.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function generateLogString($request): string
    {
        $headers = $request->headers->all();
        $log = strtoupper($request->method()).
            ' '.$request->getSchemeAndHttpHost().
            '/'.$request->path().
            ' '.$headers['content-type'][0].
            ' '.$headers['content-length'][0];

        $log = '[Request] '.$log;

        return $log;
    }
}
