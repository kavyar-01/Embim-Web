<?php require_once __DIR__ . '/partials/layout_top.php'; ?>
<div class="space-y-6">
  <div><h1 class="text-3xl font-bold tracking-tight text-gray-900">Manage Users</h1><p class="text-gray-500 text-sm mt-1">View and manage all admin panel users.</p></div>
  <?php if(!empty($message)): ?><div class="bg-green-100 border border-green-300 text-green-800 rounded-md px-4 py-3 text-sm font-medium"><?= htmlspecialchars($message) ?></div><?php endif; ?>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php 
      $fRole = $_GET['role'] ?? '';
      $ucards=[
      ['label'=>'Total Users',   'value'=>$stats['total'],   'role'=>'all',   'bg'=>'bg-blue-50',   'color'=>'text-blue-500',   'hover'=>'hover:border-blue-300 hover:shadow-[0_0_20px_rgba(59,130,246,0.3)]', 'active'=>'border-blue-500 ring-2 ring-blue-200 shadow-[0_0_20px_rgba(59,130,246,0.2)] bg-white', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>'],
      ['label'=>'Active Users',  'value'=>$stats['active'],  'role'=>'active','bg'=>'bg-green-50',  'color'=>'text-green-500',  'hover'=>'hover:border-green-300 hover:shadow-[0_0_20px_rgba(34,197,94,0.3)]', 'active'=>'border-green-500 ring-2 ring-green-200 shadow-[0_0_20px_rgba(34,197,94,0.2)] bg-white', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
      ['label'=>'Users',         'value'=>$stats['users'],   'role'=>'user',  'bg'=>'bg-gray-50',   'color'=>'text-gray-500',   'hover'=>'hover:border-gray-300 hover:shadow-[0_0_20px_rgba(156,163,175,0.3)]', 'active'=>'border-gray-500 ring-2 ring-gray-200 shadow-[0_0_20px_rgba(156,163,175,0.2)] bg-white', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
      ['label'=>'Admins',        'value'=>$stats['admins'],  'role'=>'admin', 'bg'=>'bg-purple-50', 'color'=>'text-purple-500', 'hover'=>'hover:border-purple-300 hover:shadow-[0_0_20px_rgba(168,85,247,0.3)]', 'active'=>'border-purple-500 ring-2 ring-purple-200 shadow-[0_0_20px_rgba(168,85,247,0.2)] bg-white', 'icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>'],
    ]; foreach($ucards as $uc): 
      $isSel = ($fRole !== '' && $uc['role'] === $fRole);
      $activeClass = $isSel ? $uc['active'] : 'border-gray-200 hover:-translate-y-1 bg-white';
      $onClick = "onclick=\"handleFilter('role', '{$uc['role']}')\"";
      $cursor = "cursor-pointer";
    ?>
    <div <?= $onClick ?> class="rounded-xl border p-4 flex items-center justify-between shadow-sm transition-all duration-300 <?= $uc['hover'] ?> <?= $activeClass ?> <?= $cursor ?>">
      <div>
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide"><?= $uc['label'] ?></p>
        <p class="text-2xl font-bold text-gray-900 mt-1"><?= $uc['value'] ?></p>
      </div>
      <div class="p-3 <?= $uc['bg'] ?> rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 <?= $uc['color'] ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><?= $uc['icon'] ?></svg>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
    <form method="GET" action="" class="flex flex-col md:flex-row gap-4 items-end">
      <input type="hidden" name="page" value="manage_users" />
      <?php if ($fRole !== ''): ?>
        <input type="hidden" name="role" value="<?= htmlspecialchars($fRole) ?>" />
      <?php endif; ?>

      <div class="flex-1 w-full">
        <label class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">Search</label>
        <div class="relative">
          <input type="text" name="search"
                 value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                 placeholder="Search by ID, name, email..."
                 class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" />
          <svg class="h-5 w-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>

      <div class="flex gap-2 w-full md:w-auto">
        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
          Search
        </button>
        <a href="?page=manage_users<?= !empty($fRole) ? '&role='.urlencode($fRole) : '' ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition-colors">
          Reset
        </a>
      </div>
    </form>
  </div>
  <div class="border border-gray-200 rounded-lg bg-white overflow-hidden">
    <table class="w-full text-sm"><thead class="bg-gray-50 border-b border-gray-200"><tr>
      <th class="text-left px-4 py-3 font-medium text-gray-600 w-[5%]">ID</th>
      <th class="text-left px-4 py-3 font-medium text-gray-600 w-[25%]">Name</th>
      <th class="text-left px-4 py-3 font-medium text-gray-600 w-[25%]">Email</th>
      <th class="text-left px-4 py-3 font-medium text-gray-600 w-[15%]">Phone</th>
      <th class="text-center px-4 py-3 font-medium text-gray-600 w-[10%]">Role</th>
      <th class="text-center px-4 py-3 font-medium text-gray-600 w-[10%]">Joined</th>
      <th class="text-right px-4 py-3 font-medium text-gray-600 w-[10%]">Actions</th>
    </tr></thead><tbody class="divide-y divide-gray-100">
      <?php if(empty($users)): ?><tr><td colspan="7" class="text-center py-10 text-gray-400">No users found.</td></tr>
      <?php else: foreach($users as $u):
<<<<<<< HEAD
        $rb = $u['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-700';
        $nameParts = preg_split('/\s+/', trim($u['full_name']));
        $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . substr(end($nameParts) ?: '', 0, 1));
=======
        $rb = $u['role'] === 'admin' ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-gray-100 text-gray-700 border-gray-200';
        $initials = strtoupper(substr($u['full_name'],0,1).substr(strrchr($u['full_name'],' '),1,1));
>>>>>>> 8d493b6ff0358e335b45b2c55d71163b3f357789
      ?>
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-3 text-gray-500"><?= $u['id'] ?></td>
        <td class="px-4 py-3"><div class="flex items-center gap-3">
          <?php if (!empty($u['photo_profile'])): ?>
            <img src="../assets/images/user/<?= htmlspecialchars($u['photo_profile']) ?>" alt="Photo" class="h-8 w-8 rounded-full object-cover shrink-0" />
          <?php else: ?>
            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0"><span class="text-blue-600 text-xs font-semibold"><?= $initials ?></span></div>
          <?php endif; ?>
          <span class="font-medium text-gray-900"><?= htmlspecialchars($u['full_name']) ?></span></div></td>
        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($u['email']) ?></td>
        <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
        <td class="px-4 py-3 text-center"><span class="px-2 py-1 border rounded-md text-[10px] font-bold uppercase tracking-wider <?= $rb ?>"><?= htmlspecialchars($u['role']) ?></span></td>
        <td class="px-4 py-3 text-gray-600 text-center"><?= date('Y-m-d', strtotime($u['created_at'])) ?></td>
        <td class="px-4 py-3">
          <div class="flex flex-row items-center justify-end gap-2">
            <button type="button" onclick="openDeleteModal(<?= $u['id'] ?>)" title="Delete User" class="inline-flex items-center justify-center h-8 w-8 rounded-md hover:bg-red-50 text-red-500"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
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
  <?php if($totalPages > 0): ?>
  <?php
    $baseUrl = '?page=manage_users' . (!empty($_GET['search']) ? '&search='.urlencode($_GET['search']) : '') . (!empty($_GET['role']) ? '&role='.urlencode($_GET['role']) : '');
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
      <form id="form-delete" method="POST" action="?page=manage_users" style="margin:0;flex:1;display:flex;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" id="delete-id" name="user_id" value="" />
        <button type="submit" class="btn" style="width:100%;background:#dc2626;color:#fff;border:1px solid #dc2626;padding:10px 16px;border-radius:8px;font-weight:600;cursor:pointer; justify-content:center; text-align:center;">
          Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<script>
function openDeleteModal(id) {
  document.getElementById('delete-id').value = id;
  document.getElementById('modal-delete').style.display = 'flex';
}
// Close modal if clicking outside
document.getElementById('modal-delete').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});

function handleFilter(key, value) {
  const urlParams = new URLSearchParams(window.location.search);
  
  // Reset pagination when filtering
  urlParams.delete('p');

  if (urlParams.get(key) === value) {
    // If the same filter is clicked, toggle it off
    urlParams.delete(key);
  } else {
    // Set the new filter value
    urlParams.set(key, value);
  }
  
  window.location.search = urlParams.toString();
}
</script>

<?php require_once __DIR__ . '/partials/layout_bottom.php'; ?>
