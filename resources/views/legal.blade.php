@extends('layout')

@php $titles=['privasi'=>'Kebijakan Privasi','ketentuan'=>'Ketentuan Layanan','refund'=>'Pembatalan dan Refund']; @endphp

@section('title', $titles[$page])

@section('content')
<article class="legal-page">
  <a class="back-link" href="{{ route('home') }}"><span aria-hidden="true">←</span> Beranda</a>
  <p class="eyebrow"><span class="eyebrow__line"></span> Informasi Zeflix</p>
  <h1>{{ $titles[$page] }}</h1>
  <div class="notice notice--draft"><strong>Draf kebijakan</strong><span>Ganti dengan teks final sebelum peluncuran publik.</span></div>
  <div class="legal-copy">
    @if($page==='privasi')
    <p>Kami mengumpulkan nama, email, dan riwayat transaksi untuk mengelola akun dan langganan. Data kartu atau e-wallet diproses penyedia pembayaran dan tidak disimpan di server kami.</p>
    @elseif($page==='ketentuan')
    <p>Langganan Rp{{ number_format(config('svod.price'), 0, ',', '.') }} per bulan untuk satu akun. Akses berlaku selama masa langganan aktif. Dilarang membagikan akun atau menyebarkan film tanpa izin.</p>
    @else
    <p>Langganan tidak diperpanjang otomatis; Anda membayar ulang secara manual. Ketentuan refund: isi sesuai kebijakan Anda.</p>
    @endif
  </div>
</article>
@endsection
