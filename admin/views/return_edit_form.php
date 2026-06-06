<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=return_detail&id=<?= $return['id'] ?>" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Detail
    </a>
  </div>

  <div class="page-heading">
    <h1>Edit Return Data</h1>
    <p>Return <?= $return['id'] ?> — Booking <?= $return['booking_id'] ?></p>
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
      <div class="card-header"><span class="card-title">Edit Return Data</span></div>
      <div class="card-body">
        <form method="POST" action="?page=edit_return&id=<?= $return['id'] ?>">

          <!-- Booking (readonly) -->
          <div style="margin-bottom:18px;">
            <label class="form-label">Booking</label>
            <input type="text" class="form-control"
                   value="#<?= $return['booking_id'] ?> — <?= htmlspecialchars($return['customer_name']) ?> (<?= htmlspecialchars($return['car_name']) ?>)"
                   disabled style="background:#f9fafb;color:#6b7280;" />
            <p class="text-xs text-gray-400 mt-1">Booking ID cannot be changed.</p>
          </div>

          <!-- Return Date -->
          <div style="margin-bottom:18px;">
            <label class="form-label" for="return_date">Return Date <span style="color:#ef4444;">*</span></label>
            <input type="date" name="return_date" id="return_date" class="form-control"
                   value="<?= htmlspecialchars($_POST['return_date'] ?? $return['return_date']) ?>"
                   data-end="<?= htmlspecialchars($return['end_date']) ?>"
                   required />
            <p class="text-xs text-gray-400 mt-1" id="late-hint"></p>
          </div>

          <!-- Car Condition -->
          <div style="margin-bottom:18px;">
            <label class="form-label" for="car_condition">Car Condition <span style="color:#ef4444;">*</span></label>
            <select name="car_condition" id="car_condition" class="form-control" required
                    onchange="toggleDamageFine()">
              <option value="good"    <?= (($_POST['car_condition'] ?? $return['car_condition']) === 'good')    ? 'selected' : '' ?>>Good</option>
              <option value="damaged" <?= (($_POST['car_condition'] ?? $return['car_condition']) === 'damaged') ? 'selected' : '' ?>>Damaged</option>
            </select>
          </div>

          <?php
            // Hitung eksisting damage fine
            $existingLateFine = (int)$return['late_days'] > 0 ? (int)$return['late_days'] * (float)$return['fine_per_day'] : 0;
            $existingDamageFine = max(0, (float)$return['fine_amount'] - $existingLateFine);
            $damageFineValue = $_POST['damage_fine'] ?? $existingDamageFine;
            if ($damageFineValue == 0) $damageFineValue = ''; // Biar kosong kalo 0
          ?>

          <!-- Damage Fine -->
          <div id="damage_fine_wrapper" style="margin-bottom:18px; display: <?= (($_POST['car_condition'] ?? $return['car_condition']) === 'damaged') ? 'block' : 'none' ?>;">
            <label class="form-label" for="damage_fine">Damage Fine (Rp)</label>
            <input type="text" name="damage_fine" id="damage_fine" class="form-control"
                   placeholder="Enter fine amount for damage"
                   value="<?= htmlspecialchars((string)$damageFineValue) ?>" />
            <p class="text-xs text-gray-400 mt-1">This amount will be added to the late return fine.</p>
          </div>

          <!-- Notes -->
          <div style="margin-bottom:24px;">
            <label class="form-label" for="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control" rows="3"
                      placeholder="Optional"><?= htmlspecialchars($_POST['notes'] ?? $return['notes'] ?? '') ?></textarea>
          </div>

          <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Save Changes
            </button>
            <a href="?page=return_detail&id=<?= $return['id'] ?>" class="btn btn-ghost">Cancel</a>
          </div>

        </form>
      </div>
    </div>

    <!-- Info Booking Terkait -->
    <div class="card">
      <div class="card-header"><span class="card-title">Related Booking Info</span></div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Booking ID</dt><dd><?= $return['booking_id'] ?></dd></div>
        <div class="detail-row"><dt>Customer</dt><dd><?= htmlspecialchars($return['customer_name']) ?></dd></div>
        <div class="detail-row"><dt>Car</dt><dd><?= htmlspecialchars($return['car_name']) ?></dd></div>
        <div class="detail-row"><dt>License Plate</dt><dd><?= htmlspecialchars($return['license_plate']) ?></dd></div>
        <div class="detail-row"><dt>Start Date</dt><dd><?= $return['start_date'] ?></dd></div>
        <div class="detail-row"><dt>End Date</dt><dd><strong><?= $return['end_date'] ?></strong></dd></div>
        <div class="detail-row"><dt>Duration</dt><dd><?= $return['total_days'] ?> days</dd></div>
        <div class="detail-row">
          <dt>Current Late Days</dt>
          <dd>
            <?php if ((int)$return['late_days'] > 0): ?>
              <span class="badge" style="background:#fef2f2;color:#dc2626;border-color:#fecaca;"><?= $return['late_days'] ?> days</span>
            <?php else: ?>
              <span class="badge badge-paid">On time</span>
            <?php endif; ?>
          </dd>
        </div>
      </dl>
      <div style="padding:12px 16px 0;border-top:1px solid #f3f4f6;margin-top:8px;">
        <p class="text-xs text-gray-400">Late days will be recalculated automatically if return date is changed.</p>
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
      hint.textContent = '⚠ Late by ' + diff + ' days from ' + endDate + '.';
      hint.style.color = '#ef4444';
    } else {
      hint.textContent = '✓ On time or earlier than ' + endDate + '.';
      hint.style.color = '#22c55e';
    }
  }

  function toggleDamageFine() {
    var cc = document.getElementById('car_condition').value;
    var wrapper = document.getElementById('damage_fine_wrapper');
    if (cc === 'damaged') {
      wrapper.style.display = 'block';
    } else {
      wrapper.style.display = 'none';
    }
  }

  updateLateHint();
  toggleDamageFine();
  document.getElementById('return_date').addEventListener('change', updateLateHint);
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
