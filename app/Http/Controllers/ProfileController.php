<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $user_name  = Session::get('user_name');
        $user_email = Session::get('user_email');
        $user_id    = Session::get('user_id');

        // Get user statistics
        $totalTasks     = DB::table('tasks')->where('user_id', $user_id)->count();
        $completedTasks = DB::table('tasks')->where('user_id', $user_id)->where('is_completed', 1)->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return view('pages.profile', compact(
            'user_name',
            'user_email',
            'user_id',
            'totalTasks',
            'completedTasks',
            'completionRate'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user_id = Session::get('user_id');

        $request->validate([
            'user_name'  => 'required|string|max:50',
            'user_email' => 'required|email|max:50|unique:users,user_email,' . $user_id . ',user_id',
        ]);

        try {
            // Update user in database
            DB::table('users')
                ->where('user_id', $user_id)
                ->update([
                    'user_name'  => $request->user_name,
                    'user_email' => $request->user_email,
                    'updated_at' => now(),
                ]);

            // Update session data
            Session::put('user_name', $request->user_name);
            Session::put('user_email', $request->user_email);

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật thông tin thành công!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật thông tin!',
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        $user_id = Session::get('user_id');

        $request->validate([
            'current_password' => 'required',
            'new_password'     => [
                'required',
                'confirmed',
                Password::min(6)
                    ->numbers(),
            ],
        ]);

        try {
            // Get current user
            $user = DB::table('users')->where('user_id', $user_id)->first();

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy người dùng!',
                ], 404);
            }

            // Verify current password
            if (! Hash::check($request->current_password, $user->user_password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không đúng!',
                ], 400);
            }

            // Update password
            DB::table('users')
                ->where('user_id', $user_id)
                ->update([
                    'user_password' => Hash::make($request->new_password),
                    'updated_at'    => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã đổi mật khẩu thành công!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đổi mật khẩu!',
            ], 500);
        }
    }
}