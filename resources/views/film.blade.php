@extends('layout')

@section('title', $film->title)

@section('hero')
@php
$posterUrl = $film->posterImageUrl();
@endphp
<section class="film-hero" @if($posterUrl) style="--hero-image:url('{{ $posterUrl }}')" @endif>
  <div class="film-hero__inner">
    <a class="back-link" href="{{ route('catalog') }}"><span aria-hidden="true">←</span> Kembali ke katalog</a>
    <div class="film-hero__content">
      <p class="eyebrow"><span class="eyebrow__line"></span> Pilihan Zeflix</p>
      <h1>{{ $film->title }}</h1>
      <div class="film-meta">
        <span class="badge {{ $film->access === 'free' ? 'badge--free' : 'badge--premium' }}">{{ $film->access === 'free' ? 'Gratis' : 'Premium' }}</span>
        @if($film->genre)<span>{{ $film->genre }}</span>@endif
        @if($film->duration_minutes)<span>{{ $film->duration_minutes }} menit</span>@endif
      </div>
      <p class="film-hero__synopsis">{{ $film->synopsis }}</p>
      <div class="film-hero__actions">
        @if($can)
        <a class="btn btn--primary" href="{{ route('watch', $film) }}"><span aria-hidden="true">▶</span> Tonton sekarang</a>
        @elseif(auth()->check())
        <form method="post" action="{{ route('checkout.start') }}">@csrf
          <a class="btn btn--primary" href="{{ route('checkout.page') }}">Buka akses Rp{{ number_format(config('svod.price'), 0, ',', '.') }}</a>
        </form>
        @else
        <a class="btn btn--primary" href="{{ route('login') }}">Masuk untuk menonton</a>
        <a class="btn btn--outline" href="{{ route('register') }}">Buat akun</a>
        @endif
      </div>
      @if(!$can)<p class="film-hero__notice">Bayar satu kali untuk membuka akses penuh ke seluruh katalog.</p>@endif
    </div>
  </div>
</section>
@endsection

@section('wrapclass', 'wrap--flush')
@section('content')@endsection
