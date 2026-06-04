(function () {

    const pwInput   = document.getElementById('password');
    const toggleBtn = document.getElementById('toggle-password');
    const iconEye   = document.getElementById('icon-eye');
    const iconOff   = document.getElementById('icon-eye-off');

    toggleBtn.addEventListener('click', function () {
        const isHidden = pwInput.type === 'password';
        pwInput.type   = isHidden ? 'text' : 'password';
        iconEye.classList.toggle('hidden', isHidden);
        iconOff.classList.toggle('hidden', !isHidden);
    });

    const rules = {
        'rule-length': (v) => v.length >= 8 && v.length <= 100,
        'rule-lower' : (v) => /[a-z]/.test(v),
        'rule-upper' : (v) => /[A-Z]/.test(v),
        'rule-digit' : (v) => /[0-9]/.test(v),
    };

    pwInput.addEventListener('input', function () {
        const val = this.value;
        Object.entries(rules).forEach(([id, fn]) => {
            const el = document.getElementById(id);
            el.classList.toggle('valid', fn(val));
        });
    });

    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');

    phoneInput.addEventListener('input', function () {
        const cleaned = this.value.replace(/[^0-9]/g, '');
        if (this.value !== cleaned) {
            this.value = cleaned;
        }
        if (this.value.length > 0 && !/^[0-9]+$/.test(this.value)) {
            phoneError.classList.remove('hidden');
        } else {
            phoneError.classList.add('hidden');
        }
    });

    phoneInput.addEventListener('keydown', function (e) {
        const allowed = ['Backspace','Delete','Tab','ArrowLeft','ArrowRight','Home','End'];
        if (!allowed.includes(e.key) && !/^[0-9]$/.test(e.key)) {
            e.preventDefault();
        }
    });

    phoneInput.addEventListener('paste', function (e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        const digits = pasted.replace(/[^0-9]/g, '');
        const start = this.selectionStart;
        const end   = this.selectionEnd;
        this.value  = this.value.slice(0, start) + digits + this.value.slice(end);
        phoneError.classList.add('hidden');
    });

    const checkbox  = document.getElementById('agree_terms');
    const submitBtn = document.getElementById('submit-btn');

    function syncBtn() {
        submitBtn.disabled = !checkbox.checked;
    }
    checkbox.addEventListener('change', syncBtn);
    syncBtn(); 

})();