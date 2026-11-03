@php
$posterUrl = $f->posterImageUrl();
$isInWatchlist = in_array($f->id, $watchlistIds ?? []);
$matchRate = $f->match_rate ?? 95;
$ageRating = $f->age_rating ?? '13+';
$quality = $f->quality ?? 'HD';
$canWatch = $f->canBeWatchedBy(auth()->user());
$progressPercent = isset($progress) ? $progress->progress_percent : ($f->watchProgresses->firstWhere('user_id', auth()->id())->progress_percent ?? null);
@endphp
<div class="film-card" data-film-slug="{{ $f->slug }}" data-film-id="{{ $f->id }}">
  <div class="film-card__poster" @if($posterUrl) style="background-image:url('{{ $posterUrl }}')" @endif>
    {{-- Full card clickable link --}}
    <a class="film-card__link" href="{{ route('film', $f) }}" aria-label="Lihat {{ $f->title }}"></a>

    @if(!$posterUrl)
      <span class="film-card__monogram" aria-hidden="true">ZF</span>
    @endif

    <span class="badge {{ $f->access === 'free' ? 'badge--free' : 'badge--premium' }}">
      {{ $f->access === 'free' ? 'Gratis' : 'Premium' }}
    </span>

    @if(!$canWatch)
    <div class="film-card__lock" title="Berlangganan untuk menonton">
      <span aria-hidden="true">🔒</span>
    </div>
    @endif

    {{-- Netflix Card Hover Quick Actions --}}
    <div class="film-card__overlay">
      <div class="film-card__actions">
        @if($canWatch)
        <a class="film-card__btn film-card__btn--play" href="{{ route('watch', $f) }}" title="Putar Film" aria-label="Putar {{ $f->title }}">
          <span aria-hidden="true">▶</span>
        </a>
        @else
        <a class="film-card__btn film-card__btn--play" href="{{ route('checkout.page') }}" title="Berlangganan" aria-label="Berlangganan untuk menonton {{ $f->title }}">
          <span aria-hidden="true">🔒</span>
        </a>
        @endif

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

      <div class="film-card__hover-meta">
        <span class="match-score">{{ $matchRate }}% Cocok</span>
        <span class="age-badge">{{ $ageRating }}</span>
        <span class="quality-badge">{{ $quality }}</span>
      </div>
    </div>

    @if($progressPercent !== null && $progressPercent > 0)
    <div class="film-card__progress-track" title="Ditonton {{ $progressPercent }}%">
      <div class="film-card__progress-bar" style="width: {{ $progressPercent }}%"></div>
    </div>
    @endif
  </div>

  <div class="film-card__info">
    <a class="film-card__title" href="{{ route('film', $f) }}">{{ $f->title }}</a>
    <div class="film-card__meta">
      <span class="match-score--inline">{{ $matchRate }}% Cocok</span>
      <span>{{ $f->genre }}</span>
      @if($f->duration_minutes)
      <span>{{ $f->duration_minutes }}m</span>
      @endif
    </div>
  </div>
</div>
