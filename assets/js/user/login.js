

(function () {

    const pwInput   = document.getElementById('password');
    const pwToggle  = document.getElementById('toggle-password');
    const iconEye   = document.getElementById('icon-eye');
    const iconOff   = document.getElementById('icon-eye-off');

    if (pwToggle && pwInput) {
        pwToggle.addEventListener('click', function () {
            const isHidden = pwInput.type === 'password';
            pwInput.type   = isHidden ? 'text' : 'password';
            iconEye.classList.toggle('hidden',  isHidden);
            iconOff.classList.toggle('hidden', !isHidden);
            pwInput.focus();
        });
    }

    const form       = document.getElementById('login-form');
    const emailInput = document.getElementById('email');
    const submitBtn  = document.getElementById('submit-btn');

    if (form) {
        form.addEventListener('submit', function (e) {
            let valid = true;

            const emailVal = emailInput.value.trim();
            const emailRe  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailVal || !emailRe.test(emailVal)) {
                setError(emailInput, 'Masukkan alamat email yang valid.');
                valid = false;
            } else {
                clearError(emailInput);
            }

            if (!pwInput.value) {
                setError(pwInput, 'Password cannot be empty.');
                valid = false;
            } else {
                clearError(pwInput);
            }

            if (!valid) { e.preventDefault(); return; }

            submitBtn.disabled    = true;
            submitBtn.textContent = 'Memproses...';
        });
    }

    [emailInput, pwInput].forEach(function (input) {
        if (!input) return;
        input.addEventListener('input', function () { clearError(this); });
    });

    function setError(inputEl, message) {
        inputEl.classList.add('is-error', 'shake');
        inputEl.addEventListener('animationend', () => inputEl.classList.remove('shake'), { once: true });
        let errEl = inputEl.parentElement.querySelector('.field-error');
        if (!errEl) {
            errEl = document.createElement('p');
            errEl.className = 'field-error text-xs text-red-500 mt-1 ml-1';
            inputEl.parentElement.appendChild(errEl);
        }
        errEl.textContent = message;
    }

    function clearError(inputEl) {
        inputEl.classList.remove('is-error');
        const errEl = inputEl.parentElement.querySelector('.field-error');
        if (errEl) errEl.remove();
    }

})();




function openWhatsappModal() {
    const overlay  = document.getElementById('wa-overlay');
    const modal    = document.getElementById('wa-modal');
    const backdrop = document.getElementById('wa-backdrop');

    document.getElementById('wa-phone').value = '';
    setWaAlert('', '');
    resetWaBtn();

    overlay.classList.remove('hidden');
    requestAnimationFrame(() => requestAnimationFrame(() => {
        backdrop.style.opacity = '1';
        modal.style.opacity    = '1';
        modal.style.transform  = 'translateY(0)';
    }));

    backdrop.onclick = closeWhatsappModal;
    document.addEventListener('keydown', waEscHandler);

    const phoneInput = document.getElementById('wa-phone');
    phoneInput.oninput = () => { phoneInput.value = phoneInput.value.replace(/[^0-9]/g, ''); };
    phoneInput.onkeydown = (e) => {
        const allowed = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'];
        if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) e.preventDefault();
    };
    phoneInput.onkeyup = (e) => {
        if (e.key === 'Enter') submitWhatsappLogin();
    };

    setTimeout(() => phoneInput.focus(), 300);
}

function closeWhatsappModal() {
    const overlay  = document.getElementById('wa-overlay');
    const modal    = document.getElementById('wa-modal');
    const backdrop = document.getElementById('wa-backdrop');

    backdrop.style.opacity = '0';
    modal.style.opacity    = '0';
    modal.style.transform  = 'translateY(24px)';
    setTimeout(() => overlay.classList.add('hidden'), 300);
    document.removeEventListener('keydown', waEscHandler);
}

function waEscHandler(e) {
    if (e.key === 'Escape') closeWhatsappModal();
}

function setWaAlert(type, message) {
    const el = document.getElementById('wa-alert');
    el.className = 'px-4 py-3 rounded-xl text-sm font-semibold border';
    if (!message) { el.classList.add('hidden'); return; }
    el.classList.remove('hidden');
    if (type === 'error') {
        el.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
    } else {
        el.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
    }
    el.textContent = message;
}

function resetWaBtn() {
    const btn = document.getElementById('wa-btn');
    btn.disabled = false;
    btn.innerHTML = `
        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        Masuk Sekarang`;
}

function submitWhatsappLogin() {
    const phone = document.getElementById('wa-phone').value.trim();
    const btn   = document.getElementById('wa-btn');

    if (!phone) {
        setWaAlert('error', 'Phone number is required.');
        return;
    }
    if (phone.length < 7 || phone.length > 13) {
        setWaAlert('error', 'Invalid phone number (7-13 digits).');
        return;
    }

    btn.disabled    = true;
    btn.textContent = 'Memverifikasi...';
    setWaAlert('', '');

    const formData = new FormData();
    formData.append('phone', phone);

    fetch('index.php?page=login-by-phone', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                setWaAlert('success', '✓ ' + data.message);
                setTimeout(() => { window.location.href = data.redirect; }, 1000);
            } else {
                setWaAlert('error', data.message);
                resetWaBtn();
            }
        })
        .catch(() => {
            setWaAlert('error', 'An error occurred. Please try again.');
            resetWaBtn();
        });
}




function openForgotModal() {
    const overlay  = document.getElementById('forgot-overlay');
    const modal    = document.getElementById('forgot-modal');
    const backdrop = document.getElementById('forgot-backdrop');

    showStep('verify');
    document.getElementById('verify-name').value  = '';
    document.getElementById('verify-phone').value = '';
    setAlert('verify-alert', '', '');

    overlay.classList.remove('hidden');
    requestAnimationFrame(() => requestAnimationFrame(() => {
        backdrop.style.opacity = '1';
        modal.style.opacity    = '1';
        modal.style.transform  = 'translateY(0)';
    }));

    backdrop.onclick = closeForgotModal;
    document.addEventListener('keydown', forgotEscHandler);

    const phoneInput = document.getElementById('verify-phone');
    phoneInput.oninput = function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    };
    phoneInput.onkeydown = function(e) {
        const allowed = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'];
        if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) e.preventDefault();
    };
}

function closeForgotModal() {
    const overlay  = document.getElementById('forgot-overlay');
    const modal    = document.getElementById('forgot-modal');
    const backdrop = document.getElementById('forgot-backdrop');

    backdrop.style.opacity = '0';
    modal.style.opacity    = '0';
    modal.style.transform  = 'translateY(24px)';
    setTimeout(() => overlay.classList.add('hidden'), 300);
    document.removeEventListener('keydown', forgotEscHandler);
}

function forgotEscHandler(e) {
    if (e.key === 'Escape') closeForgotModal();
}

function showStep(step) {
    document.getElementById('step-verify').classList.toggle('hidden', step !== 'verify');
    document.getElementById('step-reset').classList.toggle('hidden',  step !== 'reset');
}

function setAlert(id, type, message) {
    const el = document.getElementById(id);
    el.className = 'px-4 py-3 rounded-xl text-sm font-semibold border';
    if (!message) { el.classList.add('hidden'); return; }
    el.classList.remove('hidden');
    if (type === 'error') {
        el.classList.add('bg-red-50', 'border-red-200', 'text-red-700');
    } else {
        el.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-700');
    }
    el.textContent = message;
}

function verifyIdentity() {
    const name  = document.getElementById('verify-name').value.trim();
    const phone = document.getElementById('verify-phone').value.trim();
    const btn   = document.getElementById('verify-btn');

    if (!name || !phone) {
        setAlert('verify-alert', 'error', 'Full name and phone number are required.');
        return;
    }

    btn.disabled    = true;
    btn.textContent = 'Memverifikasi...';
    setAlert('verify-alert', '', '');

    const formData = new FormData();
    formData.append('full_name', name);
    formData.append('phone',     phone);

    fetch('index.php?page=forgot-verify', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            btn.disabled    = false;
            btn.textContent = 'Verifikasi Akun';

            if (data.success) {
                document.getElementById('reset-user-id').value = data.user_id;
                document.getElementById('reset-password').value = '';
                document.getElementById('reset-confirm').value  = '';
                setAlert('reset-alert', '', '');
                resetRules();
                showStep('reset');
            } else {
                setAlert('verify-alert', 'error', data.message);
            }
        })
        .catch(() => {
            btn.disabled    = false;
            btn.textContent = 'Verifikasi Akun';
            setAlert('verify-alert', 'error', 'An error occurred. Please try again.');
        });
}

function submitReset() {
    const userId   = document.getElementById('reset-user-id').value;
    const password = document.getElementById('reset-password').value;
    const confirm  = document.getElementById('reset-confirm').value;
    const btn      = document.getElementById('reset-btn');

    const valid = checkRules(password, confirm);
    if (!valid) {
        setAlert('reset-alert', 'error', 'Pastikan semua persyaratan password terpenuhi.');
        return;
    }

    btn.disabled    = true;
    btn.textContent = 'Menyimpan...';
    setAlert('reset-alert', '', '');

    const formData = new FormData();
    formData.append('user_id',  userId);
    formData.append('password', password);
    formData.append('confirm',  confirm);

    fetch('index.php?page=forgot-reset', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                setAlert('reset-alert', 'success', '✓ ' + data.message);
                btn.disabled    = true;
                btn.textContent = 'Password Tersimpan';
                setTimeout(closeForgotModal, 2000);
            } else {
                setAlert('reset-alert', 'error', data.message);
                btn.disabled    = false;
                btn.textContent = 'Simpan Password Baru';
            }
        })
        .catch(() => {
            btn.disabled    = false;
            btn.textContent = 'Simpan Password Baru';
            setAlert('reset-alert', 'error', 'An error occurred. Please try again.');
        });
}

function checkRules(pw, confirm) {
    const rules = {
        'rr-len':   pw.length >= 8 && pw.length <= 100,
        'rr-lower': /[a-z]/.test(pw),
        'rr-upper': /[A-Z]/.test(pw),
        'rr-digit': /[0-9]/.test(pw),
        'rr-match': pw.length > 0 && pw === confirm,
    };

    let allPassed = true;
    Object.entries(rules).forEach(([id, passed]) => {
        const el   = document.getElementById(id);
        if (!el) return;
        const icon = el.querySelector('.rule-icon');
        if (passed) {
            el.classList.replace('text-gray-400', 'text-emerald-600');
            if (icon) icon.setAttribute('stroke', '#10b981');
        } else {
            el.classList.replace('text-emerald-600', 'text-gray-400');
            if (icon) icon.setAttribute('stroke', 'currentColor');
            allPassed = false;
        }
    });
    return allPassed;
}

function resetRules() {
    ['rr-len','rr-lower','rr-upper','rr-digit','rr-match'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('text-emerald-600');
        el.classList.add('text-gray-400');
        const icon = el.querySelector('.rule-icon');
        if (icon) icon.setAttribute('stroke', 'currentColor');
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const pw      = document.getElementById('reset-password');
    const confirm = document.getElementById('reset-confirm');
    if (pw)      pw.addEventListener('input',      () => checkRules(pw.value, confirm ? confirm.value : ''));
    if (confirm) confirm.addEventListener('input', () => checkRules(pw ? pw.value : '', confirm.value));
});

function toggleResetPw(inputId, iconId) {
    const input  = document.getElementById(inputId);
    const icon   = document.getElementById(iconId);
    const isHide = input.type === 'password';
    input.type   = isHide ? 'text' : 'password';

    if (isHide) {
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}