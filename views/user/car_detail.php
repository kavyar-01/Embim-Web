<?php include 'views/user/header.php'; ?>

<main class="min-h-screen bg-slate-50 pt-24 pb-20 font-sans">
<div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

    <?php
    $imgSrc = !empty($car['photo'])
        ? 'assets/images/' . htmlspecialchars($car['photo'])
        : 'assets/images/hrv-car.png';

    $statusConfig = [
        'available'   => ['label' => 'Available',   'color' => 'text-emerald-700 bg-emerald-100 border-emerald-200 shadow-[0_0_10px_rgba(16,185,129,0.2)]'],
        'booked'      => ['label' => 'Booked',      'color' => 'text-amber-700 bg-amber-100 border-amber-200 shadow-[0_0_10px_rgba(245,158,11,0.2)]'],
        'maintenance' => ['label' => 'Maintenance', 'color' => 'text-red-700 bg-red-100 border-red-200 shadow-[0_0_10px_rgba(239,68,68,0.2)]'],
    ];
    $s = $statusConfig[$car['status']] ?? ['label' => ucfirst($car['status']), 'color' => 'text-gray-700 bg-gray-100 border-gray-200'];
    ?>

    <!-- Back Link -->
    <div class="pt-4 mb-6">
        <a href="index.php?page=cars" class="group inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition-colors duration-300 font-semibold bg-white px-4 py-2 rounded-full shadow-sm hover:shadow-md border border-gray-100">
            <svg class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Cars
        </a>
    </div>

    <!-- ── Two-Column Layout ── -->
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        <!-- ════ LEFT COLUMN ════ -->
        <div class="flex-1 min-w-0 space-y-8" style="min-width: 0; flex: 1 1 0%">

            <!-- Hero Image -->
            <div class="relative rounded-[2rem] overflow-hidden bg-white shadow-xl border border-gray-100 group">
                <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-10 pointer-events-none"></div>
                <img
                    src="<?php echo $imgSrc; ?>"
                    alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>"
                    class="w-full object-cover transform group-hover:scale-105 transition-transform duration-700 ease-out"
                    style="height: 460px;"
                >
            </div>

            <!-- Title Block -->
            <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full -z-10 opacity-50"></div>
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tight leading-tight">
                            <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>
                        </h1>
                        <div class="flex items-center gap-3 mt-3 text-sm font-semibold text-gray-500">
                            <span class="bg-gray-100 px-3 py-1 rounded-lg text-gray-700"><?php echo $car['year']; ?></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                            <span class="bg-gray-100 px-3 py-1 rounded-lg text-gray-700 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                <?php echo ucfirst($car['transmission']); ?>
                            </span>
                        </div>
                    </div>
                    <span class="flex-shrink-0 inline-flex items-center gap-1.5 text-sm font-bold px-4 py-2 rounded-xl border <?php echo $s['color']; ?> transition-all hover:scale-105 cursor-default">
                        <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                        <?php echo $s['label']; ?>
                    </span>
                </div>

                <!-- 4 Spec Pills -->
                <div class="grid grid-cols-2 md:grid-cols-2 gap-4 mt-8 pt-6 border-t border-gray-100">
                    <?php
                    $specs = [
                        [
                            'icon'  => 'assets/images/seats-icon.png',
                            'label' => $car['seats'] . ' Seats',
                            'color' => 'bg-indigo-50/50 hover:bg-indigo-50'
                        ],
                        [
                            'icon'  => 'assets/images/gasoline-icon.png',
                            'label' => ucfirst($car['fuel_type']),
                            'color' => 'bg-blue-50/50 hover:bg-blue-50'
                        ],
                    ];
                    foreach ($specs as $spec): ?>
                    <div class="group flex flex-col items-center justify-center gap-3 <?php echo $spec['color']; ?> rounded-2xl py-4 px-3 border border-transparent hover:border-gray-200 hover:shadow-md transition-all duration-300">
                        <div class="p-3 bg-white rounded-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                            <img src="<?php echo $spec['icon']; ?>" class="w-6 h-6 object-contain opacity-80 group-hover:opacity-100" alt="">
                        </div>
                        <span class="text-sm font-bold text-gray-700 text-center"><?php echo $spec['label']; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Description -->
            <?php if (!empty($car['description'])): ?>
            <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100">
                <div class="flex items-center gap-3 mb-5">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-lg font-black text-gray-900 uppercase tracking-wide">Description</h2>
                </div>
                <p class="text-base text-gray-600 leading-relaxed font-medium">
                    <?php echo nl2br(htmlspecialchars($car['description'])); ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Vehicle Highlights -->
            <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h2 class="text-lg font-black text-gray-900 uppercase tracking-wide">Vehicle Highlights</h2>
                </div>
                <?php
                $highlights = [
                    [
                        'icon_type' => 'img',
                        'icon_src'  => 'assets/images/drivetrain-icon.png',
                        'label' => 'DRIVETRAIN',
                        'value' => !empty($car['drivetrain']) ? htmlspecialchars($car['drivetrain']) : '-',
                    ],
                    [
                        'icon_type' => 'img',
                        'icon_src'  => 'assets/images/car-style-icon.png',
                        'label' => 'BODY STYLE',
                        'value' => !empty($car['body_style']) ? htmlspecialchars($car['body_style']) : ucfirst(htmlspecialchars($car['category'] ?? 'Sedan')),
                    ],
                    [
                        'icon_type' => 'img',
                        'icon_src'  => 'assets/images/engine-icon.png',
                        'label' => 'ENGINE',
                        'value' => !empty($car['engine']) ? htmlspecialchars($car['engine']) : '-',
                    ],
                    [
                        'icon_type' => 'img',
                        'icon_src'  => 'assets/images/transmission-icon.png',
                        'label' => 'TRANSMISSION',
                        'value' => !empty($car['hl_transmission']) ? ucfirst(htmlspecialchars($car['hl_transmission'])) : ucfirst(htmlspecialchars($car['transmission'] ?? '')),
                    ],
                ];
                ?>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php foreach ($highlights as $h): ?>
                    <div class="group flex flex-col p-4 rounded-2xl border border-gray-100 bg-gray-50 hover:border-indigo-200 hover:bg-indigo-50 hover:shadow-md transition-all duration-300">
                        <div class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm mb-3 group-hover:scale-110 group-hover:border-indigo-300 transition-transform duration-300">
                            <?php if (($h['icon_type'] ?? 'svg') === 'img'): ?>
                                <img src="<?php echo $h['icon_src']; ?>" class="w-6 h-6 object-contain opacity-70 group-hover:opacity-100" alt="">
                            <?php else: ?>
                                <svg class="h-5 w-5 text-gray-600 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?php echo $h['svg']; ?>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1"><?php echo $h['label']; ?></p>
                            <p class="text-sm font-black text-gray-800 leading-tight group-hover:text-indigo-900"><?php echo $h['value']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Ketentuan Denda & Refund -->
                <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100 h-full">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-red-100 rounded-lg text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h2 class="text-lg font-black text-gray-900 uppercase tracking-wide">Fine & Refund</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Denda Keterlambatan -->
                        <div class="flex items-start gap-4 bg-red-50/80 border border-red-100 rounded-2xl p-5 hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-red-800 mb-1.5">Late Fine</p>
                                <p class="text-xs text-red-700/80 leading-relaxed font-medium">
                                    Returning the vehicle past the specified date will incur a fine of
                                    <strong class="text-red-600 bg-red-100 px-1.5 py-0.5 rounded">Rp 700.000 / day</strong>.
                                </p>
                            </div>
                        </div>

                        <!-- Kebijakan Refund -->
                        <div class="flex items-start gap-4 bg-amber-50/80 border border-amber-100 rounded-2xl p-5 hover:shadow-md transition-shadow">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-amber-800 mb-1.5">Cancellation Policy</p>
                                <p class="text-xs text-amber-700/80 leading-relaxed font-medium">
                                    Cancellation refunds <strong class="text-amber-600 bg-amber-100 px-1.5 py-0.5 rounded">80%</strong> of the total price.
                                    20% is deducted for admin fees.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Persyaratan -->
                <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100 h-full">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h2 class="text-lg font-black text-gray-900 uppercase tracking-wide">Requirements</h2>
                    </div>
                    
                    <div class="flex items-start gap-4 bg-blue-50/80 border border-blue-100 rounded-2xl p-5 hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-blue-800 mb-2">Must be Shown Upon Pick Up</p>
                            <p class="text-xs text-blue-700/80 leading-relaxed font-medium mb-3">
                                Renters are required to show the following original documents when picking up the vehicle:
                            </p>
                            <ul class="space-y-2">
                                <li class="flex items-center gap-2 text-sm text-blue-800 font-bold">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Original ID Card (KTP)
                                </li>
                                <li class="flex items-center gap-2 text-sm text-blue-800 font-bold">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Driver's License (Active)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kendaraan Lainnya -->
            <?php if (!empty($relatedCars)): ?>
            <div class="bg-white rounded-[2rem] p-8 shadow-lg border border-gray-100">
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <h2 class="text-lg font-black text-gray-900 uppercase tracking-wide">Other Vehicles</h2>
                        <p class="text-sm text-gray-500 mt-1 font-medium">Explore more options from our fleet</p>
                    </div>
                    <a href="index.php?page=cars" class="inline-flex items-center gap-1.5 text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors bg-blue-50 px-4 py-2 rounded-xl">
                        View All
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($relatedCars as $rel):
                        $relImg = !empty($rel['photo'])
                            ? 'assets/images/' . htmlspecialchars($rel['photo'])
                            : 'assets/images/hrv-car.png';
                    ?>
                    <a href="index.php?page=car-detail&id=<?php echo $rel['id']; ?>" class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-blue-300 hover:shadow-xl transition-all duration-300">
                        <div class="h-40 bg-gray-50 overflow-hidden relative">
                            <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors z-10"></div>
                            <img
                                src="<?php echo $relImg; ?>"
                                alt="<?php echo htmlspecialchars($rel['brand'] . ' ' . $rel['model']); ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                loading="lazy"
                            >
                            <div class="absolute top-3 right-3 z-20">
                                <span class="bg-white/90 backdrop-blur text-gray-800 text-xs font-black px-2.5 py-1 rounded-lg shadow-sm">
                                    <?php echo $rel['year']; ?>
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <p class="text-base font-black text-gray-900 group-hover:text-blue-600 transition-colors truncate">
                                    <?php echo htmlspecialchars($rel['brand'] . ' ' . $rel['model']); ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-1 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                    <?php echo ucfirst($rel['transmission']); ?>
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-end justify-between">
                                <p class="text-lg font-black text-blue-600">
                                    Rp <?php echo number_format($rel['price_per_day'], 0, ',', '.'); ?>
                                    <span class="text-xs font-semibold text-gray-400">/ day</span>
                                </p>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- end LEFT COLUMN -->

        <!-- ════ RIGHT COLUMN — Sticky Booking Card ════ -->
        <div class="w-full lg:w-[340px] flex-shrink-0">
            <div class="sticky top-28">
                
                <!-- Pricing & Booking -->
                <div class="bg-white rounded-[2rem] p-6 shadow-2xl shadow-blue-900/5 border border-gray-100 relative overflow-hidden">
                    <!-- Decor -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 opacity-50 rounded-bl-[3rem] -z-10"></div>

                    <div class="mb-6">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Rental Price</p>
                        <div class="flex items-end gap-1">
                            <span class="text-3xl font-black text-gray-900">Rp <?php echo number_format($car['price_per_day'], 0, ',', '.'); ?></span>
                            <span class="text-sm text-gray-500 font-bold pb-1">/ day</span>
                        </div>
                    </div>

                    <!-- Stock Info -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-2xl border border-blue-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                            <span class="text-xs font-bold text-blue-900 uppercase tracking-wide">Available Stock</span>
                        </div>
                        <span class="text-base font-black text-blue-700 bg-white px-3 py-1 rounded-lg shadow-sm"><?php echo $car['stock'] ?? 0; ?> Unit</span>
                    </div>

                    <hr class="border-gray-100 mb-6">

                    <?php if ($car['status'] === 'available'): ?>
                    <form action="index.php?page=booking" method="GET" id="booking-form" class="relative z-10">
                        <input type="hidden" name="page"   value="booking">
                        <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">

                        <!-- Date Inputs -->
                        <div class="space-y-4 mb-6">
                            <div class="relative">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Pickup Date</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input
                                        type="date"
                                        id="pickup_date"
                                        name="start_date"
                                        min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-gray-100 text-sm font-bold text-gray-800 bg-gray-50 focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="relative">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Return Date</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input
                                        type="date"
                                        id="return_date"
                                        name="end_date"
                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-gray-100 text-sm font-bold text-gray-800 bg-gray-50 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Preview Durasi & Total -->
                        <div id="price-preview" class="hidden mb-6 bg-gray-900 rounded-2xl p-5 border border-gray-800 shadow-lg text-white transform transition-all duration-300 origin-bottom">
                            <div class="flex justify-between items-center text-sm mb-3 pb-3 border-b border-gray-700">
                                <span class="text-gray-400 font-medium">Rental Duration</span>
                                <span id="duration-text" class="font-bold text-white bg-gray-800 px-2.5 py-1 rounded-lg">-</span>
                            </div>
                            <div class="flex justify-between items-end">
                                <span class="text-sm font-medium text-gray-400">Estimated Total</span>
                                <span id="total-price" class="text-xl font-black text-white">-</span>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black text-base py-4 rounded-2xl shadow-[0_10px_20px_-10px_rgba(37,99,235,0.5)] hover:shadow-[0_15px_25px_-10px_rgba(37,99,235,0.6)] transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2"
                        >
                            Continue Booking
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                        <p class="text-center text-[11px] font-bold text-gray-400 mt-4 uppercase tracking-wider">No hidden fees. Instant confirmation.</p>
                    </form>
                    <?php else: ?>
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 mb-6 text-center">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                        <p class="text-sm font-bold text-gray-600">Currently Unavailable</p>
                        <p class="text-xs text-gray-400 mt-1">This vehicle is booked or under maintenance.</p>
                    </div>
                    <button disabled class="w-full bg-gray-100 text-gray-400 font-bold text-sm py-4 rounded-2xl cursor-not-allowed">
                        Booking Closed
                    </button>
                    <?php endif; ?>
                </div>


                <script>
                (function(){
                    const pricePerDay = <?php echo (int)$car['price_per_day']; ?>;
                    const pickupInput = document.getElementById('pickup_date');
                    const returnInput = document.getElementById('return_date');
                    const preview     = document.getElementById('price-preview');
                    if(!preview) return; // if unavailable
                    
                    const durationEl  = document.getElementById('duration-text');
                    const totalEl     = document.getElementById('total-price');

                    function updatePreview() {
                        const start = new Date(pickupInput.value);
                        const end   = new Date(returnInput.value);
                        if (!pickupInput.value || !returnInput.value || end <= start) {
                            preview.classList.add('hidden'); 
                            preview.classList.remove('scale-100', 'opacity-100');
                            return;
                        }
                        const days = Math.ceil((end - start) / (1000*60*60*24));
                        const total = days * pricePerDay;
                        durationEl.textContent = days + (days > 1 ? ' Days' : ' Day');
                        totalEl.textContent    = 'Rp ' + total.toLocaleString('id-ID');
                        
                        preview.classList.remove('hidden');
                        setTimeout(() => preview.classList.add('scale-100', 'opacity-100'), 10);
                        
                        const minReturn = new Date(start);
                        minReturn.setDate(minReturn.getDate() + 1);
                        returnInput.min = minReturn.toISOString().split('T')[0];
                    }
                    pickupInput.addEventListener('change', function(){
                        returnInput.value = '';
                        preview.classList.add('hidden');
                        preview.classList.remove('scale-100', 'opacity-100');
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
