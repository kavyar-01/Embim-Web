<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div class="page-heading">
    <h1>Manage Returns</h1>
    <p>Catat dan kelola pengembalian kendaraan dari pelanggan.</p>
  </div>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Data pengembalian berhasil dihapus.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      <?= htmlspecialchars($_GET['error'] === 'not_found' ? 'Data tidak ditemukan.' : 'Terjadi kesalahan.') ?>
    </div>
  <?php endif; ?>

  <!-- Filter Bar -->
  <form method="GET" action="" class="filter-bar">
    <input type="hidden" name="page" value="manage_returns" />

    <div class="search-wrap">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
      <input type="search" name="search"
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
             placeholder="Booking ID, nama pelanggan, atau mobil..."
             class="form-control w-64" />
    </div>

    <select name="condition" class="form-control" style="width:auto;">
      <option value=""       <?= (($_GET['condition'] ?? '') === '')        ? 'selected' : '' ?>>All Condition</option>
      <option value="good"   <?= (($_GET['condition'] ?? '') === 'good')   ? 'selected' : '' ?>>Baik (Good)</option>
      <option value="damaged"<?= (($_GET['condition'] ?? '') === 'damaged') ? 'selected' : '' ?>>Rusak (Damaged)</option>
    </select>

    <input type="date" name="return_date"
           value="<?= htmlspecialchars($_GET['return_date'] ?? '') ?>"
           class="form-control" style="width:auto;"
           title="Filter by return date" />

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="?page=manage_returns" class="btn btn-ghost btn-sm">Reset</a>
    <a href="?page=add_return" class="btn btn-primary btn-sm" style="margin-left:auto;">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Tambah Return
    </a>
  </form>

  <!-- Table -->
  <div class="embim-table-wrap">
    <table class="embim-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Booking ID</th>
          <th>Pelanggan</th>
          <th>Mobil</th>
          <th>Tgl Kembali</th>
          <th>Terlambat</th>
          <th>Kondisi</th>
          <th>Notes</th>
          <th>Created At</th>
          <th class="td-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($returns)): ?>
          <tr><td colspan="10" class="text-center py-10 text-gray-400">Belum ada data pengembalian.</td></tr>
        <?php else: foreach ($returns as $r):
          $condClass = $r['car_condition'] === 'good' ? 'badge badge-paid' : 'badge badge-unpaid';
          $condLabel = $r['car_condition'] === 'good' ? 'Baik' : 'Rusak';
        ?>
        <tr>
          <td class="td-id"><?= $r['id'] ?></td>
          <td class="td-primary">#<?= $r['booking_id'] ?></td>
          <td class="td-primary"><?= htmlspecialchars($r['customer_name']) ?></td>
          <td><?= htmlspecialchars($r['car_name']) ?><br><span class="td-muted text-xs"><?= htmlspecialchars($r['license_plate']) ?></span></td>
          <td><?= htmlspecialchars($r['return_date']) ?></td>
          <td>
            <?php if ((int)$r['late_days'] > 0): ?>
              <span class="badge badge-unpaid"><?= (int)$r['late_days'] ?> hari</span>
            <?php else: ?>
              <span class="td-muted">—</span>
            <?php endif; ?>
          </td>
          <td><span class="<?= $condClass ?>"><?= $condLabel ?></span></td>
          <td class="td-muted" style="max-width:160px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?= $r['notes'] ? htmlspecialchars($r['notes']) : '—' ?>
          </td>
          <td class="td-muted"><?= htmlspecialchars($r['created_at']) ?></td>
          <td class="td-right">
            <div style="display:flex;gap:6px;justify-content:flex-end;">
              <a href="?page=return_detail&id=<?= $r['id'] ?>"
                 class="btn btn-sm btn-primary" style="background:#eff6ff;color:#1d4ed8;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Detail
              </a>
              <a href="?page=edit_return&id=<?= $r['id'] ?>"
                 class="btn btn-sm btn-primary" style="background:#f0fdf4;color:#15803d;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </a>
              <form method="POST" action="?page=delete_return"
                    onsubmit="return confirm('Hapus data pengembalian ini? Status booking dan mobil akan dikembalikan.')">
                <input type="hidden" name="id" value="<?= $r['id'] ?>" />
                <button type="submit" class="btn btn-sm" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  Hapus
                </button>
              </form>
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
    $baseUrl = '?page=manage_returns'
        . (!empty($_GET['condition'])   ? '&condition='   . urlencode($_GET['condition'])   : '')
        . (!empty($_GET['search'])      ? '&search='      . urlencode($_GET['search'])      : '')
        . (!empty($_GET['return_date']) ? '&return_date=' . urlencode($_GET['return_date']) : '');
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
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
