@extends('layout')

@section('title', 'Menonton '.$film->title.' — Zeflix')

@section('content')
<section class="watch-page">
  <div class="watch-top-bar">
    <a class="back-link watch-back-btn" href="{{ route('film', $film) }}">
      <span aria-hidden="true">←</span> Kembali ke detail film
    </a>
    <div class="watch-title-meta">
      <span class="watch-title">{{ $film->title }}</span>
      <span class="watch-meta">{{ $film->genre }} · {{ $film->quality ?? '4K' }}</span>
    </div>
  </div>

  <div class="player" id="player-container">
    @if($film->video_provider === 'youtube')
      @php $ytId = $film->youtubeVideoId(); @endphp
      @if($ytId)
      <div id="yt-player-box" style="width:100%; height:100%;">
        <iframe id="yt-iframe"
                src="https://www.youtube-nocookie.com/embed/{{ $ytId }}?rel=0&modestbranding=1"
                allow="fullscreen; picture-in-picture"
                allowfullscreen
                title="{{ $film->title }}"
                style="width: 100%; height: 100%; border: 0;"></iframe>
      </div>
      @else
      <p class="note err" role="alert">ID atau URL video YouTube tidak valid. Perbarui data video melalui panel admin.</p>
      @endif
    @elseif(in_array($film->video_provider, ['mp4','r2']))
      <video id="native-video"
             controls
             autoplay
             controlsList="nodownload"
             playsinline
             preload="metadata"
             src="{{ $film->playbackUrl() }}"
             style="width: 100%; height: 100%;"></video>
    @else
      <video id="hls-video" controls autoplay playsinline style="width: 100%; height: 100%;"></video>
      <script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.17/dist/hls.min.js"></script>
      <script>
        const v = document.getElementById('hls-video'), s = @json($film->video_ref);
        if (v.canPlayType('application/vnd.apple.mpegurl')) {
          v.src = s;
        } else if (window.Hls && Hls.isSupported()) {
          const h = new Hls();
          h.loadSource(s);
          h.attachMedia(v);
        }
      </script>
    @endif
  </div>

  <div class="watch-footer-info">
    <div class="watch-heading">
      <p class="eyebrow"><span class="eyebrow__line"></span> Sedang Diputar</p>
      <h1>{{ $film->title }}</h1>
    </div>
    <div class="watch-badges">
      <span class="match-score">{{ $film->match_rate ?? 98 }}% Cocok</span>
      <span class="age-badge">{{ $film->age_rating ?? '16+' }}</span>
      <span class="quality-badge">{{ $film->quality ?? '4K' }}</span>
      @if($film->duration_minutes)
      <span>{{ $film->duration_minutes }} menit</span>
      @endif
    </div>
    <p class="watch-synopsis">{{ $film->synopsis }}</p>
  </div>
</section>

{{-- Progress Tracking Script --}}
<script>
(function() {
  const filmSlug = @json($film->slug);
  const progressUrl = @json(route('watch.progress', $film));
  const csrfToken = @json(csrf_token());
  const initialSeconds = {{ (int)($progress->seconds ?? 0) }};

  function sendProgress(percent, seconds, duration) {
    if (!progressUrl) return;
    try {
      fetch(progressUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          progress_percent: Math.round(percent),
          seconds: Math.round(seconds),
          duration: Math.round(duration)
        }),
        keepalive: true
      });
    } catch (e) {
      // quiet fail
    }
  }

  // 1. Native / HLS HTML5 Video
  const nativeVideo = document.getElementById('native-video') || document.getElementById('hls-video');
  if (nativeVideo) {
    let lastSent = 0;
    if (initialSeconds > 5) {
      nativeVideo.addEventListener('loadedmetadata', function() {
        nativeVideo.currentTime = initialSeconds;
      }, { once: true });
    }

    nativeVideo.addEventListener('timeupdate', function() {
      const now = Date.now();
      if (now - lastSent > 8000 && nativeVideo.duration > 0) {
        lastSent = now;
        const pct = (nativeVideo.currentTime / nativeVideo.duration) * 100;
        sendProgress(pct, nativeVideo.currentTime, nativeVideo.duration);
      }
    });

    nativeVideo.addEventListener('pause', function() {
      if (nativeVideo.duration > 0) {
        const pct = (nativeVideo.currentTime / nativeVideo.duration) * 100;
        sendProgress(pct, nativeVideo.currentTime, nativeVideo.duration);
      }
    });
  }

  // 2. YouTube IFrame Tracking via postMessage or periodic estimate
  let ytInterval = null;
  let simulatedSeconds = initialSeconds;
  const durationEst = {{ ($film->duration_minutes ?: 20) * 60 }};

  ytInterval = setInterval(function() {
    simulatedSeconds += 10;
    const pct = Math.min(95, (simulatedSeconds / durationEst) * 100);
    sendProgress(pct, simulatedSeconds, durationEst);
  }, 10000);

  window.addEventListener('beforeunload', function() {
    if (ytInterval) clearInterval(ytInterval);
    if (nativeVideo && nativeVideo.duration > 0) {
      const pct = (nativeVideo.currentTime / nativeVideo.duration) * 100;
      sendProgress(pct, nativeVideo.currentTime, nativeVideo.duration);
    }
  });
})();
</script>
@endsection
