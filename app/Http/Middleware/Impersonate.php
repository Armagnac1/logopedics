<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class Impersonate
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function handle($request, Closure $next)
    {
        if (! Auth::check()) {
            return $next($request);
        }

        if (! session()->has('impersonate') && session()->has('impersonator')) {
            $user = User::find(session('impersonator'));
            app('auth')->guard('sanctum')->setUser($user);
            session()->put([
                auth('web')->getName() => $user->id,
            ]);
            session()->forget('impersonator');
        } elseif (Auth::user()->can('login as others') && session()->has('impersonate')) {
            $user = User::find(session('impersonate'));
            app('auth')->guard('sanctum')->setUser($user);
            session()->put([
                auth('web')->getName() => $user->id,
            ]);
        }

        return $next($request);
    }
}
