<?php require_once __DIR__ . '/../partials/layout_top.php'; ?>
<?php require_once __DIR__ . '/../partials/sidebar.php'; ?>

<div class="flex-1 flex flex-col h-full bg-gray-50 overflow-hidden">
  <?php require_once __DIR__ . '/../partials/topbar.php'; ?>
  
  <main class="flex-1 overflow-y-auto p-6">
    <div class="max-w-3xl mx-auto">

      <!-- Page Header -->
      <div class="mb-6">
          <h1 class="text-2xl font-extrabold text-gray-900">Edit Profile Admin</h1>
          <p class="text-sm text-gray-500 mt-1">Update your account information</p>
      </div>

      <?php if (!empty($success)): ?>
      <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold px-4 py-3 rounded-xl">
          <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Profile successfully updated.
      </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
      <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 space-y-1">
          <?php foreach ($errors as $err): ?>
          <p class="text-sm text-red-600 font-medium flex items-start gap-2">
              <svg class="h-4 w-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <?= htmlspecialchars($err) ?>
          </p>
          <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <form action="?page=edit_profile" method="POST" enctype="multipart/form-data" autocomplete="off" class="space-y-5" onsubmit="event.preventDefault(); showConfirmModal(this);">
          <!-- ── Foto Profile ── -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
              <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-5">Profile Picture</h2>
              <div class="flex items-center gap-5">
                  <div class="flex-shrink-0">
                      <img
                          id="preview-profile"
                          src="<?= !empty($admin['photo_profile'])
                              ? '../assets/images/user/' . htmlspecialchars($admin['photo_profile'])
                              : '../assets/images/user_default.png' ?>"
                          alt="Profile Picture"
                          class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                      >
                  </div>
                  <div class="flex-1">
                      <label class="block text-xs font-semibold text-gray-600 mb-2">Change Profile Picture</label>
                      <input
                          type="file"
                          name="photo_profile"
                          accept="image/jpeg,image/png,image/webp"
                          onchange="previewImage(this, 'preview-profile')"
                          class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition cursor-pointer"
                      >
                      <p class="text-xs text-gray-400 mt-1.5">JPG, PNG, or WebP. Max. 2 MB.</p>
                  </div>
              </div>
          </div>

          <!-- ── Info Akun ── -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
              <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-5">Account Info</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                      <label class="block text-xs font-semibold text-gray-500 mb-1.5">Joined Since</label>
                      <div class="w-full px-3 py-2.5 rounded-xl bg-gray-50 border border-gray-100 text-sm text-gray-400">
                          <?= date('d M Y, H:i', strtotime($admin['created_at'])) ?>
                      </div>
                  </div>
                  <div>
                      <label class="block text-xs font-semibold text-gray-500 mb-1.5">Last Updated</label>
                      <div class="w-full px-3 py-2.5 rounded-xl bg-gray-50 border border-gray-100 text-sm text-gray-400">
                          <?= date('d M Y, H:i', strtotime($admin['updated_at'])) ?>
                      </div>
                  </div>
              </div>
          </div>

          <!-- ── Data Pribadi ── -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
              <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-5">Personal Data</h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div class="sm:col-span-2">
                      <label class="block text-xs font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                      <input type="text" name="full_name" value="<?= htmlspecialchars($admin['full_name']) ?>" required pattern="^[^0-9]*$" title="Name must not contain numbers" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition" placeholder="Enter full name">
                  </div>
                  <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email <span class="text-red-400">*</span></label>
                      <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition" placeholder="email@contoh.com">
                  </div>
                  <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1.5">Phone Number</label>
                      <div class="flex">
                          <span class="inline-flex items-center px-4 py-2.5 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-sm font-bold text-gray-600 select-none">
                              +62
                          </span>
                          <input type="tel" name="phone" value="<?= htmlspecialchars(preg_replace('/^\+?62/', '', $admin['phone'] ?? '')) ?>" required minlength="7" class="w-full px-3 py-2.5 rounded-r-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition" placeholder="812 xxxx xxxx" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                      </div>
                  </div>
              </div>
          </div>

          <!-- ── Ganti Password ── -->
          <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
              <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">Change Password</h2>
              <p class="text-xs text-gray-400 mb-5">Leave blank if you do not want to change your password.</p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1.5">New Password</label>
                      <div class="relative">
                          <input type="password" id="password" name="password" autocomplete="new-password" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition pr-11" placeholder="Min. 8 characters" oninput="checkPassword(this.value)">
                          <button type="button" id="toggle-password" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                              <svg id="icon-eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                              <svg id="icon-eye-off" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                          </button>
                      </div>
                      <div class="mt-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 hidden" id="password-req-box">
                        <p class="text-xs font-semibold text-gray-700 mb-2">Password must contain:</p>
                        <ul class="space-y-1">
                          <li id="req-len" class="flex items-center gap-2 text-xs text-gray-400"><span class="req-dot h-1.5 w-1.5 rounded-full bg-gray-300 shrink-0 inline-block"></span> 8–100 characters</li>
                          <li id="req-low" class="flex items-center gap-2 text-xs text-gray-400"><span class="req-dot h-1.5 w-1.5 rounded-full bg-gray-300 shrink-0 inline-block"></span> At least one lowercase letter</li>
                          <li id="req-up"  class="flex items-center gap-2 text-xs text-gray-400"><span class="req-dot h-1.5 w-1.5 rounded-full bg-gray-300 shrink-0 inline-block"></span> At least one uppercase letter</li>
                          <li id="req-num" class="flex items-center gap-2 text-xs text-gray-400"><span class="req-dot h-1.5 w-1.5 rounded-full bg-gray-300 shrink-0 inline-block"></span> At least one number</li>
                        </ul>
                      </div>
                  </div>
                  <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                      <div class="relative">
                          <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition pr-11" placeholder="Repeat new password">
                          <button type="button" id="toggle-password-confirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                              <svg id="icon-eye-conf" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                              <svg id="icon-eye-off-conf" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                          </button>
                      </div>
                  </div>
              </div>
          </div>

          <!-- ── Save Button ── -->
          <div class="flex items-center justify-end gap-3 pb-4">
              <a href="?page=dashboard" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition duration-200">Cancel</a>
              <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-200/50 transition duration-200">Save Changes</button>
          </div>
      </form>
    </div>
  </main>
</div>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function checkPassword(val) {
    const reqBox = document.getElementById('password-req-box');
    if (val.length > 0) {
        reqBox.classList.remove('hidden');
    } else {
        reqBox.classList.add('hidden');
    }

    const rules = {
      'req-len': val.length >= 8 && val.length <= 100,
      'req-low': /[a-z]/.test(val),
      'req-up':  /[A-Z]/.test(val),
      'req-num': /[0-9]/.test(val),
    };
    for (const [id, ok] of Object.entries(rules)) {
      const li  = document.getElementById(id);
      if (!li) continue;
      const dot = li.querySelector('.req-dot');
      if (ok) {
        li.classList.replace('text-gray-400', 'text-green-600');
        if (dot) dot.classList.replace('bg-gray-300', 'bg-green-500');
      } else {
        li.classList.replace('text-green-600', 'text-gray-400');
        if (dot) dot.classList.replace('bg-green-500', 'bg-gray-300');
      }
    }
}

function attachToggle(btnId, inputId, eyeId, eyeOffId) {
    const btn = document.getElementById(btnId);
    const inp = document.getElementById(inputId);
    const eye = document.getElementById(eyeId);
    const off = document.getElementById(eyeOffId);
    if(btn && inp) {
        btn.addEventListener('click', function() {
            const isHidden = inp.type === 'password';
            inp.type = isHidden ? 'text' : 'password';
            eye.classList.toggle('hidden', isHidden);
            off.classList.toggle('hidden', !isHidden);
        });
    }
}

attachToggle('toggle-password', 'password', 'icon-eye', 'icon-eye-off');
attachToggle('toggle-password-confirm', 'password_confirm', 'icon-eye-conf', 'icon-eye-off-conf');
</script>

<?php require_once __DIR__ . '/../partials/layout_bottom.php'; ?>
