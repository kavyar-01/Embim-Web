<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div class="page-heading">
    <h1>Manage Payments</h1>
    <p>Review and update customer payment records.</p>
  </div>

  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      Payment status updated successfully.
    </div>
  <?php endif; ?>

  <?php
    /* Ambil nilai filter yang dikirim controller (compact vars) */
    $fStatus    = $filterVars['status']    ?? '';
    $fSearch    = $filterVars['search']    ?? '';
    $fAmtMin    = $filterVars['amountMin'] ?? '';
    $fAmtMax    = $filterVars['amountMax'] ?? '';
    $fTimeMode  = $filterVars['timeMode']  ?? '';
    $fDateFrom  = $filterVars['dateFrom']  ?? '';
    $fDateTo    = $filterVars['dateTo']    ?? '';
    $fMonth     = $filterVars['month']     ?? '';
    $fYear      = $filterVars['year']      ?? '';

    /* Cek apakah ada filter aktif supaya panel waktu/harga terbuka by default */
    $priceActive = ($fAmtMin !== '' || $fAmtMax !== '');
    $timeActive  = ($fTimeMode !== '');
  ?>

  <!-- ═══════════════════════════════════════════════════════════ -->
  <!--  FILTER FORM                                               -->
  <!-- ═══════════════════════════════════════════════════════════ -->
  <form method="GET" action="" id="filter-form">
    <input type="hidden" name="page" value="manage_payments" />

    <!-- Baris 1: Search + Status + Tombol -->
    <div class="filter-bar" style="margin-bottom:10px;">
      <div class="search-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
        <input type="search" name="search"
               value="<?= htmlspecialchars($fSearch) ?>"
               placeholder="Search by booking ID or customer..."
               class="form-control w-64" />
      </div>

      <select name="status" class="form-control" style="width:auto;">
        <option value=""        <?= $fStatus === ''         ? 'selected' : '' ?>>All Status</option>
        <option value="unpaid"  <?= $fStatus === 'unpaid'   ? 'selected' : '' ?>>Unpaid</option>
        <option value="paid"    <?= $fStatus === 'paid'     ? 'selected' : '' ?>>Paid</option>
        <option value="refunded"<?= $fStatus === 'refunded' ? 'selected' : '' ?>>Refunded</option>
      </select>

      <!-- Toggle panels -->
      <button type="button" class="btn btn-ghost btn-sm" onclick="togglePanel('panel-price')"
              style="border:1px solid #e5e7eb;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Filter Harga
        <?php if ($priceActive): ?>
          <span style="width:7px;height:7px;border-radius:50%;background:#2563eb;display:inline-block;margin-left:2px;"></span>
        <?php endif; ?>
      </button>

      <button type="button" class="btn btn-ghost btn-sm" onclick="togglePanel('panel-time')"
              style="border:1px solid #e5e7eb;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Filter Waktu
        <?php if ($timeActive): ?>
          <span style="width:7px;height:7px;border-radius:50%;background:#2563eb;display:inline-block;margin-left:2px;"></span>
        <?php endif; ?>
      </button>

      <button type="submit" class="btn btn-primary btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
        Terapkan
      </button>
      <a href="?page=manage_payments" class="btn btn-ghost btn-sm">Reset</a>
    </div>

    <!-- ─── Panel: Rentang Harga ─────────────────────────────── -->
    <div id="panel-price"
         style="<?= $priceActive ? '' : 'display:none;' ?>background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px 20px;margin-bottom:10px;">
      <p style="font-size:13px;font-weight:600;color:#374151;margin:0 0 12px;">
        Rentang Harga (Amount)
      </p>
      <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <div style="display:flex;flex-direction:column;gap:4px;">
          <label class="form-label" style="font-size:12px;margin:0;">Minimum (Rp)</label>
          <input type="number" name="amount_min" min="0" step="1000"
                 value="<?= htmlspecialchars($fAmtMin) ?>"
                 placeholder="Contoh: 100000"
                 class="form-control" style="width:180px;" />
        </div>
        <span style="color:#9ca3af;margin-top:18px;">—</span>
        <div style="display:flex;flex-direction:column;gap:4px;">
          <label class="form-label" style="font-size:12px;margin:0;">Maksimum (Rp)</label>
          <input type="number" name="amount_max" min="0" step="1000"
                 value="<?= htmlspecialchars($fAmtMax) ?>"
                 placeholder="Contoh: 5000000"
                 class="form-control" style="width:180px;" />
        </div>
        <?php if ($priceActive): ?>
          <div style="margin-top:18px;">
            <span style="font-size:12px;color:#2563eb;background:#eff6ff;padding:4px 10px;border-radius:20px;border:1px solid #bfdbfe;">
              <?= $fAmtMin !== '' ? 'Min: Rp ' . number_format((float)$fAmtMin, 0, ',', '.') : '' ?>
              <?= ($fAmtMin !== '' && $fAmtMax !== '') ? ' – ' : '' ?>
              <?= $fAmtMax !== '' ? 'Maks: Rp ' . number_format((float)$fAmtMax, 0, ',', '.') : '' ?>
            </span>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ─── Panel: Rentang Waktu ─────────────────────────────── -->
    <div id="panel-time"
         style="<?= $timeActive ? '' : 'display:none;' ?>background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px 20px;margin-bottom:10px;">
      <p style="font-size:13px;font-weight:600;color:#374151;margin:0 0 12px;">
        Rentang Waktu <span style="font-size:11px;font-weight:400;color:#6b7280;">(berdasarkan tanggal transaksi dibuat)</span>
      </p>

      <!-- Mode selector -->
      <div style="display:flex;gap:8px;margin-bottom:14px;" id="time-mode-tabs">
        <?php
          $modes = [
            ''      => 'Semua Waktu',
            'range' => 'Rentang Tanggal',
            'month' => 'Per Bulan',
            'year'  => 'Per Tahun',
          ];
          foreach ($modes as $val => $label):
            $active = ($fTimeMode === $val);
        ?>
        <label style="cursor:pointer;">
          <input type="radio" name="time_mode" value="<?= $val ?>"
                 <?= $active ? 'checked' : '' ?>
                 onchange="switchTimeMode('<?= $val ?>')"
                 style="display:none;" />
          <span class="btn btn-sm <?= $active ? 'btn-primary' : 'btn-ghost' ?>"
                style="<?= $active ? '' : 'border:1px solid #e5e7eb;' ?>"
                id="tab-<?= $val === '' ? 'all' : $val ?>">
            <?= $label ?>
          </span>
        </label>
        <?php endforeach; ?>
      </div>

      <!-- Sub-panel: Rentang Tanggal -->
      <div id="sub-range" style="<?= $fTimeMode === 'range' ? '' : 'display:none;' ?>display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <div style="display:flex;flex-direction:column;gap:4px;">
          <label class="form-label" style="font-size:12px;margin:0;">Dari Tanggal</label>
          <input type="date" name="date_from"
                 value="<?= htmlspecialchars($fDateFrom) ?>"
                 class="form-control" style="width:180px;" />
        </div>
        <span style="color:#9ca3af;margin-top:18px;">—</span>
        <div style="display:flex;flex-direction:column;gap:4px;">
          <label class="form-label" style="font-size:12px;margin:0;">Sampai Tanggal</label>
          <input type="date" name="date_to"
                 value="<?= htmlspecialchars($fDateTo) ?>"
                 class="form-control" style="width:180px;" />
        </div>
      </div>

      <!-- Sub-panel: Per Bulan -->
      <div id="sub-month" style="<?= $fTimeMode === 'month' ? '' : 'display:none;' ?>">
        <div style="display:flex;flex-direction:column;gap:4px;">
          <label class="form-label" style="font-size:12px;margin:0;">Pilih Bulan</label>
          <input type="month" name="month"
                 value="<?= htmlspecialchars($fMonth) ?>"
                 class="form-control" style="width:200px;" />
        </div>
      </div>

      <!-- Sub-panel: Per Tahun -->
      <div id="sub-year" style="<?= $fTimeMode === 'year' ? '' : 'display:none;' ?>">
        <div style="display:flex;flex-direction:column;gap:4px;">
          <label class="form-label" style="font-size:12px;margin:0;">Pilih Tahun</label>
          <select name="year" class="form-control" style="width:140px;">
            <option value="">— Semua —</option>
            <?php
              $currentYear = (int) date('Y');
              for ($y = $currentYear; $y >= $currentYear - 5; $y--):
            ?>
              <option value="<?= $y ?>" <?= $fYear == $y ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>
    </div>

  </form><!-- /filter-form -->

  <!-- ═══════════════════════════════════════════════════════════ -->
  <!--  ACTIVE FILTER CHIPS                                       -->
  <!-- ═══════════════════════════════════════════════════════════ -->
  <?php
    $chips = [];
    if ($fStatus !== '')   $chips[] = 'Status: ' . ucfirst($fStatus);
    if ($fSearch !== '')   $chips[] = 'Cari: "' . htmlspecialchars($fSearch) . '"';
    if ($fAmtMin !== '')   $chips[] = 'Min: Rp ' . number_format((float)$fAmtMin, 0, ',', '.');
    if ($fAmtMax !== '')   $chips[] = 'Maks: Rp ' . number_format((float)$fAmtMax, 0, ',', '.');
    if ($fTimeMode === 'range') {
        $label = '';
        if ($fDateFrom && $fDateTo) $label = $fDateFrom . ' s/d ' . $fDateTo;
        elseif ($fDateFrom)          $label = 'Dari ' . $fDateFrom;
        elseif ($fDateTo)            $label = 'S/d ' . $fDateTo;
        if ($label) $chips[] = 'Tanggal: ' . $label;
    } elseif ($fTimeMode === 'month' && $fMonth) {
        $chips[] = 'Bulan: ' . date('F Y', strtotime($fMonth . '-01'));
    } elseif ($fTimeMode === 'year' && $fYear) {
        $chips[] = 'Tahun: ' . $fYear;
    }
  ?>
  <?php if (!empty($chips)): ?>
  <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
    <span style="font-size:12px;color:#6b7280;">Filter aktif:</span>
    <?php foreach ($chips as $chip): ?>
      <span style="font-size:12px;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:3px 10px;border-radius:20px;">
        <?= $chip ?>
      </span>
    <?php endforeach; ?>
    <a href="?page=manage_payments" style="font-size:12px;color:#ef4444;margin-left:4px;">
      × Hapus semua filter
    </a>
  </div>
  <?php endif; ?>

  <!-- ═══════════════════════════════════════════════════════════ -->
  <!--  TABLE                                                     -->
  <!-- ═══════════════════════════════════════════════════════════ -->
  <div class="embim-table-wrap">
    <table class="embim-table">
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Customer</th>
          <th>Car</th>
          <th>Method</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Proof</th>
          <th>Paid At</th>
          <th class="td-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($payments)): ?>
          <tr><td colspan="9" class="text-center py-10 text-gray-400">No payment records found.</td></tr>
        <?php else: foreach ($payments as $p):
          $badgeClass = match($p['status']) {
              'paid'      => 'badge badge-paid',
              'refunded'  => 'badge badge-refunded',
              default     => 'badge badge-unpaid',
          };
        ?>
        <tr>
          <td class="td-primary">#<?= $p['booking_id'] ?></td>
          <td class="td-primary"><?= htmlspecialchars($p['customer_name']) ?></td>
          <td><?= htmlspecialchars($p['car_name']) ?></td>
          <td><?= htmlspecialchars(str_replace('_', ' ', $p['payment_method'])) ?></td>
          <td>Rp <?= number_format((float)$p['amount'], 0, ',', '.') ?></td>
          <td><span class="<?= $badgeClass ?>"><?= ucfirst($p['status']) ?></span></td>
          <td>
            <?php if (!empty($p['payment_proof'])): ?>
              <a href="assets/images/<?= htmlspecialchars($p['payment_proof']) ?>" target="_blank"
                 class="text-blue-600 hover:underline text-xs">View</a>
            <?php else: ?>
              <span class="td-muted">—</span>
            <?php endif; ?>
          </td>
          <td class="td-muted"><?= $p['paid_at'] ? htmlspecialchars($p['paid_at']) : '—' ?></td>
          <td class="td-right">
            <a href="?page=payment_detail&id=<?= $p['id'] ?>" class="btn btn-sm btn-primary" style="background:#eff6ff;color:#1d4ed8;">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              Detail
            </a>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <!-- ═══════════════════════════════════════════════════════════ -->
  <!--  PAGINASI                                                  -->
  <!-- ═══════════════════════════════════════════════════════════ -->
  <?php
    $totalPages  = (int) ($totalPages  ?? 1);
    $currentPage = (int) ($currentPage ?? 1);
    $total       = (int) ($total       ?? 0);

    /* Bangun baseUrl dengan SEMUA parameter filter */
    $baseUrl = '?page=manage_payments'
        . ($fStatus  !== '' ? '&status='     . urlencode($fStatus)   : '')
        . ($fSearch  !== '' ? '&search='     . urlencode($fSearch)   : '')
        . ($fAmtMin  !== '' ? '&amount_min=' . urlencode($fAmtMin)   : '')
        . ($fAmtMax  !== '' ? '&amount_max=' . urlencode($fAmtMax)   : '')
        . ($fTimeMode!== '' ? '&time_mode='  . urlencode($fTimeMode) : '')
        . ($fDateFrom!== '' ? '&date_from='  . urlencode($fDateFrom) : '')
        . ($fDateTo  !== '' ? '&date_to='    . urlencode($fDateTo)   : '')
        . ($fMonth   !== '' ? '&month='      . urlencode($fMonth)    : '')
        . ($fYear    !== '' ? '&year='       . urlencode($fYear)     : '');

    $from = $total > 0 ? min($total, ($currentPage - 1) * 10 + 1) : 0;
    $to   = min($total, $currentPage * 10);
  ?>
  <?php if ($totalPages > 1): ?>
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
  <?php endif; ?>

</div><!-- /space-y-6 -->

<!-- ═════════════════════════════════════════════════════════════ -->
<!--  JS: toggle panel & switch time mode                        -->
<!-- ═════════════════════════════════════════════════════════════ -->
<script>
  function togglePanel(id) {
    var el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? '' : 'none';
  }

  function switchTimeMode(mode) {
    var subs = ['range', 'month', 'year'];
    subs.forEach(function(s) {
      document.getElementById('sub-' + s).style.display = (s === mode) ? '' : 'none';
    });

    // Update radio
    document.querySelectorAll('[name="time_mode"]').forEach(function(r) {
      r.checked = (r.value === mode);
    });

    // Update tab button styles
    var allModes = ['all', 'range', 'month', 'year'];
    var modeKey  = (mode === '') ? 'all' : mode;
    allModes.forEach(function(m) {
      var span = document.getElementById('tab-' + m);
      if (!span) return;
      if (m === modeKey) {
        span.className = 'btn btn-sm btn-primary';
        span.style.border = '';
      } else {
        span.className = 'btn btn-sm btn-ghost';
        span.style.border = '1px solid #e5e7eb';
      }
    });
  }
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>