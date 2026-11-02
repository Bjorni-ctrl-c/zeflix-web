@php
$posterUrl = $f->posterImageUrl();
$isInWatchlist = in_array($f->id, $watchlistIds ?? []);
$matchRate = $f->match_rate ?? 95;
$ageRating = $f->age_rating ?? '13+';
$quality = $f->quality ?? 'HD';
@endphp
<div class="top10-item" data-film-slug="{{ $f->slug }}">
  <div class="top10-rank" aria-hidden="true">{{ $rank }}</div>
  <div class="film-card top10-card">
    <div class="film-card__poster" @if($posterUrl) style="background-image:url('{{ $posterUrl }}')" @endif>
      {{-- Full card clickable link --}}
      <a class="film-card__link" href="{{ route('film', $f) }}" aria-label="Lihat {{ $f->title }}"></a>

      @if(!$posterUrl)
        <span class="film-card__monogram" aria-hidden="true">ZF</span>
      @endif

      <span class="badge {{ $f->access === 'free' ? 'badge--free' : 'badge--premium' }}">
        {{ $f->access === 'free' ? 'Gratis' : 'Premium' }}
      </span>

      <div class="film-card__overlay">
        <div class="film-card__actions">
          <a class="film-card__btn film-card__btn--play" href="{{ route('watch', $f) }}" title="Putar Film" aria-label="Putar {{ $f->title }}">
            <span aria-hidden="true">▶</span>
          </a>

          @auth
          <button type="button" class="film-card__btn film-card__btn--list js-watchlist-toggle {{ $isInWatchlist ? 'is-active' : '' }}"
                  data-slug="{{ $f->slug }}"
                  title="{{ $isInWatchlist ? 'Hapus dari Daftar Saya' : 'Tambah ke Daftar Saya' }}"
                  aria-label="{{ $isInWatchlist ? 'Hapus dari Daftar Saya' : 'Tambah ke Daftar Saya' }}">
            <span aria-hidden="true">{{ $isInWatchlist ? '✓' : '＋' }}</span>
          </button>
          @endauth

          <button type="button" class="film-card__btn film-card__btn--info js-quick-view"
                  data-slug="{{ $f->slug }}"
                  title="Info Cepat"
                  aria-label="Info cepat {{ $f->title }}">
            <span aria-hidden="true">ℹ</span>
          </button>
        </div>
      </div>
    </div>

    <div class="film-card__info">
      <a class="film-card__title" href="{{ route('film', $f) }}">{{ $f->title }}</a>
      <div class="film-card__meta">
        <span class="match-score--inline">{{ $matchRate }}% Cocok</span>
        <span>{{ $f->genre }}</span>
      </div>
    </div>
  </div>
</div>
