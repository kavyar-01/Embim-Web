<?php include 'views/user/header.php'; ?>

<main class="min-h-screen bg-gray-50 pt-24 pb-20">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Page Header -->
    <div class="pt-6 mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900">Contact Us</h1>
        <p class="text-sm text-gray-400 mt-1">Temukan kami atau hubungi langsung melalui platform favorit Anda</p>
    </div>

    <!-- ── Google Maps ── -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h2 class="text-sm font-bold text-gray-900">Lokasi Kami</h2>
            <span class="text-xs text-gray-400 ml-auto">Aksan Rent Car — Bandung, Jawa Barat</span>
        </div>
        <div class="w-full" style="height: 380px;">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.0!2d107.685563!3d-6.9025784!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68dd76355f8cd7%3A0x9968831e0bf680af!2sAksan+Rent+Car!5e0!3m2!1sid!2sid!4v1717000000000!5m2!1sid!2sid"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </div>
    </div>

    <!-- ── Contact Cards ── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

        <!-- WhatsApp -->
        <a href="https://wa.me/6282144845847" target="_blank"
           class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:border-green-200 hover:shadow-md transition-all duration-200">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center group-hover:bg-green-100 transition-colors duration-200">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="#25D366">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.858L0 24l6.338-1.507A11.95 11.95 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.894a9.886 9.886 0 01-5.031-1.375l-.361-.214-3.762.894.952-3.664-.235-.376A9.855 9.855 0 012.106 12C2.106 6.53 6.53 2.106 12 2.106c5.471 0 9.894 4.424 9.894 9.894 0 5.471-4.423 9.894-9.894 9.894z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">WhatsApp</p>
                <p class="text-sm font-bold text-gray-900 group-hover:text-green-600 transition-colors">+62 821-4484-5847</p>
                <p class="text-xs text-gray-400 mt-0.5">Chat langsung dengan kami</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-green-400 ml-auto flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <!-- Instagram -->
        <a href="https://www.instagram.com/aksanrentcar_bdg?igsh=a2cydmJzYnYybnpx" target="_blank"
           class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:border-pink-200 hover:shadow-md transition-all duration-200">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-pink-50 flex items-center justify-center group-hover:bg-pink-100 transition-colors duration-200">
                <img src="assets/images/instagram_logo.svg" class="w-6 h-6" alt="Instagram">
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Instagram</p>
                <p class="text-sm font-bold text-gray-900 group-hover:text-pink-600 transition-colors">@embim.rentcar</p>
                <p class="text-xs text-gray-400 mt-0.5">Ikuti kami di Instagram</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-pink-400 ml-auto flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <!-- Facebook -->
        <a href="https://facebook.com/embim.rentcar" target="_blank"
           class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:border-blue-200 hover:shadow-md transition-all duration-200">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-200">
                <img src="assets/images/facebook_logo.svg" class="w-6 h-6" alt="Facebook">
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Facebook</p>
                <p class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">EMBIM Rent Car</p>
                <p class="text-xs text-gray-400 mt-0.5">Like & follow halaman kami</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-blue-400 ml-auto flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <!-- Email -->
        <a href="mailto:admin@embim.com"
           class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:border-blue-200 hover:shadow-md transition-all duration-200">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors duration-200">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email</p>
                <p class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">admin@embim.com</p>
                <p class="text-xs text-gray-400 mt-0.5">Kirim pertanyaan ke email kami</p>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-blue-400 ml-auto flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

    </div>

    <!-- ── Info Operasional ── -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Jam Operasional</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <?php
            $hours = [
                ['hari' => 'Senin – Jumat',  'jam' => '08.00 – 20.00', 'status' => 'Buka', 'color' => 'text-emerald-600 bg-emerald-50'],
                ['hari' => 'Sabtu',           'jam' => '08.00 – 18.00', 'status' => 'Buka', 'color' => 'text-emerald-600 bg-emerald-50'],
                ['hari' => 'Minggu',          'jam' => '09.00 – 15.00', 'status' => 'Buka', 'color' => 'text-emerald-600 bg-emerald-50'],
            ];
            foreach ($hours as $h): ?>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                <div>
                    <p class="text-xs font-bold text-gray-700"><?php echo $h['hari']; ?></p>
                    <p class="text-xs text-gray-400 mt-0.5"><?php echo $h['jam']; ?> WIB</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full <?php echo $h['color']; ?>">
                    <?php echo $h['status']; ?>
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>
</main>

<?php include 'views/user/footer.php'; ?>
