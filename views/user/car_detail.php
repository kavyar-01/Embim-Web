<?php include 'views/user/header.php'; ?>

<main class="min-h-screen bg-gray-50 pt-24 pb-20">
<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-10">

    <?php
    $imgSrc = !empty($car['photo'])
        ? 'assets/images/' . htmlspecialchars($car['photo'])
        : 'assets/images/hrv-car.png';

    $statusConfig = [
        'available'   => ['label' => 'Tersedia',   'color' => 'text-emerald-600 bg-emerald-50 border-emerald-200'],
        'booked'      => ['label' => 'Disewa',      'color' => 'text-amber-600 bg-amber-50 border-amber-200'],
        'maintenance' => ['label' => 'Maintenance', 'color' => 'text-red-600 bg-red-50 border-red-200'],
    ];
    $s = $statusConfig[$car['status']] ?? ['label' => ucfirst($car['status']), 'color' => 'text-gray-600 bg-gray-50 border-gray-200'];
    ?>

    <!-- Back Link -->
    <div class="pt-6 mb-5">
        <a href="index.php?page=cars" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 transition-colors duration-200 font-medium">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </a>
    </div>

    <!-- ── Two-Column Layout ── -->
    <div class="flex flex-col lg:flex-row gap-5 items-start">

        <!-- ════ LEFT COLUMN ════ -->
        <div class="flex-1 min-w-0 space-y-5" style="min-width: 0; flex: 1 1 0%">

            <!-- Hero Image -->
            <div class="rounded-2xl overflow-hidden bg-gray-100 shadow-sm">
                <img
                    src="<?php echo $imgSrc; ?>"
                    alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>"
                    class="w-full object-cover"
                    style="height: 400px;"
                >
            </div>

            <!-- Title Block -->
            <div class="bg-white rounded-2xl px-6 py-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 uppercase tracking-wide leading-tight">
                            <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>
                        </h1>
                        <p class="text-sm text-gray-400 font-medium mt-1">
                            <?php echo $car['year']; ?> &nbsp;·&nbsp; <?php echo ucfirst($car['transmission']); ?>
                        </p>
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center text-xs font-semibold px-3 py-1 rounded-full border <?php echo $s['color']; ?>">
                        <?php echo $s['label']; ?>
                    </span>
                </div>

                <!-- 4 Spec Pills -->
                <div class="grid grid-cols-2 gap-5 mt-5 pt-5 border-t border-gray-100">
                    <?php
                    $specs = [
                        [
                            'icon'  => 'assets/images/seats-icon.png',
                            'label' => $car['seats'] . ' Seats',
                        ],
                        [
                            'icon'  => 'assets/images/gasoline-icon.png',
                            'label' => ucfirst($car['fuel_type']),
                        ],
                    ];
                    foreach ($specs as $spec): ?>
                    <div class="flex flex-col items-center justify-center gap-1.5 bg-gray-50 rounded-xl py-3 px-2">
                        <img src="<?php echo $spec['icon']; ?>" class="w-5 h-5 object-contain" alt="">
                        <span class="text-xs font-semibold text-gray-600 text-center leading-tight"><?php echo $spec['label']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Description -->
            <?php if (!empty($car['description'])): ?>
            <div class="bg-white rounded-2xl px-6 py-5 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Description</h2>
                <p class="text-sm text-gray-500 leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($car['description'])); ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Vehicle Highlights -->
            <div class="bg-white rounded-2xl px-6 py-5 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Vehicle Highlights</h2>
                <?php
                $highlights = [
                    [
                        'icon_type' => 'img',
                        'icon_src'  => 'assets/images/drivetrain-icon.png',
                        'label' => 'DRIVETRAIN',
                        'value' => 'AWD',
                    ],
                    [
                        'icon_type' => 'img',
                        'icon_src'  => 'assets/images/car-style-icon.png',
                        'label' => 'BODY STYLE',
                        'value' => ucfirst($car['category'] ?? 'Sedan'),
                    ],
                    [
                        'icon_type' => 'img',
                        'icon_src'  => 'assets/images/engine-icon.png',
                        'label' => 'ENGINE',
                        'value' => '3.0L Twin Turbo I6 503hp',
                    ],
                    [
                        'icon_type' => 'img',
                        'icon_src'  => 'assets/images/transmission-icon.png',
                        'label' => 'TRANSMISSION',
                        'value' => ucfirst($car['transmission']),
                    ],
                ];
                ?>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <?php foreach ($highlights as $h): ?>
                    <div class="flex items-start gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50 hover:border-blue-100 hover:bg-blue-50/40 transition-colors duration-200">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                            <?php if (($h['icon_type'] ?? 'svg') === 'img'): ?>
                                <img src="<?php echo $h['icon_src']; ?>" class="w-5 h-5 object-contain" alt="">
                            <?php else: ?>
                                <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php echo $h['svg']; ?>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-gray-800 tracking-wide leading-tight"><?php echo $h['label']; ?></p>
                            <p class="text-xs text-gray-500 mt-0.5 leading-snug "><?php echo $h['value']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Ketentuan Denda & Refund -->
            <div class="bg-white rounded-2xl px-6 py-5 shadow-sm">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Ketentuan Denda & Refund</h2>

                <!-- Denda Keterlambatan -->
                <div class="flex items-start gap-3 bg-red-45 border border-red-100 rounded-xl p-4 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-red-45 flex items-center justify-center flex-shrink-0">
                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-red-700 mb-1">Denda Keterlambatan</p>
                        <p class="text-xs text-red-600 leading-relaxed">
                            Pengembalian kendaraan melewati tanggal yang ditentukan akan dikenakan denda sebesar
                            <strong class="text-red-700">Rp 700.000 / hari</strong> keterlambatan.
                        </p>
                    </div>
                </div>

                <!-- Kebijakan Refund -->
                <div class="flex items-start gap-3 bg-amber-45 border border-amber-100 rounded-xl p-4">
                    <div class="w-8 h-8 rounded-lg bg-amber-45 flex items-center justify-center flex-shrink-0">
                        <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-amber-700 mb-1">Kebijakan Pembatalan & Refund</p>
                        <p class="text-xs text-amber-600 leading-relaxed">
                            Proses pembatalan booking hanya mengembalikan
                            <strong class="text-amber-700">80%</strong> dari total harga sewa.
                            Sisa 20% akan dipotong sebagai biaya administrasi.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kendaraan Lainnya -->
            <?php if (!empty($relatedCars)): ?>
            <div class="bg-white rounded-2xl px-6 py-5 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Kendaraan Lainnya</h2>
                    <a href="index.php?page=cars" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">Lihat Semua →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <?php foreach ($relatedCars as $rel):
                        $relImg = !empty($rel['photo'])
                            ? 'assets/images/' . htmlspecialchars($rel['photo'])
                            : 'assets/images/hrv-car.png';
                    ?>
                    <a href="index.php?page=car-detail&id=<?php echo $rel['id']; ?>" class="group block rounded-xl overflow-hidden border border-gray-100 hover:border-blue-200 hover:shadow-md transition-all duration-300">
                        <div class="h-32 bg-gray-50 overflow-hidden">
                            <img
                                src="<?php echo $relImg; ?>"
                                alt="<?php echo htmlspecialchars($rel['brand'] . ' ' . $rel['model']); ?>"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition truncate">
                                <?php echo htmlspecialchars($rel['brand'] . ' ' . $rel['model']); ?>
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5"><?php echo $rel['year']; ?> · <?php echo ucfirst($rel['transmission']); ?></p>
                            <p class="text-sm font-black text-blue-600 mt-1.5">
                                Rp <?php echo number_format($rel['price_per_day'], 0, ',', '.'); ?>
                                <span class="text-xs font-normal text-gray-400">/ hari</span>
                            </p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- end LEFT COLUMN -->

        <!-- ════ RIGHT COLUMN — Sticky Booking Card ════ -->
        <div class="w-full lg:w-72 flex-shrink-0">
            <div class="sticky top-28 space-y-4">

                <!-- Pricing & Booking -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Harga Sewa</p>
                    <div class="flex items-end gap-1 mb-5">
                        <span class="text-2xl font-black text-blue-600">Rp <?php echo number_format($car['price_per_day'], 0, ',', '.'); ?></span>
                        <span class="text-sm text-gray-400 font-medium pb-0.5">/ hari</span>
                    </div>

                    <?php if ($car['status'] === 'available'): ?>
                    <form action="index.php?page=booking" method="GET" id="booking-form">
                        <input type="hidden" name="page"   value="booking">
                        <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">

                        <!-- Date Inputs -->
                        <div class="space-y-3 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Tanggal Pickup</label>
                                <input
                                    type="date"
                                    id="pickup_date"
                                    name="start_date"
                                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                                    required
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1.5">Tanggal Kembali</label>
                                <input
                                    type="date"
                                    id="return_date"
                                    name="end_date"
                                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Preview Durasi & Total -->
                        <div id="price-preview" class="hidden mb-4 bg-blue-50 rounded-xl p-3 border border-blue-100">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Durasi</span>
                                <span id="duration-text" class="font-semibold text-gray-700">-</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold">
                                <span class="text-gray-700">Total Estimasi</span>
                                <span id="total-price" class="text-blue-600">-</span>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3 rounded-xl shadow-md shadow-blue-200/50 transition-all duration-200 mb-2"
                        >
                            Booking Sekarang
                        </button>
                    </form>
                    <?php else: ?>
                    <button disabled class="block w-full bg-gray-100 text-gray-400 font-bold text-sm py-3 rounded-xl cursor-not-allowed mb-2">
                        Tidak Tersedia
                    </button>
                    <p class="text-xs text-center text-gray-400">Kendaraan sedang tidak tersedia</p>
                    <?php endif; ?>
                </div>

                <script>
                (function(){
                    const pricePerDay = <?php echo (int)$car['price_per_day']; ?>;
                    const pickupInput = document.getElementById('pickup_date');
                    const returnInput = document.getElementById('return_date');
                    const preview     = document.getElementById('price-preview');
                    const durationEl  = document.getElementById('duration-text');
                    const totalEl     = document.getElementById('total-price');

                    function updatePreview() {
                        const start = new Date(pickupInput.value);
                        const end   = new Date(returnInput.value);
                        if (!pickupInput.value || !returnInput.value || end <= start) {
                            preview.classList.add('hidden'); return;
                        }
                        const days = Math.ceil((end - start) / (1000*60*60*24));
                        const total = days * pricePerDay;
                        durationEl.textContent = days + ' hari';
                        totalEl.textContent    = 'Rp ' + total.toLocaleString('id-ID');
                        preview.classList.remove('hidden');
                        // set min return = pickup + 1 day
                        const minReturn = new Date(start);
                        minReturn.setDate(minReturn.getDate() + 1);
                        returnInput.min = minReturn.toISOString().split('T')[0];
                    }
                    pickupInput.addEventListener('change', function(){
                        returnInput.value = '';
                        preview.classList.add('hidden');
                        const minReturn = new Date(this.value);
                        minReturn.setDate(minReturn.getDate() + 1);
                        returnInput.min = minReturn.toISOString().split('T')[0];
                    });
                    returnInput.addEventListener('change', updatePreview);
                })();
                </script>

            </div>
        </div><!-- end RIGHT COLUMN -->

    </div><!-- end Two-Column Layout -->

</div>
</main>

<?php include 'views/user/footer.php'; ?>