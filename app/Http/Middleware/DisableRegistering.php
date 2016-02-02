<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DisableRegistering
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $registerPathRequested = $request->path() == 'register';
        if ($registerPathRequested) {
            throw new NotFoundHttpException('Register path not found.');
        }

        return $next($request);
    }
}
