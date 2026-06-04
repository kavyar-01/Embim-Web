<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=manage_returns" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Returns
    </a>
  </div>

  <div class="page-heading">
    <h1>Tambah Pengembalian</h1>
    <p>Catat pengembalian kendaraan dari pelanggan.</p>
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

  <div class="card" style="max-width:640px;">
    <div class="card-header">
      <span class="card-title">Form Pengembalian</span>
    </div>
    <div class="card-body">
      <form method="POST" action="?page=add_return">

        <!-- Booking ID -->
        <div style="margin-bottom:18px;">
          <label class="form-label" for="booking_id">Booking <span style="color:#ef4444;">*</span></label>
          <?php if (empty($bookings)): ?>
            <p class="text-sm text-gray-500 mt-1">
              Tidak ada booking yang belum memiliki data pengembalian.
            </p>
            <input type="hidden" name="booking_id" value="0" />
          <?php else: ?>
            <select name="booking_id" id="booking_id" class="form-control" required
                    onchange="fillEndDate(this)">
              <option value="">— Pilih Booking —</option>
              <?php foreach ($bookings as $b): ?>
                <option value="<?= $b['id'] ?>"
                        data-end="<?= htmlspecialchars($b['end_date']) ?>"
                        <?= (($_POST['booking_id'] ?? '') == $b['id']) ? 'selected' : '' ?>>
                  #<?= $b['id'] ?> — <?= htmlspecialchars($b['customer_name']) ?>
                  (<?= htmlspecialchars($b['car_name']) ?>)
                  | s/d <?= htmlspecialchars($b['end_date']) ?>
                  | <?= ucfirst($b['status']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <p class="text-xs text-gray-400 mt-1">Hanya booking yang belum memiliki return yang ditampilkan.</p>
          <?php endif; ?>
        </div>

        <!-- Return Date -->
        <div style="margin-bottom:18px;">
          <label class="form-label" for="return_date">Tanggal Pengembalian <span style="color:#ef4444;">*</span></label>
          <input type="date" name="return_date" id="return_date" class="form-control"
                 value="<?= htmlspecialchars($_POST['return_date'] ?? date('Y-m-d')) ?>"
                 required />
          <p class="text-xs text-gray-400 mt-1" id="late-hint"></p>
        </div>

        <!-- Car Condition -->
        <div style="margin-bottom:18px;">
          <label class="form-label" for="car_condition">Kondisi Kendaraan <span style="color:#ef4444;">*</span></label>
          <select name="car_condition" id="car_condition" class="form-control" required>
            <option value="">— Pilih Kondisi —</option>
            <option value="good"    <?= (($_POST['car_condition'] ?? '') === 'good')    ? 'selected' : '' ?>>Baik (Good) — Mobil kembali ke available</option>
            <option value="damaged" <?= (($_POST['car_condition'] ?? '') === 'damaged') ? 'selected' : '' ?>>Rusak (Damaged) — Mobil masuk maintenance</option>
          </select>
        </div>

        <!-- Notes -->
        <div style="margin-bottom:24px;">
          <label class="form-label" for="notes">Notes / Catatan</label>
          <textarea name="notes" id="notes" class="form-control" rows="3"
                    placeholder="Catatan kondisi kendaraan, kerusakan, dsb. (opsional)"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
        </div>

        <div style="display:flex;gap:10px;">
          <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Simpan Pengembalian
          </button>
          <a href="?page=manage_returns" class="btn btn-ghost">Batal</a>
        </div>

      </form>
    </div>
  </div>

</div>

<script>
  // Store end dates from select options
  var endDateMap = {};
  document.querySelectorAll('#booking_id option[data-end]').forEach(function(opt) {
    endDateMap[opt.value] = opt.getAttribute('data-end');
  });

  function fillEndDate(sel) {
    var end = endDateMap[sel.value] || '';
    updateLateHint(end);
  }

  function updateLateHint(endDate) {
    var hint = document.getElementById('late-hint');
    var retVal = document.getElementById('return_date').value;
    if (!endDate || !retVal) { hint.textContent = ''; return; }
    var ret = new Date(retVal);
    var end = new Date(endDate);
    if (ret > end) {
      var diff = Math.round((ret - end) / 86400000);
      hint.textContent = '⚠ Terlambat ' + diff + ' hari dari tanggal akhir sewa (' + endDate + '). late_days akan dihitung otomatis.';
      hint.style.color = '#ef4444';
    } else {
      hint.textContent = '✓ Tepat waktu atau lebih awal dari tanggal akhir sewa (' + endDate + ').';
      hint.style.color = '#22c55e';
    }
  }

  // Init if booking already selected (POST re-render)
  (function() {
    var sel = document.getElementById('booking_id');
    if (sel && sel.value) {
      var end = endDateMap[sel.value] || '';
      updateLateHint(end);
    }
  })();

  document.getElementById('return_date').addEventListener('change', function() {
    var sel = document.getElementById('booking_id');
    var end = sel && sel.value ? (endDateMap[sel.value] || '') : '';
    updateLateHint(end);
  });
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
