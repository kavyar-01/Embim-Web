<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6 pb-10">
  <div class="page-heading">
    <h1>Manage Payments</h1>
    <p>View and monitor customer payment details.</p>
  </div>

  <?php
    $fStatus = $status ?? '';
    $fSearch = $search ?? '';
    $fMonth  = $month ?? '';
    
    $baseUrl = '?page=manage_payments'
        . (!empty($fStatus) ? '&status=' . urlencode($fStatus) : '')
        . (!empty($fSearch) ? '&search=' . urlencode($fSearch) : '')
        . (!empty($fMonth)  ? '&month=' . urlencode($fMonth)   : '');
    $from = $total > 0 ? min($total, ($currentPage - 1) * 10 + 1) : 0;
    $to   = min($total, $currentPage * 10);
  ?>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-[0_0_20px_rgba(59,130,246,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Payments</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['total'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
      </div>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-green-300 hover:shadow-[0_0_20px_rgba(34,197,94,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Paid</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['paid'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-green-50 text-green-600 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
      </div>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-yellow-300 hover:shadow-[0_0_20px_rgba(234,179,8,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Unpaid</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['unpaid'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-yellow-50 text-yellow-600 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-gray-300 hover:shadow-[0_0_20px_rgba(156,163,175,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Refunded</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['refunded'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-gray-50 text-gray-500 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
      </div>
    </div>
  </div>

  <!-- FILTER FORM -->
  <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
    <form action="" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
      <input type="hidden" name="page" value="manage_payments">
      
      <div class="flex-1 w-full">
        <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Search</label>
        <div class="relative">
          <input type="text" name="search" value="<?= htmlspecialchars($fSearch) ?>" placeholder="Booking ID, Customer, Car, or Method"
            class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" />
          <svg class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>
      
      <div class="w-full md:w-48">
        <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Month</label>
        <input type="month" name="month" value="<?= htmlspecialchars($fMonth) ?>" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
      </div>

      <div class="w-full md:w-48">
        <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Status</label>
        <select name="status" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
          <option value="">All Statuses</option>
          <option value="unpaid" <?= $fStatus==='unpaid' ? 'selected' : '' ?>>Unpaid</option>
          <option value="paid" <?= $fStatus==='paid' ? 'selected' : '' ?>>Paid</option>
          <option value="refunded" <?= $fStatus==='refunded' ? 'selected' : '' ?>>Refunded</option>
        </select>
      </div>

      <div class="flex gap-2 w-full md:w-auto">
        <button type="submit" class="flex-1 md:flex-none px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
          Filter
        </button>
        <?php if ($fStatus !== '' || $fSearch !== ''): ?>
          <a href="?page=manage_payments" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors" title="Reset Filters">
             Reset
          </a>
        <?php endif; ?>
      </div>
    </form>
  </div>

  <!-- PAYMENTS TABLE -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500">
          <tr>
            <th class="px-5 py-4">Booking ID</th>
            <th class="px-5 py-4">Customer</th>
            <th class="px-5 py-4">Car</th>
            <th class="px-5 py-4">Total Price</th>
            <th class="px-5 py-4">Payment Method</th>
            <th class="px-5 py-4">Status</th>
            <th class="px-5 py-4">Paid At</th>
            <th class="px-5 py-4 text-center">Proof</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <?php if (empty($payments)): ?>
            <tr>
              <td colspan="8" class="px-5 py-12 text-center text-gray-500">
                <div class="flex flex-col items-center justify-center">
                  <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  <p class="text-base font-medium">No payments found</p>
                  <p class="text-sm mt-1">Try adjusting your search or filter.</p>
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($payments as $p): ?>
              <tr class="hover:bg-blue-50/30 transition-colors group">
                <td class="px-5 py-4 font-medium text-gray-900">
                  <?= (int)$p['booking_id'] ?>
                </td>
                <td class="px-5 py-4 font-medium text-gray-800">
                  <?= htmlspecialchars($p['customer_name']) ?>
                </td>
                <td class="px-5 py-4">
                  <?= htmlspecialchars($p['car_name']) ?>
                </td>
                <td class="px-5 py-4 font-bold text-gray-900 whitespace-nowrap">
                  Rp <?= number_format($p['total_price'], 0, ',', '.') ?>
                </td>
                <td class="px-5 py-4">
                  <?php if ($p['payment_method']): ?>
                    <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-semibold capitalize whitespace-nowrap">
                      <?= str_replace('_', ' ', htmlspecialchars($p['payment_method'])) ?>
                    </span>
                  <?php else: ?>
                    <span class="text-gray-400 text-xs italic">-</span>
                  <?php endif; ?>
                </td>
                <td class="px-5 py-4">
                  <?php
                    $sc = match($p['payment_status']) {
                      'paid'     => 'bg-green-100 text-green-700 border-green-200',
                      'unpaid'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                      'refunded' => 'bg-gray-100 text-gray-700 border-gray-200',
                      default    => 'bg-gray-100 text-gray-700 border-gray-200'
                    };
                  ?>
                  <span class="px-2.5 py-1 border rounded-full text-xs font-bold uppercase tracking-wider <?= $sc ?>">
                    <?= htmlspecialchars($p['payment_status']) ?>
                  </span>
                </td>
                <td class="px-5 py-4 text-sm text-gray-500 whitespace-nowrap">
                  <?= $p['paid_at'] ? date('d M Y, H:i', strtotime($p['paid_at'])) : '<span class="italic text-gray-400">-</span>' ?>
                </td>
                <td class="px-5 py-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <a href="?page=payment_detail&id=<?= $p['booking_id'] ?>" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="View Details">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <a href="?page=edit_payment&id=<?= $p['booking_id'] ?>" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-100" title="Edit Payment Status">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="embim-pagination px-5 py-4 border-t border-gray-100 bg-gray-50">
      <p class="embim-pagination__info text-sm text-gray-500">Showing <?= $from ?>–<?= $to ?> of <?= $total ?> results</p>
      <div class="embim-pagination__pages flex items-center gap-2">
        <?php if ($currentPage > 1): ?>
          <a href="<?= $baseUrl ?>&p=<?= $currentPage - 1 ?>" class="embim-pagination__btn">← Previous</a>
        <?php else: ?>
          <span class="embim-pagination__btn embim-pagination__btn--disabled">← Previous</span>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i === 1 || $i === $totalPages || abs($i - $currentPage) <= 1): ?>
            <a href="<?= $baseUrl ?>&p=<?= $i ?>" class="embim-pagination__btn <?= $i === $currentPage ? 'embim-pagination__btn--active' : '' ?>"><?= $i ?></a>
          <?php elseif (($i === 2 && $currentPage > 3) || ($i === $totalPages - 1 && $currentPage < $totalPages - 2)): ?>
            <span class="embim-pagination__btn embim-pagination__btn--disabled">…</span>
          <?php endif; ?>
        <?php endfor; ?>
        <?php if ($currentPage < $totalPages): ?>
          <a href="<?= $baseUrl ?>&p=<?= $currentPage + 1 ?>" class="embim-pagination__btn">Next →</a>
        <?php else: ?>
          <span class="embim-pagination__btn embim-pagination__btn--disabled">Next →</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
