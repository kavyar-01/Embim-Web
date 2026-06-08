<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=manage_bookings" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Manage Bookings
    </a>
  </div>

  <div class="page-heading">
    <h1>Booking Details</h1>
    <p>Booking <?= (int)$booking['id'] ?> — <?= htmlspecialchars($booking['customer_name']) ?></p>
  </div>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Booking data successfully updated.
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Info Booking -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Booking Information</span>
        <?php
          $bs = $booking['status'] ?? 'pending';
          $bsCls = match($bs) {
            'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'ongoing'   => 'bg-blue-100 text-blue-700 border-blue-200',
            'cancelled' => 'bg-red-100 text-red-700 border-red-200',
            'confirmed' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
            'pending'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            default     => 'bg-gray-100 text-gray-700 border-gray-200'
          };
        ?>
        <span class="px-2.5 py-1 border rounded-md text-xs font-bold uppercase tracking-wider <?= $bsCls ?>"><?= htmlspecialchars($bs) ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Booking ID</dt><dd><strong><?= (int)$booking['id'] ?></strong></dd></div>
        <div class="detail-row">
          <dt>Rental Period</dt>
          <dd><?= htmlspecialchars($booking['start_date']) ?> → <?= htmlspecialchars($booking['end_date']) ?></dd>
        </div>
        <div class="detail-row"><dt>Total Days</dt><dd><?= (int)$booking['total_days'] ?> days</dd></div>
        <div class="detail-row">
          <dt>Total Price</dt>
          <dd><strong style="font-size:1rem;">Rp <?= number_format((float)$booking['total_price'], 0, ',', '.') ?></strong></dd>
        </div>
        <div class="detail-row"><dt>Status</dt><dd><span class="px-2.5 py-1 border rounded-md text-xs font-bold uppercase tracking-wider <?= $bsCls ?>"><?= htmlspecialchars($bs) ?></span></dd></div>
        <div class="detail-row">
          <dt>Notes</dt>
          <dd><?= !empty($booking['notes']) ? htmlspecialchars($booking['notes']) : '<span class="td-muted">—</span>' ?></dd>
        </div>
        <div class="detail-row"><dt>Created At</dt><dd><?= htmlspecialchars($booking['created_at']) ?></dd></div>
        <div class="detail-row"><dt>Updated At</dt><dd><?= htmlspecialchars($booking['updated_at']) ?></dd></div>
      </dl>
    </div>

    <!-- Info Pelanggan & Kendaraan -->
    <div class="card">
      <div class="card-header"><span class="card-title">Customer &amp; Vehicle</span></div>
      <dl class="detail-dl">
        <div class="detail-row" style="align-items: flex-end;">
          <dt style="padding-bottom: 4px;">Customer Name</dt>
          <dd>
            <div style="display:flex;flex-direction:column;gap:8px;padding-top:4px;padding-bottom:4px;">
              <div class="w-14 h-14 shrink-0 rounded-full overflow-hidden bg-gray-100 border border-gray-200 shadow-sm">
                <img src="../assets/images/user/<?= htmlspecialchars($booking['photo_profile'] ?? 'default.png') ?>" alt="Photo" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($booking['customer_name']) ?>&background=eff6ff&color=1d4ed8'" />
              </div>
              <strong><?= htmlspecialchars($booking['customer_name']) ?></strong>
            </div>
          </dd>
        </div>
        <div class="detail-row"><dt>Email</dt><dd><?= htmlspecialchars($booking['customer_email']) ?></dd></div>
        <div class="detail-row"><dt>Phone</dt><dd><?= !empty($booking['customer_phone']) ? htmlspecialchars($booking['customer_phone']) : '<span class="td-muted">—</span>' ?></dd></div>
        <div class="detail-row" style="align-items: flex-start;">
          <dt style="padding-top: 4px;">ID Card (KTP)</dt>
          <dd>
            <?php if (!empty($booking['identity_photo'])): ?>
              <a href="../assets/images/booking/<?= htmlspecialchars($booking['identity_photo']) ?>" target="_blank" class="block overflow-hidden rounded-lg border border-gray-200 mt-1" style="max-width: 200px;">
                <img src="../assets/images/booking/<?= htmlspecialchars($booking['identity_photo']) ?>" alt="ID Card" class="w-full object-cover hover:opacity-90 transition-opacity" />
              </a>
            <?php else: ?>
              <span class="td-muted mt-1 inline-block">Not uploaded</span>
            <?php endif; ?>
          </dd>
        </div>
        <div class="detail-row"><dt>Vehicle</dt><dd><strong><?= htmlspecialchars($booking['car_name']) ?></strong></dd></div>
        <div class="detail-row"><dt>License Plate</dt><dd><?= htmlspecialchars($booking['license_plate']) ?></dd></div>
        <div class="detail-row"><dt>Price/Day</dt><dd>Rp <?= number_format((float)$booking['price_per_day'], 0, ',', '.') ?></dd></div>
      </dl>
    </div>

  </div>

  <!-- Aksi -->
  <div class="card">
    <div class="card-header"><span class="card-title">Actions</span></div>
    <div class="card-body">
      <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <a href="?page=edit_booking&id=<?= (int)$booking['id'] ?>" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Edit Booking
        </a>

        <!-- Delete Button with confirmation modal -->
        <button type="button" class="btn" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;"
                onclick="document.getElementById('modal-delete').style.display='flex'">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete Booking
        </button>

        <a href="?page=manage_bookings" class="btn btn-ghost">← Back to List</a>
      </div>
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
      <form method="POST" action="?page=delete_booking" style="margin:0;flex:1;display:flex;">
        <input type="hidden" name="id" value="<?= (int)$booking['id'] ?>" />
        <button type="submit" class="btn" style="width:100%;background:#dc2626;color:#fff;border:1px solid #dc2626;padding:10px 16px;border-radius:8px;font-weight:600;cursor:pointer; justify-content:center; text-align:center;">
          Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
