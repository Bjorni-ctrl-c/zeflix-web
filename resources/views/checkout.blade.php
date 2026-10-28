@extends('layout')

@section('title', 'Buka akses Zeflix')

@section('content')
<section class="page-intro page-intro--compact">
  <p class="eyebrow"><span class="eyebrow__line"></span> Akses satu kali</p>
  <h1>Buka seluruh katalog Zeflix.</h1>
  <p>Selesaikan pembayaran untuk membuka semua film. Tidak ada biaya bulanan atau perpanjangan otomatis.</p>
</section>
<section class="surface-card subscription-status">
  <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Akses penuh</p><h2>Satu kali bayar</h2></div><span class="status-pill status-pill--active">Rp{{ number_format(config('svod.price'), 0, ',', '.') }}</span></div>
  <p>Akses katalog film premium setelah pembayaran berhasil dikonfirmasi oleh Midtrans.</p>
  <form method="post" action="{{ route('checkout.start') }}">@csrf
    <button class="btn btn--primary">Bayar Rp{{ number_format(config('svod.price'), 0, ',', '.') }} dengan Midtrans</button>
  </form>
</section>
@endsection
