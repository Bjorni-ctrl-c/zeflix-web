@extends('layout')

@section('title', 'Katalog film')

@section('content')
<section class="page-intro">
  <p class="eyebrow"><span class="eyebrow__line"></span> Temukan cerita berikutnya</p>
  <h1>Katalog film</h1>
  <p>Pilih film sesuai suasana hatimu. Cerita yang baik selalu punya waktu untukmu.</p>
</section>

@if($genres->count())
<nav class="filter-list" aria-label="Filter genre">
  <a class="filter-chip {{ request()->query('genre') ? '' : 'is-active' }}" href="{{ route('catalog') }}">Semua film</a>
  @foreach($genres as $g)
  <a class="filter-chip {{ request()->query('genre') === $g ? 'is-active' : '' }}" href="{{ route('catalog', ['genre' => $g]) }}">{{ $g }}</a>
  @endforeach
</nav>
@endif

<div class="catalog-grid">
  @forelse($films as $f)
  @include('partials.card')
  @empty
  <div class="empty-state"><span class="empty-state__mark" aria-hidden="true">ZF</span><h2>Belum ada film yang terbit.</h2><p>Coba genre lain atau kunjungi kembali nanti.</p></div>
  @endforelse
</div>
@endsection
