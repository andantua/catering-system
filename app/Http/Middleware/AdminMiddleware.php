<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $adminEmails = config('admin.emails', ['admin@catering.com']);

        if (!in_array(auth()->user()->email, $adminEmails)) {
            abort(403, 'Brak dostępu.');
        }

        return $next($request);
    }
}