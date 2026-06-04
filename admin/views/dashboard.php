<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">
  <div><h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard</h1><p class="text-gray-500 text-sm mt-1">Overview of your car rental fleet.</p></div>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php $cards = [
      ['label'=>'Total Cars',        'value'=>$stats['total_cars'],       'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 17l-1-4H5l-1-3h12l-1 3h-3l-1 4H9zm0 0h6M6 10V7a2 2 0 012-2h8a2 2 0 012 2v3"/>'],
      ['label'=>'Active Bookings',   'value'=>$stats['active_bookings'],  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
      ['label'=>'Revenue This Month','value'=>'Rp. '.number_format($stats['revenue'],0,',','.'), 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 .895-4 2s1.79 2 4 2 4 .895 4 2-1.79 2-4 2m0-8v1m0 10v1M8 10H7m10 0h-1"/>'],
      ['label'=>'Available Cars',    'value'=>$stats['available_cars'],   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
    ]; foreach ($cards as $c): ?>
    <div class="bg-white border border-gray-200 rounded-lg p-5">
      <div class="flex items-center justify-between pb-2">
        <span class="text-sm font-medium text-gray-500"><?= $c['label'] ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><?= $c['icon'] ?></svg>
      </div>
      <div class="text-2xl font-bold text-gray-900"><?= $c['value'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6">
      <h2 class="text-base font-semibold text-gray-900 mb-4">Recent Bookings</h2>
      <div class="space-y-4">
        <?php foreach (array_slice($bookings,0,4) as $b): ?>
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
          <div><p class="font-medium text-sm text-gray-900"><?= htmlspecialchars($b['customer_name']) ?></p><p class="text-xs text-gray-500"><?= htmlspecialchars($b['car_name']) ?></p></div>
          <div class="text-right"><p class="font-medium text-sm text-gray-900">Rp. <?= number_format($b['total_price'],0,',','.') ?></p><p class="text-xs text-gray-500"><?= ucfirst($b['status']) ?></p></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-6">
      <h2 class="text-base font-semibold text-gray-900 mb-4">Cars Overview</h2>
      <div class="space-y-4">
        <?php foreach (array_slice($cars,0,4) as $c): ?>
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0">
          <div><p class="font-medium text-sm text-gray-900"><?= htmlspecialchars($c['brand'].' '.$c['model']) ?></p><p class="text-xs text-gray-500"><?= $c['year'] ?> &bull; <?= ucfirst($c['transmission']) ?></p></div>
          <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?= $c['status']==='available' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' ?>"><?= ucfirst($c['status']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
