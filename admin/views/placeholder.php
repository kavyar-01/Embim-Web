<?php
  require_once __DIR__ . '/partials/layout_top.php';

  $meta = [
    'manage_payments' => ['title'=>'Manage Payments', 'desc'=>'Kelola data pembayaran dari tabel payments.',               'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>'],
    'manage_returns'  => ['title'=>'Manage Returns',  'desc'=>'Kelola data pengembalian kendaraan dari tabel returns.',     'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>'],
    'manage_fines'    => ['title'=>'Manage Fines',    'desc'=>'Kelola data denda keterlambatan dari tabel fines.',          'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'],
    'manage_reviews'  => ['title'=>'Manage Reviews',  'desc'=>'Kelola ulasan dan rating pelanggan dari tabel reviews.',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
  ];

  $current = $meta[$page] ?? ['title'=>'Coming Soon', 'desc'=>'Halaman ini sedang dalam pengembangan.', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'];
  ?>
  <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
    <div class="h-20 w-20 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center mb-6">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <?= $current['icon'] ?>
      </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($current['title']) ?></h1>
    <p class="text-gray-500 text-sm mb-2"><?= htmlspecialchars($current['desc']) ?></p>
    <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-medium px-3 py-1.5 rounded-full mt-4">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      Fitur CRUD sedang dalam pengembangan
    </span>
  </div>
  <?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
  