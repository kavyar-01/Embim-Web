<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6 pb-10">

  <div class="page-heading">
    <h1>Manage Reviews</h1>
    <p>View and manage customer reviews.</p>
  </div>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Review successfully updated.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Review successfully deleted.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      <?= htmlspecialchars($_GET['error'] === 'not_found' ? 'Review not found.' : 'An error occurred.') ?>
    </div>
  <?php endif; ?>

  <?php $fRating = $_GET['rating'] ?? ''; ?>
  <!-- Rating Stats -->
  <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
    <?php for ($i = 5; $i >= 1; $i--): ?>
      <?php
        $isSel = ($fRating === (string)$i);
        $activeClass = $isSel ? 'border-amber-500 ring-2 ring-amber-200 shadow-[0_0_20px_rgba(245,158,11,0.2)] bg-white' : 'border-gray-100 hover:border-amber-300 hover:shadow-[0_0_20px_rgba(251,191,36,0.3)] hover:-translate-y-1 bg-white';
      ?>
      <div onclick="handleFilter('rating', '<?= $i ?>')" class="bg-white rounded-xl shadow-sm border p-4 flex flex-col items-center justify-center transition-all duration-300 cursor-pointer <?= $activeClass ?>">
        <div class="text-2xl font-black text-gray-900 mb-1"><?= $stats[$i] ?? 0 ?></div>
        <div class="flex items-center gap-1 text-sm text-gray-500 font-medium">
          <?= $i ?> <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> Stars
        </div>
      </div>
    <?php endfor; ?>
  </div>

  <!-- Reviews Grid -->
  <?php if (empty($reviews)): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
      No reviews found.
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($reviews as $r): ?>
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm relative group hover:shadow-md transition-shadow">
          
          <!-- Actions (Edit/Delete) - Hidden by default, shown on hover -->
          <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity flex gap-2">
            <button type="button" onclick="openDeleteModal(<?= $r['id'] ?>)" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors" title="Delete Review">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </div>

          <!-- Stars -->
          <div class="flex text-yellow-400 mb-4">
            <?php 
              $rating = (int) $r['rating'];
              for ($i = 1; $i <= 5; $i++): 
                if ($i <= $rating):
            ?>
              <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <?php else: ?>
              <svg class="w-5 h-5 text-gray-200 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <?php endif; endfor; ?>
          </div>

          <!-- Comment -->
          <div class="mb-6 min-h-[3rem]">
            <p class="text-gray-700 italic">"<?= htmlspecialchars($r['comment'] ?: 'No comment provided') ?>"</p>
          </div>

          <!-- Divider -->
          <hr class="border-gray-100 mb-4" />

          <!-- User & Car Details -->
          <div>
            <div class="font-bold text-gray-900 text-lg mb-1">
              <?= htmlspecialchars($r['customer_name']) ?>
            </div>
            <div class="font-bold text-blue-600 uppercase text-sm tracking-wide">
              <?= htmlspecialchars($r['car_name']) ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- Pagination -->
  <?php
    $baseUrl = '?page=manage_reviews' . ($fRating !== '' ? '&rating=' . urlencode($fRating) : '');
    $from = $total > 0 ? min($total, ($currentPage - 1) * $perPage + 1) : 0;
    $to   = min($total, $currentPage * $perPage);
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
      <button type="button" class="btn btn-ghost" style="flex:1; justify-content:center; text-align:center;"
              onclick="document.getElementById('modal-delete').style.display='none'">Cancel</button>
      <form id="form-delete" method="POST" action="?page=delete_review" style="margin:0;flex:1;display:flex;">
        <input type="hidden" id="delete-id" name="id" value="" />
        <button type="submit" class="btn" style="width:100%;background:#dc2626;color:#fff;border:1px solid #dc2626;padding:10px 16px;border-radius:8px;font-weight:600;cursor:pointer; justify-content:center; text-align:center;">
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

function handleFilter(key, value) {
  const urlParams = new URLSearchParams(window.location.search);
  
  // Reset pagination when filtering
  urlParams.delete('p');

  if (urlParams.get(key) === value) {
    // If the same filter is clicked, toggle it off
    urlParams.delete(key);
  } else {
    // Set the new filter value
    urlParams.set(key, value);
  }
  
  window.location.search = urlParams.toString();
}
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
