@extends('layout')

@section('title', 'Kelola pengguna')

@section('content')
<section class="page-intro page-intro--compact"><p class="eyebrow"><span class="eyebrow__line"></span> Komunitas Zeflix</p><h1>Pengguna</h1><p>Daftar akun dan status langganan pelanggan.</p></section>
@include('admin.nav')
<section class="surface-card admin-section">
  <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Akun</p><h2>Semua pengguna</h2></div></div>
  <div class="table-scroll"><table class="data-table"><thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Langganan sampai</th></tr></thead><tbody>
    @forelse($rows as $u)
    <tr><td data-label="Nama"><strong>{{ $u->name }}</strong></td><td data-label="Email">{{ $u->email }}</td><td data-label="Peran"><span class="status-pill">{{ $u->role }}</span></td><td data-label="Langganan sampai">{{ $u->activeUntil()?->format('d/m/Y H:i') ?? '-' }}</td></tr>
    @empty
    <tr><td class="table-empty" colspan="4">Belum ada pengguna.</td></tr>
    @endforelse
  </tbody></table></div>
  <div class="pagination-wrap">{{ $rows->links() }}</div>
</section>
@endsection
