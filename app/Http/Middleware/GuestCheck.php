<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GuestCheck
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu đã login thì redirect về dashboard
        if (Session::has('user_id')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
