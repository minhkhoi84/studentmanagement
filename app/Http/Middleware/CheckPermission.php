<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }

        $user = auth()->user();

        // Super admin có tất cả quyền
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Kiểm tra quyền cụ thể
        if (!$user->hasPermission($permission)) {
            // Nếu là AJAX request, trả về JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Bạn không có quyền thực hiện hành động này',
                    'message' => 'Liên hệ quản trị viên để được cấp quyền'
                ], 403);
            }

            // Nếu không phải AJAX, redirect về trang chủ với thông báo lỗi
            return redirect()->route('home')->with('error', 'Bạn không có quyền thực hiện hành động này. Liên hệ quản trị viên để được cấp quyền.');
        }

        return $next($request);
    }
}
