
function confirmCancel(bookingId) {
    const overlay  = document.getElementById('cancel-overlay');
    const input    = document.getElementById('cancel-booking-id');
    const noBtn    = document.getElementById('cancel-no');
    const backdrop = document.getElementById('cancel-backdrop');

    input.value = bookingId;
    overlay.classList.remove('hidden');

    function close() { overlay.classList.add('hidden'); }
    noBtn.onclick    = close;
    backdrop.onclick = close;

    document.addEventListener('keydown', function esc(e) {
        if (e.key === 'Escape') {
            close();
            document.removeEventListener('keydown', esc);
        }
    });
}

let _currentRating = 0;

const ratingLabels = {
    1: 'Sangat Buruk',
    2: 'Buruk',
    3: 'Cukup',
    4: 'Bagus',
    5: 'Sangat Bagus',
};

function openReviewModal(bookingId, carId, carName) {
    
    document.getElementById('review-booking-id').value = bookingId;
    document.getElementById('review-car-id').value     = carId;
    document.getElementById('review-car-name').textContent = carName;

    _currentRating = 0;
    document.getElementById('review-rating-val').value = 0;
    document.getElementById('rating-label').textContent = 'Pilih bintang';
    document.getElementById('rating-label').className = 'text-sm font-semibold text-gray-400 ml-2';
    document.getElementById('review-comment').value = '';
    document.getElementById('char-count').textContent = '0';
    document.getElementById('rating-error').classList.add('hidden');
    renderStars(0);

    document.getElementById('review-overlay').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReviewModal() {
    document.getElementById('review-overlay').classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', function () {
    const backdrop = document.getElementById('review-backdrop');
    if (backdrop) {
        backdrop.addEventListener('click', closeReviewModal);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeReviewModal();
    });

    const textarea = document.getElementById('review-comment');
    if (textarea) {
        textarea.addEventListener('input', function () {
            document.getElementById('char-count').textContent = this.value.length;
        });
    }

    const reviewForm = document.getElementById('review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            const rating = parseInt(document.getElementById('review-rating-val').value);
            if (!rating || rating < 1 || rating > 5) {
                e.preventDefault();
                const errEl = document.getElementById('rating-error');
                errEl.classList.remove('hidden');
                errEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            const submitBtn = document.getElementById('review-submit');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';
        });
    }

    ['toast-success', 'toast-error'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) {
            setTimeout(function () {
                el.style.transition = 'opacity .4s ease';
                el.style.opacity = '0';
                setTimeout(function () { el.remove(); }, 400);
            }, 4000);
        }
    });
});

function renderStars(filled) {
    document.querySelectorAll('.star-btn').forEach(function (btn) {
        const star = parseInt(btn.dataset.star);
        const icon = btn.querySelector('.star-icon');
        if (star <= filled) {
            icon.classList.remove('text-gray-300');
            icon.classList.add('text-amber-400');
            btn.classList.add('bg-amber-50', 'border-amber-300');
            btn.classList.remove('bg-gray-50', 'border-gray-200');
        } else {
            icon.classList.add('text-gray-300');
            icon.classList.remove('text-amber-400');
            btn.classList.remove('bg-amber-50', 'border-amber-300');
            btn.classList.add('bg-gray-50', 'border-gray-200');
        }
    });
}

function setRating(val) {
    _currentRating = val;
    document.getElementById('review-rating-val').value = val;
    document.getElementById('rating-label').textContent = ratingLabels[val] || '';
    document.getElementById('rating-label').className = 'text-sm font-bold text-amber-500 ml-2';
    document.getElementById('rating-error').classList.add('hidden');
    renderStars(val);
}

function hoverRating(val) {
    renderStars(val);
}

function resetHover() {
    renderStars(_currentRating);
}