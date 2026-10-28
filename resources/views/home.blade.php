@extends('layout')

@section('title', 'Zeflix — Film pendek, cerita besar')

@section('hero')
<section class="hero hero--home hero--landing">
  <div class="hero__inner">
    <div class="hero__copy">
      <p class="eyebrow"><span class="eyebrow__line"></span> Bioskop cerita pilihanmu</p>
      <span class="hero__kicker">Film pendek <span aria-hidden="true">·</span> Cerita besar</span>
      <h1>Cerita pilihan.<br>Sekali bayar.</h1>
      <p class="hero__description">Temukan film pilihan dan nikmati akses katalog dengan satu kali pembayaran. Tanpa tagihan bulanan.</p>
      <div class="hero__actions">
        @guest
        <a class="btn btn--primary" href="{{ route('register') }}">Buat akun &amp; mulai</a>
        @else
          @if(auth()->user()->isAdmin() || auth()->user()->hasActiveSubscription())
          <a class="btn btn--primary" href="{{ route('catalog') }}">Jelajahi katalog</a>
          @else
          <a class="btn btn--primary" href="{{ route('checkout.page') }}">Buka akses</a>
          @endif
        @endguest
          <a class="btn btn--outline" href="#cara-kerja">Lihat cara kerja <span aria-hidden="true">↓</span></a>
      </div>
    </div>
    <div class="hero__poster hero__poster--landing" aria-hidden="true">
      <span class="hero__poster-mark">ZF</span>
      <span class="landing-poster__label">Satu kali bayar</span>
      <span class="landing-poster__caption">Cerita yang tinggal di hati.</span>
    </div>
  </div>
</section>
@endsection

@section('wrapclass', 'wrap--home')

@section('content')
@php
$rows = [
  'Film terbaru' => $films,
  'Tonton gratis' => $films->where('access', 'free'),
  'Khusus pelanggan' => $films->where('access', 'premium'),
];
@endphp

<div class="home-content">
  <section class="landing-section landing-how" id="cara-kerja">
    <div class="landing-section__heading">
      <p class="eyebrow"><span class="eyebrow__line"></span> Mulai dalam tiga langkah</p>
      <h2>Nggak pakai bingung.<br>Tinggal pilih dan nikmati.</h2>
      <p>Dari daftar sampai mulai menonton, semua alurnya jelas.</p>
    </div>
    <ol class="landing-steps">
      <li class="landing-step">
        <span class="landing-step__number">01</span>
        <h3>Buat akun</h3>
        <p>Daftar dengan nama, email, dan password untuk menyimpan akses serta riwayatmu.</p>
      </li>
      <li class="landing-step">
        <span class="landing-step__number">02</span>
        <h3>Buka akses sekali</h3>
        <p>Selesaikan pembayaran satu kali sebesar <strong>Rp{{ number_format(config('svod.price'), 0, ',', '.') }}</strong>. Tidak ada tagihan bulanan.</p>
      </li>
      <li class="landing-step">
        <span class="landing-step__number">03</span>
        <h3>Pilih film, lalu tonton</h3>
        <p>Setelah pembayaran terkonfirmasi, jelajahi katalog dan nikmati akses tanpa batas waktu.</p>
      </li>
    </ol>
  </section>

  <section class="landing-section landing-feature-section" id="fitur">
    <div class="landing-section__heading">
      <p class="eyebrow"><span class="eyebrow__line"></span> Dibuat untuk pengalaman menonton yang simpel</p>
      <h2>Semua yang kamu butuhkan untuk menemukan cerita.</h2>
    </div>
    <div class="landing-features">
      <article class="landing-feature">
        <span class="landing-feature__index" aria-hidden="true">01</span>
        <h3>Katalog yang mudah dijelajahi</h3>
        <p>Temukan pilihan film dan persempit pencarian berdasarkan genre.</p>
      </article>
      <article class="landing-feature">
        <span class="landing-feature__index" aria-hidden="true">02</span>
        <h3>Status akses yang jelas</h3>
        <p>Label gratis dan premium membantu kamu tahu jenis akses tiap film.</p>
      </article>
      <article class="landing-feature">
        <span class="landing-feature__index" aria-hidden="true">03</span>
        <h3>Tonton langsung di web</h3>
        <p>Pemutar tersedia di halaman film, jadi kamu bisa lanjut menonton tanpa mencari halaman lain.</p>
      </article>
      <article class="landing-feature">
        <span class="landing-feature__index" aria-hidden="true">04</span>
        <h3>Akun dan transaksi tersimpan</h3>
        <p>Cek status akses dan riwayat pembayaran, atau perbarui informasi akunmu kapan saja.</p>
      </article>
    </div>
  </section>

  @foreach($rows as $title => $list)
  @if($list->count())
  <section class="rowbox">
    <div class="section-heading">
      <div><p class="eyebrow"><span class="eyebrow__line"></span> Pilihan untukmu</p><h2>{{ $title }}</h2></div>
      <a class="text-link" href="{{ route('catalog') }}">Lihat katalog <span aria-hidden="true">→</span></a>
    </div>
    <div class="row">@foreach($list as $f) @include('partials.card') @endforeach</div>
  </section>
  @endif
  @endforeach

  @if(!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->hasActiveSubscription()))
  <section class="subscription-panel landing-offer" id="mulai">
    <div class="subscription-panel__copy">
      <p class="eyebrow"><span class="eyebrow__line"></span> Siap mencari cerita favorit?</p>
      <h2>Satu kali bayar.<br>Akses katalog tanpa batas waktu.</h2>
      <p>Pembayaran diproses dengan Midtrans dan akses dibuka setelah pembayaran terkonfirmasi.</p>
    </div>
    <div class="subscription-panel__offer">
      <span class="subscription-panel__label">Akses Zeflix</span>
      <p class="subscription-panel__price">Rp{{ number_format(config('svod.price'), 0, ',', '.') }}<span> sekali bayar</span></p>
      @guest
      <a class="btn btn--primary" href="{{ route('register') }}">Buat akun sekarang <span aria-hidden="true">→</span></a>
      <p class="landing-offer__login">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
      @else
      <a class="btn btn--primary" href="{{ route('checkout.page') }}">Lanjutkan ke pembayaran <span aria-hidden="true">→</span></a>
      @endguest
    </div>
  </section>
  @else
  <section class="landing-member-cta">
    <div>
      <p class="eyebrow"><span class="eyebrow__line"></span> Lanjutkan ceritamu</p>
      <h2>Temukan tontonan berikutnya.</h2>
    </div>
    <a class="btn btn--primary" href="{{ route('catalog') }}">Jelajahi katalog <span aria-hidden="true">→</span></a>
  </section>
  @endif
</div>
@endsection
