<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

session_start();

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3|max:50',
            'email'    => 'required|email|unique:users,user_email',
            'password' => 'required|min:6',
        ]);

        DB::table('users')->insert([
            'user_name'     => $request->username,
            'user_email'    => $request->email,
            'user_password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký thành công',
        ]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email    = $request->input('email');
        $password = $request->input('password');

        $user = DB::table('users')->where('user_email', $email)->first();

        if ($user && Hash::check($password, $user->user_password)) {
            Session::put('user_id', $user->user_id);
            Session::put('user_name', $user->user_name);
            Session::put('user_email', $user->user_email);

            return response()->json([
                'success'  => true,
                'redirect' => '/dashboard',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email hoặc mật khẩu không đúng',
        ]);
    }

    public function logout()
    {
        Session::flush();
        return redirect('/');
    }

}
