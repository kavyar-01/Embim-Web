<?php include 'views/user/header.php'; ?>

<?php
// Helper: bangun URL dengan filter aktif, kecuali yang di-skip
function buildUrl($skip = []) {
    global $search, $transmission, $fuel_type, $price_min, $price_max, $seats;
    $params = ['page' => 'cars'];
    if (!in_array('search',       $skip) && $search !== '')       $params['search']       = $search;
    if (!in_array('transmission', $skip) && $transmission !== '') $params['transmission'] = $transmission;
    if (!in_array('fuel_type',    $skip) && $fuel_type !== '')    $params['fuel_type']    = $fuel_type;
    if (!in_array('price_min',    $skip) && $price_min !== '')    $params['price_min']    = $price_min;
    if (!in_array('price_max',    $skip) && $price_max !== '')    $params['price_max']    = $price_max;
    if (!in_array('seats',        $skip) && $seats !== '')        $params['seats']        = $seats;
    return 'index.php?' . http_build_query($params);
}

$totalAll  = $carModel->getCarsWithFilter('', '', '', 99999, 0)['total'];
$hasFilter = $search !== '' || $transmission !== '' || $fuel_type !== '' || $price_min !== '' || $price_max !== '' || $seats !== '';

$baseUrl = 'index.php?page=cars'
    . ($search       !== '' ? '&search='       . urlencode($search)       : '')
    . ($transmission !== '' ? '&transmission=' . urlencode($transmission) : '')
    . ($fuel_type    !== '' ? '&fuel_type='    . urlencode($fuel_type)    : '')
    . ($price_min    !== '' ? '&price_min='    . urlencode($price_min)    : '')
    . ($price_max    !== '' ? '&price_max='    . urlencode($price_max)    : '')
    . ($seats        !== '' ? '&seats='        . urlencode($seats)        : '');
?>

<main class="min-h-screen bg-slate-50 pt-24 pb-20">
<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

    <!-- Page Title -->
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900">Car Catalog</h1>
        <p class="text-sm text-blue-500 font-medium mt-1">Find our vehicles ready to accompany your journey</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 items-start">

        <!-- ══════════════════════════════════
             KOLOM KIRI — FILTER SIDEBAR
        ══════════════════════════════════ -->
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="sticky top-24">
                <form method="GET" action="index.php" id="filter-form">
                    <input type="hidden" name="page" value="cars">
                    <input type="hidden" name="p"    value="1">

                    <!-- Header Sidebar -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                </svg>
                                <span class="text-sm font-bold text-gray-900">Filter</span>
                            </div>
                            <?php if ($hasFilter): ?>
                            <a href="index.php?page=cars" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">Reset</a>
                            <?php endif; ?>
                        </div>

                        <div class="divide-y divide-gray-100">

                            <!-- Pencarian -->
                            <div class="px-5 py-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Search</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        name="search"
                                        value="<?php echo htmlspecialchars($search); ?>"
                                        placeholder="brand, model, type..."
                                        class="w-full pl-9 pr-3 py-2.5 text-sm text-gray-800 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                                    >
                                </div>
                            </div>

                            <!-- Transmission -->
                            <div class="px-5 py-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Transmission</label>
                                <div class="space-y-2">
                                    <?php foreach (['' => 'All', 'automatic' => 'Automatic', 'manual' => 'Manual'] as $val => $label): ?>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input
                                            type="radio"
                                            name="transmission"
                                            value="<?php echo $val; ?>"
                                            <?php echo $transmission === $val ? 'checked' : ''; ?>
                                            onchange="this.form.submit()"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                        >
                                        <span class="text-sm text-gray-600 group-hover:text-gray-900 transition font-medium"><?php echo $label; ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Fuel Type -->
                            <div class="px-5 py-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Fuel Type</label>
                                <div class="space-y-2">
                                    <?php foreach (['' => 'All', 'gasoline' => 'Gasoline', 'diesel' => 'Diesel', 'electric' => 'Electric', 'hybrid' => 'Hybrid'] as $val => $label): ?>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input
                                            type="radio"
                                            name="fuel_type"
                                            value="<?php echo $val; ?>"
                                            <?php echo $fuel_type === $val ? 'checked' : ''; ?>
                                            onchange="this.form.submit()"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                        >
                                        <span class="text-sm text-gray-600 group-hover:text-gray-900 transition font-medium"><?php echo $label; ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Kapasitas Seats -->
                            <div class="px-5 py-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Capacity</label>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input
                                            type="radio"
                                            name="seats"
                                            value=""
                                            <?php echo $seats === '' ? 'checked' : ''; ?>
                                            onchange="this.form.submit()"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                        >
                                        <span class="text-sm text-gray-600 group-hover:text-gray-900 transition font-medium">All</span>
                                    </label>
                                    <?php foreach ($availableSeats as $s): ?>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input
                                            type="radio"
                                            name="seats"
                                            value="<?php echo $s; ?>"
                                            <?php echo $seats == $s ? 'checked' : ''; ?>
                                            onchange="this.form.submit()"
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                        >
                                        <span class="text-sm text-gray-600 group-hover:text-gray-900 transition font-medium"><?php echo $s; ?> Seats</span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Rentang Price -->
                            <div class="px-5 py-4">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Price Range / Day</label>
                                <div class="space-y-2.5">
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-3 flex items-center text-xs text-gray-400 font-medium pointer-events-none">Rp</span>
                                        <input
                                            type="number"
                                            name="price_min"
                                            value="<?php echo htmlspecialchars($price_min); ?>"
                                            placeholder="Min price"
                                            min="0"
                                            step="100000"
                                            class="w-full pl-8 pr-3 py-2.5 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                                        >
                                    </div>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-3 flex items-center text-xs text-gray-400 font-medium pointer-events-none">Rp</span>
                                        <input
                                            type="number"
                                            name="price_max"
                                            value="<?php echo htmlspecialchars($price_max); ?>"
                                            placeholder="Max price"
                                            min="0"
                                            step="100000"
                                            class="w-full pl-8 pr-3 py-2.5 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition"
                                        >
                                    </div>
                                    <button
                                        type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 rounded-xl transition duration-200"
                                    >
                                        Search
                                    </button>
                                </div>
                            </div>

                        </div><!-- end divide-y -->
                    </div><!-- end sidebar card -->
                </form>
            </div>
        </aside>

        <!-- ══════════════════════════════════
             KOLOM KANAN — GALERI MOBIL
        ══════════════════════════════════ -->
        <div class="flex-1 min-w-0">

            <!-- Toolbar: Info hasil + Badge filter aktif -->
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <p class="text-sm text-gray-500 font-medium">
                    <?php if ($totalCars > 0): ?>
                        Showing <span class="font-bold text-gray-800"><?php echo $totalCars; ?></span>
                        of <span class="font-bold text-gray-800"><?php echo $totalAll; ?></span> cars
                        <?php if ($totalPages > 1): ?>
                        <span class="text-gray-400">— Page <?php echo $page; ?>/<?php echo $totalPages; ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        No vehicles found.
                    <?php endif; ?>
                </p>

                <!-- Badge filter aktif -->
                <?php if ($hasFilter): ?>
                <div class="flex flex-wrap gap-1.5">
                    <?php if ($search !== ''): ?>
                    <span class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-1 rounded-full">
                        "<?php echo htmlspecialchars($search); ?>"
                        <a href="<?php echo buildUrl(['search']); ?>" class="hover:text-blue-900 ml-0.5">✕</a>
                    </span>
                    <?php endif; ?>
                    <?php if ($transmission !== ''): ?>
                    <span class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-1 rounded-full">
                        <?php echo ucfirst($transmission); ?>
                        <a href="<?php echo buildUrl(['transmission']); ?>" class="hover:text-blue-900 ml-0.5">✕</a>
                    </span>
                    <?php endif; ?>
                    <?php if ($fuel_type !== ''): ?>
                    <span class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-1 rounded-full">
                        <?php echo ucfirst($fuel_type); ?>
                        <a href="<?php echo buildUrl(['fuel_type']); ?>" class="hover:text-blue-900 ml-0.5">✕</a>
                    </span>
                    <?php endif; ?>
                    <?php if ($seats !== ''): ?>
                    <span class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-1 rounded-full">
                        <?php echo $seats; ?> Seats
                        <a href="<?php echo buildUrl(['seats']); ?>" class="hover:text-blue-900 ml-0.5">✕</a>
                    </span>
                    <?php endif; ?>
                    <?php if ($price_min !== '' || $price_max !== ''): ?>
                    <span class="inline-flex items-center gap-1 text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-1 rounded-full">
                        Rp <?php echo $price_min !== '' ? number_format($price_min, 0, ',', '.') : '0'; ?>
                        – <?php echo $price_max !== '' ? number_format($price_max, 0, ',', '.') : '∞'; ?>
                        <a href="<?php echo buildUrl(['price_min','price_max']); ?>" class="hover:text-blue-900 ml-0.5">✕</a>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($cars)): ?>

            <!-- Grid Kartu Cars -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">
                <?php foreach ($cars as $car):
                    $imgSrc = !empty($car['photo'])
                        ? 'assets/images/' . htmlspecialchars($car['photo'])
                        : 'assets/images/hrv-car.png';
                ?>
                <div class="group bg-white rounded-2xl overflow-hidden border border-blue-100/50 hover:shadow-2xl hover:shadow-blue-200/40 transition-all duration-500 hover:border-blue-200">

                    <!-- Gambar -->
                    <div class="relative h-48 overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100">
                        <img
                            src="<?php echo $imgSrc; ?>"
                            alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            loading="lazy"
                        >
                        <div class="absolute bottom-3 right-3 bg-black/70 backdrop-blur-sm text-white text-xs font-bold px-2.5 py-1 rounded-full">
                            Rp <?php echo number_format($car['price_per_day'], 0, ',', '.'); ?>/day
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-5">
                        <h3 class="text-base font-bold text-gray-900 group-hover:text-blue-600 transition mb-0.5">
                            <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>
                        </h3>
                        <p class="text-xs text-gray-400 font-medium mb-4"><?php echo $car['year']; ?></p>

                        <!-- Spesifikasi -->
                        <div class="grid grid-cols-2 gap-y-2 mb-4 pb-4 border-b border-blue-50 text-sm text-gray-600">
                            <span class="flex items-center gap-1.5 font-medium text-xs">
                                <svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <?php echo $car['seats']; ?> Seats
                            </span>
                            <span class="flex items-center gap-1.5 font-medium capitalize text-xs">
                                <svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <?php echo ucfirst($car['fuel_type']); ?>
                            </span>
                            <span class="flex items-center gap-1.5 font-medium capitalize text-xs">
                                <svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                                <?php echo ucfirst($car['transmission']); ?>
                            </span>
                            <span class="flex items-center gap-1.5 font-medium text-xs">
                                <svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Stock: <?php echo $car['stock'] ?? 0; ?> Unit
                            </span>
                        </div>

                        <!-- Price + Tombol -->
                        <div class="flex items-end justify-between">
                            <div>
                                <span class="text-xl font-black text-blue-600">
                                    Rp <?php echo number_format($car['price_per_day'], 0, ',', '.'); ?>
                                </span>
                                <span class="block text-xs font-semibold text-gray-400 uppercase mt-0.5">per day</span>
                            </div>
                            <a
                                href="index.php?page=car-detail&id=<?php echo $car['id']; ?>"
                                class="text-xs font-bold px-4 py-2 rounded-lg border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-200"
                            >
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center gap-2 pb-4">
                <?php if ($page > 1): ?>
                <a href="<?php echo $baseUrl . '&p=' . ($page - 1); ?>"
                   class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 bg-white hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition duration-200 shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Prev
                </a>
                <?php else: ?>
                <span class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-gray-100 text-sm font-semibold text-gray-300 bg-white cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Prev
                </span>
                <?php endif; ?>

                <?php $range = 2;
                for ($i = 1; $i <= $totalPages; $i++):
                    if ($i === 1 || $i === $totalPages || ($i >= $page - $range && $i <= $page + $range)): ?>
                        <?php if ($i === $page): ?>
                        <span class="px-4 py-2.5 rounded-xl text-sm font-bold bg-blue-600 text-white shadow"><?php echo $i; ?></span>
                        <?php else: ?>
                        <a href="<?php echo $baseUrl . '&p=' . $i; ?>"
                           class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 bg-white hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition duration-200 shadow-sm"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php elseif ($i === $page - $range - 1 || $i === $page + $range + 1): ?>
                        <span class="px-2 py-2.5 text-sm text-gray-400">…</span>
                    <?php endif;
                endfor; ?>

                <?php if ($page < $totalPages): ?>
                <a href="<?php echo $baseUrl . '&p=' . ($page + 1); ?>"
                   class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 bg-white hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition duration-200 shadow-sm">
                    Next
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <?php else: ?>
                <span class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-gray-100 text-sm font-semibold text-gray-300 bg-white cursor-not-allowed">
                    Next
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-24 text-center bg-white rounded-2xl border border-gray-100">
                <div class="h-20 w-20 rounded-full bg-blue-50 flex items-center justify-center mb-5">
                    <svg class="h-10 w-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Vehicle not found</h3>
                <p class="text-sm text-gray-400 mb-6">Try changing your keywords or search filters.</p>
                <a href="index.php?page=cars" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition duration-200">
                    View All Vehicles
                </a>
            </div>
            <?php endif; ?>

        </div><!-- end KOLOM KANAN -->

    </div><!-- end flex row -->

</div>
</main>

<?php include 'views/user/footer.php'; ?>