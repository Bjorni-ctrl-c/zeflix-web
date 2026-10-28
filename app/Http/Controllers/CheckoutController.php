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
            return redirect()->route('catalog');
        }
        if (! config('svod.midtrans.server_key')) {
            return back()->with('err', 'Pembayaran belum dikonfigurasi. Isi MIDTRANS_SERVER_KEY untuk mengaktifkan Midtrans Sandbox.');
        }
        $t = Transaction::create([
            'user_id' => $r->user()->id,
            'order_id' => 'SVOD-'.strtoupper(Str::random(12)),
            'amount' => config('svod.price'),
        ]);
        $snap = $mt->createSnap($t, $r->user());
        abort_unless(isset($snap['redirect_url'], $snap['token']), 502, 'Midtrans tidak mengembalikan tautan pembayaran.');
        $t->update(['snap_token' => $snap['token'] ?? null, 'redirect_url' => $snap['redirect_url'] ?? null]);

        return redirect()->away($snap['redirect_url']);
    }

    /** Halaman kembali dari gateway: status dikonfirmasi ke API Midtrans, bukan dari parameter URL. */
    public function finish(Request $r, Midtrans $mt)
    {
        $t = Transaction::where('user_id', $r->user()->id)->where('order_id', $r->query('order_id'))->first();
        abort_unless($t, 404);
        if ($t->status === 'pending' && ($p = $mt->fetchStatus($t->order_id))) {
            $t = $mt->apply($p) ?? $t;
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
