<?php

namespace App\Middleware;

class EnforceAcceptJson
{
    public function handle($request, $next)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
