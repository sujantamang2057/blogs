<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckEditorRole
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
        // Check if the user has the 'editor' role
        if (Auth::check() && Auth::user()->hasRole('editor')) {
            // Redirect or deny access
            return redirect()->route('dashboard')->with('error', 'You do not have permission to create a blog post.');
        }

        return $next($request);
    }
}
