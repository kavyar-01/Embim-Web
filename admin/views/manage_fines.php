<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div class="page-heading">
    <h1>Manage Fines</h1>
    <p>Kelola dan pantau data denda keterlambatan pengembalian kendaraan.</p>
  </div>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Status denda berhasil diperbarui.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      <?= htmlspecialchars(match($_GET['error']) {
          'not_found' => 'Data denda tidak ditemukan.',
          'invalid'   => 'Permintaan tidak valid.',
          default     => 'Terjadi kesalahan.',
      }) ?>
    </div>
  <?php endif; ?>

  <!-- Filter Bar -->
  <form method="GET" action="" class="filter-bar">
    <input type="hidden" name="page" value="manage_fines" />

    <div class="search-wrap">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
      <input type="search" name="search"
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
             placeholder="Booking ID, Return ID, pelanggan, atau mobil..."
             class="form-control w-64" />
    </div>

    <select name="status" class="form-control" style="width:auto;">
      <option value=""       <?= (($_GET['status'] ?? '') === '')       ? 'selected' : '' ?>>All Status</option>
      <option value="unpaid" <?= (($_GET['status'] ?? '') === 'unpaid') ? 'selected' : '' ?>>Belum Lunas (Unpaid)</option>
      <option value="paid"   <?= (($_GET['status'] ?? '') === 'paid')   ? 'selected' : '' ?>>Lunas (Paid)</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="?page=manage_fines" class="btn btn-ghost btn-sm">Reset</a>
  </form>

  <!-- Table -->
  <div class="embim-table-wrap">
    <table class="embim-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Return ID</th>
          <th>Booking ID</th>
          <th>Pelanggan</th>
          <th>Mobil</th>
          <th>Tgl Kembali</th>
          <th>Terlambat</th>
          <th>Denda/Hari</th>
          <th>Total Denda</th>
          <th>Status</th>
          <th>Created At</th>
          <th class="td-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($fines)): ?>
          <tr><td colspan="12" class="text-center py-10 text-gray-400">Belum ada data denda.</td></tr>
        <?php else: foreach ($fines as $f):
          $badgeClass = $f['status'] === 'paid' ? 'badge badge-paid' : 'badge badge-unpaid';
          $badgeLabel = $f['status'] === 'paid' ? 'Lunas'            : 'Belum Lunas';
        ?>
        <tr>
          <td class="td-id"><?= (int)$f['id'] ?></td>
          <td class="td-primary">#<?= (int)$f['return_id'] ?></td>
          <td class="td-primary">#<?= (int)$f['booking_id'] ?></td>
          <td class="td-primary"><?= htmlspecialchars($f['customer_name']) ?></td>
          <td>
            <?= htmlspecialchars($f['car_name']) ?><br>
            <span class="td-muted text-xs"><?= htmlspecialchars($f['license_plate']) ?></span>
          </td>
          <td><?= htmlspecialchars($f['return_date']) ?></td>
          <td>
            <span class="badge badge-unpaid"><?= (int)$f['late_days'] ?> hari</span>
          </td>
          <td class="td-muted">Rp <?= number_format((float)$f['fine_per_day'], 0, ',', '.') ?></td>
          <td><strong>Rp <?= number_format((float)$f['fine_amount'], 0, ',', '.') ?></strong></td>
          <td><span class="<?= $badgeClass ?>"><?= $badgeLabel ?></span></td>
          <td class="td-muted"><?= htmlspecialchars($f['created_at']) ?></td>
          <td class="td-right">
            <a href="?page=fine_detail&id=<?= (int)$f['id'] ?>"
               class="btn btn-sm btn-primary" style="background:#eff6ff;color:#1d4ed8;">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              Detail
            </a>
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
    $baseUrl = '?page=manage_fines'
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
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
