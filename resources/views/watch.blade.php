@extends('layout')

@section('title', $film->title.' — Zeflix')

@section('content')
<section class="watch-page">
  <a class="back-link" href="{{ route('film', $film) }}"><span aria-hidden="true">←</span> Kembali ke detail film</a>
  <div class="watch-heading"><p class="eyebrow"><span class="eyebrow__line"></span> Sedang diputar</p><h1>{{ $film->title }}</h1></div>
  <div class="player">
    @if($film->video_provider === 'youtube')
    <iframe src="https://www.youtube-nocookie.com/embed/{{ e($film->video_ref) }}?rel=0&modestbranding=1" allow="fullscreen; picture-in-picture" allowfullscreen title="{{ $film->title }}"></iframe>
    @elseif(in_array($film->video_provider, ['mp4','r2']))
    <video controls controlsList="nodownload" playsinline preload="metadata" src="{{ $film->playbackUrl() }}"></video>
    @else
    <video id="v" controls playsinline></video>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.17/dist/hls.min.js"></script>
    <script>
      const v=document.getElementById('v'),s=@json($film->video_ref);
      if(v.canPlayType('application/vnd.apple.mpegurl')){v.src=s}else if(window.Hls&&Hls.isSupported()){const h=new Hls();h.loadSource(s);h.attachMedia(v)}
    </script>
    @endif
  </div>
  <p class="watch-synopsis">{{ $film->synopsis }}</p>
</section>
@endsection
