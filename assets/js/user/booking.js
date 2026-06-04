

(function () {

    const selfieInput   = document.getElementById('selfie_ktp');
    const selfiePreview = document.getElementById('selfie-preview');
    const selfieBox     = document.getElementById('selfie-box');

    if (selfieInput) {
        selfieInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                selfiePreview.src = e.target.result;
                selfiePreview.classList.remove('hidden');
                selfieBox.classList.add('has-image');
            };
            reader.readAsDataURL(file);
        });
    }

    const startInput  = document.getElementById('start_date_display');
    const endInput    = document.getElementById('end_date_display');
    const durationEl  = document.getElementById('summary-duration');
    const totalEl     = document.getElementById('summary-total');
    const pricePerDay = parseInt(document.getElementById('price_per_day')?.value || '0');

    function recalculate() {
        if (!startInput || !endInput) return;
        const start = new Date(startInput.value);
        const end   = new Date(endInput.value);
        if (!startInput.value || !endInput.value || end <= start) return;

        const days  = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        const total = days * pricePerDay;

        if (durationEl) durationEl.textContent = days + ' hari';
        if (totalEl)    totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');

        const hiddenDays  = document.getElementById('total_days_hidden');
        const hiddenTotal = document.getElementById('total_price_hidden');
        if (hiddenDays)  hiddenDays.value  = days;
        if (hiddenTotal) hiddenTotal.value = total;
    }

    if (startInput) startInput.addEventListener('change', recalculate);
    if (endInput)   endInput.addEventListener('change', recalculate);

    const paymentCards = document.querySelectorAll('.payment-card');
    paymentCards.forEach(function (card) {
        card.addEventListener('click', function () {
            paymentCards.forEach(function (c) {
                c.classList.remove('border-blue-500', 'bg-blue-50', 'shadow-md');
                c.classList.add('border-gray-200');
                const radio = c.querySelector('input[type="radio"]');
                if (radio) radio.checked = false;
            });
            this.classList.remove('border-gray-200');
            this.classList.add('border-blue-500', 'bg-blue-50', 'shadow-md');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        });
    });

    const bookingForm  = document.getElementById('booking-form');
    const selfieError  = document.getElementById('selfie-error');
    const paymentError = document.getElementById('payment-error');

    if (bookingForm) {
        bookingForm.addEventListener('submit', function (e) {
            let valid = true;

            if (!selfieInput || !selfieInput.files || selfieInput.files.length === 0) {
                e.preventDefault();
                valid = false;
                if (selfieError) {
                    selfieError.classList.remove('hidden');
                    selfieError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                if (selfieBox) selfieBox.classList.add('border-red-400', 'bg-red-50');
            } else {
                if (selfieError) selfieError.classList.add('hidden');
                if (selfieBox)   selfieBox.classList.remove('border-red-400', 'bg-red-50');
            }

            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPayment) {
                e.preventDefault();
                valid = false;
                if (paymentError) {
                    paymentError.classList.remove('hidden');
                    if (valid === false && selfieInput && selfieInput.files.length > 0) {
                        paymentError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            } else {
                if (paymentError) paymentError.classList.add('hidden');
            }
        });
    }

    if (selfieInput) {
        selfieInput.addEventListener('change', function () {
            if (selfieBox)   selfieBox.classList.remove('border-red-400', 'bg-red-50');
            if (selfieError) selfieError.classList.add('hidden');
        });
    }

    paymentCards.forEach(function (card) {
        card.addEventListener('click', function () {
            if (paymentError) paymentError.classList.add('hidden');
        });
    });

    window.confirmCancelBooking = function () {
        const overlay  = document.getElementById('cancel-overlay');
        const noBtn    = document.getElementById('cancel-no');
        const backdrop = document.getElementById('cancel-backdrop');
        if (!overlay) return;

        overlay.classList.remove('hidden');

        function closeModal() { overlay.classList.add('hidden'); }
        noBtn.onclick    = closeModal;
        backdrop.onclick = closeModal;

        document.addEventListener('keydown', function esc(e) {
            if (e.key === 'Escape') {
                closeModal();
                document.removeEventListener('keydown', esc);
            }
        });
    };

})();