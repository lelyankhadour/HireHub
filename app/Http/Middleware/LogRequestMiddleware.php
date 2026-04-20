<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RequestLog;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        $queryCount = 0;

        DB::listen(function () use (&$queryCount) {
            $queryCount++;
        });

        $response = $next($request);

        // AFTER middleware
        // $queryCount using to discover n+1 problem 
        $duration = (microtime(true) - $start) * 1000;

        RequestLog::create([
            'user_id'     => auth()->id(),
            'method'      => $request->method(),
            'endpoint'    => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'duration_ms' => (int) $duration,
            'query_count' => $queryCount,
        ]);

        return $response;
    }
}
