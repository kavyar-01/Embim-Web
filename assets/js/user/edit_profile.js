
function previewImage(input, imgId, wrapId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(imgId);
            img.src = e.target.result;
            // Tampilkan wrapper jika ada
            if (wrapId) {
                const wrap = document.getElementById(wrapId);
                if (wrap) wrap.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const pwInput = document.querySelector('input[name="password"]');
    if (pwInput) {
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
                if (el) el.classList.toggle('valid', fn(val));
            });
        });
    }
});
