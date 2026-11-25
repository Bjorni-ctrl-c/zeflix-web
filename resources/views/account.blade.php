@extends('layout')

@section('title', 'Akun Saya — Zeflix')

@section('content')
<section class="page-intro page-intro--compact">
  <p class="eyebrow"><span class="eyebrow__line"></span> Ruang Pribadimu</p>
  <h1>Pengaturan Akun</h1>
</section>

<div class="account-grid">
  <section class="surface-card account-profile">
    <div class="account-avatar" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
    <div>
      <h2>{{ auth()->user()->name }}</h2>
      <p>{{ auth()->user()->email }}</p>
      <div style="margin-top: .6rem; display: flex; gap: .75rem;">
        <span class="status-pill status-pill--active">
          {{ $watchlistCount ?? 0 }} di Daftar Saya
        </span>
        <span class="status-pill status-pill--active">
          {{ $watchedCount ?? 0 }} Ditonton
        </span>
      </div>
    </div>
  </section>

  <section class="surface-card subscription-status">
    <div class="card-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Keanggotaan</p>
        <h2>Status Akses Zeflix</h2>
      </div>
      <span class="status-pill {{ $active ? 'status-pill--active' : 'status-pill--pending' }}">
        {{ $active ? 'Akses Aktif' : 'Belum Berlangganan' }}
      </span>
    </div>
    @if($active)
    <p>Akses streaming aktif{{ $until ? ' sampai '.$until->translatedFormat('d F Y, H:i') : ' seumur hidup tanpa batas waktu' }}. Nikmati seluruh film berkualitas 4K UHD.</p>
    <div style="display: flex; gap: .75rem; flex-wrap: wrap;">
      <a class="btn btn--primary" href="{{ route('catalog') }}">Buka Katalog Film</a>
      <a class="btn btn--outline" href="{{ route('watchlist') }}">Lihat Daftar Saya</a>
    </div>
    @else
    <p>Bayar satu kali untuk membuka akses ke seluruh katalog film Zeflix tanpa biaya langganan bulanan.</p>
    <a class="btn btn--primary" href="{{ route('checkout.page') }}">Buka Akses Rp{{ number_format(config('svod.price'), 0, ',', '.') }}</a>
    @endif
  </section>

  <section class="surface-card account-history">
    <div class="card-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Informasi Akun</p>
        <h2>Perbarui Profil</h2>
      </div>
    </div>
    <form class="form-stack" method="post" action="{{ route('account.update') }}">
      @csrf
      @method('PUT')
      <div class="form-grid">
        <label for="acc-name">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Nama Lengkap <span class="required-mark">*</span></span>
            <span id="acc-name-counter" style="font-size: .72rem; color: #888;">{{ strlen(old('name', auth()->user()->name)) }} / 50</span>
          </div>
          <input id="acc-name" name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" minlength="3" maxlength="50" required placeholder="contoh: Budi Pratama">
          <span class="field-hint">Maksimal 50 karakter (hanya huruf dan spasi)</span>
          @error('name')
            <span class="field-error" style="color: var(--netflix-red); font-size: .8rem; font-weight: 600;">{{ $message }}</span>
          @enderror
        </label>
        <label for="acc-email">
          <span>Alamat Email</span>
          <input id="acc-email" type="email" value="{{ auth()->user()->email }}" disabled style="opacity: 0.6; cursor: not-allowed;">
          <span class="field-hint">Email akun terikat dan tidak dapat diubah</span>
        </label>
        <label for="acc-password">
          <span>Password Baru</span>
          <div class="password-wrap">
            <input id="acc-password" type="password" name="password" autocomplete="new-password" placeholder="Kosongkan jika tidak diubah" minlength="8">
            <button type="button" class="pw-toggle" aria-label="Tampilkan password">&#9679;</button>
          </div>
          <span class="field-hint">Kosongkan jika tidak ingin mengganti password (min. 8 karakter)</span>
          @error('password')
            <span class="field-error" style="color: var(--netflix-red); font-size: .8rem; font-weight: 600;">{{ $message }}</span>
          @enderror
        </label>
        <label for="acc-password-confirm">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Ulangi Password Baru</span>
            <span id="acc-pw-match" style="font-size: .72rem; color: #888;"></span>
          </div>
          <div class="password-wrap">
            <input id="acc-password-confirm" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Ketik ulang password baru">
            <button type="button" class="pw-toggle" aria-label="Tampilkan password">&#9679;</button>
          </div>
          <span class="field-hint">Pastikan sama persis dengan password baru di atas</span>
          @error('password_confirmation')
            <span class="field-error" style="color: var(--netflix-red); font-size: .8rem; font-weight: 600;">{{ $message }}</span>
          @enderror
        </label>
      </div>
      <div style="margin-top: 1rem;">
        <button class="btn btn--primary">Simpan Perubahan</button>
      </div>
    </form>
  </section>

  <section class="surface-card account-history">
    <div class="card-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Pembayaran</p>
        <h2>Riwayat Transaksi</h2>
      </div>
    </div>
    <div class="table-scroll">
      <table class="data-table" style="width: 100%; text-align: left; border-collapse: collapse;">
        <thead>
          <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
            <th style="padding: .75rem;">ID Pesanan</th>
            <th style="padding: .75rem;">Nominal</th>
            <th style="padding: .75rem;">Status</th>
            <th style="padding: .75rem;">Waktu</th>
          </tr>
        </thead>
        <tbody>
          @forelse($transactions as $t)
          <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
            <td data-label="Referensi" style="padding: .75rem;">{{ $t->order_id }}</td>
            <td data-label="Nominal" style="padding: .75rem;">Rp{{ number_format($t->amount, 0, ',', '.') }}</td>
            <td data-label="Status" style="padding: .75rem;">
              <span class="status-pill status-pill--{{ $t->status }}">{{ $t->status }}</span>
            </td>
            <td data-label="Waktu" style="padding: .75rem;">{{ $t->created_at->format('d/m/Y H:i') }}</td>
          </tr>
          @empty
          <tr>
            <td class="table-empty" colspan="4" style="padding: 2rem; text-align: center; color: #888;">Belum ada riwayat transaksi.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
</div>

<script>
  (function() {
    const nameInp = document.getElementById('acc-name');
    const nameCount = document.getElementById('acc-name-counter');
    if (nameInp && nameCount) {
      nameInp.addEventListener('input', function() {
        nameCount.textContent = this.value.length + ' / 50';
      });
    }

    const pwInp = document.getElementById('acc-password');
    const pwConf = document.getElementById('acc-password-confirm');
    const pwMatch = document.getElementById('acc-pw-match');
    function checkPwMatch() {
      if (!pwInp || !pwConf || !pwMatch) return;
      if (pwConf.value.length === 0) {
        pwMatch.textContent = '';
        return;
      }
      if (pwInp.value === pwConf.value) {
        pwMatch.textContent = 'Password cocok';
        pwMatch.style.color = 'var(--netflix-green)';
      } else {
        pwMatch.textContent = 'Belum cocok';
        pwMatch.style.color = 'var(--netflix-red)';
      }
    }
    if (pwInp && pwConf) {
      pwInp.addEventListener('input', checkPwMatch);
      pwConf.addEventListener('input', checkPwMatch);
    }
  })();
</script>
@endsection
