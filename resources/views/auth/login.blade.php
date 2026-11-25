@extends('layout')

@section('title', 'Masuk — Zeflix')

@section('content')
<section class="auth-layout">
  <div class="auth-aside">
    <p class="eyebrow"><span class="eyebrow__line"></span> Selamat datang kembali</p>
    <h1>Ceritamu<br>menunggu.</h1>
    <p>Masuk untuk melanjutkan menonton film pendek dan daftar tontonanmu.</p>
    <span class="auth-aside__monogram" aria-hidden="true">ZF</span>
  </div>
  <div class="auth-card">
    <p class="eyebrow"><span class="eyebrow__line"></span> Akun Zeflix</p>
    <h2>Masuk</h2>
    <p class="auth-card__intro">Gunakan email terdaftar dan password akun Anda.</p>

    <form class="form-stack" method="post" action="{{ route('login') }}" id="login-form" novalidate>
      @csrf

      <label for="login-email">
        <span>Email Terdaftar <span class="required-mark">*</span></span>
        <input type="email"
               id="login-email"
               name="email"
               value="{{ old('email') }}"
               placeholder="contoh: pengguna@email.com"
               autocomplete="email"
               required
               autofocus>
        <span class="field-hint">Gunakan format email lengkap (contoh: nama@email.com)</span>
        @error('email')
          <span class="field-error" style="color: var(--netflix-red); font-size: .8rem; font-weight: 600;">{{ $message }}</span>
        @enderror
        <span id="login-email-client-hint" style="display: none; color: var(--color-warning); font-size: .78rem;"></span>
      </label>

      <label for="login-password">
        <span>Password <span class="required-mark">*</span></span>
        <div class="password-wrap">
          <input type="password"
                 id="login-password"
                 name="password"
                 placeholder="Masukkan password Anda"
                 autocomplete="current-password"
                 required>
          <button type="button" class="pw-toggle" aria-label="Tampilkan password">&#9679;</button>
        </div>
        @error('password')
          <span class="field-error" style="color: var(--netflix-red); font-size: .8rem; font-weight: 600;">{{ $message }}</span>
        @enderror
      </label>

      <label class="check-label">
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <span>Ingat saya di perangkat ini</span>
      </label>

      <button type="submit" class="btn btn--primary btn--wide" id="btn-login-submit">
        Masuk ke Zeflix
      </button>

      <p class="form-footnote">
        Belum memiliki akun? <a href="{{ route('register') }}">Daftar sekarang</a>
      </p>
    </form>
  </div>
</section>

<script>
  (function() {
    const emailInput = document.getElementById('login-email');
    const hintEl = document.getElementById('login-email-client-hint');

    if (emailInput && hintEl) {
      emailInput.addEventListener('input', function() {
        const val = this.value.trim();
        if (val.length > 0 && !val.includes('@')) {
          hintEl.textContent = 'Alamat email wajib menyertakan simbol @';
          hintEl.style.display = 'block';
        } else if (val.includes('@') && !val.includes('.')) {
          hintEl.textContent = 'Sertakan domain lengkap, contoh: .com atau .id';
          hintEl.style.display = 'block';
        } else {
          hintEl.style.display = 'none';
        }
      });
    }
  })();
</script>
@endsection
