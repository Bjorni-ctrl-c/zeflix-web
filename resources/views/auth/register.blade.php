@extends('layout')

@section('title', 'Daftar akun')

@section('content')
<section class="auth-layout">
  <div class="auth-aside">
    <p class="eyebrow"><span class="eyebrow__line"></span> Cerita pilihan menantimu</p>
    <h1>Temukan<br>tontonanmu.</h1>
    <p>Buat akun Zeflix dan mulai jelajahi film pilihan.</p>
    <span class="auth-aside__monogram" aria-hidden="true">ZF</span>
  </div>
  <div class="auth-card">
    <p class="eyebrow"><span class="eyebrow__line"></span> Mulai di sini</p>
    <h2>Buat akun</h2>
    <p class="auth-card__intro">Daftar untuk menikmati film-film pilihan Zeflix.</p>
    <form class="form-stack" method="post" action="{{ route('register') }}">@csrf
      <label>Nama<input name="name" value="{{ old('name') }}" autocomplete="name" required autofocus></label>
      <label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></label>
      <label>Password <span class="field-hint">Minimal 8 karakter</span><input type="password" name="password" autocomplete="new-password" required></label>
      <label>Ulangi password<input type="password" name="password_confirmation" autocomplete="new-password" required></label>
      <button class="btn btn--primary btn--wide">Daftar</button>
      <p class="form-footnote">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
    </form>
  </div>
</section>
@endsection
