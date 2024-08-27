<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 管理者がログインしているかを確認
        $adminUser = auth('admin')->user() ?? null;

        if ($adminUser) {
            return redirect()->route('dashboard.admin');
        }

        return $next($request);
    }
}
