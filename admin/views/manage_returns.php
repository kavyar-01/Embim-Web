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

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Total Returns -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-[0_0_20px_rgba(59,130,246,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Returns</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= number_format((int)($stats['total'] ?? 0)) ?></p>
      </div>
      <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
      </div>
    </div>
    
    <!-- Good Condition -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-emerald-300 hover:shadow-[0_0_20px_rgba(16,185,129,0.3)]">
      <div>
        <p class="text-xs font-medium text-emerald-600 uppercase tracking-wide">Good Condition</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= number_format((int)($stats['good_count'] ?? 0)) ?></p>
      </div>
      <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      </div>
    </div>
    
    <!-- Damaged Condition -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-red-300 hover:shadow-[0_0_20px_rgba(239,68,68,0.3)]">
      <div>
        <p class="text-xs font-medium text-red-600 uppercase tracking-wide">Damaged</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= number_format((int)($stats['damaged_count'] ?? 0)) ?></p>
      </div>
      <div class="p-3 bg-red-50 text-red-600 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
      </div>
    </div>
  </div>

  <!-- Filter Bar -->
  <form method="GET" action="" class="filter-bar">
    <input type="hidden" name="page" value="manage_returns" />

    <div class="search-wrap">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
      <input type="search" name="search"
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
             placeholder="Return ID, Booking ID, customer, or car..."
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
    <table class="w-full text-left text-sm text-gray-600">
      <thead class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500">
        <tr>
          <th class="px-3 py-3">Return ID</th>
          <th class="px-3 py-3">Booking ID</th>
          <th class="px-3 py-3">Customer</th>
          <th class="px-3 py-3">Car</th>
          <th class="px-3 py-3">Return Date</th>
          <th class="px-3 py-3 text-center">Late Days</th>
          <th class="px-3 py-3 text-center">Condition</th>
          <th class="px-3 py-3 font-bold">Fine Amount</th>
          <th class="px-3 py-3 text-center">Fine Status</th>
          <th class="px-3 py-3 text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($returns)): ?>
          <tr><td colspan="10" class="text-center py-10 text-gray-400">No return data found.</td></tr>
        <?php else: foreach ($returns as $r):
          $condClass = $r['car_condition'] === 'good' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700';
          $condLabel = $r['car_condition'] === 'good' ? 'Good' : 'Damaged';
        ?>
        <tr class="hover:bg-blue-50/30 transition-colors group border-b border-gray-50">
          <td class="px-3 py-3 font-medium text-gray-900"><?= (int)$r['id'] ?></td>
          <td class="px-3 py-3 text-gray-500"><?= (int)$r['booking_id'] ?></td>
          <td class="px-3 py-3 font-medium text-gray-800"><?= htmlspecialchars($r['customer_name']) ?></td>
          <td class="px-3 py-3">
            <div class="font-medium text-gray-900"><?= htmlspecialchars($r['car_name']) ?></div>
            <div class="text-[10px] text-gray-500 font-mono"><?= htmlspecialchars($r['license_plate']) ?></div>
          </td>
          <td class="px-3 py-3 text-xs text-gray-600 font-medium">
            <?= date('d M Y, H:i', strtotime($r['return_date'])) ?>
          </td>
          <td class="px-3 py-3 text-center">
            <?php if ((int)$r['late_days'] > 0): ?>
              <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold uppercase rounded w-max"><?= (int)$r['late_days'] ?> Days</span>
            <?php else: ?>
              <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded w-max">None</span>
            <?php endif; ?>
          </td>
          <td class="px-3 py-3 text-center">
            <span class="px-2 py-0.5 <?= $condClass ?> text-[10px] font-bold uppercase rounded w-max"><?= $condLabel ?></span>
          </td>
          <td class="px-3 py-3 font-bold text-gray-900 whitespace-nowrap text-xs">
            <?php if ((int)$r['fine_amount'] > 0): ?>
              Rp <?= number_format($r['fine_amount'], 0, ',', '.') ?>
            <?php else: ?>
              <span class="text-gray-400 font-normal">-</span>
            <?php endif; ?>
          </td>
          <td class="px-3 py-3 text-center">
            <?php
              $fs = $r['fine_status'] ?? 'none';
              $fsCls = match($fs) {
                'paid' => 'bg-green-100 text-green-700 border-green-200',
                'unpaid' => 'bg-red-100 text-red-700 border-red-200',
                default => 'bg-gray-100 text-gray-500 border-gray-200'
              };
            ?>
            <span class="px-2 py-1 border rounded-md text-[10px] font-bold uppercase tracking-wider <?= $fsCls ?>">
              <?= htmlspecialchars($fs) ?>
            </span>
          </td>
          <td class="px-3 py-3">
            <div class="flex items-center justify-end gap-1.5">
              <a href="?page=return_detail&id=<?= $r['id'] ?>" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="View Details">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </a>
              <a href="?page=edit_return&id=<?= $r['id'] ?>" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-100" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </a>
              <button type="button" onclick="openDeleteModal(<?= (int)$r['id'] ?>)" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
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

<!-- Delete Confirmation Modal -->
<div id="modal-delete"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:16px;padding:32px;max-width:400px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);text-align:center;">
    <!-- Large Exclamation Icon -->
    <div style="margin: 0 auto 16px auto; width: 64px; height: 64px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
      <svg xmlns="http://www.w3.org/2000/svg" style="width:36px;height:36px;color:#dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <h3 style="font-weight:800;font-size:1.25rem;color:#111827;margin:0 0 8px 0;">Are you sure?</h3>
    <p style="font-size:0.875rem;color:#6b7280;margin:0 0 24px 0;line-height:1.5;">Are you sure you want to delete this data? This action cannot be undone.</p>
    <div style="display:flex;gap:12px;justify-content:center;">
      <button type="button" class="btn btn-ghost" style="flex:1;"
              onclick="document.getElementById('modal-delete').style.display='none'">Cancel</button>
      <form id="form-delete" method="POST" action="?page=delete_return" style="margin:0;flex:1;display:flex;">
        <input type="hidden" id="delete-id" name="id" value="" />
        <button type="submit" class="btn" style="width:100%;background:#dc2626;color:#fff;border:1px solid #dc2626;padding:10px 16px;border-radius:8px;font-weight:600;cursor:pointer;">
          Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<script>
function openDeleteModal(id) {
  document.getElementById('delete-id').value = id;
  document.getElementById('modal-delete').style.display = 'flex';
}
// Close modal if clicking outside
document.getElementById('modal-delete').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
