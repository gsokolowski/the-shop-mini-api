<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class AuthenticateSanctum extends Middleware
{
    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        return parent::handle($request, $next, 'sanctum');
    }
}
