<?php include 'views/user/header.php'; ?>

<?php

$methodLabels = [
    'gopay'                 => 'GoPay',
    'ovo'                   => 'OVO',
    'dana'                  => 'DANA',
    'shopeepay'             => 'ShopeePay',
    'qris'                  => 'QRIS',
    'transfer_bank_bca'     => 'Bank Transfer BCA',
    'transfer_bank_mandiri' => 'Bank Transfer Mandiri',
    'transfer_bank_bni'     => 'Bank Transfer BNI',
    'transfer_bank_bri'     => 'Bank Transfer BRI',
    'transfer_bank_seabank' => 'Bank Transfer Seabank',

];
$methodLabel = $methodLabels[$booking['payment_method']] ?? ucfirst($booking['payment_method']);
?>

<main class="min-h-screen bg-slate-50 pt-24 pb-20 flex items-center">
<div class="max-w-lg mx-auto px-4 w-full">

    <div class="flex items-center gap-2 mb-8">
        <div class="flex items-center gap-2">
            <div class="h-7 w-7 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 hidden sm:inline">Booking Created</span>
        </div>
        <div class="flex-1 h-px bg-emerald-200 mx-1"></div>
        <div class="flex items-center gap-2">
            <div class="h-7 w-7 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 hidden sm:inline">Proof Uploaded</span>
        </div>
        <div class="flex-1 h-px bg-emerald-200 mx-1"></div>
        <div class="flex items-center gap-2">
            <div class="h-7 w-7 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 hidden sm:inline">Completed</span>
        </div>
    </div>

    <!-- Success Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-center pt-10 pb-8 px-8">

        <!-- Icon -->
        <div class="mx-auto mb-6 h-20 w-20 rounded-full flex items-center justify-center bg-emerald-50">
            <svg class="h-10 w-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Payment successful!</h1>
        <p class="text-black-400 text-sm mb-1" > Vehicle is ready to be picked up!</p>
        <p class="text-gray-400 text-sm mb-1">
            Booking Number: <span class="font-bold text-gray-700">#<?php echo str_pad($booking['id'], 4, '0', STR_PAD_LEFT); ?></span>
        </p>

        <!-- Ringkasan Booking -->
        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5 text-left mb-6 space-y-3">
            <?php
            $imgSrc = !empty($booking['photo'])
                ? 'assets/images/' . htmlspecialchars($booking['photo'])
                : 'assets/images/hrv-car.png';
            ?>
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <img src="<?php echo $imgSrc; ?>" alt="car"
                     class="w-16 h-12 object-cover rounded-xl flex-shrink-0 bg-gray-100"
                     onerror="this.src='assets/images/hrv-car.png'">
                <div>
                    <p class="text-sm font-extrabold text-gray-900">
                        <?php echo htmlspecialchars($booking['brand'] . ' ' . $booking['model']); ?>
                    </p>
                    <p class="text-xs text-gray-400"><?php echo $booking['year']; ?></p>
                </div>
            </div>

            <?php
            $rows = [
                ['Pickup Date',  date('d F Y', strtotime($booking['start_date']))],
                ['Return Date', date('d F Y', strtotime($booking['end_date']))],
                ['Duration',          $booking['total_days'] . ' days'],
                ['Payment Method',    $methodLabel],
            ];
            foreach ($rows as [$label, $value]): ?>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400"><?php echo $label; ?></span>
                <span class="font-semibold text-gray-800"><?php echo $value; ?></span>
            </div>
            <?php endforeach; ?>

            <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                <span class="text-sm font-bold text-gray-900">Total Payment</span>
                <span class="text-lg font-black text-blue-600">
                    Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?>
                </span>
            </div>
        </div>

        <!-- CTA Buttons -->
        <div class="space-y-3">
            <a href="index.php?page=receipt&id=<?php echo $booking['id']; ?>" target="_blank"
               class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm py-3.5 rounded-xl shadow-md shadow-emerald-200/50 transition-all duration-200">
                Download Receipt
            </a>
            <a href="index.php?page=bookings"
               class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3.5 rounded-xl shadow-md shadow-blue-200/50 transition-all duration-200">
                View My Bookings
            </a>
            <a href="index.php"
               class="block w-full border-2 border-gray-200 hover:border-blue-300 text-gray-600 hover:text-blue-600 font-semibold text-sm py-3 rounded-xl transition-all duration-200">
                Back to Home
            </a>
        </div>

    </div>

</div>
</main>

<?php include 'views/user/footer.php'; ?>