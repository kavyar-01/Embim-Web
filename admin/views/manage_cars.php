<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div class="page-heading">
    <h1>Manage Cars</h1>
    <p>View and manage your entire fleet.</p>
  </div>

  <?php
    $fSearch  = $filterVars['search']       ?? '';
    $fStatus  = $filterVars['status']       ?? '';
    $fTrans   = $filterVars['transmission'] ?? '';
    $fFuel    = $filterVars['fuelType']     ?? '';
    $fPMin    = $filterVars['priceMin']     ?? '';
    $fPMax    = $filterVars['priceMax']     ?? '';
    $fYMin    = $filterVars['yearMin']      ?? '';
    $fYMax    = $filterVars['yearMax']      ?? '';

    $hasAdvanced = ($fStatus !== '' || $fTrans !== '' || $fFuel !== ''
                 || $fPMin  !== '' || $fPMax  !== ''
                 || $fYMin  !== '' || $fYMax  !== '');
  ?>

  <!-- ═══════════════════════════════════════════════════════════ -->
  <!--  FILTER FORM                                               -->
  <!-- ═══════════════════════════════════════════════════════════ -->
  <form method="GET" action="" id="cars-filter-form">
    <input type="hidden" name="page" value="manage_cars" />

    <!-- Baris 1: Search + tombol advanced + aksi -->
    <div class="filter-bar" style="margin-bottom:10px;">
      <div class="search-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
        <input type="search" name="search"
               value="<?= htmlspecialchars($fSearch) ?>"
               placeholder="Cari brand, model, atau plat nomor..."
               class="form-control" style="width:280px;" />
      </div>

      <button type="button" class="btn btn-ghost btn-sm"
              onclick="toggleAdvanced()"
              id="btn-advanced"
              style="border:1px solid #e5e7eb;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
        Filter Lanjutan
        <?php if ($hasAdvanced): ?>
          <span style="width:7px;height:7px;border-radius:50%;background:#2563eb;flex-shrink:0;"></span>
        <?php endif; ?>
      </button>

      <button type="submit" class="btn btn-primary btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
        Terapkan
      </button>
      <a href="?page=manage_cars" class="btn btn-ghost btn-sm">Reset</a>
      <a href="?page=add_car" class="btn btn-primary btn-sm" style="margin-left:auto;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Car
      </a>
    </div>

    <!-- Baris 2: Panel filter lanjutan -->
    <div id="advanced-panel"
         style="<?= $hasAdvanced ? '' : 'display:none;' ?>background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:18px 20px;">

      <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:flex-end;">

        <!-- Status -->
        <div style="display:flex;flex-direction:column;gap:4px;min-width:140px;">
          <label class="form-label" style="font-size:12px;margin:0;">Status</label>
          <select name="status" class="form-control" style="width:auto;">
            <option value=""            <?= $fStatus === ''            ? 'selected' : '' ?>>Semua Status</option>
            <option value="available"   <?= $fStatus === 'available'   ? 'selected' : '' ?>>Available</option>
            <option value="booked"      <?= $fStatus === 'booked'      ? 'selected' : '' ?>>Booked</option>
            <option value="maintenance" <?= $fStatus === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
          </select>
        </div>

        <!-- Transmisi -->
        <div style="display:flex;flex-direction:column;gap:4px;min-width:140px;">
          <label class="form-label" style="font-size:12px;margin:0;">Transmisi</label>
          <select name="transmission" class="form-control" style="width:auto;">
            <option value=""          <?= $fTrans === ''          ? 'selected' : '' ?>>Semua</option>
            <option value="automatic" <?= $fTrans === 'automatic' ? 'selected' : '' ?>>Automatic</option>
            <option value="manual"    <?= $fTrans === 'manual'    ? 'selected' : '' ?>>Manual</option>
          </select>
        </div>

        <!-- Bahan Bakar -->
        <div style="display:flex;flex-direction:column;gap:4px;min-width:140px;">
          <label class="form-label" style="font-size:12px;margin:0;">Bahan Bakar</label>
          <select name="fuel_type" class="form-control" style="width:auto;">
            <option value=""         <?= $fFuel === ''         ? 'selected' : '' ?>>Semua</option>
            <option value="gasoline" <?= $fFuel === 'gasoline' ? 'selected' : '' ?>>Gasoline</option>
            <option value="diesel"   <?= $fFuel === 'diesel'   ? 'selected' : '' ?>>Diesel</option>
            <option value="electric" <?= $fFuel === 'electric' ? 'selected' : '' ?>>Electric</option>
            <option value="hybrid"   <?= $fFuel === 'hybrid'   ? 'selected' : '' ?>>Hybrid</option>
          </select>
        </div>

        <!-- Divider visual -->
        <div style="width:1px;background:#e5e7eb;align-self:stretch;margin:0 4px;"></div>

        <!-- Harga / Hari -->
        <div style="display:flex;flex-direction:column;gap:4px;">
          <label class="form-label" style="font-size:12px;margin:0;">Harga / Hari (Rp)</label>
          <div style="display:flex;align-items:center;gap:8px;">
            <input type="number" name="price_min" min="0" step="10000"
                   value="<?= htmlspecialchars($fPMin) ?>"
                   placeholder="Min"
                   class="form-control" style="width:130px;" />
            <span style="color:#9ca3af;font-size:12px;">—</span>
            <input type="number" name="price_max" min="0" step="10000"
                   value="<?= htmlspecialchars($fPMax) ?>"
                   placeholder="Maks"
                   class="form-control" style="width:130px;" />
          </div>
        </div>

        <!-- Divider visual -->
        <div style="width:1px;background:#e5e7eb;align-self:stretch;margin:0 4px;"></div>

        <!-- Tahun -->
        <div style="display:flex;flex-direction:column;gap:4px;">
          <label class="form-label" style="font-size:12px;margin:0;">Tahun</label>
          <div style="display:flex;align-items:center;gap:8px;">
            <input type="number" name="year_min" min="1990" max="2099"
                   value="<?= htmlspecialchars($fYMin) ?>"
                   placeholder="Dari"
                   class="form-control" style="width:100px;" />
            <span style="color:#9ca3af;font-size:12px;">—</span>
            <input type="number" name="year_max" min="1990" max="2099"
                   value="<?= htmlspecialchars($fYMax) ?>"
                   placeholder="Sampai"
                   class="form-control" style="width:100px;" />
          </div>
        </div>

      </div><!-- /flex row -->
    </div><!-- /advanced-panel -->

  </form>

  <!-- ═══════════════════════════════════════════════════════════ -->
  <!--  ACTIVE FILTER CHIPS                                       -->
  <!-- ═══════════════════════════════════════════════════════════ -->
  <?php
    $chips = [];
    if ($fSearch !== '') $chips[] = 'Cari: "' . htmlspecialchars($fSearch) . '"';
    if ($fStatus !== '') $chips[] = 'Status: ' . ucfirst($fStatus);
    if ($fTrans  !== '') $chips[] = 'Transmisi: ' . ucfirst($fTrans);
    if ($fFuel   !== '') $chips[] = 'BBM: ' . ucfirst($fFuel);
    if ($fPMin   !== '' || $fPMax !== '') {
        $lbl = 'Harga: ';
        if ($fPMin !== '' && $fPMax !== '') $lbl .= 'Rp ' . number_format((float)$fPMin,0,',','.') . ' – Rp ' . number_format((float)$fPMax,0,',','.');
        elseif ($fPMin !== '')              $lbl .= 'Min Rp ' . number_format((float)$fPMin,0,',','.');
        else                               $lbl .= 'Maks Rp ' . number_format((float)$fPMax,0,',','.');
        $chips[] = $lbl;
    }
    if ($fYMin !== '' || $fYMax !== '') {
        $lbl = 'Tahun: ';
        if ($fYMin !== '' && $fYMax !== '') $lbl .= $fYMin . ' – ' . $fYMax;
        elseif ($fYMin !== '')              $lbl .= 'dari ' . $fYMin;
        else                               $lbl .= 's/d ' . $fYMax;
        $chips[] = $lbl;
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
    <a href="?page=manage_cars" style="font-size:12px;color:#ef4444;margin-left:4px;">× Hapus semua filter</a>
  </div>
  <?php endif; ?>

  <!-- ═══════════════════════════════════════════════════════════ -->
  <!--  TABLE                                                     -->
  <!-- ═══════════════════════════════════════════════════════════ -->
  <div class="embim-table-wrap">
    <table class="embim-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Photo</th>
          <th>Brand</th>
          <th>Model</th>
          <th>Year</th>
          <th>License Plate</th>
          <th>Price / Day</th>
          <th>Status</th>
          <th>Transmission</th>
          <th>Fuel</th>
          <th>Seats</th>
          <th class="td-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($cars)): ?>
          <tr><td colspan="12" class="text-center py-10 text-gray-400">No cars found.</td></tr>
        <?php else: foreach ($cars as $c):
          $statusBadge = match($c['status']) {
              'available'   => 'badge badge-available',
              'booked'      => 'badge badge-booked',
              'maintenance' => 'badge badge-maintenance',
              default       => 'badge',
          };
        ?>
        <tr>
          <td class="td-id"><?= $c['id'] ?></td>
          <td>
            <?php if (!empty($c['photo'])): ?>
              <img src="assets/images/<?= htmlspecialchars($c['photo']) ?>"
                   alt="Car photo"
                   class="h-10 w-16 object-cover rounded-md border border-gray-200"
                   onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
              <div class="h-10 w-16 rounded-md border border-gray-200 bg-gray-100 items-center justify-center hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
            <?php else: ?>
              <div class="h-10 w-16 rounded-md border border-gray-200 bg-gray-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
            <?php endif; ?>
          </td>
          <td class="td-primary"><?= htmlspecialchars($c['brand']) ?></td>
          <td><?= htmlspecialchars($c['model']) ?></td>
          <td class="td-muted"><?= $c['year'] ?></td>
          <td><code style="background:#f3f4f6;padding:2px 7px;border-radius:4px;font-size:12px;"><?= htmlspecialchars($c['license_plate']) ?></code></td>
          <td style="font-weight:600;">Rp <?= number_format((float)$c['price_per_day'], 0, ',', '.') ?></td>
          <td><span class="<?= $statusBadge ?>"><?= ucfirst($c['status']) ?></span></td>
          <td class="td-muted"><?= ucfirst($c['transmission']) ?></td>
          <td class="td-muted"><?= ucfirst($c['fuel_type']) ?></td>
          <td class="td-muted"><?= $c['seats'] ?></td>
          <td class="td-right">
            <div style="display:flex;justify-content:flex-end;gap:4px;">
              <button title="Edit"
                      class="btn btn-sm" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </button>
              <button title="Delete"
                      class="btn btn-sm" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus
              </button>
            </div>
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

    $baseUrl = '?page=manage_cars'
        . ($fSearch !== '' ? '&search='       . urlencode($fSearch) : '')
        . ($fStatus !== '' ? '&status='       . urlencode($fStatus) : '')
        . ($fTrans  !== '' ? '&transmission=' . urlencode($fTrans)  : '')
        . ($fFuel   !== '' ? '&fuel_type='    . urlencode($fFuel)   : '')
        . ($fPMin   !== '' ? '&price_min='    . urlencode($fPMin)   : '')
        . ($fPMax   !== '' ? '&price_max='    . urlencode($fPMax)   : '')
        . ($fYMin   !== '' ? '&year_min='     . urlencode($fYMin)   : '')
        . ($fYMax   !== '' ? '&year_max='     . urlencode($fYMax)   : '');

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

</div>

<script>
  function toggleAdvanced() {
    var panel = document.getElementById('advanced-panel');
    panel.style.display = panel.style.display === 'none' ? '' : 'none';
  }
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>