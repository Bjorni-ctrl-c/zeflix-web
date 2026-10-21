@extends('layout')

@section('title', 'Status pembayaran')

@section('content')
<section class="status-page">
  @if($t->status==='paid')
  <div class="status-mark status-mark--success" aria-hidden="true">✓</div>
  <p class="eyebrow"><span class="eyebrow__line"></span> Pembayaran terkonfirmasi</p>
  <h1>Pembayaran berhasil.</h1>
  <p>Langganan Anda aktif. Pilih film dan nikmati tontonan berikutnya.</p>
  <a class="btn btn--primary" href="{{ route('catalog') }}">Mulai menonton <span aria-hidden="true">→</span></a>
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
