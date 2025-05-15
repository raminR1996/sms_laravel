<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 
    
        public function handle(Request $request, Closure $next): mixed
        {
            $user = Auth::user();
    
            if (!$user->name || !$user->email) {
                return redirect()->route('profile.complete');
            }
    
            return $next($request);
        }
    }

