<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function page(Request $r)
    {
        if ($r->user()->isAdmin() || $r->user()->hasActiveSubscription()) {
            return redirect()->route('catalog');
        }

        return view('checkout');
    }

    public function start(Request $r, Midtrans $mt)
    {
        if ($r->user()->isAdmin() || $r->user()->hasActiveSubscription()) {
            if ($r->wantsJson() || $r->ajax()) {
                return response()->json(['message' => 'Akun sudah memiliki akses aktif.'], 403);
            }
            return redirect()->route('catalog');
        }
        if (! config('svod.midtrans.server_key')) {
            if ($r->wantsJson() || $r->ajax()) {
                return response()->json(['message' => 'Pembayaran belum dikonfigurasi oleh admin. Hubungi support.'], 503);
            }
            return back()->with('err', 'Pembayaran belum dikonfigurasi. Hubungi administrator.');
        }

        try {
            $t = Transaction::create([
                'user_id'  => $r->user()->id,
                'order_id' => 'SVOD-'.strtoupper(Str::random(12)),
                'amount'   => config('svod.price'),
            ]);
            $snap = $mt->createSnap($t, $r->user());

            if (! isset($snap['token'], $snap['redirect_url'])) {
                $t->update(['status' => 'failed']);
                throw new \RuntimeException('Midtrans tidak mengembalikan token pembayaran.');
            }

            $t->update(['snap_token' => $snap['token'], 'redirect_url' => $snap['redirect_url']]);

            if ($r->wantsJson() || $r->ajax()) {
                return response()->json([
                    'status'       => 'ok',
                    'token'        => $snap['token'],
                    'redirect_url' => $snap['redirect_url'],
                    'order_id'     => $t->order_id,
                ]);
            }

            return redirect()->away($snap['redirect_url']);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Checkout start error: '.$e->getMessage());
            if ($r->wantsJson() || $r->ajax()) {
                return response()->json(['message' => 'Gagal memulai sesi pembayaran. Silakan coba beberapa saat lagi.'], 502);
            }
            return back()->with('err', 'Gagal memulai sesi pembayaran: '.$e->getMessage());
        }
    }

    /** Halaman kembali dari gateway: status dikonfirmasi ke API Midtrans, bukan dari parameter URL. */
    public function finish(Request $r, Midtrans $mt)
    {
        $t = Transaction::where('user_id', $r->user()->id)->where('order_id', $r->query('order_id'))->first();
        abort_unless($t, 404);
        if ($t->status === 'pending' && ($p = $mt->fetchStatus($t->order_id))) {
            $t = $mt->apply($p, trustSource: true) ?? $t;
        }

        return view('status', ['t' => $t->fresh()]);
    }

    public function webhook(Request $r, Midtrans $mt)
    {
        abort_unless($mt->validSignature($r->all()), 403);
        $mt->apply($r->all());

        return response()->json(['ok' => true]);
    }
}
