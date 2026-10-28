<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Midtrans
{
    private function key(): string
    {
        return (string) config('svod.midtrans.server_key');
    }

    private function prod(): bool
    {
        return config('svod.midtrans.production');
    }

    public function createSnap(Transaction $t, User $u): array
    {
        $base = $this->prod() ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
        $res = Http::withBasicAuth($this->key(), '')->acceptJson()->post("$base/snap/v1/transactions", [
            'transaction_details' => ['order_id' => $t->order_id, 'gross_amount' => $t->amount],
            'item_details' => [['id' => 'zeflix-access', 'price' => $t->amount, 'quantity' => 1, 'name' => 'Akses penuh Zeflix']],
            'customer_details' => ['first_name' => $u->name, 'email' => $u->email],
            'callbacks' => ['finish' => route('checkout.finish')],
        ]);
        abort_unless($res->successful(), 502, 'Gagal membuat transaksi pembayaran.');

        return $res->json();
    }

    public function fetchStatus(string $orderId): ?array
    {
        $base = $this->prod() ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';
        $res = Http::withBasicAuth($this->key(), '')->acceptJson()->get("$base/v2/$orderId/status");

        return $res->successful() ? $res->json() : null;
    }

    public function validSignature(array $p): bool
    {
        if (! isset($p['order_id'], $p['status_code'], $p['gross_amount'], $p['signature_key'])) {
            return false;
        }
        $expected = hash('sha512', $p['order_id'].$p['status_code'].$p['gross_amount'].$this->key());

        return hash_equals($expected, $p['signature_key']);
    }

    /** Idempoten: notifikasi ganda tidak menggandakan akses. */
    public function apply(array $p): ?Transaction
    {
        if (! $this->validSignature($p)) {
            return null;
        }

        return DB::transaction(function () use ($p) {
            $t = Transaction::where('order_id', $p['order_id'])->lockForUpdate()->first();
            if (! $t || $t->status === 'paid') {
                return $t;
            }

            if ((int) round((float) $p['gross_amount']) !== $t->amount) {
                Log::warning('Nominal tidak cocok', ['order' => $t->order_id]);

                return $t;
            }

            $ts = $p['transaction_status'] ?? '';
            $fraud = $p['fraud_status'] ?? 'accept';
            $status = match (true) {
                $ts === 'settlement', $ts === 'capture' && $fraud === 'accept' => 'paid',
                in_array($ts, ['deny', 'cancel', 'failure'], true) => 'failed',
                $ts === 'expire' => 'expired',
                default => 'pending',
            };

            $t->update(['status' => $status, 'payment_type' => $p['payment_type'] ?? null, 'raw' => $p]);

            if ($status === 'paid') {
                Subscription::create([
                    'user_id' => $t->user_id,
                    'transaction_id' => $t->id,
                    'starts_at' => now(),
                    'ends_at' => null,
                ]);
            }

            return $t;
        });
    }
}
