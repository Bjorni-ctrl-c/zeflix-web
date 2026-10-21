@extends('layout')

@section('title', 'Selamat datang di '.config('app.name'))

@section('content')
<section class="status-page">
  <p class="eyebrow"><span class="eyebrow__line"></span> Selamat datang</p>
  <h1>Temukan film pilihan.</h1>
  <p>Jelajahi katalog Zeflix dan pilih cerita yang ingin kamu tonton.</p>
  <a class="btn btn--primary" href="{{ route('catalog') }}">Jelajahi katalog <span aria-hidden="true">→</span></a>
</section>
@endsection
