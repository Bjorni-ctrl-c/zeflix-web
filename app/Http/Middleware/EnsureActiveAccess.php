<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureActiveAccess
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->isAdmin() || $request->user()->hasActiveSubscription()) {
            return $next($request);
        }

        return redirect()->route('checkout.page')->with('err', 'Selesaikan pembayaran untuk membuka katalog.');
    }
}
