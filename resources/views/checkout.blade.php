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

    <form id="checkout-form" method="post" action="{{ route('checkout.start') }}" style="margin: 0;">
      @csrf
      <button type="button" id="btn-open-verify" class="btn btn--primary btn--wide" style="font-size: 1rem; min-height: 48px;">
        <span aria-hidden="true">&#9655;</span> Bayar Sekarang dengan Midtrans
      </button>
      <noscript>
        <button type="submit" class="btn btn--primary btn--wide" style="margin-top:.5rem;">Lanjut tanpa JavaScript</button>
      </noscript>
    </form>

    <div style="margin-top: 1.25rem; display: flex; align-items: center; justify-content: center; gap: .75rem; color: #777; font-size: .78rem;">
      <span>Mendukung: QRIS · GoPay · ShopeePay · BCA · Mandiri · BNI · BRI</span>
    </div>
  </section>
</div>

{{-- Pop-up Modal Verifikasi Pembayaran --}}
<div id="checkout-verify-modal" class="quick-modal-backdrop" aria-hidden="true" style="display:none; align-items:center; justify-content:center; z-index:9999; position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(6px);">
  <div class="surface-card" style="max-width: 480px; width: 92%; padding: 2rem; border-radius: var(--radius-lg); border: 1px solid rgba(229, 9, 20, 0.4); box-shadow: 0 20px 50px rgba(0,0,0,0.95); background: #181818;">
    <div style="text-align: center; margin-bottom: 1.5rem;">
      <div style="width: 56px; height: 56px; margin: 0 auto 1rem; border-radius: 50%; background: rgba(229, 9, 20, 0.15); display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; color: var(--netflix-red); letter-spacing: .04em;">
        BAYAR
      </div>
      <p class="eyebrow" style="margin-bottom: .25rem;"><span class="eyebrow__line"></span> Konfirmasi Pembelian</p>
      <h2 style="font-size: 1.4rem; margin-bottom: .5rem;">Buka Akses Penuh Zeflix?</h2>
      <p style="font-size: .88rem; color: #aaa; margin: 0;">
        Pop-up pembayaran resmi Midtrans akan terbuka di layar ini. Tidak ada potongan berulang atau biaya bulanan.
      </p>
    </div>

    <div style="background: rgba(255,255,255,0.04); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 1.1rem; margin-bottom: 1.5rem; font-size: .88rem; display: grid; gap: .6rem;">
      <div style="display: flex; justify-content: space-between;">
        <span style="color: #aaa;">Layanan:</span>
        <strong style="color: #fff;">Akses Katalog Seumur Hidup</strong>
      </div>
      <div style="display: flex; justify-content: space-between;">
        <span style="color: #aaa;">Metode:</span>
        <span style="color: #fff;">Pop-up Snap (VA / QRIS / Kartu)</span>
      </div>
      <div style="display: flex; justify-content: space-between; padding-top: .6rem; border-top: 1px dashed var(--color-border); font-size: 1rem; align-items: center;">
        <strong>Total Bayar:</strong>
        <strong style="color: var(--netflix-red); font-size: 1.35rem; font-family: var(--font-display);">Rp{{ number_format(config('svod.price'), 0, ',', '.') }}</strong>
      </div>
    </div>

    <div id="checkout-error-msg" style="display:none;background:rgba(229,9,20,0.15);border:1px solid rgba(229,9,20,0.4);border-radius:6px;padding:.75rem 1rem;font-size:.85rem;color:#ff9e9e;margin-bottom:1rem;text-align:center;"></div>
    <div style="display: flex; gap: .75rem; justify-content: flex-end;">
      <button type="button" class="btn btn--outline" id="btn-cancel-verify" style="flex: 1;">
        Batal
      </button>
      <button type="button" class="btn btn--primary" id="btn-proceed-pay" style="flex: 1.4;">
        <span id="btn-proceed-text">Ya, Bayar Sekarang</span>
      </button>
    </div>
  </div>
</div>

@php
  $snapJsUrl = config('svod.midtrans.production')
    ? 'https://app.midtrans.com/snap/snap.js'
    : 'https://app.sandbox.midtrans.com/snap/snap.js';
  $clientKey = config('svod.midtrans.client_key');
@endphp
<script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>

<script>
  (function() {
    const triggerBtn = document.getElementById('btn-open-verify');
    const modal = document.getElementById('checkout-verify-modal');
    const cancelBtn = document.getElementById('btn-cancel-verify');
    const proceedBtn = document.getElementById('btn-proceed-pay');
    const proceedText = document.getElementById('btn-proceed-text');

    function openModal() {
      if (!modal) return;
      modal.style.display = 'flex';
      modal.setAttribute('aria-hidden', 'false');
    }

    function closeModal() {
      if (!modal) return;
      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');
    }

    function resetBtn() {
      if (proceedBtn) {
        proceedBtn.disabled = false;
        proceedText.textContent = 'Ya, Bayar Sekarang';
      }
    }

    if (triggerBtn) {
      triggerBtn.addEventListener('click', function(e) {
        e.preventDefault();
        openModal();
      });
    }

    if (cancelBtn) {
      cancelBtn.addEventListener('click', closeModal);
    }

    if (modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
      });
    }

    if (proceedBtn) {
      proceedBtn.addEventListener('click', async function() {
        proceedBtn.disabled = true;
        proceedText.textContent = 'Menyiapkan Pop-up...';

        try {
          const res = await fetch("{{ route('checkout.start') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
          });

          const data = await res.json().catch(() => ({}));

          if (!res.ok) {
            throw new Error(data.message || 'Gagal menyiapkan sesi pembayaran. Coba lagi.');
          }

          closeModal();
          resetBtn();

          // Open Midtrans Snap as Pop-up
          if (typeof window.snap !== 'undefined' && data.token) {
            window.snap.pay(data.token, {
              onSuccess: function(result) {
                const oid = result.order_id || data.order_id;
                window.location.href = "{{ route('checkout.finish') }}?order_id=" + encodeURIComponent(oid);
              },
              onPending: function(result) {
                const oid = result.order_id || data.order_id;
                window.location.href = "{{ route('checkout.finish') }}?order_id=" + encodeURIComponent(oid);
              },
              onError: function() {
                showCheckoutError('Pembayaran gagal atau ditolak. Silakan coba kembali.');
                resetBtn();
              },
              onClose: function() {
                // User closed pop-up without completing
                resetBtn();
              }
            });
          } else if (data.redirect_url) {
            // Fallback to direct redirect
            window.location.href = data.redirect_url;
          }
        } catch (err) {
          showCheckoutError(err.message || 'Terjadi kesalahan jaringan. Periksa koneksi Anda.');
          resetBtn();
        }

    function showCheckoutError(msg) {
      const el = document.getElementById('checkout-error-msg');
      if (el) { el.textContent = msg; el.style.display = 'block'; setTimeout(() => { el.style.display='none'; }, 7000); }
    }
      });
    }
  })();
</script>
@endsection
