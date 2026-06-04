<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">
  <div><h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Users</h1><p class="text-gray-500 text-sm mt-1">View and manage all admin panel users.</p></div>
  <?php if(!empty($message)): ?><div class="bg-green-100 border border-green-300 text-green-800 rounded-md px-4 py-3 text-sm font-medium"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php $ucards=[
      ['label'=>'Total Users',   'value'=>$stats['total'],   'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 4a2 2 0 11-4 0 2 2 0 014 0zM5 16a2 2 0 11-4 0 2 2 0 014 0z"/>'],
      ['label'=>'Active Users',  'value'=>$stats['active'],  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
      ['label'=>'Inactive Users','value'=>$stats['inactive'],'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>'],
      ['label'=>'Admins',        'value'=>$stats['admins'],  'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'],
    ]; foreach($ucards as $uc): ?>
    <div class="bg-white border border-gray-200 rounded-lg p-5">
      <div class="flex items-center justify-between pb-2">
        <span class="text-sm font-medium text-gray-500"><?= $uc['label'] ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><?= $uc['icon'] ?></svg>
      </div>
      <div class="text-2xl font-bold text-gray-900"><?= $uc['value'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <form method="GET" action=""><input type="hidden" name="page" value="manage_users" />
    <div class="relative w-full max-w-sm">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
      <input type="search" name="search" value="<?= htmlspecialchars($_GET['search']??'') ?>" placeholder="Search by name, email, or role..." class="w-full border border-gray-300 rounded-md pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
    </div>
  </form>
  <div class="border border-gray-200 rounded-lg bg-white overflow-hidden">
    <table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
      <th class="text-left px-4 py-3 font-medium text-gray-600">ID</th>
      <th class="text-left px-4 py-3 font-medium text-gray-600">Name</th>
      <th class="text-left px-4 py-3 font-medium text-gray-600">Email</th>
      <th class="text-left px-4 py-3 font-medium text-gray-600">Phone</th>
      <th class="text-left px-4 py-3 font-medium text-gray-600">Role</th>
      <th class="text-left px-4 py-3 font-medium text-gray-600">Joined</th>
      <th class="text-right px-4 py-3 font-medium text-gray-600">Actions</th>
    </tr></thead><tbody class="divide-y divide-gray-100">
      <?php if(empty($users)): ?><tr><td colspan="7" class="text-center py-10 text-gray-400">No users found.</td></tr>
      <?php else: foreach($users as $u):
        $rb = $u['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700';
        $initials = strtoupper(substr($u['full_name'],0,1).substr(strrchr($u['full_name'],' '),1,1));
      ?>
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-3 text-gray-500"><?= $u['id'] ?></td>
        <td class="px-4 py-3"><div class="flex items-center gap-3"><div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0"><span class="text-blue-600 text-xs font-semibold"><?= $initials ?></span></div><span class="font-medium text-gray-900"><?= htmlspecialchars($u['full_name']) ?></span></div></td>
        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($u['email']) ?></td>
        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
        <td class="px-4 py-3"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $rb ?>"><?= ucfirst($u['role']) ?></span></td>
        <td class="px-4 py-3 text-gray-600"><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
        <td class="px-4 py-3">
          <div class="flex flex-col items-center gap-1">
            <button title="Edit" class="inline-flex items-center justify-center h-8 w-8 rounded-md hover:bg-gray-100 text-gray-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
            <form method="POST" action="?page=manage_users" onsubmit="return confirm('Delete this user?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="user_id" value="<?= $u['id'] ?>"><button type="submit" title="Delete" class="inline-flex items-center justify-center h-8 w-8 rounded-md hover:bg-red-50 text-red-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
          </div>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody></table>
  </div>
  <?php
    $totalPages  = (int)($totalPages  ?? 1);
    $currentPage = (int)($currentPage ?? 1);
    $total       = (int)($total       ?? 0);
  ?>
  <?php if($totalPages > 1): ?>
  <?php
    $baseUrl = '?page=manage_users' . (!empty($_GET['search']) ? '&search='.urlencode($_GET['search']) : '');
    $currentPage = max(1, $currentPage);
    $from = min($total, ($currentPage - 1) * 10 + 1);
    $to   = min($total, $currentPage * 10);
  ?>
  <div class="flex items-center justify-between pt-4 border-t border-gray-100">
    <p class="text-sm text-gray-500">Showing <?= $from ?>–<?= $to ?> of <?= $total ?> results</p>
    <div class="flex items-center gap-0.5 text-sm">
      <?php if($currentPage > 1): ?>
        <a href="<?= $baseUrl ?>&p=<?= $currentPage-1 ?>" class="px-3 py-1.5 rounded text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors">← Previous</a>
      <?php else: ?>
        <span class="px-3 py-1.5 text-gray-300">← Previous</span>
      <?php endif; ?>
      <?php for($i=1;$i<=$totalPages;$i++): ?>
        <?php if($i==1||$i==$totalPages||abs($i-$currentPage)<=1): ?>
          <a href="<?= $baseUrl ?>&p=<?= $i ?>" class="px-3 py-1.5 rounded font-medium transition-colors <?= $i==$currentPage ? 'bg-blue-600 text-white' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' ?>"><?= $i ?></a>
        <?php elseif($i==2&&$currentPage>3||$i==$totalPages-1&&$currentPage<$totalPages-2): ?>
          <span class="px-2 py-1.5 text-gray-400">…</span>
        <?php endif; ?>
      <?php endfor; ?>
      <?php if($currentPage < $totalPages): ?>
        <a href="<?= $baseUrl ?>&p=<?= $currentPage+1 ?>" class="px-3 py-1.5 rounded text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors">Next →</a>
      <?php else: ?>
        <span class="px-3 py-1.5 text-gray-300">Next →</span>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
