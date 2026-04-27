<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
  use App\Enums\UserRole;
class CheckIsClient
{
    public function handle(Request $request, Closure $next)
    {


if (auth()->user()->role !== UserRole::Client) {
    return response()->json([
        'success' => false,
        'message' => 'Only clients can perform this action'
    ], 403);
}

 

        return $next($request);
    }
}
