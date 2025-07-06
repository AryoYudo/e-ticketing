<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('loggedInUser')) {
            return redirect('/auth')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
