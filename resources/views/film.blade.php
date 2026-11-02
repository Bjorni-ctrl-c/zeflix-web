@extends('layout')

@section('title', $film->title.' — Zeflix')

@section('hero')
@php
$posterUrl = $film->posterImageUrl();
$matchRate = $film->match_rate ?? 98;
$ageRating = $film->age_rating ?? '16+';
$quality = $film->quality ?? '4K UHD';
@endphp
<section class="film-hero" @if($posterUrl) style="--hero-image:url('{{ $posterUrl }}')" @endif>
  <div class="film-hero__inner">
    <a class="back-link" href="{{ route('catalog') }}"><span aria-hidden="true">←</span> Kembali ke katalog</a>
    <div class="film-hero__content">
      <div class="billboard-badge">
        <span class="billboard-badge__n">Z</span>
        <span class="billboard-badge__text">FILM PILIHAN ZEFLIX</span>
      </div>

      <h1>{{ $film->title }}</h1>

      <div class="film-meta">
        <span class="match-score font-bold">{{ $matchRate }}% Cocok</span>
        <span class="age-badge">{{ $ageRating }}</span>
        <span class="quality-badge">{{ $quality }}</span>
        @if($film->duration_minutes)
          <span>{{ $film->duration_minutes }} menit</span>
        @endif
        @if($film->genre)
          <span class="film-meta__genre">{{ $film->genre }}</span>
        @endif
        <span class="badge {{ $film->access === 'free' ? 'badge--free' : 'badge--premium' }}">
          {{ $film->access === 'free' ? 'Gratis' : 'Premium' }}
        </span>
      </div>

      <p class="film-hero__synopsis">{{ $film->synopsis }}</p>

      <div class="film-hero__actions">
        @if($can)
          <a class="btn btn--netflix-play" href="{{ route('watch', $film) }}">
            <span aria-hidden="true">▶</span>
            @if($progress && $progress->progress_percent > 0 && $progress->progress_percent < 95)
              Lanjutkan Menonton ({{ $progress->progress_percent }}%)
            @else
              Putar Sekarang
            @endif
          </a>
        @elseif(auth()->check())
          <a class="btn btn--netflix-play" href="{{ route('checkout.page') }}">
            <span aria-hidden="true">🔓</span> Buka Akses Rp{{ number_format(config('svod.price'), 0, ',', '.') }}
          </a>
        @else
          <a class="btn btn--netflix-play" href="{{ route('login') }}">
            Masuk untuk menonton
          </a>
          <a class="btn btn--outline" href="{{ route('register') }}">
            Daftar Akun
          </a>
        @endif

        @auth
        <button type="button" class="btn btn--netflix-secondary js-watchlist-toggle {{ $inWatchlist ? 'is-active' : '' }}"
                data-slug="{{ $film->slug }}"
                title="{{ $inWatchlist ? 'Hapus dari Daftar Saya' : 'Tambah ke Daftar Saya' }}">
          <span aria-hidden="true">{{ $inWatchlist ? '✓' : '＋' }}</span>
          {{ $inWatchlist ? 'Ada di Daftar Saya' : 'Tambah ke Daftar Saya' }}
        </button>
        @endauth
      </div>

      @if(!$can)
      <p class="film-hero__notice">Akses sekali bayar seumur hidup hanya Rp{{ number_format(config('svod.price'), 0, ',', '.') }}. Tanpa biaya langganan bulanan.</p>
      @endif
    </div>
  </div>
</section>
@endsection

@section('wrapclass', 'wrap--flush')

@section('content')
<div class="film-details-container">
  {{-- Detail Information Section --}}
  <div class="film-specs-grid">
    <div class="spec-item">
      <span class="spec-label">Genre</span>
      <span class="spec-value">{{ $film->genre ?? 'Umum' }}</span>
    </div>
    <div class="spec-item">
      <span class="spec-label">Kualitas Gambar</span>
      <span class="spec-value">{{ $film->quality ?? '4K Ultra HD' }}</span>
    </div>
    <div class="spec-item">
      <span class="spec-label">Format Audio</span>
      <span class="spec-value">Dolby Atmos / Stereo 5.1</span>
    </div>
    <div class="spec-item">
      <span class="spec-label">Klasifikasi Usia</span>
      <span class="spec-value">{{ $film->age_rating ?? '13+' }} — Disarankan dengan bimbingan</span>
    </div>
  </div>

  {{-- More Like This (Film Serupa) --}}
  @if(isset($similar) && $similar->count())
  <section class="rowbox" style="margin-top: 3.5rem;">
    <div class="section-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Rekomendasi</p>
        <h2>Film Serupa</h2>
      </div>
    </div>
    <div class="catalog-grid catalog-grid--similar">
      @foreach($similar as $f)
        @include('partials.card', ['f' => $f])
      @endforeach
    </div>
  </section>
  @endif
</div>
@endsection
