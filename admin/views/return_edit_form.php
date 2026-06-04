<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=return_detail&id=<?= $return['id'] ?>" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Detail
    </a>
  </div>

  <div class="page-heading">
    <h1>Edit Pengembalian</h1>
    <p>Return #<?= $return['id'] ?> — Booking #<?= $return['booking_id'] ?></p>
  </div>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-error">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      <ul style="margin:0;padding-left:16px;">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6" style="max-width:900px;">

    <!-- Form Edit -->
    <div class="card">
      <div class="card-header"><span class="card-title">Ubah Data Return</span></div>
      <div class="card-body">
        <form method="POST" action="?page=edit_return&id=<?= $return['id'] ?>">

          <!-- Booking (readonly) -->
          <div style="margin-bottom:18px;">
            <label class="form-label">Booking</label>
            <input type="text" class="form-control"
                   value="#<?= $return['booking_id'] ?> — <?= htmlspecialchars($return['customer_name']) ?> (<?= htmlspecialchars($return['car_name']) ?>)"
                   disabled style="background:#f9fafb;color:#6b7280;" />
            <p class="text-xs text-gray-400 mt-1">Booking ID tidak dapat diubah.</p>
          </div>

          <!-- Return Date -->
          <div style="margin-bottom:18px;">
            <label class="form-label" for="return_date">Tanggal Pengembalian <span style="color:#ef4444;">*</span></label>
            <input type="date" name="return_date" id="return_date" class="form-control"
                   value="<?= htmlspecialchars($_POST['return_date'] ?? $return['return_date']) ?>"
                   data-end="<?= htmlspecialchars($return['end_date']) ?>"
                   required />
            <p class="text-xs text-gray-400 mt-1" id="late-hint"></p>
          </div>

          <!-- Car Condition -->
          <div style="margin-bottom:18px;">
            <label class="form-label" for="car_condition">Kondisi Kendaraan <span style="color:#ef4444;">*</span></label>
            <select name="car_condition" id="car_condition" class="form-control" required>
              <option value="good"    <?= (($_POST['car_condition'] ?? $return['car_condition']) === 'good')    ? 'selected' : '' ?>>Baik (Good)</option>
              <option value="damaged" <?= (($_POST['car_condition'] ?? $return['car_condition']) === 'damaged') ? 'selected' : '' ?>>Rusak (Damaged)</option>
            </select>
          </div>

          <!-- Notes -->
          <div style="margin-bottom:24px;">
            <label class="form-label" for="notes">Notes / Catatan</label>
            <textarea name="notes" id="notes" class="form-control" rows="3"
                      placeholder="Opsional"><?= htmlspecialchars($_POST['notes'] ?? $return['notes'] ?? '') ?></textarea>
          </div>

          <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Simpan Perubahan
            </button>
            <a href="?page=return_detail&id=<?= $return['id'] ?>" class="btn btn-ghost">Batal</a>
          </div>

        </form>
      </div>
    </div>

    <!-- Info Booking Terkait -->
    <div class="card">
      <div class="card-header"><span class="card-title">Info Booking Terkait</span></div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Booking ID</dt><dd>#<?= $return['booking_id'] ?></dd></div>
        <div class="detail-row"><dt>Pelanggan</dt><dd><?= htmlspecialchars($return['customer_name']) ?></dd></div>
        <div class="detail-row"><dt>Kendaraan</dt><dd><?= htmlspecialchars($return['car_name']) ?></dd></div>
        <div class="detail-row"><dt>Plat Nomor</dt><dd><?= htmlspecialchars($return['license_plate']) ?></dd></div>
        <div class="detail-row"><dt>Tanggal Sewa</dt><dd><?= $return['start_date'] ?></dd></div>
        <div class="detail-row"><dt>Tanggal Akhir Sewa</dt><dd><strong><?= $return['end_date'] ?></strong></dd></div>
        <div class="detail-row"><dt>Durasi</dt><dd><?= $return['total_days'] ?> hari</dd></div>
        <div class="detail-row">
          <dt>Late Days Saat Ini</dt>
          <dd>
            <?php if ((int)$return['late_days'] > 0): ?>
              <span class="badge badge-unpaid"><?= $return['late_days'] ?> hari</span>
            <?php else: ?>
              <span class="badge badge-paid">Tepat waktu</span>
            <?php endif; ?>
          </dd>
        </div>
      </dl>
      <div style="padding:12px 16px 0;border-top:1px solid #f3f4f6;margin-top:8px;">
        <p class="text-xs text-gray-400">Late days akan dihitung ulang otomatis jika tanggal pengembalian diubah.</p>
      </div>
    </div>

  </div>

</div>

<script>
  var endDate = document.getElementById('return_date').getAttribute('data-end') || '';

  function updateLateHint() {
    var hint   = document.getElementById('late-hint');
    var retVal = document.getElementById('return_date').value;
    if (!endDate || !retVal) { hint.textContent = ''; return; }
    var ret  = new Date(retVal);
    var end  = new Date(endDate);
    if (ret > end) {
      var diff = Math.round((ret - end) / 86400000);
      hint.textContent = '⚠ Terlambat ' + diff + ' hari dari ' + endDate + '.';
      hint.style.color = '#ef4444';
    } else {
      hint.textContent = '✓ Tepat waktu atau lebih awal dari ' + endDate + '.';
      hint.style.color = '#22c55e';
    }
  }

  updateLateHint();
  document.getElementById('return_date').addEventListener('change', updateLateHint);
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
