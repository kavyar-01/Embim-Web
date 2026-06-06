<!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login — EMBIM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style/style.css" />
  </head>
  <body class="min-h-screen flex flex-col items-center justify-center px-4 py-12 relative bg-cover bg-center bg-no-repeat" style="background-image: url('../assets/images/admin_bg.jpg');">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/65 backdrop-blur-[4px] z-0"></div>

    <!-- Brand -->
    <div class="mb-8 text-center relative z-10">
      <div class="flex items-center justify-center gap-2 mb-1">
        <a href="../index.php" class="text-2xl font-extrabold text-white tracking-tight">EMBIM ADMIN</a>
      </div>
    </div>

    <!-- Card -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl px-8 py-10 relative z-10">

      <h1 class="text-2xl font-bold text-gray-900 text-center mb-7">Login</h1>

      <?php if (!empty($errors)): ?>
      <div class="bg-red-50 border border-red-300 text-red-700 rounded-lg px-4 py-3 mb-5 text-sm space-y-1">
        <?php foreach ($errors as $e): ?>
        <p class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <?= htmlspecialchars($e) ?>
        </p>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php if (!empty($_GET['registered'])): ?>
      <div class="bg-green-50 border border-green-300 text-green-700 rounded-lg px-4 py-3 mb-5 text-sm flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Admin account successfully created! Please login.
      </div>
      <?php endif; ?>

      <?php if (!empty($_GET['logout'])): ?>
      <div class="bg-blue-50 border border-blue-300 text-blue-700 rounded-lg px-4 py-3 mb-5 text-sm flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/></svg>
        You have successfully logged out.
      </div>
      <?php endif; ?>

      <form action="?page=login" method="POST" class="space-y-5">

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" id="email" name="email"
            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
            placeholder="Enter your email address"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            required autocomplete="email" />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <div class="relative">
            <input type="password" id="password" name="password"
              placeholder="Enter your password"
              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-11"
              required autocomplete="current-password" />
            <button type="button" onclick="togglePass('password')"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <div class="flex justify-end mt-1.5">
            <button type="button" onclick="openForgotModal()" class="text-xs text-blue-600 hover:text-blue-700 font-medium transition-colors">Forgot Password?</button>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 rounded-lg transition-colors text-sm shadow-sm">
          Login
        </button>

      </form>

      <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an admin account?
        <a href="?page=register" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">Register New Account</a>
      </p>

    </div>

    <p class="mt-8 text-gray-300 text-xs text-center relative z-10">
      By logging in, you agree to
      <a href="#" class="underline hover:text-white transition-colors">Terms &amp; Conditions</a> dan
      <a href="#" class="underline hover:text-white transition-colors">Privacy Policy</a> EMBIM.
    </p>

<!-- ══════════════════════════════════════════
     POPUP LUPA PASSWORD
══════════════════════════════════════════ -->
<div id="forgot-overlay" class="hidden fixed inset-0 z-[999] flex items-center justify-center px-4">

    <!-- Backdrop -->
    <div id="forgot-backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

    <!-- Modal -->
    <div id="forgot-modal"
         class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden
                translate-y-6 opacity-0 transition-all duration-300 ease-out">

        <!-- Top accent -->
        <div class="h-1.5 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-500"></div>

        <!-- Close button -->
        <button onclick="closeForgotModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition text-gray-500">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- ── STEP 1: Verifikasi Identitas ── -->
        <div id="step-verify" class="px-7 pt-6 pb-7">
            <div class="mb-5">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-extrabold text-gray-900">Forgot Admin Password?</h3>
                <p class="text-sm text-gray-400 mt-1">Enter your registered full name and phone number to verify your account.</p>
            </div>

            <div id="verify-alert" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-semibold border"></div>

            <div class="space-y-4">
                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Full Name</label>
                    <input
                        type="text"
                        id="verify-name"
                        placeholder="Enter your full name"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-800 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                        autocomplete="name"
                    >
                </div>

                <!-- Phone Number -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 py-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold text-gray-600 select-none">
                            +62
                        </span>
                        <input
                            type="tel"
                            id="verify-phone"
                            placeholder="8123456789"
                            inputmode="numeric"
                            maxlength="13"
                            class="flex-1 px-4 py-3 border border-gray-200 rounded-r-xl text-sm text-gray-800 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                        >
                    </div>
                </div>

                <button
                    onclick="verifyIdentity()"
                    id="verify-btn"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3.5 rounded-xl shadow-md shadow-blue-200/50 transition duration-200"
                >
                    Verify Account
                </button>
            </div>
        </div>

        <!-- ── STEP 2: Reset Password ── -->
        <div id="step-reset" class="hidden px-7 pt-6 pb-7">
            <div class="mb-5">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-extrabold text-gray-900">Reset Password Admin</h3>
                <p class="text-sm text-gray-400 mt-1">Identity verified. Create a new password for your account.</p>
            </div>

            <input type="hidden" id="reset-user-id">

            <div id="reset-alert" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-semibold border"></div>

            <div class="space-y-4">
                <!-- New Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">New Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="reset-password"
                            placeholder="Min. 8 characters"
                            class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-xl text-sm text-gray-800 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                        >
                        <button type="button" onclick="toggleResetPw('reset-password', 'eye1')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye1" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="reset-confirm"
                            placeholder="Repeat new password"
                            class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-xl text-sm text-gray-800 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                        >
                        <button type="button" onclick="toggleResetPw('reset-confirm', 'eye2')"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye2" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Rules mini -->
                <div class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 space-y-1.5" id="reset-rules">
                    <?php foreach ([
                        ['id' => 'rr-len',   'text' => '8–100 characters'],
                        ['id' => 'rr-lower', 'text' => 'At least one lowercase letter'],
                        ['id' => 'rr-upper', 'text' => 'At least one uppercase letter'],
                        ['id' => 'rr-digit', 'text' => 'At least one number'],
                        ['id' => 'rr-match', 'text' => 'Passwords match'],
                    ] as $r): ?>
                    <div class="flex items-center gap-2 text-xs text-gray-400" id="<?php echo $r['id']; ?>">
                        <svg class="h-3.5 w-3.5 flex-shrink-0 rule-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="1.5"/>
                        </svg>
                        <span><?php echo $r['text']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <button
                    onclick="submitReset()"
                    id="reset-btn"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm py-3.5 rounded-xl shadow-md shadow-emerald-200/50 transition duration-200"
                >
                    Save New Password
                </button>
            </div>
        </div>

    </div>
</div>

    <script>
      function togglePass(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
      }

      function openForgotModal() {
          const overlay  = document.getElementById('forgot-overlay');
          const modal    = document.getElementById('forgot-modal');
          const backdrop = document.getElementById('forgot-backdrop');

          showStep('verify');
          document.getElementById('verify-name').value  = '';
          document.getElementById('verify-phone').value = '';
          setAlert('verify-alert', '', '');

          overlay.classList.remove('hidden');
          requestAnimationFrame(() => requestAnimationFrame(() => {
              backdrop.style.opacity = '1';
              modal.style.opacity    = '1';
              modal.style.transform  = 'translateY(0)';
          }));

          backdrop.onclick = closeForgotModal;
          document.addEventListener('keydown', forgotEscHandler);

          const phoneInput = document.getElementById('verify-phone');
          phoneInput.oninput = function() {
              this.value = this.value.replace(/[^0-9]/g, '');
          };
          phoneInput.onkeydown = function(e) {
              const allowed = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'];
              if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) e.preventDefault();
          };
      }

      function closeForgotModal() {
          const overlay  = document.getElementById('forgot-overlay');
          const modal    = document.getElementById('forgot-modal');
          const backdrop = document.getElementById('forgot-backdrop');

          backdrop.style.opacity = '0';
          modal.style.opacity    = '0';
          modal.style.transform  = 'translateY(24px)';
          setTimeout(() => overlay.classList.add('hidden'), 300);
          document.removeEventListener('keydown', forgotEscHandler);
      }

      function forgotEscHandler(e) {
          if (e.key === 'Escape') closeForgotModal();
      }

      function showStep(step) {
          document.getElementById('step-verify').classList.toggle('hidden', step !== 'verify');
          document.getElementById('step-reset').classList.toggle('hidden',  step !== 'reset');
      }

      function setAlert(id, type, message) {
          const el = document.getElementById(id);
          el.className = 'px-4 py-3 rounded-xl text-sm font-semibold border';
          if (!message) { el.classList.add('hidden'); return; }
          el.classList.remove('hidden');
          if (type === 'error') {
              el.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
          } else {
              el.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
          }
          el.textContent = message;
      }

      function verifyIdentity() {
          const name  = document.getElementById('verify-name').value.trim();
          const phone = document.getElementById('verify-phone').value.trim();
          const btn   = document.getElementById('verify-btn');

          if (!name || !phone) {
              setAlert('verify-alert', 'error', 'Nama lengkap dan nomor telepon wajib diisi.');
              return;
          }

          btn.disabled    = true;
          btn.textContent = 'Verifying...';
          setAlert('verify-alert', '', '');

          const formData = new FormData();
          formData.append('full_name', name);
          formData.append('phone',     phone);

          fetch('?page=forgot-verify', { method: 'POST', body: formData })
              .then(r => r.json())
              .then(data => {
                  btn.disabled    = false;
                  btn.textContent = 'Verify Account';

                  if (data.success) {
                      document.getElementById('reset-user-id').value = data.user_id;
                      document.getElementById('reset-password').value = '';
                      document.getElementById('reset-confirm').value  = '';
                      setAlert('reset-alert', '', '');
                      resetRules();
                      showStep('reset');
                  } else {
                      setAlert('verify-alert', 'error', data.message);
                  }
              })
              .catch(() => {
                  btn.disabled    = false;
                  btn.textContent = 'Verify Account';
                  setAlert('verify-alert', 'error', 'An error occurred. Please try again.');
              });
      }

      function submitReset() {
          const userId   = document.getElementById('reset-user-id').value;
          const password = document.getElementById('reset-password').value;
          const confirm  = document.getElementById('reset-confirm').value;
          const btn      = document.getElementById('reset-btn');

          const valid = checkRules(password, confirm);
          if (!valid) {
              setAlert('reset-alert', 'error', 'Make sure all password requirements are met.');
              return;
          }

          btn.disabled    = true;
          btn.textContent = 'Saving...';
          setAlert('reset-alert', '', '');

          const formData = new FormData();
          formData.append('user_id',  userId);
          formData.append('password', password);
          formData.append('confirm',  confirm);

          fetch('?page=forgot-reset', { method: 'POST', body: formData })
              .then(r => r.json())
              .then(data => {
                  if (data.success) {
                      setAlert('reset-alert', 'success', '✓ ' + data.message);
                      btn.disabled    = true;
                      btn.textContent = 'Password Saved';
                      setTimeout(closeForgotModal, 2000);
                  } else {
                      setAlert('reset-alert', 'error', data.message);
                      btn.disabled    = false;
                      btn.textContent = 'Save New Password';
                  }
              })
              .catch(() => {
                  btn.disabled    = false;
                  btn.textContent = 'Save New Password';
                  setAlert('reset-alert', 'error', 'An error occurred. Please try again.');
              });
      }

      function checkRules(pw, confirm) {
          const rules = {
              'rr-len':   pw.length >= 8 && pw.length <= 100,
              'rr-lower': /[a-z]/.test(pw),
              'rr-upper': /[A-Z]/.test(pw),
              'rr-digit': /[0-9]/.test(pw),
              'rr-match': pw.length > 0 && pw === confirm,
          };

          let allPassed = true;
          Object.entries(rules).forEach(([id, passed]) => {
              const el   = document.getElementById(id);
              if (!el) return;
              const icon = el.querySelector('.rule-icon');
              if (passed) {
                  el.classList.replace('text-gray-400', 'text-emerald-600');
                  if (icon) icon.setAttribute('stroke', '#10b981');
              } else {
                  el.classList.replace('text-emerald-600', 'text-gray-400');
                  if (icon) icon.setAttribute('stroke', 'currentColor');
                  allPassed = false;
              }
          });
          return allPassed;
      }

      function resetRules() {
          ['rr-len','rr-lower','rr-upper','rr-digit','rr-match'].forEach(id => {
              const el = document.getElementById(id);
              if (!el) return;
              el.classList.remove('text-emerald-600');
              el.classList.add('text-gray-400');
              const icon = el.querySelector('.rule-icon');
              if (icon) icon.setAttribute('stroke', 'currentColor');
          });
      }

      document.addEventListener('DOMContentLoaded', function () {
          const pw      = document.getElementById('reset-password');
          const confirm = document.getElementById('reset-confirm');
          if (pw)      pw.addEventListener('input',      () => checkRules(pw.value, confirm ? confirm.value : ''));
          if (confirm) confirm.addEventListener('input', () => checkRules(pw ? pw.value : '', confirm.value));
      });

      function toggleResetPw(inputId, iconId) {
          const input  = document.getElementById(inputId);
          const icon   = document.getElementById(iconId);
          const isHide = input.type === 'password';
          input.type   = isHide ? 'text' : 'password';

          if (isHide) {
              icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
          } else {
              icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
          }
      }
    </script>
  </body>
</html>