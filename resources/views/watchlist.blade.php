@extends('layout')

@section('title', 'Daftar Saya — Zeflix')

@section('content')
<section class="page-intro page-intro--split">
  <div>
    <p class="eyebrow"><span class="eyebrow__line"></span> Koleksi Pribadi</p>
    <h1>Daftar Saya</h1>
    <p>Film yang kamu simpan untuk ditonton nanti. Siap diputar kapan saja.</p>
  </div>
  <a class="btn btn--outline" href="{{ route('catalog') }}"><span aria-hidden="true">＋</span> Jelajahi Film Lain</a>
</section>

@if($films->count())
<div class="catalog-grid">
  @foreach($films as $f)
    @include('partials.card')
  @endforeach
</div>
@else
<div class="empty-state">
  <span class="empty-state__mark" aria-hidden="true">✚</span>
  <h2>Daftar Saya masih kosong</h2>
  <p>Kamu belum menambahkan film ke daftar tontonan. Klik tombol <strong>+ Daftar Saya</strong> pada kartu film untuk menyimpannya di sini.</p>
  <div style="margin-top: 1.5rem;">
    <a class="btn btn--primary" href="{{ route('catalog') }}">Jelajahi Katalog Film</a>
  </div>
</div>
@endif
@endsection
