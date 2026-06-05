<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div class="page-heading">
    <h1>Manage Bookings</h1>
    <p>Review and manage customer vehicle bookings.</p>
  </div>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Booking data successfully deleted.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      <?= htmlspecialchars(match($_GET['error']) {
          'not_found'     => 'Data not found.',
          'invalid'       => 'Invalid request.',
          'delete_failed' => 'Failed to delete data. Please try again.',
          default         => 'An error occurred.',
      }) ?>
    </div>
  <?php endif; ?>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['pending'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-yellow-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Confirmed</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['confirmed'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-indigo-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ongoing</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['ongoing'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-blue-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Completed</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['completed'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-emerald-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Cancelled</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['cancelled'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-red-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </div>
    </div>
  </div>

  <!-- Filter Bar -->
  <form method="GET" action="" class="filter-bar">
    <input type="hidden" name="page" value="manage_bookings" />

    <div class="search-wrap">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
      <input type="search" name="search"
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
             placeholder="Booking ID, customer name, or car..."
             class="form-control w-64" />
    </div>

    <select name="status" class="form-control" style="width:auto;">
      <option value=""          <?= (($_GET['status'] ?? '') === '')          ? 'selected' : '' ?>>All Status</option>
      <option value="pending"   <?= (($_GET['status'] ?? '') === 'pending')   ? 'selected' : '' ?>>Pending</option>
      <option value="confirmed" <?= (($_GET['status'] ?? '') === 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
      <option value="ongoing"   <?= (($_GET['status'] ?? '') === 'ongoing')   ? 'selected' : '' ?>>Ongoing</option>
      <option value="completed" <?= (($_GET['status'] ?? '') === 'completed') ? 'selected' : '' ?>>Completed</option>
      <option value="cancelled" <?= (($_GET['status'] ?? '') === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
    <a href="?page=manage_bookings" class="btn btn-ghost btn-sm">Reset</a>
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
          <th class="px-5 py-4">Total Days</th>
          <th class="px-5 py-4 text-center">Booking Status</th>
          <th class="px-5 py-4">Notes</th>
          <th class="px-5 py-4">Created At</th>
          <th class="px-5 py-4 text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($bookings)): ?>
          <tr><td colspan="10" class="text-center py-10 text-gray-400">No booking data found.</td></tr>
        <?php else: foreach ($bookings as $b): ?>
        <tr class="hover:bg-blue-50/30 transition-colors group border-b border-gray-50">
          <td class="px-5 py-4 font-medium text-gray-900">#<?= str_pad((string)$b['id'], 4, '0', STR_PAD_LEFT) ?></td>
          <td class="px-5 py-4 font-medium text-gray-800"><?= htmlspecialchars($b['customer_name']) ?></td>
          <td class="px-5 py-4">
            <?= htmlspecialchars($b['car_name']) ?><br>
            <span class="text-xs text-gray-500 font-mono"><?= htmlspecialchars($b['license_plate']) ?></span>
          </td>
          <td class="px-5 py-4 text-sm text-gray-600 whitespace-nowrap">
            <?= date('d M Y', strtotime($b['start_date'])) ?> - <?= date('d M Y', strtotime($b['end_date'])) ?>
          </td>
          <td class="px-5 py-4 font-medium text-gray-800 whitespace-nowrap">
            <?= (int)$b['total_days'] ?> Days
          </td>
          <td class="px-5 py-4 text-center">
            <?php
              $bs = $b['status'] ?? 'completed';
              $bsCls = match($bs) {
                'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                'ongoing' => 'bg-blue-100 text-blue-700 border-blue-200',
                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                'confirmed' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                default => 'bg-gray-100 text-gray-700 border-gray-200'
              };
            ?>
            <span class="px-2.5 py-1 border rounded-full text-xs font-bold uppercase tracking-wider <?= $bsCls ?>">
              <?= htmlspecialchars($bs) ?>
            </span>
          </td>
          <td class="px-5 py-4 text-sm text-gray-500 truncate max-w-[150px]" title="<?= htmlspecialchars($b['notes'] ?? '') ?>">
            <?= !empty($b['notes']) ? htmlspecialchars($b['notes']) : '<span class="italic text-gray-400">—</span>' ?>
          </td>
          <td class="px-5 py-4 text-sm text-gray-500 whitespace-nowrap">
            <?= htmlspecialchars(date('d M Y, H:i', strtotime($b['created_at']))) ?>
          </td>
          <td class="px-5 py-4">
            <div class="flex items-center justify-end gap-2">
              <a href="?page=booking_detail&id=<?= $b['id'] ?>" class="p-1.5 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="View Details">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </a>
              <a href="?page=edit_booking&id=<?= $b['id'] ?>" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-100" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </a>
              <button type="button" onclick="openDeleteModal(<?= (int)$b['id'] ?>, '<?= addslashes(htmlspecialchars($b['customer_name'])) ?>')" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
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
    $baseUrl = '?page=manage_bookings'
        . (!empty($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '')
        . (!empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '');
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
  <div style="background:#fff;border-radius:12px;padding:28px 32px;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
      <div style="background:#fef2f2;border-radius:50%;padding:10px;flex-shrink:0;">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
      </div>
      <div>
        <p style="font-weight:700;font-size:1rem;color:#111827;margin:0;">Delete Booking?</p>
        <p id="modal-desc" style="font-size:0.825rem;color:#6b7280;margin:4px 0 0;"></p>
      </div>
    </div>
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
      <button type="button" class="btn btn-ghost"
              onclick="document.getElementById('modal-delete').style.display='none'">Cancel</button>
      <form id="form-delete" method="POST" action="?page=delete_booking" style="margin:0;">
        <input type="hidden" id="delete-id" name="id" value="" />
        <button type="submit" class="btn" style="background:#dc2626;color:#fff;border:1px solid #dc2626;">
          Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<script>
function openDeleteModal(id, name) {
  document.getElementById('delete-id').value  = id;
  document.getElementById('modal-desc').textContent =
    'Booking #' + id + ' for ' + name + ' will be permanently deleted along with its related return and fine data.';
  document.getElementById('modal-delete').style.display = 'flex';
}
// Close modal if clicking outside
document.getElementById('modal-delete').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
