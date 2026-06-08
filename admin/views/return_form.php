<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=manage_returns" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Returns
    </a>
  </div>

  <div class="page-heading">
    <h1>Add Return Data</h1>
    <p>Record vehicle return from customer.</p>
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
      <span class="card-title">Return Form</span>
    </div>
    <div class="card-body">
      <form method="POST" action="?page=add_return">

        <!-- Booking ID -->
        <div style="margin-bottom:18px;">
          <label class="form-label" for="booking_id">Booking <span style="color:#ef4444;">*</span></label>
          <?php if (empty($bookings)): ?>
            <p class="text-sm text-gray-500 mt-1">
              No bookings without return data found.
            </p>
            <input type="hidden" name="booking_id" value="0" />
          <?php else: ?>
            <select name="booking_id" id="booking_id" class="form-control" required
                    onchange="fillEndDate(this)">
              <option value="">— Select Booking —</option>
              <?php foreach ($bookings as $b): ?>
                <option value="<?= $b['id'] ?>"
                        data-end="<?= htmlspecialchars($b['end_date']) ?>"
                        <?= (($_POST['booking_id'] ?? '') == $b['id']) ? 'selected' : '' ?>>
                  #<?= $b['id'] ?> — <?= htmlspecialchars($b['customer_name']) ?>
                  (<?= htmlspecialchars($b['car_name']) ?>)
                  | to <?= htmlspecialchars($b['end_date']) ?>
                  | <?= ucfirst($b['status']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <p class="text-xs text-gray-400 mt-1">Only completed bookings without returns are shown.</p>
          <?php endif; ?>
        </div>

        <!-- Return Date -->
        <div style="margin-bottom:18px;">
          <label class="form-label" for="return_date">Return Date <span style="color:#ef4444;">*</span></label>
          <input type="date" name="return_date" id="return_date" class="form-control"
                 value="<?= htmlspecialchars($_POST['return_date'] ?? date('Y-m-d')) ?>"
                 required />
          <p class="text-xs text-gray-400 mt-1" id="late-hint"></p>
        </div>

        <!-- Car Condition -->
        <div style="margin-bottom:18px;">
          <label class="form-label" for="car_condition">Car Condition <span style="color:#ef4444;">*</span></label>
          <select name="car_condition" id="car_condition" class="form-control" required
                  onchange="toggleDamageFine()">
            <option value="">— Select Condition —</option>
            <option value="good"    <?= (($_POST['car_condition'] ?? '') === 'good')    ? 'selected' : '' ?>>Good — Car becomes available</option>
            <option value="damaged" <?= (($_POST['car_condition'] ?? '') === 'damaged') ? 'selected' : '' ?>>Damaged — Car goes to maintenance</option>
          </select>
        </div>

        <!-- Damage Fine -->
        <div id="damage_fine_wrapper" style="margin-bottom:18px; display: <?= (($_POST['car_condition'] ?? '') === 'damaged') ? 'block' : 'none' ?>;">
          <label class="form-label" for="damage_fine">Damage Fine (Rp)</label>
          <input type="number" name="damage_fine" id="damage_fine" class="form-control"
                 placeholder="Enter fine amount for damage" min="0" step="any"
                 value="<?= htmlspecialchars($_POST['damage_fine'] ?? '') ?>" />
          <p class="text-xs text-gray-400 mt-1">This amount will be added to the late return fine.</p>
        </div>

        <!-- Notes -->
        <div style="margin-bottom:24px;">
          <label class="form-label" for="notes">Notes</label>
          <textarea name="notes" id="notes" class="form-control" rows="3"
                    placeholder="Notes on car condition, damages, etc. (optional)"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
        </div>

        <div style="display:flex;gap:10px;">
          <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Save Return
          </button>
          <a href="?page=manage_returns" class="btn btn-ghost">Cancel</a>
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
      hint.textContent = '⚠ Late by ' + diff + ' days from rental end date (' + endDate + '). late_days will be calculated automatically.';
      hint.style.color = '#ef4444';
    } else {
      hint.textContent = '✓ On time or earlier than rental end date (' + endDate + ').';
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

  // Init if booking already selected (POST re-render)
  (function() {
    var sel = document.getElementById('booking_id');
    if (sel && sel.value) {
      var end = endDateMap[sel.value] || '';
      updateLateHint(end);
    }
    toggleDamageFine();
  })();

  document.getElementById('return_date').addEventListener('change', function() {
    var sel = document.getElementById('booking_id');
    var end = sel && sel.value ? (endDateMap[sel.value] || '') : '';
    updateLateHint(end);
  });
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
