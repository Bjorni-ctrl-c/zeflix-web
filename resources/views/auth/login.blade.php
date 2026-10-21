@extends('layout')

@section('title', 'Masuk')

@section('content')
<section class="auth-layout">
  <div class="auth-aside">
    <p class="eyebrow"><span class="eyebrow__line"></span> Selamat datang kembali</p>
    <h1>Ceritamu<br>menunggu.</h1>
    <p>Masuk untuk melanjutkan ke film-film pilihanmu.</p>
    <span class="auth-aside__monogram" aria-hidden="true">ZF</span>
  </div>
  <div class="auth-card">
    <p class="eyebrow"><span class="eyebrow__line"></span> Akun Zeflix</p>
    <h2>Masuk</h2>
    <p class="auth-card__intro">Gunakan email dan password akunmu.</p>
    <form class="form-stack" method="post" action="{{ route('login') }}">@csrf
      <label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus></label>
      <label>Password<input type="password" name="password" autocomplete="current-password" required></label>
      <label class="check-label"><input type="checkbox" name="remember"> <span>Ingat saya</span></label>
      <button class="btn btn--primary btn--wide">Masuk</button>
      <p class="form-footnote">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
    </form>
  </div>
</section>
@endsection
