@extends('layout')

@section('title', 'Zeflix — Film pendek, cerita besar')

@section('hero')
@php
$featuredPoster = $featured?->posterImageUrl();
@endphp
<section class="hero hero--home" @if($featuredPoster) style="--hero-image:url('{{ $featuredPoster }}')" @endif>
  <div class="hero__inner">
    <div class="hero__copy">
      <p class="eyebrow"><span class="eyebrow__line"></span> Cerita pilihan, dalam genggaman</p>
      @if($featured)
      <span class="hero__kicker">Pilihan Zeflix <span class="badge {{ $featured->access === 'free' ? 'badge--free' : 'badge--premium' }}">{{ $featured->access === 'free' ? 'Gratis' : 'Premium' }}</span></span>
      <h1>{{ $featured->title }}</h1>
      <p class="hero__meta">{{ $featured->genre }}{{ $featured->genre && $featured->duration_minutes ? ' · ' : '' }}{{ $featured->duration_minutes ? $featured->duration_minutes.' menit' : '' }}</p>
      <p class="hero__description">{{ $featured->synopsis }}</p>
      <div class="hero__actions">
        <a class="btn btn--light" href="{{ route('film', $featured) }}"><span aria-hidden="true">▶</span> Lihat film</a>
        <a class="btn btn--outline" href="{{ route('catalog') }}">Jelajahi katalog</a>
      </div>
      @else
      <span class="hero__kicker">Segera hadir di Zeflix</span>
      <h1>Film pendek,<br>cerita besar.</h1>
      <p class="hero__description">Temukan cerita pilihan untuk menemani waktu luangmu. Katalog sedang disiapkan.</p>
      <div class="hero__actions"><a class="btn btn--light" href="{{ route('catalog') }}">Jelajahi katalog</a></div>
      @endif
    </div>
    <div class="hero__poster" @if($featuredPoster) style="background-image:url('{{ $featuredPoster }}')" role="img" aria-label="Poster film {{ $featured->title }}" @else aria-hidden="true" @endif>@if(!$featuredPoster)<span class="hero__poster-mark">ZF</span>@endif</div>
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

  <section class="subscription-panel">
    <div class="subscription-panel__copy">
      <p class="eyebrow"><span class="eyebrow__line"></span> Satu langganan, cerita pilihan</p>
      <h2>Nikmati film Premium.<br>Tanpa perpanjangan otomatis.</h2>
      <p>Tonton semua film Premium selama langganan aktif. Pembayaran dilakukan secara manual.</p>
    </div>
    <div class="subscription-panel__offer">
      <span class="subscription-panel__label">Zeflix Premium</span>
      <p class="subscription-panel__price">Rp{{ number_format(config('svod.price'), 0, ',', '.') }}<span> / bulan</span></p>
      <a class="btn btn--primary" href="{{ auth()->check() ? route('account') : route('register') }}">Mulai berlangganan <span aria-hidden="true">→</span></a>
    </div>
  </section>
</div>
@endsection
