<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseWrapper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->admin) {
            return $next($request);
        } else {
            return ResponseWrapper::forbiddenResponse(__('messages.not_authorized'));
        };
    }
}
