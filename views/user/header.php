<!DOCTYPE html>
<?php
$_photoProfile = (!empty($_SESSION['user_photo']))
    ? 'assets/images/user/' . htmlspecialchars($_SESSION['user_photo'])
    : 'assets/images/user_default.png';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMBIM - Easy Mobility Booking In Minutes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/jpeg" href="/assets/images/web-icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/user-style/style.css">
</head>
<body class="bg-white text-gray-900">

    <!-- Navbar -->
    <nav id="navbar" class="fixed w-full z-50 bg-white border-b border-gray-100 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative flex justify-between h-16 items-center">

                <!-- Logo -->
                <div class="flex items-center">
                    <a href="index.php" class="text-xl font-extrabold tracking-tight text-gray-900 hover:text-blue-600 transition duration-200">EMBIM</a>

                    <!-- Desktop Nav Links (centered) -->
                    <div class="hidden md:flex md:items-center md:absolute md:left-1/2 md:-translate-x-1/2 md:space-x-8">
                        <a href="index.php" class="text-base font-semibold text-gray-700 hover:text-blue-600 transition duration-200">Home</a>
                        <a href="index.php?page=cars" class="text-base font-semibold text-gray-600 hover:text-blue-600 transition duration-200">Browse Cars</a>
                        <a href="index.php?page=bookings" class="text-base font-semibold text-gray-600 hover:text-blue-600 transition duration-200">My Bookings</a>
                        <a href="index.php?page=contact" class="text-base font-semibold text-gray-600 hover:text-blue-600 transition duration-200">Contact Us</a>
                    </div>
                </div>

                <!-- Desktop Right Actions -->
                <div class="hidden md:flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>

                        <!-- STATE: USER SUDAH LOGIN — tampilkan avatar + nama + dropdown -->
                        <div class="relative" id="profile-menu-wrapper">
                            <button
                                id="profile-menu-btn"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl hover:bg-gray-50 transition duration-200 focus:outline-none"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <!-- Foto Profil -->
                                <img
                                    src="<?php echo $_photoProfile; ?>"
                                    alt="Profile"
                                    class="h-8 w-8 rounded-full object-cover"
                                >
                                <!-- Username -->
                                <span class="text-sm font-semibold text-gray-800 max-w-[120px] truncate">
                                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                                </span>
                                <!-- Chevron -->
                                <svg id="profile-chevron" class="h-4 w-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                id="profile-dropdown"
                                class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50
                                       transition-all duration-200 origin-top-right opacity-0 scale-95"
                            >
                                <!-- Info User -->
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                    <p class="text-xs text-gray-400 font-medium">Login sebagai</p>
                                    <p class="text-sm font-bold text-gray-800 truncate"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                                </div>

                                <!-- Edit Profile -->
                                <a
                                    href="index.php?page=edit-profile"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition duration-150"
                                >
                                    <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit Profile
                                </a>

                                <!-- Logout -->
                                <a
                                    href="#" onclick="confirmLogout(event)"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 hover:text-red-600 transition duration-150 border-t border-gray-100"
                                >
                                    <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Logout
                                </a>
                            </div>
                        </div>

                    <?php else: ?>

                        <!-- STATE: BELUM LOGIN — tampilkan Login & Sign Up -->
                        <a href="index.php?page=login" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition duration-200">Login</a>
                        <a href="index.php?page=register" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition duration-200 shadow-sm">Sign Up</a>

                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center gap-2">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Avatar kecil di mobile -->
                        <img
                            src="<?php echo $_photoProfile; ?>"
                            alt="Profile"
                            class="h-8 w-8 rounded-full object-cover"
                        >
                    <?php endif; ?>
                    <button id="mobile-menu-button" class="text-gray-600 hover:text-blue-600 focus:outline-none p-2 transition duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-100 px-4 py-4 space-y-1 shadow-md">
            <a href="index.php" class="block text-sm font-semibold text-blue-600 py-2.5 px-3 rounded-lg hover:bg-gray-50 transition duration-200">Home</a>
            <a href="index.php?page=cars" class="block text-sm font-medium text-gray-600 py-2.5 px-3 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition duration-200">Browse Cars</a>
            <a href="index.php?page=bookings" class="block text-sm font-medium text-gray-600 py-2.5 px-3 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition duration-200">My Bookings</a>
            <a href="index.php?page=contact" class="block text-sm font-medium text-gray-600 py-2.5 px-3 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition duration-200">Contact Us</a>
            <hr class="my-2 border-gray-100">

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Mobile: Info user + aksi -->
                <div class="flex items-center gap-3 px-3 py-2.5 bg-gray-50 rounded-lg mb-1">
                    <img src="<?php echo $_photoProfile; ?>" alt="Profile" class="h-9 w-9 rounded-full object-cover">
                    <div>
                        <p class="text-xs text-gray-400">Login sebagai</p>
                        <p class="text-sm font-bold text-gray-800 truncate max-w-[160px]"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                    </div>
                </div>
                <a href="index.php?page=edit-profile" class="flex items-center gap-2 text-sm font-medium text-gray-700 py-2.5 px-3 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition duration-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profile
                </a>
                <a href="#" onclick="confirmLogout(event)" class="flex items-center gap-2 text-sm font-medium text-red-500 py-2.5 px-3 rounded-lg hover:bg-red-50 transition duration-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </a>

            <?php else: ?>
                <!-- Mobile: Login & Sign Up -->
                <a href="index.php?page=login" class="block text-sm font-medium text-gray-700 py-2.5 px-3 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition duration-200">Login</a>
                <a href="index.php?page=register" class="block text-sm font-medium text-center bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-3 rounded-xl transition duration-200 mx-1">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>

    <script src="assets/js/user/header.js"></script>