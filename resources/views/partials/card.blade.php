@php
$posterUrl = $f->posterImageUrl();
@endphp
<a class="film-card" href="{{ route('film', $f) }}">
  <div class="film-card__poster" @if($posterUrl) style="background-image:url('{{ $posterUrl }}')" @endif>
    @if(!$posterUrl)<span class="film-card__monogram" aria-hidden="true">ZF</span>@endif
    <span class="badge {{ $f->access === 'free' ? 'badge--free' : 'badge--premium' }}">{{ $f->access === 'free' ? 'Gratis' : 'Premium' }}</span>
    <span class="film-card__play" aria-hidden="true">▶</span>
  </div>
  <span class="film-card__title">{{ $f->title }}</span>
  <span class="film-card__meta">{{ $f->genre }}{{ $f->genre && $f->duration_minutes ? ' · ' : '' }}{{ $f->duration_minutes ? $f->duration_minutes.' menit' : '' }}</span>
</a>
