<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class SanctumMiddleware extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        return $next($request);
    }

    protected function authenticate($request, array $guards)
    {
        // Use Sanctum's authentication guard
        if ($request->expectsJson()) {
            $user = $this->auth->guard('sanctum')->user();
            if (!$user) {
                abort(401, 'Unauthenticated.');
            }
        }
    }
}
