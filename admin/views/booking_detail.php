<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=manage_bookings" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Kembali ke Manage Bookings
    </a>
  </div>

  <div class="page-heading">
    <h1>Detail Booking</h1>
    <p>Booking #<?= (int)$booking['id'] ?> — <?= htmlspecialchars($booking['customer_name']) ?></p>
  </div>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Data booking berhasil diperbarui.
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Info Booking -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Informasi Booking</span>
        <?php
          $badgeClass = match($booking['status']) {
              'completed' => 'badge badge-paid',
              'cancelled' => 'badge badge-unpaid',
              default     => 'badge',
          };
          $badgeStyle = match($booking['status']) {
              'confirmed' => ' style="background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;"',
              'ongoing'   => ' style="background:#faf5ff;color:#7e22ce;border-color:#e9d5ff;"',
              default     => '',
          };
        ?>
        <span class="<?= $badgeClass ?>"<?= $badgeStyle ?>><?= ucfirst(htmlspecialchars($booking['status'])) ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Booking ID</dt><dd><strong>#<?= (int)$booking['id'] ?></strong></dd></div>
        <div class="detail-row">
          <dt>Periode Sewa</dt>
          <dd><?= htmlspecialchars($booking['start_date']) ?> → <?= htmlspecialchars($booking['end_date']) ?></dd>
        </div>
        <div class="detail-row"><dt>Total Hari</dt><dd><?= (int)$booking['total_days'] ?> hari</dd></div>
        <div class="detail-row">
          <dt>Total Harga</dt>
          <dd><strong style="font-size:1rem;">Rp <?= number_format((float)$booking['total_price'], 0, ',', '.') ?></strong></dd>
        </div>
        <div class="detail-row"><dt>Status</dt><dd><span class="<?= $badgeClass ?>"<?= $badgeStyle ?>><?= ucfirst(htmlspecialchars($booking['status'])) ?></span></dd></div>
        <div class="detail-row">
          <dt>Catatan</dt>
          <dd><?= !empty($booking['notes']) ? htmlspecialchars($booking['notes']) : '<span class="td-muted">—</span>' ?></dd>
        </div>
        <div class="detail-row"><dt>Dibuat Pada</dt><dd><?= htmlspecialchars($booking['created_at']) ?></dd></div>
        <div class="detail-row"><dt>Diperbarui</dt><dd><?= htmlspecialchars($booking['updated_at']) ?></dd></div>
      </dl>
    </div>

    <!-- Info Pelanggan & Kendaraan -->
    <div class="card">
      <div class="card-header"><span class="card-title">Pelanggan &amp; Kendaraan</span></div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Nama Pelanggan</dt><dd><strong><?= htmlspecialchars($booking['customer_name']) ?></strong></dd></div>
        <div class="detail-row"><dt>Email</dt><dd><?= htmlspecialchars($booking['customer_email']) ?></dd></div>
        <div class="detail-row"><dt>Telepon</dt><dd><?= !empty($booking['customer_phone']) ? htmlspecialchars($booking['customer_phone']) : '<span class="td-muted">—</span>' ?></dd></div>
        <div class="detail-row"><dt>Kendaraan</dt><dd><strong><?= htmlspecialchars($booking['car_name']) ?></strong></dd></div>
        <div class="detail-row"><dt>Plat Nomor</dt><dd><?= htmlspecialchars($booking['license_plate']) ?></dd></div>
        <div class="detail-row"><dt>Harga/Hari</dt><dd>Rp <?= number_format((float)$booking['price_per_day'], 0, ',', '.') ?></dd></div>
      </dl>
    </div>

  </div>

  <!-- Aksi -->
  <div class="card">
    <div class="card-header"><span class="card-title">Aksi</span></div>
    <div class="card-body">
      <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <a href="?page=edit_booking&id=<?= (int)$booking['id'] ?>" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Edit Booking
        </a>

        <!-- Tombol Hapus dengan modal konfirmasi -->
        <button type="button" class="btn" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;"
                onclick="document.getElementById('modal-delete').style.display='flex'">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Hapus Booking
        </button>

        <a href="?page=manage_bookings" class="btn btn-ghost">← Kembali ke Daftar</a>
      </div>
    </div>
  </div>

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
        <p style="font-size:0.825rem;color:#6b7280;margin:4px 0 0;">Booking #<?= (int)$booking['id'] ?> akan dihapus permanen beserta data return dan denda terkait.</p>
      </div>
    </div>
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
      <button type="button" class="btn btn-ghost"
              onclick="document.getElementById('modal-delete').style.display='none'">Batal</button>
      <form method="POST" action="?page=delete_booking" style="margin:0;">
        <input type="hidden" name="id" value="<?= (int)$booking['id'] ?>" />
        <button type="submit" class="btn" style="background:#dc2626;color:#fff;border:1px solid #dc2626;">
          Ya, Hapus
        </button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
