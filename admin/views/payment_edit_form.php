<?php require_once __DIR__ . '/partials/layout_top.php'; ?>

<div class="space-y-6 max-w-2xl mx-auto pb-10">

  <div>
    <a href="?page=manage_payments" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Manage Payments
    </a>
  </div>

  <div class="page-heading">
    <h1>Edit Payment Status</h1>
    <p>Update the payment status for Booking <?= (int)$payment['booking_id'] ?></p>
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert alert-error mb-4 flex items-center gap-2 text-red-600 bg-red-50 p-4 rounded-xl border border-red-100">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <div class="card p-6">
    <form method="POST" action="" onsubmit="event.preventDefault(); showConfirmModal(this);">
      <div class="form-group mb-4">
        <label class="form-label block text-sm font-semibold text-gray-700 mb-1">Customer</label>
        <div class="form-control w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-500"><?= htmlspecialchars($payment['customer_name']) ?> (<?= htmlspecialchars($payment['customer_email']) ?>)</div>
      </div>

      <div class="form-group mb-4">
        <label class="form-label block text-sm font-semibold text-gray-700 mb-1">Total Price</label>
        <div class="form-control w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 font-bold">Rp <?= number_format($payment['total_price'], 0, ',', '.') ?></div>
      </div>

      <div class="form-group mb-4">
        <label class="form-label block text-sm font-semibold text-gray-700 mb-1">Payment Method</label>
        <div class="form-control w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-500 capitalize"><?= $payment['payment_method'] ? str_replace('_', ' ', htmlspecialchars($payment['payment_method'])) : '-' ?></div>
      </div>

      <div class="form-group mb-6">
        <label class="form-label block text-sm font-semibold text-gray-700 mb-1" for="payment_status">Payment Status</label>
        <select name="payment_status" id="payment_status" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" required>
          <?php if ($payment['payment_status'] === 'unpaid'): ?>
            <option value="unpaid" hidden selected>Unpaid</option>
          <?php endif; ?>
          <option value="paid" <?= $payment['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
          <option value="refunded" <?= $payment['payment_status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
        </select>
        <p class="text-xs text-gray-500 mt-2">Setting the status to "Paid" will automatically record the current time as the payment time.</p>
      </div>

      <div class="mt-8 flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">Save Changes</button>
        <a href="?page=payment_detail&id=<?= (int)$payment['booking_id'] ?>" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">Cancel</a>
      </div>
    </form>
  </div>

</div>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
