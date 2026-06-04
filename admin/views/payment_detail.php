<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=manage_payments" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Payments
    </a>
  </div>

  <div class="page-heading">
    <h1>Payment Detail</h1>
    <p>Booking #<?= $payment['booking_id'] ?></p>
  </div>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Status updated successfully.
    </div>
  <?php endif; ?>
  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      Failed to update status. Please try again.
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Payment Info -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Payment Information</span>
        <?php
          $badgeClass = match($payment['status']) {
              'paid'      => 'badge badge-paid',
              'refunded'  => 'badge badge-refunded',
              default     => 'badge badge-unpaid',
          };
        ?>
        <span class="<?= $badgeClass ?>"><?= ucfirst($payment['status']) ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Booking ID</dt><dd>#<?= $payment['booking_id'] ?></dd></div>
        <div class="detail-row"><dt>Method</dt><dd><?= htmlspecialchars(str_replace('_', ' ', $payment['payment_method'])) ?></dd></div>
        <div class="detail-row"><dt>Amount</dt><dd><strong>Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?></strong></dd></div>
        <div class="detail-row"><dt>Paid At</dt><dd><?= $payment['paid_at'] ? htmlspecialchars($payment['paid_at']) : '—' ?></dd></div>
        <div class="detail-row"><dt>Created At</dt><dd><?= htmlspecialchars($payment['created_at']) ?></dd></div>
        <div class="detail-row"><dt>Updated At</dt><dd><?= htmlspecialchars($payment['updated_at']) ?></dd></div>
      </dl>
    </div>

    <!-- Booking & Customer Info -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Booking &amp; Customer</span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Customer</dt><dd><strong><?= htmlspecialchars($payment['customer_name']) ?></strong></dd></div>
        <div class="detail-row"><dt>Email</dt><dd><?= htmlspecialchars($payment['customer_email']) ?></dd></div>
        <div class="detail-row"><dt>Car</dt><dd><?= htmlspecialchars($payment['car_name']) ?></dd></div>
        <div class="detail-row"><dt>License Plate</dt><dd><?= htmlspecialchars($payment['license_plate']) ?></dd></div>
        <div class="detail-row"><dt>Rental Period</dt><dd><?= $payment['start_date'] ?> → <?= $payment['end_date'] ?> (<?= $payment['total_days'] ?> days)</dd></div>
        <div class="detail-row"><dt>Total Price</dt><dd><strong>Rp <?= number_format((float)$payment['total_price'], 0, ',', '.') ?></strong></dd></div>
        <div class="detail-row"><dt>Booking Status</dt><dd><?= ucfirst($payment['booking_status']) ?></dd></div>
        <?php if (!empty($payment['notes'])): ?>
        <div class="detail-row"><dt>Notes</dt><dd><?= htmlspecialchars($payment['notes']) ?></dd></div>
        <?php endif; ?>
      </dl>
    </div>

  </div>

  <!-- Payment Proof -->
  <?php if (!empty($payment['payment_proof'])): ?>
  <div class="card">
    <div class="card-header"><span class="card-title">Payment Proof</span></div>
    <div class="card-body">
      <?php
        $proof   = htmlspecialchars($payment['payment_proof']);
        $ext     = strtolower(pathinfo($payment['payment_proof'], PATHINFO_EXTENSION));
        $imgExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
      ?>
      <?php if (in_array($ext, $imgExts, true)): ?>
        <img src="assets/images/<?= $proof ?>" alt="Payment Proof" class="proof-img" />
        <div class="mt-3">
          <a href="assets/images/<?= $proof ?>" target="_blank" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Open in new tab
          </a>
        </div>
      <?php else: ?>
        <a href="assets/images/<?= $proof ?>" target="_blank" class="back-link">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
          <?= $proof ?>
        </a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- Update Status -->
  <div class="card">
    <div class="card-header"><span class="card-title">Update Payment Status</span></div>
    <div class="card-body">
      <form method="POST" action="?page=update_payment_status" class="flex items-center gap-3 flex-wrap">
        <input type="hidden" name="id" value="<?= $payment['id'] ?>" />
        <select name="status" class="form-control" style="width:auto;">
          <option value="unpaid"   <?= $payment['status'] === 'unpaid'   ? 'selected' : '' ?>>Unpaid</option>
          <option value="paid"     <?= $payment['status'] === 'paid'     ? 'selected' : '' ?>>Paid</option>
          <option value="refunded" <?= $payment['status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
        </select>
        <button type="submit" class="btn btn-primary">Save Status</button>
        <a href="?page=manage_payments" class="btn btn-ghost">Cancel</a>
      </form>
      <p class="mt-3 text-xs text-gray-400">Changing status to <strong>Paid</strong> will automatically record the current timestamp as the payment date.</p>
    </div>
  </div>

</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
