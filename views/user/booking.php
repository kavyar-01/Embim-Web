<?php include 'views/user/header.php'; ?>

<?php
$imgSrc = !empty($car['photo'])
    ? 'assets/images/' . htmlspecialchars($car['photo'])
    : 'assets/images/hrv-car.png';

$paymentMethods = [
    'e-wallet' => [
        'label'   => 'E-Wallet',
        'options' => [
            ['value' => 'gopay', 'label' => 'GoPay', 'logo' => 'assets/images/gopay-icon.jpeg'],
            ['value' => 'ovo',   'label' => 'OVO',   'logo' => 'assets/images/ovo-icon.png'],
            ['value' => 'dana',  'label' => 'DANA',  'logo' => 'assets/images/dana-icon.png'],
            ['value' => 'shopeepay',  'label' => 'Shopeepay',  'logo' => 'assets/images/shopeepay-icon.png'],
        ],
    ],
    'bank' => [
        'label'   => 'Transfer Bank',
        'options' => [
            ['value' => 'transfer_bank_bca',     'label' => 'BCA',     'logo' => 'assets/images/bca-icon.png'],
            ['value' => 'transfer_bank_mandiri',  'label' => 'Mandiri', 'logo' => 'assets/images/mandiri-icon.png'],
            ['value' => 'transfer_bank_bni',      'label' => 'BNI',     'logo' => 'assets/images/bni-icon.png'],
            ['value' => 'transfer_bank_bri',      'label' => 'BRI',     'logo' => 'assets/images/bri-icon.png'],
            ['value' => 'transfer_bank_seabank',  'label' => 'Seabank', 'logo' => 'assets/images/seabank-icon.png'],
        ],
    ],
];
?>

<main class="min-h-screen bg-slate-50 pt-24 pb-20">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-400 pt-6 mb-8">
        <a href="index.php?page=car-detail&id=<?php echo $car['id']; ?>" class="hover:text-blue-600 transition">Detail Kendaraan</a>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 font-semibold">Form Booking</span>
    </nav>

    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900">Konfirmasi Booking</h1>
        <p class="text-sm text-gray-400 mt-1">Lengkapi data berikut untuk menyelesaikan pemesanan kendaraan</p>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($errors)): ?>
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
        <p class="text-sm font-bold text-red-700 mb-2 flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Peringatan:
        </p>
        <ul class="space-y-1">
            <?php foreach ($errors as $err): ?>
            <li class="text-sm text-red-600 flex items-start gap-2">
                <svg class="h-4 w-4 flex-shrink-0 mt-0.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <?php echo htmlspecialchars($err); ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form action="index.php?page=booking" method="POST" enctype="multipart/form-data" id="booking-form">
        <input type="hidden" name="car_id"            value="<?php echo $car['id']; ?>">
        <input type="hidden" name="start_date"        value="<?php echo htmlspecialchars($startDate); ?>">
        <input type="hidden" name="end_date"          value="<?php echo htmlspecialchars($endDate); ?>">
        <input type="hidden" name="total_days_hidden" id="total_days_hidden" value="<?php echo $totalDays; ?>">
        <input type="hidden" name="total_price_hidden"id="total_price_hidden"value="<?php echo $totalPrice; ?>">
        <input type="hidden" id="price_per_day"       value="<?php echo $car['price_per_day']; ?>">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- ════ KOLOM KIRI (2/3) ════ -->
            <div class="lg:col-span-2 space-y-5">

                <!-- ── 1. Ringkasan Kendaraan ── -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Kendaraan yang Dipilih</h2>
                    </div>
                    <div class="flex items-center gap-4 p-5">
                        <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($car['brand']); ?>"
                             class="w-28 h-20 object-cover rounded-xl flex-shrink-0 bg-gray-100"
                             onerror="this.src='assets/images/hrv-car.png'">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-extrabold text-gray-900">
                                <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5 mb-2">
                                <?php echo $car['year']; ?> · <?php echo ucfirst($car['transmission']); ?> · <?php echo ucfirst($car['fuel_type']); ?>
                            </p>
                            <p class="text-sm font-black text-blue-600">
                                Rp <?php echo number_format($car['price_per_day'], 0, ',', '.'); ?>
                                <span class="text-xs font-normal text-gray-400">/ hari</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- ── 2. Detail Tanggal ── -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Detail Tanggal Sewa</h2>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Tanggal Pickup</label>
                            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                                <svg class="h-4 w-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">
                                    <?php echo $startDate ? date('d F Y', strtotime($startDate)) : '-'; ?>
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Tanggal Kembali</label>
                            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                                <svg class="h-4 w-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">
                                    <?php echo $endDate ? date('d F Y', strtotime($endDate)) : '-'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="sm:col-span-2 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-600">Durasi Sewa</span>
                            <span class="text-sm font-extrabold text-blue-600" id="summary-duration">
                                <?php echo $totalDays; ?> hari
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ── 3. Catatan ── -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Catatan Tambahan</h2>
                    </div>
                    <div class="p-5">
                        <textarea
                            name="notes"
                            rows="3"
                            placeholder="Contoh: Butuh infant seat, antar ke bandara, dll. (opsional)"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400 transition resize-none"
                        ><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                    </div>
                </div>

                <!-- ── 4. Foto Selfie KTP ── -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Verifikasi Identitas</h2>
                    </div>
                    <div class="p-5">
                        <p class="text-sm text-gray-500 mb-4 leading-relaxed">
                            Upload foto <strong class="text-gray-700">selfie sambil memegang KTP / SIM</strong> Anda.
                            Pastikan wajah dan teks pada dokumen terlihat jelas.
                        </p>

                        <label
                            id="selfie-box"
                            for="selfie_ktp"
                            class="relative flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer bg-slate-50 hover:bg-blue-50/40 hover:border-blue-300 transition-all duration-200 overflow-hidden"
                        >
                            <!-- Placeholder -->
                            <div id="selfie-placeholder" class="flex flex-col items-center gap-2 pointer-events-none">
                                <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm font-semibold text-gray-400">Klik untuk upload foto</p>
                                <p class="text-xs text-gray-300">JPG, PNG, WebP · Maks 5 MB</p>
                            </div>
                            <!-- Preview gambar -->
                            <img
                                id="selfie-preview"
                                src=""
                                alt="Preview"
                                class="hidden absolute inset-0 w-full h-full object-cover rounded-2xl"
                            >
                            <input
                                type="file"
                                id="selfie_ktp"
                                name="selfie_ktp"
                                accept="image/jpeg,image/png,image/webp"
                                class="hidden"
                            >
                        </label>

                        <p class="text-xs text-gray-400 mt-2 flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Data identitas Anda hanya digunakan untuk keperluan verifikasi.
                        </p>
                        <p id="selfie-error" class="hidden text-xs text-red-500 mt-2 font-semibold">
                            ⚠ Foto selfie memegang KTP wajib diunggah.
                        </p>
                    </div>
                </div>

                <!-- ── 5. Metode Pembayaran ── -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Metode Pembayaran</h2>
                    </div>
                    <div class="p-5 space-y-5">
                        <?php
                        $firstRadio = true;
                        foreach ($paymentMethods as $groupKey => $group): ?>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3"><?php echo $group['label']; ?></p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <?php foreach ($group['options'] as $opt):
                                    $isSelected = isset($_POST['payment_method']) && $_POST['payment_method'] === $opt['value'];
                                ?>
                                <label
                                    class="payment-card flex flex-col items-center justify-center gap-2 py-3 px-2 border-2 rounded-xl cursor-pointer transition-all duration-200
                                           <?php echo $isSelected ? 'border-blue-500 bg-blue-50 shadow-md' : 'border-gray-200 hover:border-blue-300'; ?>"
                                >
                                    <img src="<?php echo $opt['logo']; ?>" alt="<?php echo $opt['label']; ?>" class="h-8 object-contain">
                                    <span class="text-xs font-bold text-gray-700"><?php echo $opt['label']; ?></span>
                                    <input
                                        type="radio"
                                        name="payment_method"
                                        value="<?php echo $opt['value']; ?>"
                                        class="sr-only"
                                        <?php echo $isSelected ? 'checked' : ''; ?>
                                        <?php if ($firstRadio): ?>required<?php $firstRadio = false; endif; ?>
                                    >
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <p id="payment-error" class="hidden text-xs text-red-500 mt-3 px-5 pb-4 font-semibold">
                        ⚠ Metode pembayaran wajib dipilih.
                    </p>
                </div>

            </div><!-- end KOLOM KIRI -->

            <!-- ════ KOLOM KANAN — Order Summary ════ -->
            <div class="lg:col-span-1">
                <div class="sticky top-28 space-y-4">

                    <!-- Summary Card -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Ringkasan Pesanan</h2>

                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Harga per hari</span>
                                <span class="font-semibold text-gray-800">
                                    Rp <?php echo number_format($car['price_per_day'], 0, ',', '.'); ?>
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Durasi</span>
                                <span class="font-semibold text-gray-800" id="summary-duration-side">
                                    <?php echo $totalDays; ?> hari
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Biaya layanan</span>
                                <span class="font-semibold text-emerald-600">Gratis</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-3 mb-5">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-900">Total Pembayaran</span>
                                <span class="text-xl font-black text-blue-600" id="summary-total">
                                    Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3.5 rounded-xl shadow-md shadow-blue-200/50 transition-all duration-200 mb-3"
                        >
                            Konfirmasi Booking
                        </button>

                        <!-- Tombol Batal -->
                        <button
                            type="button"
                            onclick="confirmCancelBooking()"
                            class="w-full border-2 border-gray-200 hover:border-red-300 text-gray-500 hover:text-red-500 font-semibold text-sm py-3 rounded-xl transition-all duration-200"
                        >
                            Batalkan
                        </button>
                    </div>

                    <!-- Info Kebijakan -->
                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
                        <p class="text-xs font-bold text-amber-700 mb-2 flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Info Pembayaran
                        </p>
                        <ul class="space-y-1 text-xs text-amber-600">
                            <li>• Booking berstatus <strong>Confirmed</strong> setelah bukti pembayaran diunggah</li>
                            <li>• Instruksi pembayaran dikirim ke email Anda</li>
                            <li>• Pembayaran maks. <strong>1x24 jam</strong> setelah booking</li>
                        </ul>
                    </div>

                </div>
            </div><!-- end KOLOM KANAN -->

        </div><!-- end grid -->
    </form>

</div>
</main>

<!-- Modal Konfirmasi Batalkan -->
<div id="cancel-overlay" class="hidden fixed inset-0 z-[999] flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" id="cancel-backdrop"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-7 text-center">
        <div class="mx-auto mb-4 h-14 w-14 rounded-full bg-red-50 flex items-center justify-center">
            <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">Batalkan Booking?</h3>
        <p class="text-sm text-gray-500 mb-7">Data yang sudah diisi akan hilang. Yakin ingin kembali?</p>
        <div class="flex gap-3">
            <button id="cancel-no" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                Tidak
            </button>
            <a href="index.php?page=car-detail&id=<?php echo $car['id']; ?>"
               class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition text-center">
                Ya, Batalkan
            </a>
        </div>
    </div>
</div>

<script src="assets/js/user/booking.js"></script>
<?php include 'views/user/footer.php'; ?>