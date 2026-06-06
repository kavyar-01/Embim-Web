<?php include 'views/user/header.php'; ?>

<?php
// Label metode pembayaran yang ditampilkan ke user
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
$isBank      = strpos($booking['payment_method'], 'transfer_bank') === 0;

$imgSrc = !empty($booking['car']['photo'])
    ? 'assets/images/' . htmlspecialchars($booking['car']['photo'])
    : 'assets/images/hrv-car.png';
?>

<main class="min-h-screen bg-slate-50 pt-24 pb-20">
<div class="max-w-xl mx-auto px-4 sm:px-6">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-400 pt-6 mb-8">
        <span class="text-gray-400">Booking Confirmation</span>
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 font-semibold">Upload Payment Proof</span>
    </nav>

    <!-- Step Indicator -->
    <div class="flex items-center gap-2 mb-8">
        <!-- Step 1 done -->
        <div class="flex items-center gap-2">
            <div class="h-7 w-7 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-emerald-600 hidden sm:inline">Booking Created</span>
        </div>
        <div class="flex-1 h-px bg-emerald-200 mx-1"></div>
        <!-- Step 2 active -->
        <div class="flex items-center gap-2">
            <div class="h-7 w-7 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-white">2</span>
            </div>
            <span class="text-xs font-semibold text-blue-600 hidden sm:inline">Upload Proof</span>
        </div>
        <div class="flex-1 h-px bg-gray-200 mx-1"></div>
        <!-- Step 3 pending -->
        <div class="flex items-center gap-2">
            <div class="h-7 w-7 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold text-gray-400">3</span>
            </div>
            <span class="text-xs font-semibold text-gray-400 hidden sm:inline">Completed</span>
        </div>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($errors)): ?>
    <div class="mb-5 bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
        <p class="text-sm font-bold text-red-700 mb-1">Please correct the following errors:</p>
        <ul class="space-y-1">
            <?php foreach ($errors as $err): ?>
            <li class="text-sm text-red-600">• <?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- Ringkasan Booking -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Order Summary</h2>
        <div class="flex items-center gap-3 pb-4 mb-4 border-b border-slate-100">
            <img src="<?php echo $imgSrc; ?>" alt="car"
                 class="w-16 h-12 object-cover rounded-xl flex-shrink-0 bg-gray-100"
                 onerror="this.src='assets/images/hrv-car.png'">
            <div>
                <p class="text-sm font-extrabold text-gray-900">
                    <?php echo htmlspecialchars($booking['car']['brand'] . ' ' . $booking['car']['model']); ?>
                </p>
                <p class="text-xs text-gray-400"><?php echo $booking['car']['year']; ?></p>
            </div>
        </div>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Booking No.</span>
                <span class="font-bold text-gray-400 italic">Created after payment</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Rental Period</span>
                <span class="font-semibold text-gray-800">
                    <?php echo date('d M Y', strtotime($booking['start_date'])); ?> –
                    <?php echo date('d M Y', strtotime($booking['end_date'])); ?>
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Payment Method</span>
                <span class="font-semibold text-gray-800"><?php echo $methodLabel; ?></span>
            </div>
            <div class="flex justify-between pt-2 border-t border-slate-100 mt-2">
                <span class="font-bold text-gray-900">Total</span>
                <span class="font-black text-blue-600 text-base">
                    Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Instruksi Transfer -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 mb-5">
        <p class="text-xs font-bold text-blue-700 mb-3 flex items-center gap-1.5">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Payment Instructions
        </p>
        <?php if ($isBank): ?>
        <div class="space-y-2 text-xs text-blue-700">
            <p>Transfer to the following account:</p>
            <div class="bg-white rounded-xl border border-blue-200 p-3 space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-gray-400">Bank</span>
                    <span class="font-bold"><?php echo $methodLabel; ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Account Number</span>
                    <span class="font-bold tracking-widest">1234-5678-9012</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Account Name</span>
                    <span class="font-bold">PT EMBIM Indonesia</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Amount</span>
                    <span class="font-black text-blue-600">Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="space-y-1 text-xs text-blue-700">
            <p>Make payment via <strong><?php echo $methodLabel; ?></strong> to:</p>
            <div class="bg-white rounded-xl border border-blue-200 p-3 space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-gray-400">Phone Number</span>
                    <span class="font-bold tracking-widest">0812-2144-7689</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Account Name</span>
                    <span class="font-bold">PT EMBIM Indonesia</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Amount</span>
                    <span class="font-black text-blue-600">Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Form Upload Bukti -->
    <form action="index.php?page=booking&step=upload-proof"
          method="POST"
          enctype="multipart/form-data"
          id="proof-form">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Upload Payment Proof</h2>
            <p class="text-xs text-gray-400 mb-4">Screenshot or photo of your transfer / payment receipt.</p>

            <!-- Drop Zone -->
            <label
                id="proof-box"
                for="payment_proof"
                class="relative flex flex-col items-center justify-center w-full h-44 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer bg-slate-50 hover:bg-blue-50/40 hover:border-blue-300 transition-all duration-200 overflow-hidden mb-3"
            >
                <div id="proof-placeholder" class="flex flex-col items-center gap-2 pointer-events-none">
                    <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm font-semibold text-gray-400">Click to upload proof</p>
                    <p class="text-xs text-gray-300">JPG, PNG, WebP · Max 5 MB</p>
                </div>
                <img id="proof-preview" src="" alt="Preview"
                     class="hidden absolute inset-0 w-full h-full object-contain rounded-2xl bg-slate-50 p-2">
                <input
                    type="file"
                    id="payment_proof"
                    name="payment_proof"
                    accept="image/jpeg,image/png,image/webp"
                    class="hidden"
                >
            </label>

            <p id="proof-error" class="hidden text-xs text-red-500 font-semibold mb-3">
                Payment proof is required.
            </p>

            <p class="text-xs text-gray-400 flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Ensure the amount and recipient name on the proof are clearly visible.
            </p>
        </div>

        <!-- Tombol -->
        <button
            type="submit"
            id="proof-submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-3.5 rounded-xl shadow-md shadow-blue-200/50 transition-all duration-200"
        >
            Confirm Payment
        </button>

    </form>

</div>
</main>

<script>
(function () {
    const input    = document.getElementById('payment_proof');
    const preview  = document.getElementById('proof-preview');
    const box      = document.getElementById('proof-box');
    const errEl    = document.getElementById('proof-error');
    const form     = document.getElementById('proof-form');
    const submitBtn= document.getElementById('proof-submit');

    if (input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                document.getElementById('proof-placeholder').classList.add('hidden');
                box.classList.remove('border-red-400', 'bg-red-50');
                if (errEl) errEl.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            if (!input || !input.files || input.files.length === 0) {
                e.preventDefault();
                box.classList.add('border-red-400', 'bg-red-50');
                if (errEl) {
                    errEl.classList.remove('hidden');
                    errEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            // Loading state
            submitBtn.disabled = true;
            submitBtn.textContent = 'Uploading...';
        });
    }
})();
</script>

<?php include 'views/user/footer.php'; ?>
