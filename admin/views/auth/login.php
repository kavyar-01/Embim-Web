<!DOCTYPE html>
  <html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login — EMBIM Admin</title>
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
        Akun admin berhasil dibuat! Silakan login.
      </div>
      <?php endif; ?>

      <?php if (!empty($_GET['logout'])): ?>
      <div class="bg-blue-50 border border-blue-300 text-blue-700 rounded-lg px-4 py-3 mb-5 text-sm flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/></svg>
        Anda telah berhasil logout.
      </div>
      <?php endif; ?>

      <form action="?page=login" method="POST" class="space-y-5">

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
          <input type="email" id="email" name="email"
            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
            placeholder="Masukkan alamat email Anda"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            required autocomplete="email" />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <div class="relative">
            <input type="password" id="password" name="password"
              placeholder="Masukkan password Anda"
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
            <a href="#" class="text-xs text-blue-600 hover:text-blue-700 font-medium transition-colors">Lupa Password?</a>
          </div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 rounded-lg transition-colors text-sm shadow-sm">
          Login
        </button>

      </form>

      <p class="text-center text-sm text-gray-500 mt-6">
        Belum punya akun admin?
        <a href="?page=register" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">Daftar Akun Baru</a>
      </p>

    </div>

    <p class="mt-8 text-blue-200 text-xs text-center">
      Dengan login, Anda menyetujui
      <a href="#" class="underline hover:text-white transition-colors">Syarat &amp; Ketentuan</a> dan
      <a href="#" class="underline hover:text-white transition-colors">Kebijakan Privasi</a> EMBIM.
    </p>

    <script>
      function togglePass(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
      }
    </script>
  </body>
  </html>
  