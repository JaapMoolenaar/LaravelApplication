<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DisableLogin
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
        $loginPathRequested = $request->path() == 'login';
        if ($loginPathRequested) {
            throw new NotFoundHttpException('Login path not found.');
        }

        return $next($request);
    }
}
