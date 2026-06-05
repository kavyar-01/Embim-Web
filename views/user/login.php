<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — EMBIM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user-style/login.css">
</head>
<body class="min-h-screen flex flex-col items-center justify-center py-12 px-4">

    <!-- Logo -->
    <div class="text-center mb-8 fade-up">
        <a href="index.php" class="text-3xl font-extrabold tracking-tight text-blue-600">EMBIM</a>
    </div>

    <!-- Card -->
    <div class="login-card w-full max-w-md px-8 py-10 fade-up delay-1">

        <!-- Judul -->
        <h1 class="text-2xl font-bold text-gray-900 text-center mb-7">
            Login
        </h1>

        <!-- Alert Error -->
        <?php if (!empty($error)): ?>
        <div class="alert alert-error fade-up">
            <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
        <?php endif; ?>

        <!-- Alert Success (setelah register berhasil) -->
        <?php if (!empty($success)): ?>
        <div class="alert alert-success fade-up">
            <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><?php echo htmlspecialchars($success); ?></span>
        </div>
        <?php endif; ?>

        <!-- Tombol Login WhatsApp -->
        <div class="fade-up delay-2">
            <button
                type="button"
                onclick="openWhatsappModal()"
                class="btn-whatsapp w-full"
            >
                <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" style="color:#25D366">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                <span>Login with Phone Number</span>
            </button>
        </div>

        <!-- Divider OR -->
        <div class="divider fade-up delay-2">
            <span>OR</span>
        </div>

        <!-- Form Login -->
        <form action="index.php?page=login" method="POST" id="login-form" novalidate>

            <!-- Email -->
            <div class="mb-4 fade-up delay-3">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email address"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    class="form-input"
                    required
                    autocomplete="email"
                >
            </div>

            <!-- Password -->
            <div class="mb-2 fade-up delay-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Password
                </label>
                <div class="pw-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        class="form-input"
                        style="padding-right: 3rem;"
                        required
                        autocomplete="current-password"
                    >
                    <!-- Toggle show/hide password -->
                    <button type="button" id="toggle-password" class="pw-toggle" tabindex="-1" aria-label="Toggle password visibility">
                        <!-- Eye open -->
                        <svg id="icon-eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <!-- Eye closed (default hidden) -->
                        <svg id="icon-eye-off" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Forgot Password -->
            <div class="text-right mb-6 fade-up delay-4">
                <button
                    type="button"
                    onclick="openForgotModal()"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition duration-200"
                >
                    Forgot Password?
                </button>
            </div>

            <!-- Submit Button -->
            <div class="fade-up delay-5">
                <button
                    type="submit"
                    id="submit-btn"
                    class="btn-submit"
                >
                    Login
                </button>
            </div>

        </form>

        <!-- Daftar Akun Baru -->
        <div class="text-center mt-6 fade-up delay-5">
            <a
                href="index.php?page=register"
                class="text-sm font-semibold text-blue-600 hover:text-blue-700 underline underline-offset-2 transition duration-200"
            >
                Create New Account ↗
            </a>
        </div>

    </div>

    <!-- Catatan Bawah -->
    <div class="text-center mt-6 fade-up delay-5">
        <p class="text-xs text-gray-400 leading-relaxed max-w-sm mx-auto">
            By logging in, you agree to EMBIM's
            <a href="#" class="underline hover:text-gray-600 transition">Terms & Conditions</a>,
            <a href="#" class="underline hover:text-gray-600 transition">Privacy Policy</a>, and
            <a href="#" class="underline hover:text-gray-600 transition">Acceptable Use Policy</a>.
        </p>
    </div>

<!-- ══════════════════════════════════════════
     POPUP LOGIN WHATSAPP
══════════════════════════════════════════ -->
<div id="wa-overlay" class="hidden fixed inset-0 z-[999] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300" id="wa-backdrop"></div>

    <div id="wa-modal"
         class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden
                translate-y-6 opacity-0 transition-all duration-300 ease-out">

        <!-- Top accent hijau WhatsApp -->
        <div class="h-1.5 bg-gradient-to-r from-green-400 via-green-500 to-emerald-500"></div>

        <!-- Close -->
        <button onclick="closeWhatsappModal()"
                class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition text-gray-500">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="px-7 pt-6 pb-7">

            <!-- Header -->
            <div class="flex items-center gap-3 mb-5">
                <div class="w-11 h-11 rounded-2xl bg-green-50 flex items-center justify-center flex-shrink-0">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" style="color:#25D366">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900">Login via WhatsApp</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Enter registered phone number</p>
                </div>
            </div>

            <!-- Alert -->
            <div id="wa-alert" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-semibold border"></div>

            <!-- Input Nomor -->
            <div class="mb-5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                <div class="flex">
                    <span class="inline-flex items-center px-4 py-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold text-gray-600 select-none">
                        +62
                    </span>
                    <input
                        type="tel"
                        id="wa-phone"
                        placeholder="8123456789"
                        inputmode="numeric"
                        maxlength="13"
                        class="flex-1 px-4 py-3 border border-gray-200 rounded-r-xl text-sm text-gray-800 bg-gray-50 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-400/30 focus:border-green-400 transition"
                    >
                </div>
                <p class="text-xs text-gray-400 mt-1.5">Use the number registered to your EMBIM account.</p>
            </div>

            <!-- Submit -->
            <button
                type="button"
                onclick="submitWhatsappLogin()"
                id="wa-btn"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold text-sm py-3.5 rounded-xl
                       shadow-md shadow-green-200/50 transition duration-200 flex items-center justify-center gap-2"
            >
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Login Now
            </button>

        </div>
    </div>
</div>

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
                <h3 class="text-lg font-extrabold text-gray-900">Lupa Password?</h3>
                <p class="text-sm text-gray-400 mt-1">Enter your full name and registered phone number to verify your account.</p>
            </div>

            <div id="verify-alert" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-semibold border"></div>

            <div class="space-y-4">
                <!-- Nama Lengkap -->
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

                <!-- Nomor Telepon -->
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
                <h3 class="text-lg font-extrabold text-gray-900">Reset Password</h3>
                <p class="text-sm text-gray-400 mt-1">Identity verified. Create a new password for your account.</p>
            </div>

            <input type="hidden" id="reset-user-id">

            <div id="reset-alert" class="hidden mb-4 px-4 py-3 rounded-xl text-sm font-semibold border"></div>

            <div class="space-y-4">
                <!-- Password Baru -->
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

                <!-- Konfirmasi Password -->
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

<script src="assets/js/user/login.js"></script>
</body>
</html>