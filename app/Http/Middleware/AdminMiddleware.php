<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        //Kiểm tra xem người dùng có vai trò admin không
        if (auth()->user()->role != 0) {
            //Nếu không phải admin, bạn có thể xử lí theo ý muốn của bạn, ví dụ chuyển hướng hoặc ném ngoại lệ
            //Ví dụ: return redirect()->rote('home');
            //Hoặc throw new \Exception('không có quyền truy cập');
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập vào trang này.');
        }

        //Nếu người dùng có vai trò admin, cho phép truy cập vào tuyến đường
        return $next($request);
    }
}
