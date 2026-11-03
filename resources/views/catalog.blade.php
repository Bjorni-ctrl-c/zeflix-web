@extends('layout')

@section('title', 'Katalog Film — Zeflix')

@section('content')
<section class="page-intro page-intro--split">
  <div>
    <p class="eyebrow"><span class="eyebrow__line"></span> Jelajahi Film</p>
    <h1>Katalog Film</h1>
    <p>Film pendek, animasi, dokumenter, dan fiksi ilmiah karya sineas independen.</p>
  </div>

  {{-- In-catalog Search and Sort Form --}}
  <form method="get" action="{{ route('catalog') }}" class="catalog-search-form">
    @if(request('genre'))
      <input type="hidden" name="genre" value="{{ request('genre') }}">
    @endif
    <div class="search-input-wrap">
      <span class="search-icon" aria-hidden="true"></span>
      <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari film..." autocomplete="off">
      @if($searchQuery)
      <a href="{{ route('catalog', request()->except('q')) }}" class="search-clear-btn" title="Hapus pencarian">✕</a>
      @endif
    </div>
  </form>
</section>

{{-- Filters and Sort Bar --}}
<div class="catalog-controls">
  @if($genres->count())
  <nav class="filter-list" aria-label="Filter genre">
    <a class="filter-chip {{ request()->query('genre') ? '' : 'is-active' }}" href="{{ route('catalog', array_merge(request()->except('genre'), [])) }}">Semua Genre</a>
    @foreach($genres as $g)
    <a class="filter-chip {{ request()->query('genre') === $g ? 'is-active' : '' }}" href="{{ route('catalog', array_merge(request()->except('genre'), ['genre' => $g])) }}">{{ $g }}</a>
    @endforeach
  </nav>
  @endif

  <div class="sort-selector">
    <label for="catalog-sort" class="sr-only">Urutkan</label>
    <select id="catalog-sort" onchange="location = this.value">
      <option value="{{ route('catalog', array_merge(request()->query(), ['sort' => 'latest'])) }}" {{ $currentSort === 'latest' ? 'selected' : '' }}>Film Terbaru</option>
      <option value="{{ route('catalog', array_merge(request()->query(), ['sort' => 'popular'])) }}" {{ $currentSort === 'popular' ? 'selected' : '' }}>Paling Populer</option>
      <option value="{{ route('catalog', array_merge(request()->query(), ['sort' => 'match'])) }}" {{ $currentSort === 'match' ? 'selected' : '' }}>Kecocokan Tertinggi</option>
    </select>
  </div>
</div>

{{-- Results info --}}
<div class="catalog-meta-bar">
  <span>Menampilkan <strong>{{ $films->count() }}</strong> judul</span>
  @if($searchQuery)
    <span>untuk pencarian <em>"{{ $searchQuery }}"</em></span>
  @endif
  @if($currentGenre)
    <span class="catalog-genre-tag">Genre: {{ $currentGenre }}</span>
  @endif
</div>

<div class="catalog-grid">
  @forelse($films as $f)
    @include('partials.card')
  @empty
  <div class="empty-state">
    <span class="empty-state__mark" aria-hidden="true">ZF</span>
    <h2>Tidak ada film yang cocok.</h2>
    <p>Coba kata kunci pencarian lain atau pilih filter genre yang berbeda.</p>
    <div style="margin-top: 1rem;">
      <a class="btn btn--outline" href="{{ route('catalog') }}">Reset Filter</a>
    </div>
  </div>
  @endforelse
</div>
@endsection
