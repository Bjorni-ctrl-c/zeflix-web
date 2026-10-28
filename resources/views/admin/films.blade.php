@extends('layout')

@section('title', 'Kelola film')

@section('content')
<section class="page-intro page-intro--compact page-intro--split"><div><p class="eyebrow"><span class="eyebrow__line"></span> Kelola katalog</p><h1>Film</h1><p>Atur film yang tersedia di katalog Zeflix.</p></div><a class="btn btn--primary" href="{{ route('admin.films.create') }}">Tambah film <span aria-hidden="true">+</span></a></section>
@include('admin.nav')
<section class="surface-card admin-section">
  <div class="card-heading"><div><p class="eyebrow"><span class="eyebrow__line"></span> Katalog</p><h2>Semua film <span class="heading-count">{{ $films->count() }}</span></h2></div></div>
  <div class="table-scroll"><table class="data-table"><thead><tr><th>Judul</th><th>Genre</th><th>Akses</th><th>Status</th><th><span class="sr-only">Tindakan</span></th></tr></thead><tbody>
    @forelse($films as $f)
    <tr><td data-label="Judul"><strong>{{ $f->title }}</strong></td><td data-label="Genre">{{ $f->genre ?: '-' }}</td><td data-label="Akses"><span class="badge {{ $f->access === 'free' ? 'badge--free' : 'badge--premium' }}">{{ $f->access === 'free' ? 'Gratis' : 'Premium' }}</span></td><td data-label="Status"><span class="status-pill status-pill--{{ $f->status }}">{{ $f->status }}</span></td><td class="table-action"><a class="btn btn--small btn--outline" href="{{ route('admin.films.edit', $f) }}">Ubah</a><form method="post" action="{{ route('admin.films.destroy', $f) }}" onsubmit="return confirm('Hapus film ini?')">@csrf @method('DELETE')<button class="btn btn--small btn--outline">Hapus</button></form></td></tr>
    @empty
    <tr><td colspan="5" class="table-empty">Belum ada film. Tambahkan judul pertama ke katalog.</td></tr>
    @endforelse
  </tbody></table></div>
</section>
@endsection
