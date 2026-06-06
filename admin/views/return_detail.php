<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div>
    <a href="?page=manage_returns" class="back-link">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Back to Returns
    </a>
  </div>

  <div class="page-heading">
    <h1>Return Details</h1>
    <p>Return <?= $return['id'] ?> — Booking <?= $return['booking_id'] ?></p>
  </div>

  <?php if (isset($_GET['created'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Return data successfully recorded. Booking and vehicle status updated.
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Return data successfully updated.
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Return Info -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Return Information</span>
        <?php
          $condClass = $return['car_condition'] === 'good' ? 'badge badge-paid' : 'badge badge-unpaid';
          $condLabel = $return['car_condition'] === 'good' ? 'Good Condition' : 'Damaged';
        ?>
        <span class="<?= $condClass ?>"><?= $condLabel ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Return ID</dt><dd><?= $return['id'] ?></dd></div>
        <div class="detail-row"><dt>Booking ID</dt><dd><?= $return['booking_id'] ?></dd></div>
        <div class="detail-row"><dt>Return Date</dt><dd><strong><?= htmlspecialchars($return['return_date']) ?></strong></dd></div>
        <div class="detail-row">
          <dt>Late Status</dt>
          <dd>
            <?php if ((int)$return['late_days'] > 0): ?>
              <span class="badge" style="background:#fef2f2;color:#dc2626;border-color:#fecaca;"><?= (int)$return['late_days'] ?> Days Late</span>
            <?php else: ?>
              <span class="badge badge-paid">On Time</span>
            <?php endif; ?>
          </dd>
        </div>
        <div class="detail-row"><dt>Vehicle Condition</dt><dd><?= $condLabel ?></dd></div>
        <div class="detail-row"><dt>Notes</dt><dd><?= $return['notes'] ? htmlspecialchars($return['notes']) : '<span class="td-muted">—</span>' ?></dd></div>
        <div class="detail-row"><dt>Recorded At</dt><dd><?= htmlspecialchars($return['created_at']) ?></dd></div>
      </dl>
    </div>

    <!-- Booking & Customer Info -->
    <div class="card">
      <div class="card-header">
        <span class="card-title">Booking &amp; Customer</span>
        <?php
          $bookingBadge = match($return['booking_status']) {
              'completed'  => 'badge badge-paid',
              'cancelled'  => 'badge badge-refunded',
              'ongoing'    => 'badge badge-unpaid',
              default      => 'badge',
          };
        ?>
        <span class="<?= $bookingBadge ?>"><?= ucfirst($return['booking_status']) ?></span>
      </div>
      <dl class="detail-dl">
        <div class="detail-row"><dt>Customer</dt><dd><strong><?= htmlspecialchars($return['customer_name']) ?></strong></dd></div>
        <div class="detail-row"><dt>Email</dt><dd><?= htmlspecialchars($return['customer_email']) ?></dd></div>
        <div class="detail-row"><dt>Vehicle</dt><dd><?= htmlspecialchars($return['car_name']) ?></dd></div>
        <div class="detail-row"><dt>License Plate</dt><dd><?= htmlspecialchars($return['license_plate']) ?></dd></div>
        <div class="detail-row">
          <dt>Rental Period</dt>
          <dd><?= $return['start_date'] ?> → <?= $return['end_date'] ?> (<?= $return['total_days'] ?> days)</dd>
        </div>
        <div class="detail-row"><dt>Total Price</dt><dd><strong>Rp <?= number_format((float)$return['total_price'], 0, ',', '.') ?></strong></dd></div>
        <?php if (!empty($return['booking_notes'])): ?>
        <div class="detail-row"><dt>Booking Notes</dt><dd><?= htmlspecialchars($return['booking_notes']) ?></dd></div>
        <?php endif; ?>
      </dl>
    </div>

  </div>

  <!-- Actions -->
  <div class="card">
    <div class="card-header"><span class="card-title">Actions</span></div>
    <div class="card-body">
      <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <a href="?page=edit_return&id=<?= $return['id'] ?>" class="btn btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Edit Return
        </a>
        <form method="POST" action="?page=delete_return"
              onsubmit="return confirm('Delete this return data? Booking and car statuses will be reverted.')">
          <input type="hidden" name="id" value="<?= $return['id'] ?>" />
          <button type="submit" class="btn" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
          </button>
        </form>
        <a href="?page=manage_returns" class="btn btn-ghost">← Back to List</a>
      </div>
    </div>
  </div>

</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
