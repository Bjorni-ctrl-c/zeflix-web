@extends('layout')

@section('title', 'Daftar Akun — Zeflix')

@section('content')
<section class="auth-layout">
  <div class="auth-aside">
    <p class="eyebrow"><span class="eyebrow__line"></span> Cerita pilihan menantimu</p>
    <h1>Temukan<br>tontonanmu.</h1>
    <p>Buat akun Zeflix dan mulai jelajahi film pendek terbaik karya sineas berbakat.</p>
    <span class="auth-aside__monogram" aria-hidden="true">ZF</span>
  </div>
  <div class="auth-card">
    <p class="eyebrow"><span class="eyebrow__line"></span> Mulai di sini</p>
    <h2>Buat Akun</h2>
    <p class="auth-card__intro">Daftar untuk menikmati akses film-film pilihan Zeflix.</p>

    <form class="form-stack" method="post" action="{{ route('register') }}" id="register-form" novalidate>
      @csrf

      {{-- Field Nama --}}
      <label for="reg-name">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span>Nama Lengkap <span class="required-mark">*</span></span>
          <span id="name-count-indicator" style="font-size: .72rem; color: #888;">0 / 50</span>
        </div>
        <input type="text"
               id="reg-name"
               name="name"
               value="{{ old('name') }}"
               maxlength="50"
               minlength="3"
               placeholder="contoh: Budi Pratama"
               autocomplete="name"
               required
               autofocus>
        <span class="field-hint">Maksimal 50 karakter (hanya huruf, spasi, dan tanda baca umum)</span>
        @error('name')
          <span class="field-error" style="color: var(--netflix-red); font-size: .8rem; font-weight: 600;">{{ $message }}</span>
        @enderror
        <span id="name-client-warning" style="display: none; color: var(--color-warning); font-size: .78rem;"></span>
      </label>

      {{-- Field Email --}}
      <label for="reg-email">
        <span>Alamat Email <span class="required-mark">*</span></span>
        <input type="email"
               id="reg-email"
               name="email"
               value="{{ old('email') }}"
               placeholder="contoh: budi@gmail.com"
               autocomplete="email"
               required>
        <span class="field-hint">Format lengkap dan domain aktif (contoh: nama@email.com)</span>
        @error('email')
          <span class="field-error" style="color: var(--netflix-red); font-size: .8rem; font-weight: 600;">{{ $message }}</span>
        @enderror
        <span id="email-client-hint" style="display: none; color: var(--color-warning); font-size: .78rem;"></span>
      </label>

      {{-- Field Password --}}
      <label for="reg-password">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span>Password <span class="required-mark">*</span></span>
          <span id="pw-length-indicator" style="font-size: .72rem; color: #888;">Min. 8 karakter</span>
        </div>
        <div class="password-wrap">
          <input type="password"
                 id="reg-password"
                 name="password"
                 minlength="8"
                 placeholder="Minimal 8 karakter kombinasi"
                 autocomplete="new-password"
                 required>
          <button type="button" class="pw-toggle" aria-label="Tampilkan password">&#9679;</button>
        </div>
        <span class="field-hint">Gunakan minimal 8 karakter agar akun tetap aman</span>
        @error('password')
          <span class="field-error" style="color: var(--netflix-red); font-size: .8rem; font-weight: 600;">{{ $message }}</span>
        @enderror
      </label>

      {{-- Field Konfirmasi Password --}}
      <label for="reg-password-confirm">
        <div style="display: flex; justify-content: space-between; align-items: center;">
          <span>Ulangi Password <span class="required-mark">*</span></span>
          <span id="pw-match-indicator" style="font-size: .72rem; color: #888;"></span>
        </div>
        <div class="password-wrap">
          <input type="password"
                 id="reg-password-confirm"
                 name="password_confirmation"
                 minlength="8"
                 placeholder="Ketik ulang password di atas"
                 autocomplete="new-password"
                 required>
          <button type="button" class="pw-toggle" aria-label="Tampilkan password">&#9679;</button>
        </div>
        <span class="field-hint">Pastikan konfirmasi password sama persis</span>
        @error('password_confirmation')
          <span class="field-error" style="color: var(--netflix-red); font-size: .8rem; font-weight: 600;">{{ $message }}</span>
        @enderror
      </label>

      <button type="submit" class="btn btn--primary btn--wide" id="btn-reg-submit">
        Daftar Akun Baru
      </button>

      <p class="form-footnote">
        Sudah memiliki akun Zeflix? <a href="{{ route('login') }}">Masuk</a>
      </p>
    </form>
  </div>
</section>

<script>
  (function() {
    // 1. Live Name Counter & Character Sanitizer Hint
    const nameInput = document.getElementById('reg-name');
    const nameCounter = document.getElementById('name-count-indicator');
    const nameWarning = document.getElementById('name-client-warning');

    if (nameInput) {
      const updateName = () => {
        const len = nameInput.value.length;
        if (nameCounter) nameCounter.textContent = len + ' / 50';

        if (nameWarning) {
          if (/\d/.test(nameInput.value)) {
            nameWarning.textContent = 'Perhatian: Nama tidak disarankan mengandung angka.';
            nameWarning.style.display = 'block';
          } else {
            nameWarning.style.display = 'none';
          }
        }
      };
      nameInput.addEventListener('input', updateName);
      updateName();
    }

    // 2. Live Email Format Helper
    const emailInput = document.getElementById('reg-email');
    const emailHint = document.getElementById('email-client-hint');

    if (emailInput && emailHint) {
      emailInput.addEventListener('input', function() {
        const val = this.value.trim();
        if (val.length === 0) {
          emailHint.style.display = 'none';
          return;
        }

        if (!val.includes('@')) {
          emailHint.textContent = 'Alamat email wajib menyertakan tanda @';
          emailHint.style.display = 'block';
        } else {
          const parts = val.split('@');
          if (parts[1] && !parts[1].includes('.')) {
            emailHint.textContent = 'Sertakan domain lengkap (contoh: .com, .id)';
            emailHint.style.display = 'block';
          } else {
            emailHint.style.display = 'none';
          }
        }
      });
    }

    // 3. Live Password Length & Match Feedback
    const pwInput = document.getElementById('reg-password');
    const pwConfirm = document.getElementById('reg-password-confirm');
    const pwLengthInd = document.getElementById('pw-length-indicator');
    const pwMatchInd = document.getElementById('pw-match-indicator');

    if (pwInput && pwLengthInd) {
      pwInput.addEventListener('input', function() {
        const len = this.value.length;
        if (len === 0) {
          pwLengthInd.textContent = 'Min. 8 karakter';
          pwLengthInd.style.color = '#888';
        } else if (len < 8) {
          pwLengthInd.textContent = (8 - len) + ' karakter lagi';
          pwLengthInd.style.color = 'var(--color-warning)';
        } else {
          pwLengthInd.textContent = 'Panjang memenuhi syarat';
          pwLengthInd.style.color = 'var(--netflix-green)';
        }
        checkMatch();
      });
    }

    function checkMatch() {
      if (!pwInput || !pwConfirm || !pwMatchInd) return;
      if (pwConfirm.value.length === 0) {
        pwMatchInd.textContent = '';
        return;
      }

      if (pwInput.value === pwConfirm.value) {
        pwMatchInd.textContent = 'Password cocok';
        pwMatchInd.style.color = 'var(--netflix-green)';
      } else {
        pwMatchInd.textContent = 'Belum cocok';
        pwMatchInd.style.color = 'var(--netflix-red)';
      }
    }

    if (pwConfirm) {
      pwConfirm.addEventListener('input', checkMatch);
    }
  })();
</script>
@endsection
