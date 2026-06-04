<!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Daftar Admin — EMBIM Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style/style.css" />
  </head>
  <body class="min-h-screen bg-blue-700 flex flex-col items-center justify-center px-4 py-12">

    <!-- Brand -->
    <div class="mb-8 text-center">
      <div class="flex items-center justify-center gap-2 mb-1">
        <span class="text-2xl font-extrabold text-white tracking-tight">EMBIM ADMIN</span>
      </div>
      <p class="text-blue-200 text-sm">Panel Manajemen Administrator</p>
    </div>

    <!-- Card -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl px-8 py-10">

      <h1 class="text-2xl font-bold text-gray-900 text-center mb-2">Buat Akun Baru!</h1>
      <p class="text-gray-500 text-sm text-center mb-7">
        Akun akan terdaftar sebagai <span class="font-semibold text-blue-600">Admin</span>
      </p>

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

      <form action="?page=register" method="POST" class="space-y-5">

        <div>
          <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
          <input type="text" id="full_name" name="full_name"
            value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
            placeholder="Masukkan nama lengkap Anda"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            required autocomplete="name" />
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
          <input type="email" id="email" name="email"
            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
            placeholder="contoh@email.com"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            required autocomplete="email" />
        </div>

        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
          <input type="tel" id="phone" name="phone"
            value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
            placeholder="+62 812 3456 7890"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            autocomplete="tel" />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <div class="relative">
            <input type="password" id="password" name="password"
              placeholder="Buat password yang kuat"
              class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors pr-11"
              required autocomplete="new-password"
              oninput="checkPassword(this.value)" />
            <button type="button" onclick="togglePass('password')"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
            </button>
          </div>
          <div class="mt-2 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
            <p class="text-xs font-semibold text-gray-700 mb-2">Password harus mengandung:</p>
            <ul class="space-y-1">
              <li id="req-len" class="flex items-center gap-2 text-xs text-gray-400"><span class="req-dot h-1.5 w-1.5 rounded-full bg-gray-300 shrink-0 inline-block"></span> 8–100 karakter</li>
              <li id="req-low" class="flex items-center gap-2 text-xs text-gray-400"><span class="req-dot h-1.5 w-1.5 rounded-full bg-gray-300 shrink-0 inline-block"></span> Minimal satu huruf kecil</li>
              <li id="req-up"  class="flex items-center gap-2 text-xs text-gray-400"><span class="req-dot h-1.5 w-1.5 rounded-full bg-gray-300 shrink-0 inline-block"></span> Minimal satu huruf besar</li>
              <li id="req-num" class="flex items-center gap-2 text-xs text-gray-400"><span class="req-dot h-1.5 w-1.5 rounded-full bg-gray-300 shrink-0 inline-block"></span> Minimal satu angka</li>
            </ul>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <input type="checkbox" id="terms" name="terms" value="1"
            class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
            <?= !empty($old['terms']) ? 'checked' : '' ?> />
          <label for="terms" class="text-xs text-gray-500 cursor-pointer leading-relaxed">
            Dengan mendaftar, saya menyetujui
            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Syarat &amp; Ketentuan</a>,
            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Kebijakan Privasi</a>, dan
            <a href="#" class="text-blue-600 hover:text-blue-700 font-medium">Kebijakan Penggunaan</a> EMBIM.
          </label>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 rounded-lg transition-colors text-sm shadow-sm">
          Buat Akun
        </button>

      </form>

      <p class="text-center text-sm text-gray-500 mt-6">
        Sudah punya akun?
        <a href="?page=login" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">Login di sini</a>
      </p>

    </div>

    <p class="mt-8 text-blue-200 text-xs text-center">
      &copy; 2026 EMBIM. All rights reserved.
    </p>

    <script>
      function togglePass(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
      }
      function checkPassword(val) {
        const rules = {
          'req-len': val.length >= 8 && val.length <= 100,
          'req-low': /[a-z]/.test(val),
          'req-up':  /[A-Z]/.test(val),
          'req-num': /[0-9]/.test(val),
        };
        for (const [id, ok] of Object.entries(rules)) {
          const li  = document.getElementById(id);
          const dot = li.querySelector('.req-dot');
          if (ok) {
            li.classList.replace('text-gray-400', 'text-green-600');
            dot.classList.replace('bg-gray-300',  'bg-green-500');
          } else {
            li.classList.replace('text-green-600', 'text-gray-400');
            dot.classList.replace('bg-green-500',  'bg-gray-300');
          }
        }
      }
    </script>
  </body>
  </html>
  