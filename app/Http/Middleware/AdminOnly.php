<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()) {
            return redirect()->guest(route('login'))->with('err', 'Silakan masuk dengan akun administrator.');
        }

        if (! $request->user()->isAdmin()) {
            AuditLog::record(
                'UNAUTHORIZED_ACCESS',
                "Percobaan akses ke '{$request->path()}' oleh pengguna non-admin '{$request->user()->name}' (#{$request->user()->id})",
                $request->user()
            );

            abort(403, 'Akses ditolak. Anda tidak memiliki izin administrator untuk mengakses area ini.');
        }

        return $next($request);
    }
}
