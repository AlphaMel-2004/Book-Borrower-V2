<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::warning('AdminMiddleware: User not authenticated', [
                'ip' => $request->ip(),
                'url' => $request->url()
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Authentication required.'], 401);
            }
            return redirect()->route('login')->with('error', 'Please log in to access this area.');
        }

        $user = Auth::user();
        
        // Debug logging for admin check
        Log::info('AdminMiddleware: Admin check', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'is_admin_accessor' => $user->isAdmin ?? 'accessor_failed',
        ]);

        // Check admin status using the accessor
        $isAdmin = (bool) $user->isAdmin;
        
        if (!$isAdmin) {
            Log::warning('AdminMiddleware: Access denied - user is not admin', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'You must be an administrator to access this area.');
        }

        return $next($request);
    }
}
