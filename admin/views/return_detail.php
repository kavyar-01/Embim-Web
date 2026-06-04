<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=manage_returns" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Returns
    </a>
  </div>

  <div class="page-heading">
    <h1>Detail Pengembalian</h1>
    <p>Return #<?= $return['id'] ?> — Booking #<?= $return['booking_id'] ?></p>
  </div>

  <?php if (isset($_GET['created'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Data pengembalian berhasil dicatat. Status booking dan kendaraan telah diperbarui.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Data pengembalian berhasil diperbarui.
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Return Info -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Informasi Pengembalian</span>
        <?php
          $condClass = $return['car_condition'] === 'good' ? 'badge badge-paid' : 'badge badge-unpaid';
          $condLabel = $return['car_condition'] === 'good' ? 'Baik (Good)' : 'Rusak (Damaged)';
        ?>
        <span class="<?= $condClass ?>"><?= $condLabel ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Return ID</dt><dd>#<?= $return['id'] ?></dd></div>
        <div class="detail-row"><dt>Booking ID</dt><dd>#<?= $return['booking_id'] ?></dd></div>
        <div class="detail-row"><dt>Tanggal Kembali</dt><dd><strong><?= htmlspecialchars($return['return_date']) ?></strong></dd></div>
        <div class="detail-row">
          <dt>Keterlambatan</dt>
          <dd>
            <?php if ((int)$return['late_days'] > 0): ?>
              <span class="badge badge-unpaid"><?= (int)$return['late_days'] ?> hari</span>
            <?php else: ?>
              <span class="badge badge-paid">Tepat waktu</span>
            <?php endif; ?>
          </dd>
        </div>
        <div class="detail-row"><dt>Kondisi Kendaraan</dt><dd><?= $condLabel ?></dd></div>
        <div class="detail-row"><dt>Notes</dt><dd><?= $return['notes'] ? htmlspecialchars($return['notes']) : '<span class="td-muted">—</span>' ?></dd></div>
        <div class="detail-row"><dt>Dicatat Pada</dt><dd><?= htmlspecialchars($return['created_at']) ?></dd></div>
      </dl>
    </div>

    <!-- Booking & Customer Info -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Booking &amp; Pelanggan</span>
        <?php
          $bookingBadge = match($return['booking_status']) {
              'completed'  => 'badge badge-paid',
              'cancelled'  => 'badge badge-refunded',
              'ongoing'    => 'badge badge-unpaid',
              default      => 'badge',
          };
        ?>
        <span class="<?= $bookingBadge ?>"><?= ucfirst($return['booking_status']) ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Pelanggan</dt><dd><strong><?= htmlspecialchars($return['customer_name']) ?></strong></dd></div>
        <div class="detail-row"><dt>Email</dt><dd><?= htmlspecialchars($return['customer_email']) ?></dd></div>
        <div class="detail-row"><dt>Kendaraan</dt><dd><?= htmlspecialchars($return['car_name']) ?></dd></div>
        <div class="detail-row"><dt>Plat Nomor</dt><dd><?= htmlspecialchars($return['license_plate']) ?></dd></div>
        <div class="detail-row">
          <dt>Periode Sewa</dt>
          <dd><?= $return['start_date'] ?> → <?= $return['end_date'] ?> (<?= $return['total_days'] ?> hari)</dd>
        </div>
        <div class="detail-row"><dt>Total Harga</dt><dd><strong>Rp <?= number_format((float)$return['total_price'], 0, ',', '.') ?></strong></dd></div>
        <?php if (!empty($return['booking_notes'])): ?>
        <div class="detail-row"><dt>Catatan Booking</dt><dd><?= htmlspecialchars($return['booking_notes']) ?></dd></div>
        <?php endif; ?>
      </dl>
    </div>

  </div>

  <!-- Actions -->
  <div class="card">
    <div class="card-header"><span class="card-title">Aksi</span></div>
    <div class="card-body">
      <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <a href="?page=edit_return&id=<?= $return['id'] ?>" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Edit Return
        </a>
        <form method="POST" action="?page=delete_return"
              onsubmit="return confirm('Hapus data pengembalian ini? Status booking dan mobil akan dikembalikan.')">
          <input type="hidden" name="id" value="<?= $return['id'] ?>" />
          <button type="submit" class="btn" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Hapus
          </button>
        </form>
        <a href="?page=manage_returns" class="btn btn-ghost">← Kembali ke Daftar</a>
      </div>
    </div>
  </div>

</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
