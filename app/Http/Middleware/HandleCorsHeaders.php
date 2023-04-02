<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HandleCorsHeaders
{
    /**
     * API 路徑
     *
     * @var array<string>
     */
    protected $paths;

    /**
     * 允許的方法
     *
     * @var array<string>
     */
    protected $allow_methods;

    /**
     * 允許的 Origin
     *
     * @var array<string>
     */
    protected $allow_origins;

    /**
     * 允許的 Origin 形式
     *
     * @var array<string>
     */
    protected $allow_origin_patterns;

    /**
     * 允許的標頭
     *
     * @var array<string>
     */
    protected $allow_headers;

    /**
     * 要暴露的標頭
     *
     * @var array<string>
     */
    protected $expose_headers;

    /**
     * 最大有效時間
     *
     * @var int
     */
    protected $max_age;

    /**
     * 支援 Credentials
     *
     * @var bool
     */
    protected $supports_credentials;

    /**
     * 建構方法
     *
     * @return void
     */
    public function __construct()
    {
        $this->paths = config('cors.paths');
        $this->allow_methods = config('cors.allowed_methods');
        $this->allow_origins = config('cors.allowed_origins');
        $this->allow_origin_patterns = config('cors.allowed_origins_patterns');
        $this->allow_headers = config('cors.allowed_headers');
        $this->expose_headers = config('cors.exposed_headers');
        $this->max_age = config('cors.max_age');
        $this->supports_credentials = config('cors.supports_credentials');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (strtoupper($request->getMethod()) !== 'OPTIONS') {
            $response = $next($request);
        } else {
            $response = response()->json();
        }

        $origin = (in_array('*', $this->allow_origins)
            ? '*'
            : $request->headers->get('host'));

        if (
            ! $response instanceof StreamedResponse && (
                in_array($origin, $this->allow_origins) ||
                in_array('*', $this->allow_origins)
            )
        ) {
            $response
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Max-Age', $this->max_age);

            if (! in_array('*', $this->allow_origins)) {
                $response
                    ->header('Vary', 'Origin');
            }

            $response
                ->header('Access-Control-Allow-Methods', strtoupper(implode(', ', $this->allow_methods)));

            if (count($this->allow_headers) > 0) {
                $response
                    ->header('Access-Control-Allow-Headers', implode(', ', $this->allow_headers));
            }

            if (count($this->expose_headers) > 0) {
                $response
                    ->header('Access-Control-Expose-Headers', implode(', ', $this->expose_headers));
            }
        }

        return $response;
    }
}
