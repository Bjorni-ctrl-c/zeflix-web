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
    <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Status paket</p><h2>Langganan</h2></div><span class="status-pill {{ $until ? 'status-pill--active' : 'status-pill--muted' }}">{{ $until ? 'Aktif' : 'Belum aktif' }}</span></div>
    @if($until)
    <p>Aktif sampai <strong>{{ $until->translatedFormat('d F Y, H:i') }}</strong>. Perpanjang kapan saja, masa aktif ditambahkan di belakang masa berjalan.</p>
    @else
    <p>Belum ada langganan aktif. Berlangganan untuk menikmati semua film Premium.</p>
    @endif
    <form method="post" action="{{ route('checkout.start') }}">@csrf
      <button class="btn btn--primary">{{ $until ? 'Perpanjang' : 'Berlangganan' }} Rp{{ number_format(config('svod.price'), 0, ',', '.') }} / bulan</button>
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
