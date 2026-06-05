<?php include 'views/user/header.php'; ?>

<main class="min-h-screen bg-gray-50 pt-24 pb-20">
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Page Header -->
    <div class="pt-6 mb-6">
        <h1 class="text-2xl font-extrabold text-gray-900">Edit Profile</h1>
        <p class="text-sm text-gray-400 mt-1">Update your account information</p>
    </div>

    <?php if ($success): ?>
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
            <?php echo htmlspecialchars($err); ?>
        </p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form action="index.php?page=edit-profile" method="POST" enctype="multipart/form-data" class="space-y-5">

        <!-- ── Foto Profil ── -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-5">Profile Picture</h2>
            <div class="flex items-center gap-5">
                <!-- Preview -->
                <div class="flex-shrink-0">
                    <img
                        id="preview-profile"
                        src="<?php echo !empty($user['photo_profile'])
                            ? 'assets/images/user/' . htmlspecialchars($user['photo_profile'])
                            : 'assets/images/user_default.png'; ?>"
                        alt="Profile Picture"
                        class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 shadow-sm"
                    >
                </div>
                <!-- Upload -->
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

        <!-- ── Info Akun (read-only) ── -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-5">Account Info</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">User ID</label>
                    <div class="w-full px-3 py-2.5 rounded-xl bg-gray-50 border border-gray-100 text-sm text-gray-400 font-mono">
                        #<?php echo str_pad($user['id'], 4, '0', STR_PAD_LEFT); ?>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Joined Since</label>
                    <div class="w-full px-3 py-2.5 rounded-xl bg-gray-50 border border-gray-100 text-sm text-gray-400">
                        <?php echo date('d M Y, H:i', strtotime($user['created_at'])); ?>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Last Updated</label>
                    <div class="w-full px-3 py-2.5 rounded-xl bg-gray-50 border border-gray-100 text-sm text-gray-400">
                        <?php echo date('d M Y, H:i', strtotime($user['updated_at'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Data Pribadi ── -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-5">Personal Data</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- Nama Lengkap -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-400">*</span></label>
                    <input
                        type="text"
                        name="full_name"
                        value="<?php echo htmlspecialchars($user['full_name']); ?>"
                        required
                        minlength="4"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                        placeholder="Enter full name"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email <span class="text-red-400">*</span></label>
                    <input
                        type="email"
                        name="email"
                        value="<?php echo htmlspecialchars($user['email']); ?>"
                        required
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                        placeholder="email@contoh.com"
                    >
                </div>

                <!-- No. HP -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Phone Number</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-4 py-2.5 bg-gray-100 border border-r-0 border-gray-200 rounded-l-xl text-sm font-bold text-gray-600 select-none">
                            +62
                        </span>
                        <input
                            type="tel"
                            name="phone"
                            value="<?php echo htmlspecialchars(preg_replace('/^\+?62/', '', $user['phone'] ?? '')); ?>"
                            class="w-full flex-1 px-3 py-2.5 rounded-r-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                            placeholder="8123456789"
                            maxlength="13"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/^0+/, '');"
                        >
                    </div>
                </div>

                <!-- Alamat -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Address</label>
                    <textarea
                        name="address"
                        rows="3"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition resize-none"
                        placeholder="Ex: 123 Main St, Bandung"
                    ><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>

            </div>
        </div>

        <!-- ── Ganti Password ── -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">Change Password</h2>
            <p class="text-xs text-gray-400 mb-5">Leave blank if you do not want to change the password.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">New Password</label>
                    <input
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                        placeholder="Min. 8 characters"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                    <input
                        type="password"
                        name="password_confirm"
                        autocomplete="new-password"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                        placeholder="Repeat new password"
                    >
                </div>
            </div>
        </div>


        <!-- ── Tombol Simpan ── -->
        <div class="flex items-center justify-end gap-3 pb-4">
            <a href="index.php?page=home"
               class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition duration-200">
                Cancel
            </a>
            <button
                type="submit"
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-200/50 transition duration-200"
            >
                Save Changes
            </button>
        </div>

    </form>

</div>
</main>

<script src="assets/js/user/edit_profile.js"></script>

<?php include 'views/user/footer.php'; ?>