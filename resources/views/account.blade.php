@extends('layout')

@section('title', 'Akun saya')

@section('content')
<section class="page-intro page-intro--compact">
  <p class="eyebrow"><span class="eyebrow__line"></span> Ruang pribadimu</p>
  <h1>Akun saya</h1>
</section>

<div class="account-grid">
  <section class="surface-card account-profile">
    <span class="account-avatar" aria-hidden="true">ZF</span>
    <div><h2>{{ auth()->user()->name }}</h2><p>{{ auth()->user()->email }}</p></div>
  </section>

  <section class="surface-card subscription-status">
    <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Status akses</p><h2>Akses Zeflix</h2></div><span class="status-pill {{ $active ? 'status-pill--active' : 'status-pill--muted' }}">{{ $active ? 'Aktif' : 'Belum aktif' }}</span></div>
    @if($active)
    <p>Akses penuh aktif{{ $until ? ' sampai '.$until->translatedFormat('d F Y, H:i') : ' tanpa batas waktu' }}.</p>
    <a class="btn btn--primary" href="{{ route('catalog') }}">Jelajahi katalog</a>
    @else
    <p>Bayar satu kali untuk membuka seluruh katalog film.</p>
    <a class="btn btn--primary" href="{{ route('checkout.page') }}">Buka akses · Rp{{ number_format(config('svod.price'), 0, ',', '.') }}</a>
    @endif
  </section>

  <section class="surface-card account-history">
    <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Informasi akun</p><h2>Perbarui profil</h2></div></div>
    <form class="form-stack" method="post" action="{{ route('account.update') }}">@csrf @method('PUT')
      <label>Nama<input name="name" value="{{ old('name', auth()->user()->name) }}" autocomplete="name" maxlength="100" required></label>
      <label>Password baru <span class="field-hint">Kosongkan jika tidak ingin mengganti password.</span><input type="password" name="password" autocomplete="new-password"></label>
      <label>Ulangi password baru<input type="password" name="password_confirmation" autocomplete="new-password"></label>
      <button class="btn btn--primary">Simpan perubahan</button>
    </form>
  </section>

  <section class="surface-card account-history">
    <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Pembayaran</p><h2>Riwayat transaksi</h2></div></div>
    <div class="table-scroll"><table class="data-table"><thead><tr><th>Referensi</th><th>Nominal</th><th>Status</th><th>Waktu</th></tr></thead><tbody>
      @forelse($transactions as $t)
      <tr><td data-label="Referensi">{{ $t->order_id }}</td><td data-label="Nominal">Rp{{ number_format($t->amount, 0, ',', '.') }}</td><td data-label="Status"><span class="status-pill status-pill--{{ $t->status }}">{{ $t->status }}</span></td><td data-label="Waktu">{{ $t->created_at->format('d/m/Y H:i') }}</td></tr>
      @empty
      <tr><td class="table-empty" colspan="4">Belum ada transaksi.</td></tr>
      @endforelse
    </tbody></table></div>
  </section>
</div>
@endsection
