@extends('layout')

@section('title', 'Buka Akses Penuh Zeflix')

@section('content')
<section class="page-intro page-intro--compact">
  <a class="back-link" href="{{ route('home') }}"><span aria-hidden="true">←</span> Kembali ke Beranda</a>
  <p class="eyebrow"><span class="eyebrow__line"></span> Keanggotaan Zeflix</p>
  <h1>Buka Akses Seluruh Katalog</h1>
  <p>Selesaikan pembayaran satu kali untuk membuka akses streaming ke seluruh film tanpa batas waktu.</p>
</section>

<div class="account-grid" style="align-items: start;">
  {{-- Left Card: Plan Summary & Benefits Checklist --}}
  <section class="surface-card">
    <div class="card-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Paket Seumur Hidup</p>
        <h2>Keuntungan Akses Penuh</h2>
      </div>
      <span class="status-pill status-pill--active">Satu Kali Bayar</span>
    </div>

    <ul style="list-style: none; padding: 0; margin: 1.5rem 0 2rem; display: grid; gap: 1rem;">
      <li style="display: flex; align-items: flex-start; gap: .75rem; font-size: .92rem;">
        <span style="color: var(--netflix-green); font-weight: 800; font-size: 1.1rem;">✓</span>
        <span>Akses tanpa batas waktu ke seluruh film pendek, dokumenter, dan fiksi ilmiah</span>
      </li>
      <li style="display: flex; align-items: flex-start; gap: .75rem; font-size: .92rem;">
        <span style="color: var(--netflix-green); font-weight: 800; font-size: 1.1rem;">✓</span>
        <span>Kualitas pemutaran video 4K Ultra HD &amp; audio Dolby Atmos jernih</span>
      </li>
      <li style="display: flex; align-items: flex-start; gap: .75rem; font-size: .92rem;">
        <span style="color: var(--netflix-green); font-weight: 800; font-size: 1.1rem;">✓</span>
        <span>Simpan film favorit ke <strong>Daftar Saya</strong> dan lanjutkan menonton otomatis</span>
      </li>
      <li style="display: flex; align-items: flex-start; gap: .75rem; font-size: .92rem;">
        <span style="color: var(--netflix-green); font-weight: 800; font-size: 1.1rem;">✓</span>
        <span><strong>Tanpa biaya bulanan</strong> atau perpanjangan otomatis yang menjebak</span>
      </li>
    </ul>

    <p style="font-size: .84rem; color: #888; margin: 0;">
      Akses akun akan segera aktif otomatis begitu pembayaran terverifikasi oleh gateway Midtrans.
    </p>
  </section>

  {{-- Right Card: Price & Payment Action --}}
  <section class="surface-card" style="border-color: rgba(229, 9, 20, 0.4); background: radial-gradient(circle at 100% 0%, rgba(229, 9, 20, 0.15) 0%, var(--color-surface) 70%);">
    <div class="card-heading">
      <div>
        <p class="eyebrow"><span class="eyebrow__line"></span> Ringkasan Tagihan</p>
        <h2>Total Pembayaran</h2>
      </div>
    </div>

    <div style="margin: 1.5rem 0 2rem; border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border); padding: 1.25rem 0;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: .6rem; font-size: .9rem;">
        <span style="color: #bbb;">Paket Akses Zeflix Unlimited</span>
        <strong style="color: #fff;">Rp{{ number_format(config('svod.price'), 0, ',', '.') }}</strong>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; font-size: .9rem;">
        <span style="color: #bbb;">Biaya Admin &amp; Layanan</span>
        <span style="color: var(--netflix-green); font-weight: 600;">GRATIS</span>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--color-border); font-size: 1.15rem;">
        <strong>Total Bayar</strong>
        <strong style="color: var(--netflix-red); font-size: 1.6rem; font-family: var(--font-display);">
          Rp{{ number_format(config('svod.price'), 0, ',', '.') }}
        </strong>
      </div>
    </div>

    <form method="post" action="{{ route('checkout.start') }}" style="margin: 0;">
      @csrf
      <button class="btn btn--primary btn--wide" style="font-size: 1rem; min-height: 48px;">
        <span aria-hidden="true">🔒</span> Bayar Sekarang dengan Midtrans
      </button>
    </form>

    <div style="margin-top: 1.25rem; display: flex; align-items: center; justify-content: center; gap: .75rem; color: #777; font-size: .78rem;">
      <span>Mendukung: QRIS · GoPay · ShopeePay · BCA · Mandiri · BNI · BRI</span>
    </div>
  </section>
</div>
@endsection
