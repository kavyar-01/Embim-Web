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

    $links = [
      ['page'=>'dashboard', 'label'=>'Dashboard', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>'],
      ['page'=>'manage_cars', 'label'=>'Cars', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M5 11l1.5-4.5h11L19 11M5 11v6h2v2h2v-2h6v2h2v-2h2v-6M5 11h14M8 14h.01M16 14h.01"/>'],
      ['page'=>'manage_bookings', 'label'=>'Bookings', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
      ['page'=>'manage_payments', 'label'=>'Payments', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>'],
      ['page'=>'manage_returns',  'label'=>'Returns',  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>'],
      ['page'=>'manage_reviews', 'label'=>'Reviews', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
      ['page'=>'manage_users', 'label'=>'Users', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>'],
    ];
  ?>
  <aside class="w-64 bg-white border-r border-gray-200 flex flex-col h-full shrink-0 shadow-sm relative">
    <!-- Gradasi biru tebal di pojok kiri bawah -->
    <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-blue-400 rounded-full mix-blend-multiply filter blur-[50px] opacity-60 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-tr from-blue-300/60 via-transparent to-transparent pointer-events-none"></div>

    <div class="p-6 pb-2 relative z-10">
      <?php
        $isProfileActive = ($activePage === 'edit_profile');
        $profileCls = $isProfileActive
          ? 'flex items-center gap-3 mb-6 p-2.5 rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/30 transition-all'
          : 'flex items-center gap-3 mb-6 p-2.5 rounded-xl hover:bg-gray-100 transition-all';
        $nameCls = $isProfileActive ? 'text-white' : 'text-gray-900';
        $roleCls = $isProfileActive ? 'text-blue-100' : 'text-gray-500';
      ?>
      <a href="?page=edit_profile" class="<?= $profileCls ?>">
        <?php if (!empty($_SESSION['admin_photo'])): ?>
          <img src="../assets/images/user/<?= htmlspecialchars($_SESSION['admin_photo']) ?>" alt="Foto Admin" class="h-10 w-10 rounded-full object-cover border <?= $isProfileActive ? 'border-blue-400' : 'border-gray-200' ?> shadow-sm">
        <?php else: ?>
          <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center border <?= $isProfileActive ? 'border-blue-400' : 'border-gray-200' ?> shadow-sm">
            <span class="text-blue-600 font-semibold text-sm"><?= $adminInitials ?></span>
          </div>
        <?php endif; ?>
        <div class="flex flex-col">
          <span class="text-base font-bold <?= $nameCls ?>"><?= htmlspecialchars($adminName) ?></span>
          <span class="text-sm font-medium <?= $roleCls ?>">Admin</span>
        </div>
      </a>
    </div>

    <nav class="flex-1 px-4 overflow-y-auto space-y-1 pb-4 relative z-10">
      <?php foreach ($links as $link):
        $isActive = ($activePage === $link['page']);
        $cls = $isActive
          ? 'flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium bg-blue-600 text-white shadow-md shadow-blue-500/30'
          : 'flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors';
      ?>
      <a href="?page=<?= $link['page'] ?>" class="<?= $cls ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><?= $link['icon'] ?></svg>
        <?= $link['label'] ?>
      </a>
      <?php endforeach; ?>
    </nav>
  </aside>
  