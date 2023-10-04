<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseWrapper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotGuest
{
    /**
     * Handle an incoming request. Guests are not allowed to access parts protected by this middleware
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->guest) {
            return ResponseWrapper::forbiddenResponse(__('messages.not_for_guest'));
        } else {
            return $next($request);
        };
    }
}
