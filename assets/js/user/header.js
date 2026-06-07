

(function () {

    const btn = document.getElementById('profile-menu-btn');
    const dropdown = document.getElementById('profile-dropdown');
    const chevron = document.getElementById('profile-chevron');

    if (btn && dropdown) {
        let isOpen = false;

        function openMenu() {
            dropdown.classList.remove('hidden');
            requestAnimationFrame(() => {
                dropdown.classList.remove('opacity-0', 'scale-95');
                dropdown.classList.add('opacity-100', 'scale-100');
            });
            chevron.classList.add('rotate-180');
            btn.setAttribute('aria-expanded', 'true');
            isOpen = true;
        }

        function closeMenu() {
            dropdown.classList.remove('opacity-100', 'scale-100');
            dropdown.classList.add('opacity-0', 'scale-95');
            setTimeout(() => dropdown.classList.add('hidden'), 180);
            chevron.classList.remove('rotate-180');
            btn.setAttribute('aria-expanded', 'false');
            isOpen = false;
        }

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            isOpen ? closeMenu() : openMenu();
        });

        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('profile-menu-wrapper');
            if (isOpen && wrapper && !wrapper.contains(e.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && isOpen) closeMenu();
        });
    }

    const mobileBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });
    }

})();

function confirmLogout(e) {
    e.preventDefault();

    const overlay = document.createElement('div');
    overlay.id = 'logout-overlay';
    overlay.className = 'fixed inset-0 z-[999] flex items-center justify-center';
    overlay.innerHTML = `
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" id="logout-backdrop"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-7 text-center"
             style="animation: fadeUp .25s ease both">
            <!-- Ikon -->
            <div class="mx-auto mb-4 h-14 w-14 rounded-full bg-red-50 flex items-center justify-center">
                <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <!-- Teks -->
<<<<<<< HEAD
            <h3 class="text-lg font-bold text-gray-900 mb-1">Confirm Logout</h3>
            <p class="text-sm text-gray-500 mb-7">Are you sure you want to logout of your EMBIM account?</p>
=======
            <h3 class="text-lg font-bold text-gray-900 mb-1">Logout Confirmation</h3>
            <p class="text-sm text-gray-500 mb-7">Are you sure you want to log out of your EMBIM account?</p>
>>>>>>> e80092552572cabebe2d5558bf07313d9e270e8a
            <!-- Tombol -->
            <div class="flex gap-3">
                <button id="logout-cancel"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition duration-200">
                    Cancel
                </button>
                <a href="index.php?page=logout"
                    class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition duration-200">
                    Yes, Logout
                </a>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);

    document.getElementById('logout-backdrop').addEventListener('click', removeModal);
    document.getElementById('logout-cancel').addEventListener('click', removeModal);

    document.addEventListener('keydown', function escHandler(e) {
        if (e.key === 'Escape') {
            removeModal();
            document.removeEventListener('keydown', escHandler);
        }
    });

    function removeModal() {
        const el = document.getElementById('logout-overlay');
        if (el) el.remove();
    }
}