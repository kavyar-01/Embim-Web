<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div class="page-heading">
    <h1>Manage Returns</h1>
    <p>Record and manage customer vehicle returns.</p>
  </div>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Return data successfully deleted.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      <?= htmlspecialchars($_GET['error'] === 'not_found' ? 'Data not found.' : 'An error occurred.') ?>
    </div>
  <?php endif; ?>

  <!-- Filter Bar -->
  <form method="GET" action="" class="filter-bar">
    <input type="hidden" name="page" value="manage_returns" />

    <div class="search-wrap">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
      <input type="search" name="search"
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
             placeholder="Booking ID, customer name, or car..."
             class="form-control w-64" />
    </div>

    <select name="condition" class="form-control" style="width:auto;">
      <option value=""       <?= (($_GET['condition'] ?? '') === '')        ? 'selected' : '' ?>>All Conditions</option>
      <option value="good"   <?= (($_GET['condition'] ?? '') === 'good')   ? 'selected' : '' ?>>Good</option>
      <option value="damaged"<?= (($_GET['condition'] ?? '') === 'damaged') ? 'selected' : '' ?>>Damaged</option>
    </select>

    <input type="date" name="return_date"
           value="<?= htmlspecialchars($_GET['return_date'] ?? '') ?>"
           class="form-control" style="width:auto;"
           title="Filter by return date" />

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="?page=manage_returns" class="btn btn-ghost btn-sm">Reset</a>
    <a href="?page=add_return" class="btn btn-primary btn-sm" style="margin-left:auto;">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Add Return
    </a>
  </form>

  <!-- Table -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500">
        <tr>
          <th class="px-5 py-4">Booking ID</th>
          <th class="px-5 py-4">Customer</th>
          <th class="px-5 py-4">Car</th>
          <th class="px-5 py-4">Rental Period</th>
          <th class="px-5 py-4">Total Price</th>
          <th class="px-5 py-4 text-center">Payment Status</th>
          <th class="px-5 py-4 text-center">Booking Status</th>
          <th class="px-5 py-4">Return Status</th>
          <th class="px-5 py-4 text-center">Review Status</th>
          <th class="px-5 py-4 text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($returns)): ?>
          <tr><td colspan="10" class="text-center py-10 text-gray-400">No return data found.</td></tr>
        <?php else: foreach ($returns as $r):
          $condClass = $r['car_condition'] === 'good' ? 'badge badge-paid' : 'badge badge-unpaid';
          $condLabel = $r['car_condition'] === 'good' ? 'Baik' : 'Rusak';
        ?>
        <tr class="hover:bg-blue-50/30 transition-colors group border-b border-gray-50">
          <td class="px-5 py-4 font-medium text-gray-900">#<?= str_pad((string)$r['booking_id'], 4, '0', STR_PAD_LEFT) ?></td>
          <td class="px-5 py-4 font-medium text-gray-800"><?= htmlspecialchars($r['customer_name']) ?></td>
          <td class="px-5 py-4">
            <?= htmlspecialchars($r['car_name']) ?><br>
            <span class="text-xs text-gray-500 font-mono"><?= htmlspecialchars($r['license_plate']) ?></span>
          </td>
          <td class="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">
            <?= date('d M Y', strtotime($r['start_date'])) ?> - <?= date('d M Y', strtotime($r['end_date'])) ?>
          </td>
          <td class="px-5 py-4 font-bold text-gray-900 whitespace-nowrap">
            Rp <?= number_format($r['total_price'] ?? 0, 0, ',', '.') ?>
          </td>
          <td class="px-5 py-4 text-center">
            <?php
              $ps = $r['payment_status'] ?? 'unpaid';
              $psCls = match($ps) {
                'paid' => 'bg-green-100 text-green-700 border-green-200',
                'unpaid' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                'refunded' => 'bg-gray-100 text-gray-700 border-gray-200',
                default => 'bg-gray-100 text-gray-700 border-gray-200'
              };
            ?>
            <span class="px-2.5 py-1 border rounded-full text-xs font-bold uppercase tracking-wider <?= $psCls ?>">
              <?= htmlspecialchars($ps) ?>
            </span>
          </td>
          <td class="px-5 py-4 text-center">
            <?php
              $bs = $r['booking_status'] ?? 'completed';
              $bsCls = match($bs) {
                'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                'ongoing' => 'bg-blue-100 text-blue-700 border-blue-200',
                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                default => 'bg-gray-100 text-gray-700 border-gray-200'
              };
            ?>
            <span class="px-2.5 py-1 border rounded-full text-xs font-bold uppercase tracking-wider <?= $bsCls ?>">
              <?= htmlspecialchars($bs) ?>
            </span>
          </td>
          <td class="px-5 py-4">
            <div class="flex flex-col gap-1">
              <?php if ((int)$r['late_days'] > 0): ?>
                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[11px] font-bold uppercase rounded w-max">Late <?= (int)$r['late_days'] ?> Days</span>
              <?php else: ?>
                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[11px] font-bold uppercase rounded w-max">On Time</span>
              <?php endif; ?>
              
              <?php if ($r['car_condition'] === 'good'): ?>
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[11px] font-bold uppercase rounded w-max">Good Condition</span>
              <?php else: ?>
                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[11px] font-bold uppercase rounded w-max">Damaged</span>
              <?php endif; ?>
            </div>
          </td>
          <td class="px-5 py-4 text-center">
            <?php if (!empty($r['review_count']) && $r['review_count'] > 0): ?>
              <span class="px-2.5 py-1 bg-purple-100 text-purple-700 border border-purple-200 rounded-full text-xs font-bold uppercase tracking-wider">Reviewed</span>
            <?php else: ?>
              <span class="px-2.5 py-1 bg-gray-100 text-gray-500 border border-gray-200 rounded-full text-xs font-bold uppercase tracking-wider">Pending</span>
            <?php endif; ?>
          </td>
          <td class="px-5 py-4">
            <div class="flex items-center justify-end gap-2">
              <a href="?page=return_detail&id=<?= $r['id'] ?>" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="View Details">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </a>
              <a href="?page=edit_return&id=<?= $r['id'] ?>" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-100" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </a>
              <form method="POST" action="?page=delete_return" onsubmit="return confirm('Delete this return data? Booking and car statuses will be reverted.')" class="inline-block">
                <input type="hidden" name="id" value="<?= $r['id'] ?>" />
                <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

  <?php
    $totalPages  = (int) ($totalPages  ?? 1);
    $currentPage = (int) ($currentPage ?? 1);
    $total       = (int) ($total       ?? 0);
    $baseUrl = '?page=manage_returns'
        . (!empty($_GET['condition'])   ? '&condition='   . urlencode($_GET['condition'])   : '')
        . (!empty($_GET['search'])      ? '&search='      . urlencode($_GET['search'])      : '')
        . (!empty($_GET['return_date']) ? '&return_date=' . urlencode($_GET['return_date']) : '');
    $from = $total > 0 ? min($total, ($currentPage - 1) * 10 + 1) : 0;
    $to   = min($total, $currentPage * 10);
  ?>
  <div class="embim-pagination">
    <p class="embim-pagination__info">Showing <?= $from ?>–<?= $to ?> of <?= $total ?> results</p>
    <div class="embim-pagination__pages">
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
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
