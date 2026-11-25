@extends('layout')

@section('title', 'Status pembayaran')

@section('content')
<section class="status-page">
  @if($t->status==='paid')
  <div class="status-mark status-mark--success" aria-hidden="true">✓</div>
  <p class="eyebrow"><span class="eyebrow__line"></span> Pembayaran Terkonfirmasi</p>
  <h1>Pembayaran Berhasil!</h1>
  <p style="font-size: 1.05rem; color: #fff; margin-bottom: .5rem;">
    Selamat, akunmu sekarang sudah menjadi <strong style="color: var(--netflix-green);">Pelanggan Aktif</strong>.
  </p>
  <p style="margin-bottom: 2rem;">
    Seluruh katalog dan pemutar video Zeflix telah terbuka penuh tanpa batas waktu.
  </p>
  <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
    <a class="btn btn--primary" href="{{ route('catalog') }}" style="min-width: 180px;">Buka Katalog Film <span aria-hidden="true">→</span></a>
    <a class="btn btn--outline" href="{{ route('home') }}">Ke Beranda</a>
  </div>
  @elseif($t->status==='pending')
  <div class="status-mark status-mark--pending" aria-hidden="true">···</div>
  <p class="eyebrow"><span class="eyebrow__line"></span> Menunggu konfirmasi</p>
  <h1>Pembayaran belum selesai.</h1>
  <p>Akses aktif otomatis setelah pembayaran terkonfirmasi. Muat ulang halaman ini beberapa saat lagi.</p>
  @else
  <div class="status-mark status-mark--failed" aria-hidden="true">!</div>
  <p class="eyebrow"><span class="eyebrow__line"></span> Pembayaran tidak berhasil</p>
  <h1>{{ $t->status==='expired' ? 'Pembayaran kedaluwarsa.' : 'Pembayaran tidak berhasil.' }}</h1>
  <p>Akses Premium tidak diaktifkan. Silakan coba lagi untuk melanjutkan.</p>
  <form method="post" action="{{ route('checkout.start') }}">@csrf<button class="btn btn--primary">Coba lagi</button></form>
  @endif
  <p class="status-reference">Referensi <strong>{{ $t->order_id }}</strong></p>
</section>
@endsection
