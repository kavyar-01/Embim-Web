
document.addEventListener('DOMContentLoaded', function () {


    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function () {
            mobileMenu.classList.toggle('hidden');
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function () {
                mobileMenu.classList.add('hidden');
            });
        });
    }


    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });


    const navbar = document.getElementById('navbar');

    if (navbar) {
        const handleNavbarScroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };

        window.addEventListener('scroll', handleNavbarScroll, { passive: true });
        handleNavbarScroll(); 
    }


    const revealSelectors = '.reveal, .reveal-left, .reveal-right, .reveal-scale';
    const revealEls = document.querySelectorAll(revealSelectors);

    if (revealEls.length > 0) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -60px 0px'
        });

        revealEls.forEach(el => revealObserver.observe(el));
    }


    const searchInput = document.getElementById('search-input');

    if (searchInput) {
        const phrases = [
            "Mau sewa mobil premium apa hari ini?...",
            "Toyota Avanza Veloz",
            "MPV",
            "SUV",
            "Mitshubishi Pajero",

        ];

        let phraseIndex = 0;
        let characterIndex = 0;
        let isDeleting = false;
        let typingSpeed = 100;

        function typeEffect() {
            if (document.activeElement === searchInput || searchInput.value.length > 0) {
                setTimeout(typeEffect, 500);
                return;
            }

            const currentPhrase = phrases[phraseIndex];

            if (isDeleting) {
                searchInput.placeholder = currentPhrase.substring(0, characterIndex - 1);
                characterIndex--;
                typingSpeed = 50;
            } else {
                searchInput.placeholder = currentPhrase.substring(0, characterIndex + 1);
                characterIndex++;
                typingSpeed = 100;
            }

            if (!isDeleting && characterIndex === currentPhrase.length) {
                typingSpeed = 2000; 
                isDeleting = true;
            } else if (isDeleting && characterIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                typingSpeed = 500; 
            }

            setTimeout(typeEffect, typingSpeed);
        }

        searchInput.addEventListener('blur', function () {
            if (this.value.length === 0) {
                this.placeholder = '';
            }
        });

        typeEffect();
    }


    const pickupDateInput = document.getElementById('pickup-date');
    const returnDateInput = document.getElementById('return-date');

    if (pickupDateInput && returnDateInput) {
        const today = new Date().toISOString().split('T')[0];
        pickupDateInput.setAttribute('min', today);
        returnDateInput.setAttribute('min', today);

        pickupDateInput.addEventListener('change', function () {
            returnDateInput.setAttribute('min', this.value);
        });
    }


    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const emailInput = this.querySelector('input[type="email"]');
            if (emailInput && emailInput.value) {
                alert('Terima kasih telah berlangganan newsletter EMBIM!');
                emailInput.value = '';
            }
        });
    }


    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    }
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    console.log('EMBIM initialized ✓');
});


function formatCurrency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: currency,
    }).format(amount);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(new Date(date));
}

window.EMBIM = { formatCurrency, formatDate };



(function () {
    const track    = document.getElementById('carousel-track');
    const prevBtn  = document.getElementById('carousel-prev');
    const nextBtn  = document.getElementById('carousel-next');
    const dotsWrap = document.getElementById('carousel-dots');
    if (!track) return;

    const items      = track.querySelectorAll('.carousel-item');
    const totalItems = items.length;
    const GAP        = 32; 
    let current      = 0;
    let perView      = getPerView();
    let totalSlides  = Math.ceil(totalItems / perView);

    function getPerView() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 640)  return 2;
        return 1;
    }


    function setItemWidths() {
        perView     = getPerView();
        totalSlides = Math.max(1, Math.ceil(totalItems / perView));

        const containerW = track.parentElement.offsetWidth;
        const itemW      = (containerW - GAP * (perView - 1)) / perView;

        items.forEach(item => {
            item.style.width    = itemW + 'px';
            item.style.flexShrink = '0';
        });
        track.style.gap = GAP + 'px';


        if (current >= totalSlides) current = totalSlides - 1;
        if (current < 0) current = 0;

        renderDots();
        goTo(current, false);
    }


    function goTo(index, animate = true) {
        current = Math.max(0, Math.min(index, totalSlides - 1));

        const containerW = track.parentElement.offsetWidth;
        const itemW      = (containerW - GAP * (perView - 1)) / perView;
        const pageW      = perView * itemW + perView * GAP;
        const offset     = current * pageW;

        track.style.transition = animate
            ? 'transform 0.45s cubic-bezier(0.4, 0, 0.2, 1)'
            : 'none';
        track.style.transform = 'translateX(-' + offset + 'px)';

        prevBtn.disabled = current === 0;
        nextBtn.disabled = current >= totalSlides - 1;

        dotsWrap.querySelectorAll('.carousel-dot').forEach(function (d, i) {
            if (i === current) {
                d.style.width      = '24px';
                d.style.background = '#2563eb';
            } else {
                d.style.width      = '10px';
                d.style.background = '#bfdbfe';
            }
        });
    }

    function renderDots() {
        dotsWrap.innerHTML = '';
        if (totalSlides <= 1) return;
        for (var i = 0; i < totalSlides; i++) {
            var dot = document.createElement('button');
            dot.className = 'carousel-dot';
            dot.style.cssText = [
                'height:10px',
                'border-radius:9999px',
                'border:none',
                'cursor:pointer',
                'transition:width .3s, background .3s',
                'padding:0',
                i === current ? 'width:24px;background:#2563eb' : 'width:10px;background:#bfdbfe'
            ].join(';');
            dot.setAttribute('aria-label', 'Slide ' + (i + 1));
            (function(idx){ dot.addEventListener('click', function(){ goTo(idx); }); })(i);
            dotsWrap.appendChild(dot);
        }
    }

    prevBtn.addEventListener('click', function () { goTo(current - 1); });
    nextBtn.addEventListener('click', function () { goTo(current + 1); });

    var startX = 0;
    track.addEventListener('touchstart', function (e) {
        startX = e.touches[0].clientX;
    }, { passive: true });
    track.addEventListener('touchend', function (e) {
        var diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            diff > 0 ? goTo(current + 1) : goTo(current - 1);
        }
    });

    var resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(setItemWidths, 150);
    });

    setItemWidths();
})();