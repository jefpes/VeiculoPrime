<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class EnsureCentralDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array($request->getHost(), config('tenancy.central_domains'))) {
            //throw new RuntimeException('This domain is not a central domain.');
            abort(404);
        }

        return $next($request);
    }
}
