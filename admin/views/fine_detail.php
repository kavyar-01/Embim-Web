<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=manage_fines" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Kembali ke Manage Fines
    </a>
  </div>

  <div class="page-heading">
    <h1>Detail Denda</h1>
    <p>Fine #<?= (int)$fine['id'] ?> — Return #<?= (int)$fine['return_id'] ?> — Booking #<?= (int)$fine['booking_id'] ?></p>
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
      Gagal memperbarui status. Silakan coba lagi.
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Informasi Denda -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Informasi Denda</span>
        <?php
          $badgeClass = $fine['status'] === 'paid' ? 'badge badge-paid' : 'badge badge-unpaid';
          $badgeLabel = $fine['status'] === 'paid' ? 'Lunas (Paid)'     : 'Belum Lunas (Unpaid)';
        ?>
        <span class="<?= $badgeClass ?>"><?= $badgeLabel ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Fine ID</dt><dd>#<?= (int)$fine['id'] ?></dd></div>
        <div class="detail-row"><dt>Return ID</dt><dd>#<?= (int)$fine['return_id'] ?></dd></div>
        <div class="detail-row"><dt>Booking ID</dt><dd>#<?= (int)$fine['booking_id'] ?></dd></div>
        <div class="detail-row">
          <dt>Perhitungan Denda</dt>
          <dd>
            <span style="font-family:monospace;font-size:0.85rem;">
              <?= (int)$fine['late_days'] ?> hari
              &times;
              Rp <?= number_format((float)$fine['fine_per_day'], 0, ',', '.') ?>
              =
              <strong>Rp <?= number_format((float)$fine['fine_amount'], 0, ',', '.') ?></strong>
            </span>
          </dd>
        </div>
        <div class="detail-row">
          <dt>Hari Terlambat</dt>
          <dd><span class="badge badge-unpaid"><?= (int)$fine['late_days'] ?> hari</span></dd>
        </div>
        <div class="detail-row">
          <dt>Denda per Hari</dt>
          <dd>Rp <?= number_format((float)$fine['fine_per_day'], 0, ',', '.') ?></dd>
        </div>
        <div class="detail-row">
          <dt>Total Denda</dt>
          <dd><strong style="font-size:1rem;">Rp <?= number_format((float)$fine['fine_amount'], 0, ',', '.') ?></strong></dd>
        </div>
        <div class="detail-row"><dt>Status</dt><dd><span class="<?= $badgeClass ?>"><?= $badgeLabel ?></span></dd></div>
        <div class="detail-row"><dt>Dicatat Pada</dt><dd><?= htmlspecialchars($fine['created_at']) ?></dd></div>
      </dl>
    </div>

    <!-- Info Pengembalian -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Informasi Pengembalian</span>
        <?php
          $condClass = $fine['car_condition'] === 'good' ? 'badge badge-paid' : 'badge badge-unpaid';
          $condLabel = $fine['car_condition'] === 'good' ? 'Baik (Good)'      : 'Rusak (Damaged)';
        ?>
        <span class="<?= $condClass ?>"><?= $condLabel ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Tanggal Kembali</dt><dd><strong><?= htmlspecialchars($fine['return_date']) ?></strong></dd></div>
        <div class="detail-row"><dt>Kondisi Kendaraan</dt><dd><?= $condLabel ?></dd></div>
        <div class="detail-row">
          <dt>Catatan Return</dt>
          <dd><?= !empty($fine['return_notes']) ? htmlspecialchars($fine['return_notes']) : '<span class="td-muted">—</span>' ?></dd>
        </div>
      </dl>
    </div>

  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Info Booking & Pelanggan -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Booking &amp; Pelanggan</span>
        <?php
          $bookingBadge = match($fine['booking_status']) {
              'completed' => 'badge badge-paid',
              'cancelled' => 'badge badge-refunded',
              'ongoing'   => 'badge badge-unpaid',
              default     => 'badge',
          };
        ?>
        <span class="<?= $bookingBadge ?>"><?= ucfirst(htmlspecialchars($fine['booking_status'])) ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Pelanggan</dt><dd><strong><?= htmlspecialchars($fine['customer_name']) ?></strong></dd></div>
        <div class="detail-row"><dt>Email</dt><dd><?= htmlspecialchars($fine['customer_email']) ?></dd></div>
        <div class="detail-row">
          <dt>Periode Sewa</dt>
          <dd><?= htmlspecialchars($fine['start_date']) ?> → <?= htmlspecialchars($fine['end_date']) ?> (<?= (int)$fine['total_days'] ?> hari)</dd>
        </div>
        <div class="detail-row">
          <dt>Total Harga Sewa</dt>
          <dd>Rp <?= number_format((float)$fine['total_price'], 0, ',', '.') ?></dd>
        </div>
      </dl>
    </div>

    <!-- Info Kendaraan -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Kendaraan</span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Nama Mobil</dt><dd><strong><?= htmlspecialchars($fine['car_name']) ?></strong></dd></div>
        <div class="detail-row"><dt>Plat Nomor</dt><dd><?= htmlspecialchars($fine['license_plate']) ?></dd></div>
      </dl>
    </div>

  </div>

  <!-- Update Status -->
  <div class="card">
    <div class="card-header"><span class="card-title">Update Status Denda</span></div>
    <div class="card-body">
      <form method="POST" action="?page=update_fine_status"
            onsubmit="return confirm('Yakin ingin mengubah status denda ini?')"
            style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <input type="hidden" name="id" value="<?= (int)$fine['id'] ?>" />
        <select name="status" class="form-control" style="width:auto;">
          <option value="unpaid" <?= $fine['status'] === 'unpaid' ? 'selected' : '' ?>>Belum Lunas (Unpaid)</option>
          <option value="paid"   <?= $fine['status'] === 'paid'   ? 'selected' : '' ?>>Lunas (Paid)</option>
        </select>
        <button type="submit" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Simpan Status
        </button>
        <a href="?page=manage_fines" class="btn btn-ghost">← Kembali ke Daftar</a>
      </form>
    </div>
  </div>

</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
