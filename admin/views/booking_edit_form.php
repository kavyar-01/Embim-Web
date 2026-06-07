<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=booking_detail&id=<?= (int)$booking['id'] ?>" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Details
    </a>
  </div>

  <div class="page-heading">
    <h1>Edit Booking</h1>
    <p>Booking #<?= (int)$booking['id'] ?> — <?= htmlspecialchars($booking['customer_name']) ?></p>
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
      <div class="card-header"><span class="card-title">Edit Booking Data</span></div>
      <div class="card-body">
        <form method="POST" action="?page=edit_booking&id=<?= (int)$booking['id'] ?>" onsubmit="event.preventDefault(); showConfirmModal(this);">

          <!-- Pelanggan (readonly) -->
          <div style="margin-bottom:18px;">
            <label class="form-label">Customer</label>
            <input type="text" class="form-control"
                   value="<?= htmlspecialchars($booking['customer_name']) ?> (<?= htmlspecialchars($booking['customer_email']) ?>)"
                   disabled style="background:#f9fafb;color:#6b7280;" />
            <p class="text-xs text-gray-400 mt-1">Customer data cannot be changed.</p>
          </div>

          <!-- Kendaraan (readonly) -->
          <div style="margin-bottom:18px;">
            <label class="form-label">Vehicle</label>
            <input type="text" class="form-control"
                   value="<?= htmlspecialchars($booking['car_name']) ?> — <?= htmlspecialchars($booking['license_plate']) ?>"
                   disabled style="background:#f9fafb;color:#6b7280;" />
          </div>

          <!-- Status -->
          <div style="margin-bottom:18px;">
            <label class="form-label" for="status">Status <span style="color:#ef4444;">*</span></label>
            <select name="status" id="status" class="form-control" required>
              <?php
                $statuses = ['confirmed', 'ongoing', 'completed', 'cancelled'];
                $current  = $_POST['status'] ?? $booking['status'];
                foreach ($statuses as $s):
              ?>
              <option value="<?= $s ?>" <?= $current === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Notes -->
          <div style="margin-bottom:24px;">
            <label class="form-label" for="notes">Notes</label>
            <textarea name="notes" id="notes" class="form-control" rows="3"
                      placeholder="Optional"><?= htmlspecialchars($_POST['notes'] ?? $booking['notes'] ?? '') ?></textarea>
          </div>

          <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Save Changes
            </button>
            <a href="?page=booking_detail&id=<?= (int)$booking['id'] ?>" class="btn btn-ghost">Cancel</a>
          </div>

        </form>
      </div>
    </div>

    <!-- Info Booking (readonly) -->
    <div class="card">
      <div class="card-header"><span class="card-title">Booking Information</span></div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Booking ID</dt><dd>#<?= (int)$booking['id'] ?></dd></div>
        <div class="detail-row"><dt>Start Date</dt><dd><?= htmlspecialchars($booking['start_date']) ?></dd></div>
        <div class="detail-row"><dt>End Date</dt><dd><?= htmlspecialchars($booking['end_date']) ?></dd></div>
        <div class="detail-row"><dt>Total Days</dt><dd><?= (int)$booking['total_days'] ?> days</dd></div>
        <div class="detail-row">
          <dt>Total Price</dt>
          <dd><strong>Rp <?= number_format((float)$booking['total_price'], 0, ',', '.') ?></strong></dd>
        </div>
        <div class="detail-row"><dt>Price/Day</dt><dd>Rp <?= number_format((float)$booking['price_per_day'], 0, ',', '.') ?></dd></div>
      </dl>
      <div style="padding:12px 16px 0;border-top:1px solid #f3f4f6;margin-top:8px;">
        <p class="text-xs text-gray-400">Date and price cannot be changed here as they relate to payment data.</p>
      </div>
    </div>

  </div>

</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
