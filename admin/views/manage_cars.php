<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">

  <div class="page-heading">
    <h1>Manage Cars</h1>
    <p>View and manage your entire fleet.</p>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-green-300 hover:shadow-[0_0_20px_rgba(34,197,94,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Available</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['available'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-green-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
      </div>
    </div>
    
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-300 hover:shadow-[0_0_20px_rgba(59,130,246,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Booked</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['booked'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-blue-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-red-300 hover:shadow-[0_0_20px_rgba(239,68,68,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Maintenance</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['maintenance'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-red-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-purple-300 hover:shadow-[0_0_20px_rgba(168,85,247,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Automatic</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['automatic'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-purple-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center justify-between shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-orange-300 hover:shadow-[0_0_20px_rgba(249,115,22,0.3)]">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Manual</p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $stats['manual'] ?? 0 ?></p>
      </div>
      <div class="p-3 bg-orange-50 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
      </div>
    </div>
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
  <!--  GRID OF CARS                                              -->
  <!-- ═══════════════════════════════════════════════════════════ -->
  <?php if (empty($cars)): ?>
    <div class="text-center py-12 bg-white rounded-xl border border-gray-200">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
      <p class="text-gray-500 font-medium">No cars found matching your criteria.</p>
    </div>
  <?php else: ?>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach ($cars as $c): 
      $statusBadge = match($c['status']) {
          'available'   => 'bg-green-100 text-green-800 border-green-200',
          'booked'      => 'bg-blue-100 text-blue-800 border-blue-200',
          'maintenance' => 'bg-red-100 text-red-800 border-red-200',
          default       => 'bg-gray-100 text-gray-800 border-gray-200',
      };
      $carJson = htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow flex flex-col group relative">
      <!-- Status Badge -->
      <div class="absolute top-3 right-3 z-10 px-2.5 py-1 text-xs font-bold uppercase tracking-wide rounded-md border <?= $statusBadge ?> backdrop-blur-sm bg-opacity-90">
        <?= ucfirst($c['status']) ?>
      </div>

      <!-- Photo -->
      <div class="h-48 w-full bg-gray-100 relative overflow-hidden">
        <?php if (!empty($c['photo'])): ?>
          <img src="../assets/images/<?= htmlspecialchars($c['photo']) ?>" alt="Car" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
          <div class="h-full w-full items-center justify-center hidden absolute inset-0 bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
        <?php else: ?>
          <div class="h-full w-full flex items-center justify-center absolute inset-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
        <?php endif; ?>
      </div>

      <!-- Info -->
      <div class="p-5 flex-1 flex flex-col">
        <h3 class="font-bold text-lg text-gray-900 leading-tight"><?= htmlspecialchars($c['brand'] . ' ' . $c['model']) ?></h3>
        <p class="text-sm text-gray-500 mb-4"><?= $c['year'] ?></p>

        <!-- Features Grid -->
        <div class="grid grid-cols-2 gap-y-2 gap-x-2 text-xs text-gray-600 mb-4 bg-gray-50 p-3 rounded-lg border border-gray-100">
          <div class="flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            <?= ucfirst($c['transmission']) ?>
          </div>
          <div class="flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <?= ucfirst($c['fuel_type']) ?>
          </div>
          <div class="flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 11-4 0 2 2 0 014 0zM5 16a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <?= $c['seats'] ?> Seats
          </div>
          <div class="flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Stock: <?= $c['stock'] ?? 0 ?> Unit
          </div>
        </div>

        <!-- Price & Actions -->
        <div class="mt-auto">
          <div class="text-xl font-bold text-blue-600 mb-4">
            Rp <?= number_format((float)$c['price_per_day'], 0, ',', '.') ?><span class="text-xs font-normal text-gray-500"> /day</span>
          </div>
          
          <div class="flex items-center justify-between gap-2">
            <button onclick="showCarDetail(<?= $carJson ?>)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 rounded-lg text-sm transition-colors">
              Detail
            </button>
            <a href="?page=edit_car&id=<?= $c['id'] ?>" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-100" title="Edit Car">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </a>
            <button onclick="confirmDelete(<?= $c['id'] ?>)" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-100" title="Delete Car">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

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

<!-- Car Detail Modal -->
<div id="carDetailModal" class="fixed inset-0 z-[100] hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity opacity-0" onclick="closeModal(event)">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform" onclick="event.stopPropagation()">
    <!-- Header -->
    <div class="flex items-center justify-between p-5 border-b border-gray-100">
      <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Car Details</h3>
      <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    
    <!-- Body -->
    <div class="p-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Photo Side -->
        <div class="w-full md:w-5/12 shrink-0">
          <div class="bg-gray-100 rounded-xl overflow-hidden aspect-[4/3] border border-gray-200">
            <img id="modalPhoto" src="" alt="Car Photo" class="w-full h-full object-cover hidden" />
            <div id="modalPhotoPlaceholder" class="h-full w-full flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
          </div>
          <div class="mt-4 text-center">
            <span id="modalStatus" class="inline-block px-3 py-1 text-sm font-bold uppercase tracking-wide rounded-md border"></span>
          </div>
        </div>

        <!-- Info Side -->
        <div class="w-full md:w-7/12 space-y-4">
          <div>
            <h4 id="modalName" class="text-2xl font-bold text-gray-900 leading-tight"></h4>
            <p id="modalYear" class="text-gray-500 font-medium"></p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
              <p class="text-xs text-gray-500 uppercase font-semibold">License Plate</p>
              <p id="modalPlate" class="text-sm font-bold text-gray-900 mt-0.5 font-mono"></p>
            </div>
            <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
              <p class="text-xs text-gray-500 uppercase font-semibold">Price / Day</p>
              <p id="modalPrice" class="text-sm font-bold text-blue-600 mt-0.5"></p>
            </div>
          </div>

          <div class="grid grid-cols-4 gap-2">
            <div class="bg-blue-50/50 rounded-lg p-2.5 text-center border border-blue-100/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
              <p id="modalTrans" class="text-xs font-medium text-gray-700 capitalize"></p>
            </div>
            <div class="bg-blue-50/50 rounded-lg p-2.5 text-center border border-blue-100/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
              <p id="modalFuel" class="text-xs font-medium text-gray-700 capitalize"></p>
            </div>
            <div class="bg-blue-50/50 rounded-lg p-2.5 text-center border border-blue-100/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 11-4 0 2 2 0 014 0zM5 16a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              <p id="modalSeats" class="text-xs font-medium text-gray-700"><span id="modalSeatsNum"></span> Seats</p>
            </div>
            <div class="bg-blue-50/50 rounded-lg p-2.5 text-center border border-blue-100/50">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
              <p id="modalStock" class="text-xs font-medium text-gray-700"><span id="modalStockNum"></span> Unit</p>
            </div>
          </div>

          <div>
            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Vehicle Highlights</p>
            <div class="grid grid-cols-2 gap-2">
              <div class="bg-gray-50 rounded-lg p-2 border border-gray-100 flex justify-between items-center">
                <span class="text-xs text-gray-500">Drivetrain</span>
                <span id="modalDrivetrain" class="text-xs font-bold text-gray-900 truncate pl-2"></span>
              </div>
              <div class="bg-gray-50 rounded-lg p-2 border border-gray-100 flex justify-between items-center">
                <span class="text-xs text-gray-500">Body Style</span>
                <span id="modalBodyStyle" class="text-xs font-bold text-gray-900 truncate pl-2"></span>
              </div>
              <div class="bg-gray-50 rounded-lg p-2 border border-gray-100 flex justify-between items-center">
                <span class="text-xs text-gray-500">Engine</span>
                <span id="modalEngine" class="text-xs font-bold text-gray-900 truncate pl-2"></span>
              </div>
              <div class="bg-gray-50 rounded-lg p-2 border border-gray-100 flex justify-between items-center">
                <span class="text-xs text-gray-500">Trans (HL)</span>
                <span id="modalHlTrans" class="text-xs font-bold text-gray-900 truncate pl-2"></span>
              </div>
            </div>
          </div>

          <div>
            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Description</p>
            <div id="modalDesc" class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 h-24 overflow-y-auto"></div>
          </div>

        </div>
      </div>
    </div>
    
    <!-- Footer -->
    <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end gap-3 rounded-b-2xl">
      <button onclick="closeModal()" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Close</button>
      <a id="modalEditBtn" href="#" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Edit Car</a>
    </div>
  </div>
</div>

<form id="deleteForm" action="?page=delete_car" method="POST" style="display:none;">
    <input type="hidden" name="id" id="deleteId" value="" />
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function toggleAdvanced() {
    var panel = document.getElementById('advanced-panel');
    panel.style.display = panel.style.display === 'none' ? '' : 'none';
  }

  // Modal Logic
  const modal = document.getElementById('carDetailModal');
  const modalInner = modal.querySelector('div');

  function showCarDetail(car) {
    document.getElementById('modalName').textContent = car.brand + ' ' + car.model;
    document.getElementById('modalYear').textContent = 'Year ' + car.year;
    document.getElementById('modalPlate').textContent = car.license_plate;
    document.getElementById('modalPrice').textContent = 'Rp ' + Number(car.price_per_day).toLocaleString('id-ID');
    document.getElementById('modalTrans').textContent = car.transmission;
    document.getElementById('modalFuel').textContent = car.fuel_type;
    document.getElementById('modalSeatsNum').textContent = car.seats;
    document.getElementById('modalStockNum').textContent = car.stock || 0;
    document.getElementById('modalDesc').textContent = car.description || 'No description provided.';
    
    document.getElementById('modalDrivetrain').textContent = car.drivetrain || '-';
    document.getElementById('modalDrivetrain').title = car.drivetrain || '-';
    document.getElementById('modalBodyStyle').textContent = car.body_style || '-';
    document.getElementById('modalBodyStyle').title = car.body_style || '-';
    document.getElementById('modalEngine').textContent = car.engine || '-';
    document.getElementById('modalEngine').title = car.engine || '-';
    document.getElementById('modalHlTrans').textContent = car.hl_transmission || '-';
    document.getElementById('modalHlTrans').title = car.hl_transmission || '-';
    
    const photoEl = document.getElementById('modalPhoto');
    const phEl = document.getElementById('modalPhotoPlaceholder');
    if (car.photo) {
      photoEl.src = '../assets/images/' + car.photo;
      photoEl.classList.remove('hidden');
      phEl.classList.add('hidden');
    } else {
      photoEl.classList.add('hidden');
      phEl.classList.remove('hidden');
    }

    const statusEl = document.getElementById('modalStatus');
    statusEl.textContent = car.status;
    statusEl.className = 'inline-block px-3 py-1 text-sm font-bold uppercase tracking-wide rounded-md border';
    if (car.status === 'available') statusEl.classList.add('bg-green-100', 'text-green-800', 'border-green-200');
    else if (car.status === 'booked') statusEl.classList.add('bg-blue-100', 'text-blue-800', 'border-blue-200');
    else if (car.status === 'maintenance') statusEl.classList.add('bg-red-100', 'text-red-800', 'border-red-200');

    document.getElementById('modalEditBtn').href = '?page=edit_car&id=' + car.id;

    // Show
    modal.classList.remove('hidden');
    // Trigger transition
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modalInner.classList.remove('scale-95');
    }, 10);
  }

  function closeModal(e) {
    if(e && e.target !== modal && e.target !== modal.querySelector('button[onclick="closeModal()"]')) return;
    modal.classList.add('opacity-0');
    modalInner.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200); // match transition duration
  }

  // Delete Validation Logic
  function confirmDelete(id) {
    document.getElementById('delete-id').value = id;
    document.getElementById('modal-delete').style.display = 'flex';
  }
</script>

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
      <form id="form-delete" method="POST" action="?page=delete_car" style="margin:0;flex:1;display:flex;">
        <input type="hidden" id="delete-id" name="id" value="" />
        <button type="submit" class="btn" style="width:100%;background:#dc2626;color:#fff;border:1px solid #dc2626;padding:10px 16px;border-radius:8px;font-weight:600;cursor:pointer;">
          Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>