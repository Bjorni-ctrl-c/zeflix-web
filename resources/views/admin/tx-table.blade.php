<div class="table-scroll"><table class="data-table"><thead><tr><th>Referensi</th><th>Pengguna</th><th>Nominal</th><th>Status</th><th>Metode</th><th>Waktu</th></tr></thead><tbody>
  @forelse($rows as $t)
  <tr><td data-label="Referensi"><strong>{{ $t->order_id }}</strong></td><td data-label="Pengguna">{{ $t->user->email }}</td><td data-label="Nominal">Rp{{ number_format($t->amount, 0, ',', '.') }}</td><td data-label="Status"><span class="status-pill status-pill--{{ $t->status }}">{{ $t->status }}</span></td><td data-label="Metode">{{ $t->payment_type ?: '-' }}</td><td data-label="Waktu">{{ $t->created_at->format('d/m/Y H:i') }}</td></tr>
  @empty
  <tr><td colspan="6" class="table-empty">Belum ada transaksi.</td></tr>
  @endforelse
</tbody></table></div>
