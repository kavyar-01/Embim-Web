
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
