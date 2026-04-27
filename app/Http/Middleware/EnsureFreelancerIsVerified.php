<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;

class EnsureFreelancerIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'You must be logged in to perform this action.'
            ], 401);
        }

        if ($user->role !== UserRole::Freelancer) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'Only freelancers can perform this action.'
            ], 403);
        }

        if (! $user->is_verified) {
            return response()->json([
                'error' => 'Freelancer not verified',
                'message' => 'You must complete verification before performing this action.'
            ], 403);
        }

        return $next($request);
    }
}
