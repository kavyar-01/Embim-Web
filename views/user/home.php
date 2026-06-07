<?php
/** @var array $featuredCars */
// Redirect if accessed directly (not through index.php)
if (!defined('BASE_URL')) {
    header("Location: /index.php");
    exit;
}
?>
<?php include 'views/user/header.php'; ?>

<!-- Hero Section -->
<section class="relative pt-40 pb-32 overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Hero Background Decor -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-5xl h-96 hero-bg-overlay -z-10"></div>


        <div class="text-center mb-20">
            <!-- Hero heading — reveal from bottom -->
            <h1 class="reveal text-6xl md:text-7xl font-extrabold tracking-tight text-gray-900 mb-6 leading-tight">
                Easy Mobility <br> <span class="text-blue-600">Booking In Minutes</span>
            </h1>
            <!-- Subtitle — reveal with slight delay -->
            <p class="reveal reveal-delay-1 text-lg md:text-xl text-gray-600 max-w-3xl mx-auto mb-16 font-medium leading-relaxed">
                The most practical premium car rental solution for those who want a hassle-free experience. Because your vacation or business trip deserves the best!
            </p>

            <!-- Search Widget — reveal with delay -->
            <div class="reveal reveal-delay-2 max-w-2xl mx-auto">
                <img src="/assets/images/main_car.png" alt="Main Car Picture">
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-28 bg-white relative overflow-hidden">

    <!-- Background decorative elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-50/60 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-50/40 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        <!-- Section Heading -->
        <div class="text-center mb-20 reveal">
            <span class="inline-block text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-4 py-2 rounded-full mb-4">Why EMBIM?</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-5 leading-tight">
                A Driving Experience <br class="hidden md:block">
                <span class="text-blue-600">Like No Other</span>
            </h2>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto font-medium leading-relaxed">
                More than just a car rental — we bring comfort, trust,
                and driving freedom for every journey.
            </p>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">

            <!-- Card 1: Armada Premium -->
            <div class="reveal reveal-scale group relative bg-white rounded-2xl p-8 border border-gray-100 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/0 to-blue-50/60 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-500 pointer-events-none"></div>
                <div class="relative">
                    <div class="h-14 w-14 rounded-2xl bg-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-blue-200/60 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">Premium Fleet</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        World-class luxury vehicle selection — from executive sedans to premium SUVs — all perfectly maintained and ready to accompany your journey.
                    </p>
                </div>
            </div>

            <!-- Card 2: Booking Kilat -->
            <div class="reveal reveal-scale reveal-delay-1 group relative bg-white rounded-2xl p-8 border border-gray-100 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/0 to-blue-50/60 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-500 pointer-events-none"></div>
                <div class="relative">
                    <div class="h-14 w-14 rounded-2xl bg-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-blue-200/60 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">Booking in Minutes</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        A booking process designed to be as easy as possible. Choose a vehicle, set a schedule, and confirm — done in less than 5 minutes.
                    </p>
                </div>
            </div>

            <!-- Card 3: Price Transparan -->
            <div class="reveal reveal-scale reveal-delay-2 group relative bg-white rounded-2xl p-8 border border-gray-100 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/0 to-blue-50/60 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-500 pointer-events-none"></div>
                <div class="relative">
                    <div class="h-14 w-14 rounded-2xl bg-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-blue-200/60 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">Transparent Prices, No Surprises</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        No hidden fees. The price you see is the price you pay — clear, honest, and competitive for every vehicle category.
                    </p>
                </div>
            </div>

            <!-- Card 4: Dukungan 24/7 -->
            <div class="reveal reveal-scale reveal-delay-1 group relative bg-white rounded-2xl p-8 border border-gray-100 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/0 to-blue-50/60 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-500 pointer-events-none"></div>
                <div class="relative">
                    <div class="h-14 w-14 rounded-2xl bg-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-blue-200/60 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">24/7 Customer Support</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Our team is ready to help whenever you need — day, night, even during emergencies on the road. Because your satisfaction is our top priority.
                    </p>
                </div>
            </div>

            <!-- Card 5: Asuransi Lengkap -->
            <div class="reveal reveal-scale reveal-delay-2 group relative bg-white rounded-2xl p-8 border border-gray-100 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/0 to-blue-50/60 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-500 pointer-events-none"></div>
                <div class="relative">
                    <div class="h-14 w-14 rounded-2xl bg-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-blue-200/60 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">Protected with Full Insurance</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Every rental includes comprehensive insurance protection. Drive with peace of mind, knowing you and the vehicle are fully protected.
                    </p>
                </div>
            </div>

            <!-- Card 6: Pengiriman ke Lokasi -->
            <div class="reveal reveal-scale reveal-delay-3 group relative bg-white rounded-2xl p-8 border border-gray-100 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/0 to-blue-50/60 opacity-0 group-hover:opacity-100 rounded-2xl transition-opacity duration-500 pointer-events-none"></div>
                <div class="relative">
                    <div class="h-14 w-14 rounded-2xl bg-blue-600 flex items-center justify-center mb-6 shadow-lg shadow-blue-200/60 group-hover:scale-110 transition-transform duration-300">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">Delivery to Your Location</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Vehicles delivered directly to your address — office, hotel, or airport. No need to come to our office, we come to you.
                    </p>
                </div>
            </div>

        </div>

        <!-- Stats Bar -->
        <div class="reveal bg-blue-600 rounded-3xl px-8 py-10 grid grid-cols-2 md:grid-cols-4 gap-8">
            <?php
            $stats = [
                ['value' => (isset($totalAvailableCars) ? $totalAvailableCars : '0') . '+',  'label' => 'Available Vehicles'],
                ['value' => (isset($totalCustomers) ? $totalCustomers : '0') . '+',  'label' => 'Customers'],
                ['value' => (isset($averageRating) ? $averageRating : '0') . '★',  'label' => 'Average Rating'],
                ['value' => '24/7',  'label' => 'Active Service'],
            ];
            foreach ($stats as $stat): ?>
            <div class="text-center">
                <p class="text-3xl md:text-4xl font-black text-white mb-1"><?php echo $stat['value']; ?></p>
                <p class="text-blue-200 text-sm font-semibold"><?php echo $stat['label']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Featured Vehicles Section -->
<section class="py-24 bg-gradient-to-b from-blue-50/30 to-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section heading -->
        <div class="reveal flex justify-between items-end mb-16">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-3">
                    <?php echo !empty($_GET['search']) ? 'Search Results' : 'Featured Vehicles'; ?>
                </h2>
                <p class="text-gray-600 text-lg font-medium">
                    <?php echo !empty($_GET['search'])
                        ? 'Found ' . count($featuredCars) . ' vehicles based on your search.'
                        : 'Discover our car collection.'; ?>
                </p>
            </div>
            <a href="index.php?page=cars" class="hidden sm:block text-base font-bold text-blue-600 border-b-2 border-blue-600 pb-1 hover:text-blue-700 hover:border-blue-700 transition">
                View All →
            </a>
        </div>

        <?php if (empty($featuredCars)): ?>
            <div class="reveal text-center py-24 bg-white rounded-3xl border border-blue-100/50 shadow-sm">
                <div class="bg-blue-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-12 w-12 text-blue-400" version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                         viewBox="0 0 32 32" xml:space="preserve">
                        <style type="text/css">
                            .st0{fill:none;stroke:#2563eb;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
                        </style>
                        <circle class="st0" cx="16" cy="16" r="13"/>
                        <line class="st0" x1="12" y1="12" x2="12" y2="16"/>
                        <line class="st0" x1="20" y1="12" x2="20" y2="16"/>
                        <line class="st0" x1="10" y1="20" x2="22" y2="20"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No cars found</h3>
            </div>

        <?php else: ?>

        <!-- ── CAROUSEL WRAPPER ── -->
        <div class="relative" id="featured-carousel">

            <!-- Overflow container -->
            <div class="overflow-hidden">
                <div id="carousel-track" class="flex transition-transform duration-500 ease-in-out gap-8">
                    <?php foreach ($featuredCars as $car):
                        $imgSrc = !empty($car['photo'])
                            ? 'assets/images/' . htmlspecialchars($car['photo'])
                            : 'assets/images/hrv-car.png';
                    ?>
                    <!-- Slide Item -->
                    <div class="carousel-item flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 group bg-white rounded-2xl overflow-hidden border border-blue-100/50 hover:shadow-2xl hover:shadow-blue-200/40 transition-all duration-500 hover:border-blue-200">

                        <!-- Gambar -->
                        <div class="relative h-56 overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100">
                            <img
                                src="<?php echo $imgSrc; ?>"
                                alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                loading="lazy"
                            >
                            <div class="absolute top-4 right-4 bg-blue-600 px-3 py-1.5 rounded-full text-xs font-bold text-white shadow-lg capitalize">
                                <?php echo htmlspecialchars($car['transmission']); ?>
                            </div>
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-full text-xs font-bold text-gray-700 shadow capitalize">
                                <?php echo htmlspecialchars($car['fuel_type']); ?>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition mb-1">
                                <?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>
                            </h3>
                            <p class="text-xs text-gray-400 font-medium mb-4"><?php echo $car['year']; ?></p>

                            <div class="flex items-center space-x-6 mb-5 pb-5 border-b border-blue-50">
                                <span class="flex items-center text-sm text-gray-600 font-medium">
                                    <svg class="h-4 w-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <?php echo ucfirst($car['transmission']); ?>
                                </span>
                                <span class="flex items-center text-sm text-gray-600 font-medium">
                                    <svg class="h-4 w-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <?php echo $car['seats']; ?> Seats
                                </span>
                            </div>

                            <div class="flex justify-between items-center mb-5">
                                <div>
                                    <span class="text-2xl font-black text-blue-600">
                                        Rp <?php echo number_format($car['price_per_day'], 0, ',', '.'); ?>
                                    </span>
                                    <span class="block text-xs font-semibold text-gray-400 uppercase mt-0.5">per day</span>
                                </div>
                            </div>

                            <a
                                href="index.php?page=car-detail&id=<?php echo $car['id']; ?>"
                                class="block w-full py-3 rounded-lg border-2 border-blue-600 font-bold text-sm text-center text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-blue-200/50"
                            >
                                Rent Now
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Arrow Prev -->
            <button
                id="carousel-prev"
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-5 h-11 w-11 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-gray-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-200 z-10 disabled:opacity-30 disabled:cursor-not-allowed"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Arrow Next -->
            <button
                id="carousel-next"
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-5 h-11 w-11 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-gray-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-200 z-10 disabled:opacity-30 disabled:cursor-not-allowed"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Dot Indicators -->
        <div id="carousel-dots" class="flex justify-center items-center gap-2 mt-10">
            <!-- Dots dirender oleh JS -->
        </div>

        <!-- Lihat Semua (mobile) -->
        <div class="text-center mt-8 sm:hidden">
            <a href="index.php?page=cars" class="text-sm font-bold text-blue-600 underline underline-offset-4">View All Vehicles →</a>
        </div>

        <?php endif; ?>
    </div>
</section>

<!-- Booking Requirements Section -->
<section class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section heading -->
        <div class="text-center mb-20 reveal">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Booking Requirements</h2>
            <p class="text-lg text-gray-600 font-medium max-w-2xl mx-auto">
                To ensure a smooth rental process, please prepare the following documents and fulfill these requirements before making a booking.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Requirement 1 -->
            <div class="reveal reveal-left reveal-delay-1 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-8 border border-blue-200/50 hover:shadow-lg hover:shadow-blue-200/30 transition-all duration-300">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-600 text-white shadow-lg">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v10a2 2 0 002 2h5m0 0h5a2 2 0 002-2V8a2 2 0 00-2-2h-5m0 0V5a2 2 0 012-2h1a2 2 0 012 2v1m0 0h5a2 2 0 012 2v10a2 2 0 01-2 2h-5"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Valid Identity Card</h3>
                        <p class="text-gray-700 font-medium">Original or certified copy of Identity Card (KTP/ID). The identity document must be valid and not expired.</p>
                    </div>
                </div>
            </div>

            <!-- Requirement 2 -->
            <div class="reveal reveal-right reveal-delay-1 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-8 border border-blue-200/50 hover:shadow-lg hover:shadow-blue-200/30 transition-all duration-300">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-600 text-white shadow-lg">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Driver's License</h3>
                        <p class="text-gray-700 font-medium">Valid and unexpired Driver's License that allows you to drive private vehicles. Must be issued by the transportation authority in your country.</p>
                    </div>
                </div>
            </div>

            <!-- Requirement 3 -->
            <div class="reveal reveal-left reveal-delay-2 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-8 border border-blue-200/50 hover:shadow-lg hover:shadow-blue-200/30 transition-all duration-300">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-600 text-white shadow-lg">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Minimum Age Requirement</h3>
                        <p class="text-gray-700 font-medium">You must be at least 21 years old to rent a vehicle. Drivers under 21 years of age are not permitted.</p>
                    </div>
                </div>
            </div>

            <!-- Requirement 4 -->
            <div class="reveal reveal-right reveal-delay-2 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-8 border border-blue-200/50 hover:shadow-lg hover:shadow-blue-200/30 transition-all duration-300">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-600 text-white shadow-lg">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Self-Identity Photo</h3>
                        <p class="text-gray-700 font-medium">A clear recent selfie or a photo of yourself holding your official identity card.</p>
                    </div>
                </div>
            </div>

            <!-- Requirement 5 -->
            <div class="reveal reveal-left reveal-delay-3 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-8 border border-blue-200/50 hover:shadow-lg hover:shadow-blue-200/30 transition-all duration-300">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-600 text-white shadow-lg">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Valid Contact Information</h3>
                        <p class="text-gray-700 font-medium">Valid email address and phone number. We will use this information to confirm your booking, send updates, and contact you regarding your rental.</p>
                    </div>
                </div>
            </div>

            <!-- Requirement 6 -->
            <div class="reveal reveal-right reveal-delay-3 bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-8 border border-blue-200/50 hover:shadow-lg hover:shadow-blue-200/30 transition-all duration-300">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-14 w-14 rounded-xl bg-blue-600 text-white shadow-lg">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Valid Payment Method</h3>
                        <p class="text-gray-700 font-medium">Valid credit card, debit card, or digital payment method. We require a registered payment method to secure your booking and process rental charges.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="reveal bg-blue-50/50 rounded-2xl p-8 border border-blue-200/50">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="h-6 w-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Important Notes
            </h3>
            <ul class="space-y-3 text-gray-700 font-medium">
                <li class="flex items-start"><span class="text-blue-600 font-bold mr-3">•</span><span>All documents must be clear, legible, and in good condition. Blurred or incomplete documents may be rejected.</span></li>
                <li class="flex items-start"><span class="text-blue-600 font-bold mr-3">•</span><span>International renters are required to present a passport and International Driving Permit (IDP) in addition to their home country's driver's license.</span></li>
                <li class="flex items-start"><span class="text-blue-600 font-bold mr-3">•</span><span>We reserve the right to request additional documents or verification if necessary for compliance and security reasons.</span></li>
            </ul>
        </div>
    </div>
</section>

<!-- Booking Flow Section -->
<section class="py-24 bg-gradient-to-b from-blue-50/40 to-white relative overflow-hidden">

    <!-- Decorative blob -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-blue-100/30 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        <!-- Heading -->
        <div class="text-center mb-16 reveal">
            <span class="inline-block text-xs font-bold text-blue-600 uppercase tracking-widest bg-blue-50 px-4 py-2 rounded-full mb-4">Fast & Easy</span>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-5 leading-tight">
                Booking Flow <span class="text-blue-600">EMBIM</span>
            </h2>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto font-medium leading-relaxed">
                From choosing a vehicle to hitting the road — it only takes a few steps!
            </p>
        </div>

        <!-- Steps -->
        <div class="flex flex-col lg:flex-row items-center lg:items-start justify-center gap-4 lg:gap-0">

            <?php
            $steps = [
                [
                    'step'  => '01',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                    'title' => 'Create Account',
                    'desc'  => 'Register and fill in your personal details.',
                ],
                [
                    'step'  => '02',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
                    'title' => 'Choose a Vehicle',
                    'desc'  => 'Browse our fleet collection and find the most suitable vehicle.',
                ],
                [
                    'step'  => '03',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>',
                    'title' => 'Fill the Form',
                    'desc'  => 'Complete the booking details.',
                ],
                [
                    'step'  => '04',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                    'title' => 'Payment',
                    'desc'  => 'Choose your favorite payment method. Secure, fast, and instantly confirmed.',
                ],
                [
                    'step'  => '05',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'title' => 'Confirmation',
                    'desc'  => 'Booking confirmed & details sent to your email.',
                ],
                [
                    'step'  => '06',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V6m0 0l-4 4m4-4l4 4M5 20h14"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l1.5-5h15L21 10H3z"/>',
                    'title' => 'Pick Up & Drive!',
                    'desc'  => 'Vehicle is ready! Enjoy your journey with full comfort.',
                ],
            ];

            $delays = ['', 'reveal-delay-1', 'reveal-delay-2', 'reveal-delay-1', 'reveal-delay-2', 'reveal-delay-3'];

            foreach ($steps as $i => $step):
                $isLast = $i === count($steps) - 1;
            ?>

            <!-- Step Card -->
            <div class="reveal reveal-scale <?php echo $delays[$i]; ?> flex lg:flex-col items-center gap-4 lg:gap-0 lg:flex-1 lg:text-center group">

                <!-- Icon + Number -->
                <div class="flex-shrink-0 flex lg:flex-col lg:items-center lg:mb-6">
                    <div class="relative">
                        <!-- Glow ring on hover -->
                        <div class="absolute inset-0 rounded-2xl bg-blue-400/0 group-hover:bg-blue-400/20 blur-lg transition-all duration-500 scale-150"></div>
                        <!-- Icon box -->
                        <div class="relative h-16 w-16 rounded-2xl bg-blue-600 group-hover:bg-blue-700 shadow-lg shadow-blue-200/60 flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:shadow-blue-300/60">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?php echo $step['icon']; ?>
                            </svg>
                        </div>
                        <!-- Step number badge -->
                        <span class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-white border-2 border-blue-600 text-blue-600 text-xs font-black flex items-center justify-center shadow">
                            <?php echo $step['step']; ?>
                        </span>
                    </div>
                </div>

                <!-- Text -->
                <div class="lg:px-3 lg:pt-5">
                    <h3 class="text-base font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors duration-200">
                        <?php echo $step['title']; ?>
                    </h3>
                    <p class="text-xs text-gray-400 leading-relaxed max-w-[140px] mx-auto hidden lg:block">
                        <?php echo $step['desc']; ?>
                    </p>
                    <p class="text-sm text-gray-400 leading-relaxed lg:hidden">
                        <?php echo $step['desc']; ?>
                    </p>
                </div>

            </div>

            <!-- Arrow connector (hidden on last item) -->
            <?php if (!$isLast): ?>
            <div class="hidden lg:flex items-start pt-8 px-1 text-blue-300 flex-shrink-0">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <!-- Mobile vertical arrow -->
            <div class="lg:hidden flex items-center justify-center text-blue-300 self-start mt-2">
                <svg class="h-6 w-6 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <?php endif; ?>

            <?php endforeach; ?>
        </div>

        <!-- CTA Button -->
        <div class="text-center mt-16 reveal">
            <a
                href="index.php?page=cars"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-base px-8 py-4 rounded-2xl transition-all duration-300 shadow-lg shadow-blue-200/60 hover:shadow-blue-300/60 hover:scale-105"
            >
                Start Booking Now
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

    </div>
</section>



<!-- Testimonials Section -->
<section class="py-24 bg-gradient-to-b from-blue-50/20 to-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row justify-between items-center mb-16 reveal" id="testimonials">
            <div class="text-center md:text-left mb-6 md:mb-0">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">What Our Customers Say</h2>
                <p class="text-lg text-gray-600 font-medium">
                </p>
            </div>
            <div>
                <form method="GET" action="index.php" id="review-limit-form">
                    <label for="review_limit" class="text-sm font-bold text-gray-700 mr-2">Show:</label>
                    <select name="review_limit" id="review_limit" onchange="window.location.href='index.php?review_limit='+this.value+'#testimonials'" class="bg-white border border-gray-200 text-gray-700 rounded-xl px-4 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/50 shadow-sm cursor-pointer transition hover:border-blue-300">
                        <option value="6" <?php echo $reviewLimit == 6 ? 'selected' : ''; ?>>6 Reviews</option>
                        <option value="10" <?php echo $reviewLimit == 10 ? 'selected' : ''; ?>>10 Reviews</option>
                        <option value="30" <?php echo $reviewLimit == 30 ? 'selected' : ''; ?>>30 Reviews</option>
                        <option value="50" <?php echo $reviewLimit == 50 ? 'selected' : ''; ?>>50 Reviews</option>
                        <option value="100" <?php echo $reviewLimit == 100 ? 'selected' : ''; ?>>100 Reviews</option>
                    </select>
                    <?php if (!empty($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search']); ?>">
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if (!empty($reviews)): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            $tDelays = ['', 'reveal-delay-2', 'reveal-delay-4'];
            foreach ($reviews as $idx => $r):
                $delay = $tDelays[$idx % 3];
            ?>
            <div class="reveal reveal-scale <?php echo $delay; ?> bg-white p-8 rounded-2xl border border-blue-100/50 hover:shadow-lg hover:shadow-blue-200/30 transition-all duration-300">
                <!-- Stars -->
                <div class="flex text-yellow-500 mb-4">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <svg class="h-5 w-5 <?php echo $i <= $r['rating'] ? 'fill-current' : 'fill-gray-200'; ?>" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <?php endfor; ?>
                </div>
                <!-- Comment -->
                <p class="text-gray-700 italic mb-6 font-medium leading-relaxed">
                    "<?php echo htmlspecialchars($r['comment']); ?>"
                </p>
                <!-- Author -->
                <div class="border-t border-blue-100 pt-4">
                    <h4 class="font-bold text-gray-900"><?php echo htmlspecialchars($r['full_name']); ?></h4>
                    <p class="text-sm text-blue-600 font-bold uppercase tracking-wide"><?php echo htmlspecialchars($r['car_name']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16 text-gray-400 font-medium">
            No reviews from customers yet.
        </div>
        <?php endif; ?>

    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-extrabold text-gray-900 mb-3">Never Miss a Deal!</h2>
        <p class="text-gray-500 text-base mb-10">Subscribe to get the latest offers, new products, and exclusive discounts</p>
        <form class="flex items-stretch max-w-xl mx-auto shadow-sm" id="newsletter-form" onsubmit="handleNewsletterSubmit(event)">
            <input
                id="newsletter-email"
                type="email"
                placeholder="Enter your email id"
                required
                class="flex-1 px-5 py-4 border border-gray-200 border-r-0 rounded-l-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder-gray-400"
            >
            <button
                type="submit"
                class="bg-blue-600 text-white px-8 py-4 rounded-r-lg text-sm font-bold hover:bg-blue-700 transition whitespace-nowrap"
            >
                Subscribe
            </button>
        </form>
    </div>
</section>

<!-- ══════════════════════════════════════
     POPUP NEWSLETTER SUCCESS
══════════════════════════════════════ -->
<div id="newsletter-popup" class="hidden fixed inset-0 z-[999] flex items-center justify-center px-4">

    <!-- Backdrop -->
    <div id="newsletter-backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>

    <!-- Modal -->
    <div id="newsletter-modal"
         class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden
                translate-y-8 opacity-0 transition-all duration-300 ease-out">

        <!-- Decorative top bar -->
        <div class="h-2 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600"></div>

        <!-- Close button -->
        <button
            onclick="closeNewsletterPopup()"
            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition text-gray-500 hover:text-gray-700"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="px-8 py-8 text-center">

            <!-- Icon animasi -->
            <div class="mx-auto mb-5 w-20 h-20 rounded-full bg-blue-50 flex items-center justify-center
                        ring-8 ring-blue-100/60" id="popup-icon">
                <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <!-- Centang sukses (muncul setelah icon) -->
            <div id="popup-check" class="hidden mx-auto mb-5 w-20 h-20 rounded-full bg-emerald-50
                                          flex items-center justify-center ring-8 ring-emerald-100/60">
                <svg class="h-10 w-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Thank You! </h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-1">
                <span id="popup-email" class="font-bold text-blue-600"></span>
            </p>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                We will send exclusive offers and the latest promos straight to your inbox!
            </p>

            <!-- CTA -->
            <button
                onclick="closeNewsletterPopup()"
                class="block w-full text-sm text-gray-400 hover:text-gray-600 transition py-2 font-medium"
            >
                Close
            </button>
        </div>

    </div>
</div>

<script src="assets/js/user/newsletter.js"></script>

<?php include 'views/user/footer.php'; ?>