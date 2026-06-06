<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6 pb-10">

  <div>
    <a href="?page=manage_payments" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Manage Payments
    </a>
  </div>

  <div class="page-heading">
    <h1>Payment Details</h1>
    <p>Booking <?= (int)$payment['booking_id'] ?> — <?= htmlspecialchars($payment['customer_name']) ?></p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Payment Info & Actions -->
    <div class="lg:col-span-1 space-y-6">
      
      <!-- Status Card -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col items-center text-center">
        <?php
          $sc = match($payment['payment_status']) {
            'paid'     => 'bg-green-100 text-green-700 border-green-200',
            'unpaid'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'refunded' => 'bg-gray-100 text-gray-700 border-gray-200',
            default    => 'bg-gray-100 text-gray-700 border-gray-200'
          };
        ?>
        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-3 <?= $sc ?> bg-opacity-50">
          <?php if($payment['payment_status'] === 'paid'): ?>
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
          <?php elseif($payment['payment_status'] === 'unpaid'): ?>
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <?php else: ?>
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
          <?php endif; ?>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Rp <?= number_format($payment['total_price'], 0, ',', '.') ?></h2>
        <span class="mt-2 px-3 py-1 border rounded-full text-xs font-bold uppercase tracking-wider <?= $sc ?>">
          <?= htmlspecialchars($payment['payment_status']) ?>
        </span>
      </div>

      <!-- Payment Summary -->
      <div class="card">
        <div class="card-header"><span class="card-title">Payment Summary</span></div>
        <dl class="detail-dl">
          <div class="detail-row"><dt>Booking ID</dt><dd><strong><?= (int)$payment['booking_id'] ?></strong></dd></div>
          <div class="detail-row">
            <dt>Method</dt>
            <dd>
              <?php if ($payment['payment_method']): ?>
                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-semibold capitalize">
                  <?= str_replace('_', ' ', htmlspecialchars($payment['payment_method'])) ?>
                </span>
              <?php else: ?>
                <span class="td-muted">-</span>
              <?php endif; ?>
            </dd>
          </div>
          <div class="detail-row">
            <dt>Paid At</dt>
            <dd><?= $payment['paid_at'] ? date('d M Y, H:i', strtotime($payment['paid_at'])) : '<span class="td-muted">-</span>' ?></dd>
          </div>
          <div class="detail-row">
            <dt>Booking Status</dt>
            <dd>
              <?php
                $bs = $payment['booking_status'] ?? '';
                $bsCls = match($bs) {
                  'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                  'ongoing'   => 'bg-blue-100 text-blue-700 border-blue-200',
                  'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                  'confirmed' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                  'pending'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                  default     => 'bg-gray-100 text-gray-700 border-gray-200'
                };
              ?>
              <span class="px-2.5 py-1 border rounded-md text-xs font-bold uppercase tracking-wider <?= $bsCls ?>">
                <?= htmlspecialchars($bs) ?>
              </span>
            </dd>
          </div>
        </dl>
      </div>
      
      <!-- Customer Info -->
      <div class="card">
        <div class="card-header"><span class="card-title">Customer</span></div>
        <div class="p-5 flex items-center gap-4 border-b border-gray-100">
          <div class="w-12 h-12 shrink-0 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
            <img src="../assets/images/user/<?= htmlspecialchars($payment['photo_profile'] ?? 'default.png') ?>" alt="Photo" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($payment['customer_name']) ?>&background=eff6ff&color=1d4ed8'" />
          </div>
          <div>
            <p class="font-bold text-gray-900"><?= htmlspecialchars($payment['customer_name']) ?></p>
            <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($payment['customer_email']) ?></p>
          </div>
        </div>
        <dl class="detail-dl border-t-0">
          <div class="detail-row"><dt>Phone</dt><dd><?= !empty($payment['customer_phone']) ? htmlspecialchars($payment['customer_phone']) : '<span class="td-muted">—</span>' ?></dd></div>
          <div class="detail-row"><dt>Car</dt><dd><strong><?= htmlspecialchars($payment['car_name']) ?></strong></dd></div>
          <div class="detail-row"><dt>Plate</dt><dd><span class="font-mono text-xs text-gray-600"><?= htmlspecialchars($payment['license_plate']) ?></span></dd></div>
        </dl>
      </div>

      <!-- Actions -->
      <div class="card">
        <div class="p-4">
          <a href="?page=edit_payment&id=<?= (int)$payment['booking_id'] ?>" class="btn w-full bg-blue-600 hover:bg-blue-700 text-white flex justify-center items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Payment Status
          </a>
        </div>
      </div>

    </div>

    <!-- Payment Proof -->
    <div class="lg:col-span-2">
      <div class="card h-full flex flex-col">
        <div class="card-header border-b border-gray-100">
          <span class="card-title flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Payment Proof
          </span>
        </div>
        <div class="p-6 flex-1 flex items-center justify-center bg-gray-50/50">
          <?php if (!empty($payment['payment_proof'])): ?>
            <div class="max-w-md w-full bg-white p-2 rounded-xl border border-gray-200 shadow-sm">
              <img src="../assets/images/<?= htmlspecialchars($payment['payment_proof']) ?>" alt="Payment Proof" class="w-full h-auto rounded-lg object-contain max-h-[600px]" onerror="this.onerror=null; this.src='https://via.placeholder.com/600x800?text=Image+Not+Found';" />
              <div class="mt-3 text-center pb-2">
                <a href="../assets/images/<?= htmlspecialchars($payment['payment_proof']) ?>" target="_blank" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                  Open Original Image ↗
                </a>
              </div>
            </div>
          <?php else: ?>
            <div class="text-center py-16">
              <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
              </div>
              <h3 class="text-lg font-bold text-gray-900">No Proof Uploaded</h3>
              <p class="text-sm text-gray-500 mt-1">The customer has not uploaded a payment receipt yet.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>
</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
