// ====================================
// OPTIMIZED TOKODUS SCRIPTS
// Performance & Production Ready
// ====================================

// Utility Functions - Simplified
const $ = (s, p = document) => p.querySelector(s);
const $$ = (s, p = document) => p.querySelectorAll(s);
const esc = text => {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
};

// ====================================
// 1. BANNER SLIDER - Optimized
// ====================================
const BannerSlider = (() => {
    let current = 1;
    let interval;
    let transitioning = false;

    const init = () => {
        const container = $('.benner-slides');
        const dots = $$('.dots .dot');
        const prev = $('.prev');
        const next = $('.next');

        if (!container || !dots.length) return;

        const slides = Array.from(container.children);
        if (!slides.length) return;

        // Clone slides
        container.appendChild(slides[0].cloneNode(true));
        container.insertBefore(slides[slides.length - 1].cloneNode(true), slides[0]);

        const total = container.children.length;
        container.style.transform = 'translateX(-100%)';

        const updateDots = () => {
            let idx = current - 1;
            if (current === 0) idx = slides.length - 1;
            if (current === total - 1) idx = 0;
            dots.forEach((dot, i) => dot.classList.toggle('active', i === idx));
        };

        const goTo = idx => {
            if (transitioning) return;
            transitioning = true;
            current = idx;

            container.style.transition = 'transform 0.5s ease-in-out';
            container.style.transform = `translateX(-${current * 100}%)`;

            const onEnd = () => {
                transitioning = false;
                if (current === total - 1) {
                    container.style.transition = 'none';
                    current = 1;
                    container.style.transform = 'translateX(-100%)';
                } else if (current === 0) {
                    container.style.transition = 'none';
                    current = slides.length;
                    container.style.transform = `translateX(-${current * 100}%)`;
                }
                updateDots();
                container.removeEventListener('transitionend', onEnd);
            };

            container.addEventListener('transitionend', onEnd);
        };

        const start = () => {
            if (interval) clearInterval(interval);
            interval = setInterval(() => goTo(current + 1), 5000);
        };

        const stop = () => clearInterval(interval);

        next?.addEventListener('click', () => { stop(); goTo(current + 1); });
        prev?.addEventListener('click', () => { stop(); goTo(current - 1); });
        dots.forEach((dot, i) => dot.addEventListener('click', () => { stop(); goTo(i + 1); }));

        document.addEventListener('click', e => {
            if (!e.target.closest('.prev') && !e.target.closest('.next')) start();
        });

        start();
        updateDots();
    };

    return { init };
})();

// ====================================
// 2. GENERIC SLIDER - Reusable for Category & Recommended
// ====================================
const GenericSlider = (containerSel, sliderSel, config = {}) => {
    const {
        cardWidth = 230,
        autoScrollInterval = 2500,
        snapOnDrag = true
    } = config;

    const container = $(containerSel);
    const slider = $(sliderSel);

    if (!container || !slider) return;

    const cards = Array.from(slider.children);
    if (!cards.length) return;

    // Clone for infinite scroll
    cards.forEach(card => slider.appendChild(card.cloneNode(true)));

    let dragging = false;
    let startX = 0;
    let translate = 0;
    let prevTranslate = 0;
    let autoInterval;
    let autoEnabled = true;

    const min = -cardWidth * cards.length;
    const max = 0;

    const startAuto = () => {
        if (!autoEnabled) return;
        stopAuto();
        autoInterval = setInterval(() => {
            translate -= cardWidth;
            if (translate <= min) {
                slider.style.transition = 'none';
                translate = 0;
                slider.style.transform = `translateX(0)`;
                slider.offsetHeight; // Force reflow
                setTimeout(() => slider.style.transition = 'transform 0.5s ease-in-out', 50);
            } else {
                slider.style.transition = 'transform 0.5s ease-in-out';
                slider.style.transform = `translateX(${translate}px)`;
            }
            prevTranslate = translate;
        }, autoScrollInterval);
    };

    const stopAuto = () => clearInterval(autoInterval);

    const snap = () => {
        const snapPoint = Math.max(min, Math.min(max, Math.round(translate / cardWidth) * cardWidth));
        slider.style.transition = 'transform 0.3s ease-out';
        slider.style.transform = `translateX(${snapPoint}px)`;
        translate = prevTranslate = snapPoint;
        setTimeout(() => { if (!dragging) { autoEnabled = true; startAuto(); } }, 300);
    };

    // Event Listeners
    container.addEventListener('mouseenter', stopAuto);
    container.addEventListener('mouseleave', () => { if (!dragging) { autoEnabled = true; startAuto(); } });

    container.addEventListener('mousedown', e => {
        dragging = true;
        autoEnabled = false;
        startX = e.pageX;
        stopAuto();
        container.classList.add('grabbing');
        slider.style.transition = 'none';
    });

    document.addEventListener('mouseup', () => {
        if (dragging) {
            dragging = false;
            container.classList.remove('grabbing');
            if (snapOnDrag) snap();
        }
    });

    container.addEventListener('mousemove', e => {
        if (!dragging) return;
        e.preventDefault();
        const walk = e.pageX - startX;
        translate = Math.max(min, Math.min(max, prevTranslate + walk));
        slider.style.transform = `translateX(${translate}px)`;
    });

    startAuto();
};

// ====================================
// 3. PARTNER LOGO SCROLL - Optimized with RAF
// ====================================
const PartnerLogo = (() => {
    const init = () => {
        const container = $('.logo-slider-container');
        const slider = $('.logo-slide');

        if (!container || !slider) return;

        slider.innerHTML += slider.innerHTML;

        let speed = 1;
        let paused = false;

        const scroll = () => {
            if (!paused) {
                if (container.scrollLeft >= slider.scrollWidth / 2) {
                    container.scrollLeft = 0;
                }
                container.scrollLeft += speed;
            }
            requestAnimationFrame(scroll);
        };

        scroll();

        container.addEventListener('mouseenter', () => paused = true);
        container.addEventListener('mouseleave', () => paused = false);
    };

    return { init };
})();

// ====================================
// 4. STORE SECTION - Optimized with Event Delegation
// ====================================
const StoreSection = (() => {
    const init = () => {
        const btn = $('.dropdown-btn');
        const dropdown = $('#dropdownContent');
        const info = $('#storeInfo');
        const map = $('#storeMap');

        if (!btn || !dropdown || !info || !map) return;

        const defaultMap = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63387.67678038912!2d107.5731161675113!3d-6.914864054387586!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6421e57f7a3%3A0x1f9c85fd11fc66b2!2sBandung%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1726500000000";

        let selected = false;
        let hideTimeout;

        // Toggle dropdown
        const show = () => {
            clearTimeout(hideTimeout);
            dropdown.style.display = 'block';
        };

        const hide = () => {
            hideTimeout = setTimeout(() => dropdown.style.display = 'none', 200);
        };

        btn.addEventListener('mouseenter', show);
        btn.addEventListener('mouseleave', hide);
        dropdown.addEventListener('mouseenter', show);
        dropdown.addEventListener('mouseleave', hide);

        // Event delegation for store items
        dropdown.addEventListener('click', e => {
            const item = e.target.closest('li[data-store-id]');
            if (!item) return;

            const name = item.dataset.storeName;
            const addr = item.dataset.storeAddress;
            const phone = item.dataset.storePhone;
            const email = item.dataset.storeEmail;
            const mapUrl = item.dataset.storeMap;

            const cleanPhone = phone ? phone.replace(/\D/g, '') : '';
            const waLink = cleanPhone ? `https://wa.me/${cleanPhone}` : '#';

            let html = `<h3>${esc(name)}</h3>`;
            if (addr && addr !== 'null') html += `<p><strong>Alamat:</strong> ${esc(addr)}</p>`;
            if (phone && phone !== 'null') html += `<p><strong>No. Telp:</strong> <a href="${waLink}" target="_blank" rel="noopener">${esc(phone)}</a></p>`;
            if (email && email !== 'null') html += `<p><strong>Email:</strong> ${esc(email)}</p>`;

            info.innerHTML = html;
            map.src = (mapUrl && mapUrl !== 'null' && mapUrl.trim()) ? mapUrl : defaultMap;
            dropdown.style.display = 'none';
            selected = true;
        });

        // Reset on outside click
        document.addEventListener('click', e => {
            if (!dropdown.contains(e.target) && !btn.contains(e.target)) {
                dropdown.style.display = 'none';
                if (!selected) {
                    map.src = defaultMap;
                    info.innerHTML = '<h3>Bandung, Jawa Barat</h3><p><strong>Silakan pilih cabang toko untuk melihat informasi lebih lanjut.</strong></p>';
                }
            }
        });

        map.src = defaultMap;
    };

    return { init };
})();

// ====================================
// MAIN INITIALIZATION - Optimized
// ====================================
const init = () => {
    const components = [
        { name: 'Banner Slider', fn: () => BannerSlider.init() },
        { name: 'Category Slider', fn: () => GenericSlider('.category-slider-container', '.category-slider', { cardWidth: 230, autoScrollInterval: 2500 }) },
        { name: 'Recommended Slider', fn: () => GenericSlider('.recommended-slider-container', '.recommended-slider', { cardWidth: 240, autoScrollInterval: 3000 }) },
        { name: 'Partner Logo', fn: () => PartnerLogo.init() },
        { name: 'Store Section', fn: () => StoreSection.init() }
    ];

    let success = 0;
    components.forEach(({ name, fn }) => {
        try {
            fn();
            console.log(`✅ ${name}`);
            success++;
        } catch (err) {
            console.error(`❌ ${name}:`, err);
        }
    });

    console.log(`📊 ${success}/${components.length} initialized`);
};

// Run on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}