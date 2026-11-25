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

@if(isset($recentLogs) && $recentLogs->count())
<section class="surface-card admin-section" style="margin-top: 2rem;">
  <div class="card-heading">
    <div>
      <p class="eyebrow"><span class="eyebrow__line"></span> Keamanan</p>
      <h2>Log Aktivitas Administrator Terkini</h2>
    </div>
    <a class="text-link" href="{{ route('admin.audit-logs') }}">Semua log audit <span aria-hidden="true">→</span></a>
  </div>
  <div class="table-scroll">
    <table class="data-table">
      <thead>
        <tr>
          <th>Waktu</th>
          <th>Admin</th>
          <th>Aksi</th>
          <th>Keterangan</th>
          <th>IP</th>
        </tr>
      </thead>
      <tbody>
        @foreach($recentLogs as $log)
          <tr>
            <td data-label="Waktu" style="white-space: nowrap; font-size: .8rem; color: #888;">
              {{ $log->created_at->timezone('Asia/Jakarta')->format('d M H:i') }}
            </td>
            <td data-label="Admin"><strong>{{ $log->admin_name }}</strong></td>
            <td data-label="Aksi"><span class="status-pill status-pill--muted">{{ $log->action }}</span></td>
            <td data-label="Keterangan" style="color: #ddd;">{{ $log->description }}</td>
            <td data-label="IP" style="font-family: monospace; font-size: .8rem; color: #aaa;">{{ $log->ip_address }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</section>
@endif
@endsection
