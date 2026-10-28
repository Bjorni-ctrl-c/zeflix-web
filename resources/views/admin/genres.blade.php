@extends('layout')

@section('title', 'Kelola genre')

@section('content')
<section class="page-intro page-intro--compact">
  <p class="eyebrow"><span class="eyebrow__line"></span> Kelola katalog</p>
  <h1>Genre film</h1>
  <p>Genre yang aktif akan tersedia sebagai pilihan saat mengelola film.</p>
</section>
@include('admin.nav')
<section class="surface-card admin-section">
  <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Tambahkan kategori</p><h2>Genre baru</h2></div></div>
  <form class="form-stack" method="post" action="{{ route('admin.genres.store') }}">@csrf
    <label>Nama genre<input name="name" maxlength="80" required></label>
    <button class="btn btn--primary">Tambah genre</button>
  </form>
</section>
<section class="surface-card admin-section">
  <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Kategori katalog</p><h2>Semua genre</h2></div></div>
  <div class="table-scroll"><table class="data-table"><thead><tr><th>Nama</th><th>Jumlah film</th><th><span class="sr-only">Tindakan</span></th></tr></thead><tbody>
    @forelse($genres as $genre)
    <tr>
      <td data-label="Nama"><form class="form-inline" method="post" action="{{ route('admin.genres.update', $genre) }}">@csrf @method('PUT')<input name="name" value="{{ $genre->name }}" maxlength="80" required><button class="btn btn--small btn--outline">Simpan</button></form></td>
      <td data-label="Jumlah film">{{ $genre->films_count }}</td>
      <td class="table-action"><form method="post" action="{{ route('admin.genres.destroy', $genre) }}" onsubmit="return confirm('Hapus genre ini?')">@csrf @method('DELETE')<button class="btn btn--small btn--outline">Hapus</button></form></td>
    </tr>
    @empty
    <tr><td colspan="3" class="table-empty">Belum ada genre.</td></tr>
    @endforelse
  </tbody></table></div>
</section>
@endsection
