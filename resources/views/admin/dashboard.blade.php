@extends('layout')

@section('title', 'Ringkasan admin')

@section('content')
<section class="page-intro page-intro--compact"><p class="eyebrow"><span class="eyebrow__line"></span> Ruang pengelola</p><h1>Ringkasan</h1><p>Pantau katalog, pelanggan, dan transaksi Zeflix.</p></section>
@include('admin.nav')
<div class="stats-grid">
  <article class="stat-card"><span class="stat-card__label">Film</span><strong>{{ $films }}</strong><span class="stat-card__detail">Judul dalam katalog</span></article>
  <article class="stat-card"><span class="stat-card__label">Pengguna</span><strong>{{ $users }}</strong><span class="stat-card__detail">Akun terdaftar</span></article>
  <article class="stat-card"><span class="stat-card__label">Akses aktif</span><strong>{{ $active }}</strong><span class="stat-card__detail">Pelanggan berbayar</span></article>
  <article class="stat-card stat-card--accent"><span class="stat-card__label">Pendapatan kotor</span><strong>Rp{{ number_format($revenue, 0, ',', '.') }}</strong><span class="stat-card__detail">Total transaksi berhasil</span></article>
</div>
<section class="surface-card admin-section"><div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Aktivitas terbaru</p><h2>Transaksi terbaru</h2></div><a class="text-link" href="{{ route('admin.transactions') }}">Semua transaksi <span aria-hidden="true">→</span></a></div>@include('admin.tx-table', ['rows' => $recent])</section>
@endsection
