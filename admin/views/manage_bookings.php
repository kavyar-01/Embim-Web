<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div class="page-heading">
    <h1>Manage Bookings</h1>
    <p>Review dan kelola data pemesanan kendaraan dari pelanggan.</p>
  </div>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Data booking berhasil dihapus.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      <?= htmlspecialchars(match($_GET['error']) {
          'not_found'     => 'Data tidak ditemukan.',
          'invalid'       => 'Permintaan tidak valid.',
          'delete_failed' => 'Gagal menghapus data. Silakan coba lagi.',
          default         => 'Terjadi kesalahan.',
      }) ?>
    </div>
  <?php endif; ?>

  <!-- Filter Bar -->
  <form method="GET" action="" class="filter-bar">
    <input type="hidden" name="page" value="manage_bookings" />

    <div class="search-wrap">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
      <input type="search" name="search"
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
             placeholder="Booking ID, nama pelanggan, atau mobil..."
             class="form-control w-64" />
    </div>

    <select name="status" class="form-control" style="width:auto;">
      <option value=""          <?= (($_GET['status'] ?? '') === '')          ? 'selected' : '' ?>>All Status</option>
      <option value="confirmed" <?= (($_GET['status'] ?? '') === 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
      <option value="ongoing"   <?= (($_GET['status'] ?? '') === 'ongoing')   ? 'selected' : '' ?>>Ongoing</option>
      <option value="completed" <?= (($_GET['status'] ?? '') === 'completed') ? 'selected' : '' ?>>Completed</option>
      <option value="cancelled" <?= (($_GET['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="?page=manage_bookings" class="btn btn-ghost btn-sm">Reset</a>
  </form>

  <!-- Table -->
  <div class="embim-table-wrap">
    <table class="embim-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Pelanggan</th>
          <th>Mobil</th>
          <th>Mulai</th>
          <th>Selesai</th>
          <th>Total Hari</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th>Notes</th>
          <th>Created At</th>
          <th class="td-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($bookings)): ?>
          <tr><td colspan="11" class="text-center py-10 text-gray-400">Belum ada data booking.</td></tr>
        <?php else: foreach ($bookings as $b):
          $badgeClass = match($b['status']) {
              'completed' => 'badge badge-paid',
              'cancelled' => 'badge badge-unpaid',
              default     => 'badge',
          };
          $badgeStyle = match($b['status']) {
              'confirmed' => ' style="background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;"',
              'ongoing'   => ' style="background:#faf5ff;color:#7e22ce;border-color:#e9d5ff;"',
              default     => '',
          };
        ?>
        <tr>
          <td class="td-id"><?= (int)$b['id'] ?></td>
          <td class="td-primary"><?= htmlspecialchars($b['customer_name']) ?></td>
          <td>
            <?= htmlspecialchars($b['car_name']) ?>
            <?php if (!empty($b['license_plate'])): ?>
              <br><span class="td-muted text-xs"><?= htmlspecialchars($b['license_plate']) ?></span>
            <?php endif; ?>
          </td>
          <td><?= htmlspecialchars($b['start_date']) ?></td>
          <td><?= htmlspecialchars($b['end_date']) ?></td>
          <td><?= (int)$b['total_days'] ?> hari</td>
          <td><strong>Rp <?= number_format((float)$b['total_price'], 0, ',', '.') ?></strong></td>
          <td>
            <span class="<?= $badgeClass ?>"<?= $badgeStyle ?>>
              <?= ucfirst(htmlspecialchars($b['status'])) ?>
            </span>
          </td>
          <td class="td-muted" style="max-width:150px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?= !empty($b['notes']) ? htmlspecialchars($b['notes']) : '—' ?>
          </td>
          <td class="td-muted"><?= htmlspecialchars($b['created_at']) ?></td>
          <td class="td-right">
            <div style="display:flex;gap:6px;justify-content:flex-end;">
              <a href="?page=booking_detail&id=<?= (int)$b['id'] ?>"
                 class="btn btn-sm btn-primary" style="background:#eff6ff;color:#1d4ed8;" title="Detail">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </a>
              <a href="?page=edit_booking&id=<?= (int)$b['id'] ?>"
                 class="btn btn-sm btn-primary" style="background:#f0fdf4;color:#15803d;" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </a>
              <!-- Tombol hapus membuka modal konfirmasi -->
              <button type="button"
                      class="btn btn-sm" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;" title="Hapus"
                      onclick="openDeleteModal(<?= (int)$b['id'] ?>, '<?= addslashes(htmlspecialchars($b['customer_name'])) ?>')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <?php
    $totalPages  = (int) ($totalPages  ?? 1);
    $currentPage = (int) ($currentPage ?? 1);
    $total       = (int) ($total       ?? 0);
    $baseUrl = '?page=manage_bookings'
        . (!empty($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '')
        . (!empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '');
    $from = $total > 0 ? min($total, ($currentPage - 1) * 10 + 1) : 0;
    $to   = min($total, $currentPage * 10);
  ?>
  <?php if ($totalPages > 1): ?>
  <div class="embim-pagination">
    <p class="embim-pagination__info">Showing <?= $from ?>–<?= $to ?> of <?= $total ?> results</p>
    <div class="embim-pagination__pages">
      <?php if ($currentPage > 1): ?>
        <a href="<?= $baseUrl ?>&p=<?= $currentPage - 1 ?>" class="embim-pagination__btn">← Previous</a>
      <?php else: ?>
        <span class="embim-pagination__btn embim-pagination__btn--disabled">← Previous</span>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <?php if ($i === 1 || $i === $totalPages || abs($i - $currentPage) <= 1): ?>
          <a href="<?= $baseUrl ?>&p=<?= $i ?>" class="embim-pagination__btn <?= $i === $currentPage ? 'embim-pagination__btn--active' : '' ?>"><?= $i ?></a>
        <?php elseif (($i === 2 && $currentPage > 3) || ($i === $totalPages - 1 && $currentPage < $totalPages - 2)): ?>
          <span class="embim-pagination__btn embim-pagination__btn--disabled">…</span>
        <?php endif; ?>
      <?php endfor; ?>
      <?php if ($currentPage < $totalPages): ?>
        <a href="<?= $baseUrl ?>&p=<?= $currentPage + 1 ?>" class="embim-pagination__btn">Next →</a>
      <?php else: ?>
        <span class="embim-pagination__btn embim-pagination__btn--disabled">Next →</span>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

</div>

<!-- Modal Konfirmasi Hapus -->
<div id="modal-delete"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:12px;padding:28px 32px;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
      <div style="background:#fef2f2;border-radius:50%;padding:10px;flex-shrink:0;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
      </div>
      <div>
        <p style="font-weight:700;font-size:1rem;color:#111827;margin:0;">Hapus Booking?</p>
        <p id="modal-desc" style="font-size:0.825rem;color:#6b7280;margin:4px 0 0;"></p>
      </div>
    </div>
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
      <button type="button" class="btn btn-ghost"
              onclick="document.getElementById('modal-delete').style.display='none'">Batal</button>
      <form id="form-delete" method="POST" action="?page=delete_booking" style="margin:0;">
        <input type="hidden" id="delete-id" name="id" value="" />
        <button type="submit" class="btn" style="background:#dc2626;color:#fff;border:1px solid #dc2626;">
          Ya, Hapus
        </button>
      </form>
    </div>
  </div>
</div>

<script>
function openDeleteModal(id, name) {
  document.getElementById('delete-id').value  = id;
  document.getElementById('modal-desc').textContent =
    'Booking #' + id + ' atas nama ' + name + ' akan dihapus permanen beserta data return dan denda terkait.';
  document.getElementById('modal-delete').style.display = 'flex';
}
// Tutup modal jika klik di luar kotak
document.getElementById('modal-delete').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
