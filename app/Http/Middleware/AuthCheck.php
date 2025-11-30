<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthCheck
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa login thì chuyển hướng về trang login
        if (! Session::has('user_id')) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập trước');
        }

        return $next($request);
    }
}
