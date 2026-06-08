<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — EMBIM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user-style/style.css">
    <link rel="stylesheet" href="assets/css/user-style/register.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4">

    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-8 fade-up">
            <a href="index.php" class="text-3xl font-extrabold tracking-tight text-blue-600">EMBIM</a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-10">

            <!-- Heading -->
            <h1 class="text-2xl font-bold text-gray-900 text-center mb-7 fade-up delay-1">
                Create New Account!
            </h1>

            <!-- Error / Success Messages -->
            <?php if (!empty($error)): ?>
            <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                <svg class="h-5 w-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
            <div class="mb-5 flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3">
                <svg class="h-5 w-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
            <?php endif; ?>

            <!-- Register Form -->
            <form action="index.php?page=register" method="POST" id="register-form" novalidate>

                <!-- Full Name -->
                <div class="mb-4 fade-up delay-2">
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Full Name
                    </label>
                    <input
                        type="text"
                        id="full_name"
                        name="full_name"
                        placeholder="Enter your full name"
                        value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                        class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-gray-50 transition duration-200"
                        required
                        minlength="4"
                        autocomplete="name"
                    >
                </div>

                <!-- Email -->
                <div class="mb-4 fade-up delay-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="example@email.com"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-gray-50 transition duration-200"
                        required
                        autocomplete="email"
                    >
                </div>

                <!-- Phone -->
                <div class="mb-4 fade-up delay-3">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Phone Number
                    </label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 py-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold text-gray-600 select-none">
                            +62
                        </span>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            placeholder="8123456789"
                            value="<?php echo htmlspecialchars(preg_replace('/^\+?62/', '', $_POST['phone'] ?? '')); ?>"
                            class="form-input flex-1 px-4 py-3 border border-gray-200 rounded-r-xl text-sm text-gray-900 placeholder-gray-400 bg-gray-50 transition duration-200 focus:outline-none"
                            autocomplete="tel"
                            maxlength="13"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');"
                        >
                    </div>
                    <p id="phone-error" class="hidden mt-1.5 text-xs text-red-500 font-semibold">⚠ Phone number must contain only numbers.</p>
                </div>

                <!-- Password -->
                <div class="mb-2 fade-up delay-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Create a strong password"
                            class="form-input w-full px-4 py-3 pr-12 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-gray-50 transition duration-200"
                            required
                            autocomplete="new-password"
                        >
                        <!-- Toggle visibility -->
                        <button
                            type="button"
                            id="toggle-password"
                            tabindex="-1"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition"
                        >
                            <!-- Eye icon -->
                            <svg id="icon-eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <!-- Eye-off icon (hidden default) -->
                            <svg id="icon-eye-off" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Rules -->
                <div class="mb-5 bg-slate-50 border border-slate-100 rounded-xl px-4 py-3.5 fade-up delay-4">
                    <p class="text-xs font-semibold text-gray-600 mb-2.5">Password must contain:</p>
                    <ul class="space-y-1.5">
                        <li class="rule-item flex items-center gap-2.5 text-xs text-gray-400" id="rule-length">
                            <span class="rule-dot"></span> 8–100 characters
                        </li>
                        <li class="rule-item flex items-center gap-2.5 text-xs text-gray-400" id="rule-lower">
                            <span class="rule-dot"></span> At least one lowercase letter
                        </li>
                        <li class="rule-item flex items-center gap-2.5 text-xs text-gray-400" id="rule-upper">
                            <span class="rule-dot"></span> At least one uppercase letter
                        </li>
                        <li class="rule-item flex items-center gap-2.5 text-xs text-gray-400" id="rule-digit">
                            <span class="rule-dot"></span> At least one number
                        </li>
                    </ul>
                </div>

                <!-- Terms checkbox -->
                <div class="mb-6 fade-up delay-5">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="agree_terms" id="agree_terms" class="mt-0.5" required>
                        <span class="text-xs text-gray-500 leading-relaxed">
                            By registering, I agree to EMBIM's
                            <a href="#" class="text-blue-600 hover:underline font-medium">Terms & Conditions</a>,
                            <a href="#" class="text-blue-600 hover:underline font-medium">Privacy Policy</a>,
                            and
                            <a href="#" class="text-blue-600 hover:underline font-medium">Acceptable Use Policy</a>.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="fade-up delay-6">
                    <button
                        type="submit"
                        id="submit-btn"
                        class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm py-3.5 rounded-xl transition duration-200 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Create Account
                    </button>
                </div>

            </form>

            <!-- Divider -->
            <div class="relative my-6 fade-up delay-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-100"></div>
                </div>
                <div class="relative flex justify-center text-xs text-gray-400 uppercase tracking-wider">
                    <span class="bg-white px-3">Already have an account?</span>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center fade-up delay-6">
                <a
                    href="index.php?page=login"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-700 underline underline-offset-2 transition duration-200"
                >
                    Login here
                </a>
            </div>

        </div>

        <!-- Bottom note -->
        <p class="text-center text-xs text-gray-400 mt-6 fade-up delay-6">
            &copy; 2026 EMBIM. All rights reserved.
        </p>
    </div>

    <script src="assets/js/user/register.js"></script>

</body>
</html>