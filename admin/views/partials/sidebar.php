<?php
    $activePage = $page ?? 'dashboard';

    // Use session data from login — NOT a DB query
    $adminName     = $_SESSION['admin_name']  ?? 'Admin';
    $adminEmail    = $_SESSION['admin_email'] ?? '';
    $nameParts     = explode(' ', $adminName);
    $adminInitials = strtoupper(
        substr($nameParts[0], 0, 1) .
        (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
    );

    $sections = [
      [
        'label' => 'Overview',
        'links' => [
          ['page'=>'dashboard', 'label'=>'Dashboard', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>'],
        ],
      ],
      [
        'label' => 'Fleet',
        'links' => [
          ['page'=>'add_car',     'label'=>'Add Car',     'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>'],
          ['page'=>'manage_cars', 'label'=>'Manage Cars', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 17l-1-4H5l-1-3h12l-1 3h-3l-1 4H9zm0 0h6M6 10V7a2 2 0 012-2h8a2 2 0 012 2v3"/>'],
        ],
      ],
      [
        'label' => 'Transactions',
        'links' => [
          ['page'=>'manage_bookings', 'label'=>'Manage Bookings', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
          ['page'=>'manage_payments', 'label'=>'Manage Payments', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>'],
          ['page'=>'manage_returns',  'label'=>'Manage Returns',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>'],
          ['page'=>'manage_fines',    'label'=>'Manage Fines',    'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'],
        ],
      ],
      [
        'label' => 'Community',
        'links' => [
          ['page'=>'manage_reviews', 'label'=>'Manage Reviews', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
        ],
      ],
      [
        'label' => 'Admin',
        'links' => [
          ['page'=>'manage_users', 'label'=>'Manage Users', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 11-4 0 2 2 0 014 0zM5 16a2 2 0 11-4 0 2 2 0 014 0z"/>'],
        ],
      ],
    ];
  ?>
  <aside class="w-64 bg-gray-50 border-r border-gray-200 flex flex-col h-full shrink-0">
    <div class="p-6 pb-2">
      <div class="flex items-center gap-3 mb-6">
        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center border border-gray-200">
          <span class="text-blue-600 font-semibold text-sm"><?= $adminInitials ?></span>
        </div>
        <div class="flex flex-col">
          <span class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($adminName) ?></span>
          <span class="text-xs text-gray-500">Admin</span>
        </div>
      </div>
    </div>

    <nav class="flex-1 px-4 overflow-y-auto space-y-4 pb-4">
      <?php foreach ($sections as $section): ?>
      <div>
        <p class="px-3 mb-1 text-[10px] font-bold uppercase tracking-widest text-gray-400">
          <?= $section['label'] ?>
        </p>
        <?php foreach ($section['links'] as $link):
          $isActive = ($activePage === $link['page']);
          $cls = $isActive
            ? 'flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium bg-blue-600 text-white'
            : 'flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors';
        ?>
        <a href="?page=<?= $link['page'] ?>" class="<?= $cls ?>">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><?= $link['icon'] ?></svg>
          <?= $link['label'] ?>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
    </nav>
  </aside>
  