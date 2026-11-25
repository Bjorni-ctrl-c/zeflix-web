@extends('layout')

@section('title', 'Log Audit Administrator — Zeflix')

@section('content')
<section class="page-intro page-intro--compact">
  <p class="eyebrow"><span class="eyebrow__line"></span> Keamanan &amp; Audit</p>
  <h1>Log Audit Administrator</h1>
  <p>Riwayat seluruh aktivitas krusial, perubahan konten, dan akses pengelola sistem.</p>
</section>

@include('admin.nav')

<section class="surface-card admin-section">
  <div class="card-heading">
    <div>
      <p class="eyebrow"><span class="eyebrow__line"></span> Rekam Jejak Sistem</p>
      <h2>Daftar Aktivitas Administrator</h2>
    </div>
    <span class="heading-count">{{ $rows->total() }} entri</span>
  </div>

  <div class="table-scroll">
    <table class="data-table">
      <thead>
        <tr>
          <th>Waktu</th>
          <th>Administrator</th>
          <th>Aksi</th>
          <th>Keterangan</th>
          <th>IP Address</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $log)
          <tr>
            <td data-label="Waktu" style="white-space: nowrap; font-size: .8rem; color: #888;">
              {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
            </td>
            <td data-label="Administrator">
              <strong>{{ $log->admin_name }}</strong>
              @if($log->user)
                <div style="font-size: .72rem; color: #777;">ID: #{{ $log->user->id }}</div>
              @endif
            </td>
            <td data-label="Aksi">
              @php
                $pillColor = match(true) {
                  str_contains($log->action, 'DELETE') => 'status-pill--failed',
                  str_contains($log->action, 'CREATE') => 'status-pill--active',
                  str_contains($log->action, 'UPDATE') => 'status-pill--pending',
                  default => 'status-pill--muted',
                };
              @endphp
              <span class="status-pill {{ $pillColor }}">{{ $log->action }}</span>
            </td>
            <td data-label="Keterangan" style="max-width: 400px; color: #ddd;">
              {{ $log->description }}
            </td>
            <td data-label="IP Address" style="font-family: monospace; font-size: .8rem; color: #aaa;">
              {{ $log->ip_address ?? '-' }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="table-empty">Belum ada catatan log aktivitas admin.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($rows->hasPages())
    <div class="pagination-wrap">
      {{ $rows->links() }}
    </div>
  @endif
</section>
@endsection
