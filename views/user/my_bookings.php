<?php include 'views/user/header.php'; ?>

<main class="min-h-screen bg-slate-50 pt-24 pb-20">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Page Header -->
    <div class="pt-6 mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900">My Bookings</h1>
        <p class="text-sm text-gray-400 mt-1">Your vehicle rental history and status</p>
    </div>

    <?php
    // Konfigurasi warna & label status
    $statusCfg = [
        'confirmed' => ['label' => 'Confirmed',  'bg' => 'bg-blue-50',    'text' => 'text-blue-600',   'border' => 'border-blue-200',   'dot' => 'bg-blue-500'],
        'ongoing'   => ['label' => 'Ongoing',    'bg' => 'bg-violet-50',  'text' => 'text-violet-600', 'border' => 'border-violet-200', 'dot' => 'bg-violet-500'],
        'completed' => ['label' => 'Completed',  'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600','border' => 'border-emerald-200','dot' => 'bg-emerald-500'],
        'cancelled' => ['label' => 'Cancelled',  'bg' => 'bg-red-50',     'text' => 'text-red-500',    'border' => 'border-red-200',    'dot' => 'bg-red-400'],
    ];

    $paymentCfg = [
        'paid'     => ['label' => 'Paid',    'class' => 'text-emerald-600 bg-emerald-50'],
        'unpaid'   => ['label' => 'Unpaid', 'class' => 'text-amber-600 bg-amber-50'],
        'refunded' => ['label' => 'Refunded', 'class' => 'text-blue-600 bg-blue-50'],
    ];

    $tabs = [
        'all'       => 'All',
        'confirmed' => 'Confirmed',
        'ongoing'   => 'Ongoing',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];
    ?>

    <!-- ── Toast Notifikasi Review ── -->
    <?php if (!empty($_SESSION['review_success'])): ?>
    <div id="toast-success" class="fixed top-6 right-4 z-[9999] flex items-center gap-3 bg-emerald-600 text-white text-sm font-semibold px-5 py-3.5 rounded-2xl shadow-xl animate-fade-in">
        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <?php echo htmlspecialchars($_SESSION['review_success']); unset($_SESSION['review_success']); ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['booking_success'])): ?>
    <div id="toast-booking-success" class="fixed top-6 right-4 z-[9999] flex items-center gap-3 bg-emerald-600 text-white text-sm font-semibold px-5 py-3.5 rounded-2xl shadow-xl animate-fade-in">
        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <?php echo htmlspecialchars($_SESSION['booking_success']); unset($_SESSION['booking_success']); ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['review_error'])): ?>
    <div id="toast-error" class="fixed top-6 right-4 z-[9999] flex items-center gap-3 bg-red-500 text-white text-sm font-semibold px-5 py-3.5 rounded-2xl shadow-xl animate-fade-in">
        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <?php echo htmlspecialchars($_SESSION['review_error']); unset($_SESSION['review_error']); ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['booking_error'])): ?>
    <div id="toast-booking-error" class="fixed top-6 right-4 z-[9999] flex items-center gap-3 bg-red-500 text-white text-sm font-semibold px-5 py-3.5 rounded-2xl shadow-xl animate-fade-in">
        <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <?php echo htmlspecialchars($_SESSION['booking_error']); unset($_SESSION['booking_error']); ?>
    </div>
    <?php endif; ?>

    <!-- ── Filter Tabs ── -->
    <div class="flex gap-2 overflow-x-auto pb-1 mb-6 scrollbar-hide">
        <?php foreach ($tabs as $key => $label):
            $isActive = $filterStatus === $key;
            $count    = $counts[$key];
        ?>
        <a
            href="index.php?page=bookings&status=<?php echo $key; ?>"
            class="flex-shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-200
                   <?php echo $isActive
                       ? 'bg-blue-600 text-white shadow-md shadow-blue-200/50'
                       : 'bg-white text-gray-500 border border-gray-200 hover:border-blue-300 hover:text-blue-600'; ?>"
        >
            <?php echo $label; ?>
            <span class="text-xs px-1.5 py-0.5 rounded-full font-bold
                         <?php echo $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500'; ?>">
                <?php echo $count; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- ── Booking List ── -->
    <?php if (!empty($bookings)): ?>

    <div class="space-y-4">
        <?php foreach ($bookings as $booking):
            $st  = $statusCfg[$booking['status']]  ?? $statusCfg['confirmed'];
            $pay = $paymentCfg[$booking['payment_status']] ?? $paymentCfg['unpaid'];
            $imgSrc = 'assets/images/' . htmlspecialchars($booking['car_photo']);
            $startDate = date('d M Y', strtotime($booking['start_date']));
            $endDate   = date('d M Y', strtotime($booking['end_date']));
        ?>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:border-gray-200 transition-all duration-200">

            <!-- Card Top -->
            <div class="flex flex-col sm:flex-row gap-0">

                <!-- Car Image -->
                <div class="sm:w-44 h-40 sm:h-auto flex-shrink-0 bg-gray-100 overflow-hidden">
                    <img
                        src="<?php echo $imgSrc; ?>"
                        alt="<?php echo htmlspecialchars($booking['car_brand'] . ' ' . $booking['car_model']); ?>"
                        class="w-full h-full object-cover"
                        onerror="this.src='assets/images/hrv-car.png'"
                    >
                </div>

                <!-- Booking Info -->
                <div class="flex-1 p-5">
                    <div class="flex items-start justify-between gap-3 mb-3">

                        <!-- Car Name & Booking ID -->
                        <div>
                            <p class="text-xs text-gray-400 font-medium mb-0.5">Booking #<?php echo str_pad($booking['id'], 4, '0', STR_PAD_LEFT); ?></p>
                            <h3 class="text-base font-extrabold text-gray-900">
                                <?php echo htmlspecialchars($booking['car_brand'] . ' ' . $booking['car_model']); ?>
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5"><?php echo $booking['car_year']; ?></p>
                        </div>

                        <!-- Status Badge -->
                        <span class="flex-shrink-0 inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full border
                                     <?php echo $st['bg'] . ' ' . $st['text'] . ' ' . $st['border']; ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?php echo $st['dot']; ?>"></span>
                            <?php echo $st['label']; ?>
                        </span>
                        <?php if (($booking['fine_status'] ?? '') === 'unpaid'): ?>
                        <span class="flex-shrink-0 inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full border bg-red-50 text-red-600 border-red-200">
                            Fine: Rp <?php echo number_format($booking['fine_amount'] ?? 0, 0, ',', '.'); ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Start Date</p>
                            <p class="text-sm font-semibold text-gray-700"><?php echo $startDate; ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">End Date</p>
                            <p class="text-sm font-semibold text-gray-700"><?php echo $endDate; ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Duration</p>
                            <p class="text-sm font-semibold text-gray-700"><?php echo $booking['total_days']; ?> Days</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Payment</p>
                            <span class="inline-block text-xs font-bold px-2 py-0.5 rounded-full <?php echo $pay['class']; ?>">
                                <?php echo $pay['label']; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Footer: Total + Actions -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 gap-3">
                        <div>
                            <p class="text-xs text-gray-400 font-medium">Total Payment</p>
                            <p class="text-lg font-black text-blue-600">
                                Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?>
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2">

                            <?php if ($booking['status'] === 'confirmed'): ?>
                            <a href="index.php?page=receipt&id=<?php echo $booking['id']; ?>" target="_blank"
                               class="text-xs font-bold border border-green-200 text-green-600 hover:bg-green-50 px-4 py-2 rounded-xl transition duration-200 shadow-sm mr-2">
                                Download Receipt
                            </a>
                            <button onclick="confirmCancel(<?php echo $booking['id']; ?>)"
                               class="text-xs font-bold border border-red-200 text-red-500 hover:bg-red-50 px-4 py-2 rounded-xl transition duration-200 shadow-sm mr-2">
                                Cancel Booking
                            </button>
                            <a href="index.php?page=car-detail&id=<?php echo $booking['car_id']; ?>"
                               class="text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition duration-200 shadow-sm">
                                View Details
                            </a>

                            <?php elseif ($booking['status'] === 'ongoing'): ?>
                            <div class="flex items-center gap-2">
                                <a href="index.php?page=receipt&id=<?php echo $booking['id']; ?>" target="_blank"
                                   class="text-xs font-bold border border-green-200 text-green-600 hover:bg-green-50 px-4 py-2 rounded-xl transition duration-200 shadow-sm">
                                    Download Receipt
                                </a>
                                <span class="text-xs font-semibold text-violet-600 bg-violet-50 border border-violet-200 px-4 py-2 rounded-xl">
                                    Ongoing
                                </span>
                            </div>

                            <?php elseif ($booking['status'] === 'completed'): ?>
                            <div class="flex items-center gap-2">
                                <a href="index.php?page=receipt&id=<?php echo $booking['id']; ?>" target="_blank"
                                   class="text-xs font-bold border border-green-200 text-green-600 hover:bg-green-50 px-4 py-2 rounded-xl transition duration-200 shadow-sm">
                                    Download Receipt
                                </a>
                                <?php if (!$booking['has_review']): ?>
                                <button
                                    onclick="openReviewModal(<?php echo $booking['id']; ?>, <?php echo $booking['car_id']; ?>, '<?php echo htmlspecialchars(addslashes($booking['car_brand'] . ' ' . $booking['car_model']), ENT_QUOTES); ?>')"
                                    class="text-xs font-bold border border-amber-300 text-amber-600 hover:bg-amber-50 px-4 py-2 rounded-xl transition duration-200 flex items-center gap-1.5"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    Give Review
                                </button>
                                <?php else: ?>
                                <div class="flex items-center gap-1 text-xs font-semibold text-amber-500 bg-amber-50 border border-amber-200 px-3 py-2 rounded-xl">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <svg class="h-3.5 w-3.5 <?php echo $s <= $booking['review_rating'] ? 'text-amber-400' : 'text-gray-200'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <?php endfor; ?>
                                    <span class="ml-1 text-gray-500">Reviewed</span>
                                </div>
                                <?php endif; ?>
                                <a href="index.php?page=car-detail&id=<?php echo $booking['car_id']; ?>"
                                   class="text-xs font-bold border border-blue-200 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-xl transition duration-200">
                                    Rent Again
                                </a>
                            </div>

                            <?php elseif ($booking['status'] === 'cancelled'): ?>
                            <span class="text-xs font-semibold text-red-400 bg-red-50 border border-red-200 px-4 py-2 rounded-xl">
                                Cancelled
                            </span>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes (jika ada) -->
            <?php if (!empty($booking['notes'])): ?>
            <div class="px-5 py-3 bg-amber-50/60 border-t border-amber-100 flex items-start gap-2">
                <svg class="h-4 w-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-amber-700 font-medium"><?php echo htmlspecialchars($booking['notes']); ?></p>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
    </div>

    <?php else: ?>

    <!-- ── Empty State ── -->
    <div class="flex flex-col items-center justify-center py-24 text-center bg-white rounded-2xl border border-gray-100">
        <div class="h-20 w-20 rounded-full bg-blue-50 flex items-center justify-center mb-5">
            <svg class="h-10 w-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-700 mb-2">No bookings yet</h3>
        <p class="text-sm text-gray-400 mb-6 max-w-xs">
            <?php echo $filterStatus !== 'all'
                ? 'No bookings with status "' . ucfirst($filterStatus) . '".'
                : 'You have never made a vehicle reservation.'; ?>
        </p>
        <a href="index.php?page=cars"
           class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition duration-200 shadow-sm">
            Browse Vehicles
        </a>
    </div>

    <?php endif; ?>

</div>
</main>

<!-- Modal Konfirmasi Batalkan -->
<div id="cancel-overlay" class="hidden fixed inset-0 z-[999] flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" id="cancel-backdrop"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-7 text-center"
         style="animation: fadeUp .25s ease both">
        <div class="mx-auto mb-4 h-14 w-14 rounded-full bg-red-50 flex items-center justify-center">
            <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-1">Cancel Booking?</h3>
        <p class="text-sm text-gray-500 mb-3">Cancelled bookings cannot be reversed. Are you sure you want to proceed?</p>
        <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 mb-6">
            <p class="text-xs text-amber-700 font-semibold mb-1"> Refund Warning</p>
            <p class="text-[11px] text-amber-600 leading-tight">If cancelled, the refunded amount is only <strong>80%</strong> of the total rental price.</p>
        </div>
        <form id="cancel-form" method="POST" action="index.php?page=bookings">
            <input type="hidden" name="action" value="cancel_booking">
            <input type="hidden" name="booking_id" id="cancel-booking-id" value="">
            <div class="flex gap-3">
                <button type="button" id="cancel-no"
                    class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition duration-200">
                    No
                </button>
                <button type="submit" id="cancel-yes"
                    class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition duration-200 text-center">
                    Yes, Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     Modal Form Ulasan
     ══════════════════════════════════════════════════════ -->
<div id="review-overlay" class="hidden fixed inset-0 z-[9999] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="review-backdrop"></div>

    <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md mx-auto overflow-hidden"
         style="animation: fadeUp .25s ease both">

        <!-- Header Modal -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <div>
                <h3 class="text-base font-extrabold text-gray-900">Give Review</h3>
                <p id="review-car-name" class="text-xs text-gray-400 mt-0.5"></p>
            </div>
            <button onclick="closeReviewModal()"
                    class="h-8 w-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form action="index.php?page=bookings&status=completed" method="POST" id="review-form">
            <input type="hidden" name="action"     value="submit_review">
            <input type="hidden" name="booking_id" id="review-booking-id" value="">
            <input type="hidden" name="car_id"     id="review-car-id"     value="">
            <input type="hidden" name="rating"     id="review-rating-val" value="0">

            <div class="px-6 py-5 space-y-5">

                <!-- Star Rating -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Rating</label>
                    <div class="flex items-center gap-2" id="star-container">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button"
                                data-star="<?php echo $i; ?>"
                                onclick="setRating(<?php echo $i; ?>)"
                                onmouseover="hoverRating(<?php echo $i; ?>)"
                                onmouseout="resetHover()"
                                class="star-btn h-10 w-10 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center transition-all duration-150 hover:bg-amber-50 hover:border-amber-300">
                            <svg class="h-6 w-6 text-gray-300 star-icon transition-colors duration-150" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                        <?php endfor; ?>
                        <span id="rating-label" class="text-sm font-semibold text-gray-400 ml-2">Select stars</span>
                    </div>
                    <p id="rating-error" class="hidden text-xs text-red-500 font-semibold mt-2">⚠ Rating must be selected.</p>
                </div>

                <!-- Komentar -->
                <div>
                    <label for="review-comment" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                        Comment <span class="text-gray-300 font-normal">(optional)</span>
                    </label>
                    <textarea
                        id="review-comment"
                        name="comment"
                        rows="4"
                        maxlength="500"
                        placeholder="Tell us about your experience renting this vehicle..."
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400 transition resize-none"
                    ></textarea>
                    <p class="text-xs text-gray-300 text-right mt-1"><span id="char-count">0</span>/500</p>
                </div>

            </div>

            <!-- Footer Modal -->
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" onclick="closeReviewModal()"
                        class="flex-1 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" id="review-submit"
                        class="flex-1 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold shadow-md shadow-amber-200/50 transition">
                    Submit Review
                </button>
            </div>
        </form>
    </div>
</div>

<script src="assets/js/user/my_bookings.js"></script>

<?php include 'views/user/footer.php'; ?>
