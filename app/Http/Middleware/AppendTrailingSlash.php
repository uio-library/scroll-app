<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class AppendTrailingSlash
{
    public function __construct(Redirector $redirector)
    {
        $this->redirector = $redirector;
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
        if (!preg_match('/\/$/', $request->getRequestUri())) {
            // Create a RedirectResponse
            $response = $this->redirector->to($request->getRequestUri(), 301);

            // Then append the '/' (we have to do this manually afterwards because Laravel strips them off otherwise)
            $response ->setTargetUrl($response->getTargetUrl() . '/');

            return $response;
        }

        return $next($request);
    }
}
