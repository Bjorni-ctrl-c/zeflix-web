@extends('layout')

@section('title', 'Kelola transaksi')

@section('content')
<section class="page-intro page-intro--compact"><p class="eyebrow"><span class="eyebrow__line"></span> Aktivitas pembayaran</p><h1>Transaksi</h1><p>Lihat riwayat pembayaran pelanggan.</p></section>
@include('admin.nav')
<section class="surface-card admin-section">
  <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Semua pembayaran</p><h2>Riwayat transaksi</h2></div></div>
  @include('admin.tx-table')
  <div class="pagination-wrap">{{ $rows->links() }}</div>
</section>
@endsection
