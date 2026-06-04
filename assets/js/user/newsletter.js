
function handleNewsletterSubmit(e) {
    e.preventDefault();

    const email = document.getElementById('newsletter-email').value.trim();
    if (!email) return;

    document.getElementById('popup-email').textContent = email;

    openNewsletterPopup();

    setTimeout(() => {
        document.getElementById('popup-icon').classList.add('hidden');
        const check = document.getElementById('popup-check');
        check.classList.remove('hidden');
        check.style.transform = 'scale(0)';
        check.style.transition = 'transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                check.style.transform = 'scale(1)';
            });
        });
    }, 600);

    document.getElementById('newsletter-form').reset();
}

function openNewsletterPopup() {
    const popup    = document.getElementById('newsletter-popup');
    const backdrop = document.getElementById('newsletter-backdrop');
    const modal    = document.getElementById('newsletter-modal');

    document.getElementById('popup-icon').classList.remove('hidden');
    document.getElementById('popup-check').classList.add('hidden');

    popup.classList.remove('hidden');

    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            backdrop.style.opacity = '1';
            modal.style.opacity    = '1';
            modal.style.transform  = 'translateY(0)';
        });
    });

    document.getElementById('newsletter-backdrop').onclick = closeNewsletterPopup;

    document.addEventListener('keydown', handleEscKey);
}

function closeNewsletterPopup() {
    const popup    = document.getElementById('newsletter-popup');
    const backdrop = document.getElementById('newsletter-backdrop');
    const modal    = document.getElementById('newsletter-modal');

    backdrop.style.opacity = '0';
    modal.style.opacity    = '0';
    modal.style.transform  = 'translateY(16px)';

    setTimeout(() => {
        popup.classList.add('hidden');
        modal.style.transform = 'translateY(32px)';
    }, 300);

    document.removeEventListener('keydown', handleEscKey);
}

function handleEscKey(e) {
    if (e.key === 'Escape') closeNewsletterPopup();
}
