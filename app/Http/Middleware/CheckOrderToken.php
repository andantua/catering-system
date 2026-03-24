<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckOrderToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('order_ward_id')) {
            return redirect()->route('order.login')
                ->with('error', 'Musisz się zalogować.');
        }
        
        return $next($request);
    }
}