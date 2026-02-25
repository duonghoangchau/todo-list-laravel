<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chưa đăng nhập → về trang login
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Đã đăng nhập nhưng không phải admin → về trang chủ
        if (!$user->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Tài khoản bị khóa
        if (!$user->isActive()) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Tài khoản đã bị khóa.']);
        }

        return $next($request);
    }
}
