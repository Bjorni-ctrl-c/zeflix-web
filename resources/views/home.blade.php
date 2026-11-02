@extends('layout')

@section('title', 'Zeflix — Nonton Film Pendek Terbaik')

@section('hero')
@if($featured && $canBrowseCatalog)
@php
$heroPoster = $featured->posterImageUrl();
$isInWatchlist = in_array($featured->id, $watchlistIds ?? []);
@endphp
<section class="netflix-billboard" @if($heroPoster) style="--billboard-bg: url('{{ $heroPoster }}')" @endif>
  <div class="netflix-billboard__overlay"></div>
  <div class="netflix-billboard__vignette"></div>
  <div class="netflix-billboard__inner">
    <div class="netflix-billboard__content">
      <div class="billboard-badge">
        <span class="billboard-badge__n">Z</span>
        <span class="billboard-badge__text">FILM PILIHAN ZEFLIX</span>
      </div>

      <h1 class="netflix-billboard__title">{{ $featured->title }}</h1>

      <div class="netflix-billboard__meta">
        <span class="match-score font-bold">{{ $featured->match_rate ?? 98 }}% Cocok</span>
        <span class="age-badge">{{ $featured->age_rating ?? '16+' }}</span>
        <span class="quality-badge">{{ $featured->quality ?? '4K UHD' }}</span>
        @if($featured->duration_minutes)
        <span class="duration">{{ $featured->duration_minutes }} menit</span>
        @endif
        <span class="badge {{ $featured->access === 'free' ? 'badge--free' : 'badge--premium' }}">
          {{ $featured->access === 'free' ? 'Gratis' : 'Premium' }}
        </span>
      </div>

      <p class="netflix-billboard__synopsis">{{ $featured->synopsis }}</p>

      <div class="netflix-billboard__actions">
        <a class="btn btn--netflix-play" href="{{ route('watch', $featured) }}">
          <span aria-hidden="true">▶</span> Putar
        </a>

        <button type="button" class="btn btn--netflix-secondary js-quick-view" data-slug="{{ $featured->slug }}">
          <span aria-hidden="true">ℹ</span> Info Selengkapnya
        </button>

        @auth
        <button type="button" class="btn btn--netflix-secondary js-watchlist-toggle {{ $isInWatchlist ? 'is-active' : '' }}"
                data-slug="{{ $featured->slug }}"
                title="{{ $isInWatchlist ? 'Hapus dari Daftar Saya' : 'Tambah ke Daftar Saya' }}">
          <span aria-hidden="true">{{ $isInWatchlist ? '✓' : '＋' }}</span> Daftar Saya
        </button>
        @endauth
      </div>
    </div>
  </div>
</section>
@else
{{-- Landing Hero for Guests / Pre-subscription --}}
<section class="hero hero--home hero--landing">
  <div class="hero__inner">
    <div class="hero__copy">
      <p class="eyebrow"><span class="eyebrow__line"></span> Bioskop cerita pilihanmu</p>
      <span class="hero__kicker">Film pendek <span aria-hidden="true">·</span> Cerita besar</span>
      <h1>Cerita pilihan.<br>Sekali bayar.</h1>
      <p class="hero__description">Temukan film pilihan dan nikmati akses katalog dengan satu kali pembayaran <strong>Rp{{ number_format(config('svod.price'), 0, ',', '.') }}</strong>. Tidak ada tagihan bulanan.</p>
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
@endif
@endsection

@section('wrapclass', $canBrowseCatalog ? 'wrap--browse' : 'wrap--home')

@section('content')
@if($canBrowseCatalog)
<div class="netflix-rows">
  {{-- 1. Lanjutkan Menonton (Continue Watching) --}}
  @if($continueWatching->isNotEmpty())
  <section class="rowbox">
    <div class="section-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Kembali Ke Cerita</p>
        <h2>Lanjutkan Menonton</h2>
      </div>
    </div>
    <div class="netflix-row-wrap">
      <button class="slider-btn slider-btn--left js-slide-left" aria-label="Geser ke kiri" type="button">‹</button>
      <div class="row js-slider-row">
        @foreach($continueWatching as $progress)
          @php $f = $progress->film; @endphp
          @include('partials.card', ['f' => $f, 'progress' => $progress])
        @endforeach
      </div>
      <button class="slider-btn slider-btn--right js-slide-right" aria-label="Geser ke kanan" type="button">›</button>
    </div>
  </section>
  @endif

  {{-- 2. Top 10 Hari Ini --}}
  @if($top10->isNotEmpty())
  <section class="rowbox">
    <div class="section-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Paling Banyak Ditonton</p>
        <h2>Top 10 Film Hari Ini di Zeflix</h2>
      </div>
    </div>
    <div class="netflix-row-wrap">
      <button class="slider-btn slider-btn--left js-slide-left" aria-label="Geser ke kiri" type="button">‹</button>
      <div class="row row--top10 js-slider-row">
        @foreach($top10 as $index => $f)
          @include('partials.card-top10', ['f' => $f, 'rank' => $index + 1])
        @endforeach
      </div>
      <button class="slider-btn slider-btn--right js-slide-right" aria-label="Geser ke kanan" type="button">›</button>
    </div>
  </section>
  @endif

  {{-- 3. Daftar Saya (Watchlist) --}}
  @if($watchlistFilms->isNotEmpty())
  <section class="rowbox">
    <div class="section-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Disimpan Oleh Kamu</p>
        <h2>Daftar Saya</h2>
      </div>
      <a class="text-link" href="{{ route('watchlist') }}">Lihat semua <span aria-hidden="true">→</span></a>
    </div>
    <div class="netflix-row-wrap">
      <button class="slider-btn slider-btn--left js-slide-left" aria-label="Geser ke kiri" type="button">‹</button>
      <div class="row js-slider-row">
        @foreach($watchlistFilms as $f)
          @include('partials.card', ['f' => $f])
        @endforeach
      </div>
      <button class="slider-btn slider-btn--right js-slide-right" aria-label="Geser ke kanan" type="button">›</button>
    </div>
  </section>
  @endif

  {{-- 4. Trending Sekarang --}}
  @if($trending->isNotEmpty())
  <section class="rowbox">
    <div class="section-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Hangat Diperbincangkan</p>
        <h2>Trending Sekarang</h2>
      </div>
      <a class="text-link" href="{{ route('catalog') }}">Semua katalog <span aria-hidden="true">→</span></a>
    </div>
    <div class="netflix-row-wrap">
      <button class="slider-btn slider-btn--left js-slide-left" aria-label="Geser ke kiri" type="button">‹</button>
      <div class="row js-slider-row">
        @foreach($trending as $f)
          @include('partials.card', ['f' => $f])
        @endforeach
      </div>
      <button class="slider-btn slider-btn--right js-slide-right" aria-label="Geser ke kanan" type="button">›</button>
    </div>
  </section>
  @endif

  {{-- 5. Per Kategori Genre --}}
  @foreach($genresWithFilms as $genreName => $genreFilms)
  <section class="rowbox">
    <div class="section-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Koleksi Genre</p>
        <h2>Film {{ $genreName }}</h2>
      </div>
      <a class="text-link" href="{{ route('catalog', ['genre' => $genreName]) }}">Lihat genre {{ $genreName }} <span aria-hidden="true">→</span></a>
    </div>
    <div class="netflix-row-wrap">
      <button class="slider-btn slider-btn--left js-slide-left" aria-label="Geser ke kiri" type="button">‹</button>
      <div class="row js-slider-row">
        @foreach($genreFilms as $f)
          @include('partials.card', ['f' => $f])
        @endforeach
      </div>
      <button class="slider-btn slider-btn--right js-slide-right" aria-label="Geser ke kanan" type="button">›</button>
    </div>
  </section>
  @endforeach
</div>
@endif

{{-- Information & Features Section (Consistent for both Guests and Members) --}}
<div class="home-content" style="{{ $canBrowseCatalog ? 'margin-top: 4.5rem; border-top: 1px solid var(--color-border); padding-top: 2rem;' : '' }}">
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
        <p>Temukan pilihan film dan persempit pencarian berdasarkan genre atau kata kunci.</p>
      </article>
      <article class="landing-feature">
        <span class="landing-feature__index" aria-hidden="true">02</span>
        <h3>Daftar Saya &amp; Riwayat Tontonan</h3>
        <p>Simpan film favorit dan lanjutkan tontonan dari durasi terakhir secara otomatis.</p>
      </article>
      <article class="landing-feature">
        <span class="landing-feature__index" aria-hidden="true">03</span>
        <h3>Tonton langsung di web</h3>
        <p>Pemutar film kualitas tinggi 4K UHD langsung di browsermu tanpa aplikasi tambahan.</p>
      </article>
      <article class="landing-feature">
        <span class="landing-feature__index" aria-hidden="true">04</span>
        <h3>Akun dan transaksi tersimpan</h3>
        <p>Cek status akses dan riwayat pembayaran, atau perbarui informasi akunmu kapan saja.</p>
      </article>
    </div>
  </section>

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
      <a class="btn btn--primary" href="{{ route('register') }}">Buat akun &amp; mulai <span aria-hidden="true">→</span></a>
      <p class="landing-offer__login">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
      @else
      <a class="btn btn--primary" href="{{ route('checkout.page') }}">Lanjutkan ke pembayaran <span aria-hidden="true">→</span></a>
      @endguest
    </div>
  </section>
  @else
  <section class="landing-member-cta" style="margin-top: 3rem; border: 1px solid rgba(229, 9, 20, 0.35); border-radius: var(--radius-md); padding: 2rem; background: var(--color-surface); display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;">
    <div>
      <p class="eyebrow"><span class="eyebrow__line"></span> Lanjutkan ceritamu</p>
      <h2>Temukan tontonan berikutnya.</h2>
    </div>
    <a class="btn btn--primary" href="{{ route('catalog') }}">Jelajahi katalog <span aria-hidden="true">→</span></a>
  </section>
  @endif
</div>
@endsection
