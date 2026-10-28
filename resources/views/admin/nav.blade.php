<nav class="admin-nav" aria-label="Navigasi admin">
  <a class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">Ringkasan</a>
  <a class="{{ request()->routeIs('admin.films.*') ? 'is-active' : '' }}" href="{{ route('admin.films.index') }}">Film</a>
  <a class="{{ request()->routeIs('admin.genres.*') ? 'is-active' : '' }}" href="{{ route('admin.genres.index') }}">Genre</a>
  <a class="{{ request()->routeIs('admin.transactions') ? 'is-active' : '' }}" href="{{ route('admin.transactions') }}">Transaksi</a>
  <a class="{{ request()->routeIs('admin.users') ? 'is-active' : '' }}" href="{{ route('admin.users') }}">Pengguna</a>
</nav>
