@extends('layout')

@section('title', $film->exists ? 'Ubah film' : 'Tambah film')

@section('content')
<section class="page-intro page-intro--compact"><p class="eyebrow"><span class="eyebrow__line"></span> Kelola katalog</p><h1>{{ $film->exists ? 'Ubah film' : 'Tambah film' }}</h1><p>Lengkapi informasi untuk menampilkan film di katalog.</p></section>
@include('admin.nav')
<form class="form-panel form-stack" method="post" enctype="multipart/form-data" action="{{ $film->exists ? route('admin.films.update', $film) : route('admin.films.store') }}">@csrf
  @if($film->exists)@method('PUT')@endif
  <div class="form-panel__heading"><p class="eyebrow"><span class="eyebrow__line"></span> Detail film</p><p>Kolom bertanda wajib harus diisi.</p></div>
  <div class="form-grid">
    <label>Judul <span class="required-mark">*</span><input name="title" value="{{ old('title', $film->title) }}" required></label>
    <label>Genre<input name="genre" value="{{ old('genre', $film->genre) }}"></label>
    <label class="form-grid__wide">Sinopsis <span class="required-mark">*</span><textarea name="synopsis" rows="5" required>{{ old('synopsis', $film->synopsis) }}</textarea></label>
    <label class="form-grid__wide">Unggah poster ke R2 privat <span class="field-hint">JPG, PNG, atau WebP; maksimal 4 MB. Jika memilih file baru, file akan diunggah ke bucket poster privat.</span><input type="file" name="poster_file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"></label>
    @if($film->poster_url && !str_starts_with($film->poster_url, 'http://') && !str_starts_with($film->poster_url, 'https://'))
    <p class="field-hint form-grid__wide">Poster R2 saat ini: {{ $film->poster_url }}. Unggah file baru untuk menggantinya.</p>
    @endif
    <label class="form-grid__wide">Atau URL poster publik<input type="url" name="poster_url" value="{{ old('poster_url', str_starts_with((string) $film->poster_url, 'http') ? $film->poster_url : '') }}" placeholder="https://..."><span class="field-hint">Opsional, untuk mempertahankan poster eksternal lama. File yang diunggah di atas akan diprioritaskan.</span></label>
    <label>Durasi (menit)<input type="number" name="duration_minutes" value="{{ old('duration_minutes', $film->duration_minutes ?? 0) }}" required></label>
    <label>Akses<select name="access">@foreach(['premium','free'] as $o)<option value="{{ $o }}" @selected(old('access', $film->access)===$o)>{{ $o === 'free' ? 'Gratis' : 'Premium' }}</option>@endforeach</select></label>
    <label>Status<select name="status">@foreach(['draft','published','hidden','archived'] as $o)<option value="{{ $o }}" @selected(old('status', $film->status)===$o)>{{ ucfirst($o) }}</option>@endforeach</select></label>
    <label class="form-grid__wide">Sumber video<select name="video_provider">@foreach(['r2'=>'Cloudflare R2 (nama file, mis. films/judul.mp4)','youtube'=>'YouTube (ID video)','mp4'=>'MP4 (URL)','hls'=>'HLS .m3u8 (URL)'] as $k=>$l)<option value="{{ $k }}" @selected(old('video_provider', $film->video_provider)===$k)>{{ $l }}</option>@endforeach</select></label>
    <label class="form-grid__wide">ID / URL / key objek video<input name="video_ref" value="{{ old('video_ref', $film->video_ref) }}"><span class="field-hint">Untuk Cloudflare R2, isi key objek di dalam bucket, misalnya films/film-1.mp4. Video diunggah ke R2 secara terpisah.</span></label>
    <label class="form-grid__wide">Catatan hak penggunaan <span class="field-hint">Musik, pemeran, lokasi</span><textarea name="rights_note" rows="3">{{ old('rights_note', $film->rights_note) }}</textarea></label>
  </div>
  <div class="form-actions"><a class="btn btn--outline" href="{{ route('admin.films.index') }}">Batal</a><button class="btn btn--primary">Simpan film</button></div>
</form>
@endsection
