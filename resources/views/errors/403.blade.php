@extends('layout')

@section('title', '403 — Akses Ditolak | Zeflix')

@section('content')
<section class="surface-card" style="max-width: 620px; margin: 3rem auto; text-align: center; padding: clamp(2rem, 5vw, 3.5rem);">
  <div style="width: 64px; height: 64px; margin: 0 auto 1.25rem; border-radius: 50%; background: rgba(229, 9, 20, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 900; color: var(--netflix-red); border: 1px solid rgba(229, 9, 20, 0.4);">
    403
  </div>
  <p class="eyebrow" style="justify-content: center; margin-bottom: .4rem;"><span class="eyebrow__line"></span> Keamanan &amp; Hak Akses</p>
  <h1 style="font-size: clamp(2rem, 4vw, 2.8rem); margin-bottom: .8rem;">Akses Ditolak</h1>
  <p style="font-size: .95rem; color: #ccc; max-width: 480px; margin: 0 auto 2rem; line-height: 1.7;">
    {{ $exception->getMessage() ?: 'Halaman ini dilindungi dan hanya dapat diakses oleh akun dengan izin administrator.' }}
  </p>
  <div style="display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap;">
    <a href="{{ route('home') }}" class="btn btn--primary">
      Kembali ke Beranda
    </a>
    @guest
      <a href="{{ route('login') }}" class="btn btn--outline">
        Masuk Akun Admin
      </a>
    @endguest
  </div>
</section>
@endsection
