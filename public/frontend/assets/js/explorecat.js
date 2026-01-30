/**
 * EXPLORE CATEGORIES SLIDER - Version 323
 * Data sudah di-render dari Laravel Blade
 */

// ========================================
// DESKTOP SLIDER - INFINITE LOOP
// ========================================
function initCategorySlider323() {
    const container = document.querySelector('.category-slider-container-323');
    const slider = document.querySelector('.category-slider-323');
    if (!container || !slider) return;

    // Cek apakah ada cards yang sudah di-render
    const cards = slider.querySelectorAll('.category-card-323');
    if (cards.length === 0) {
        console.warn('No category cards found in desktop slider');
        return;
    }

    let isDragging = false;
    let startX = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    let autoScrollInterval;
    let animationID;

    const cardWidth = 200; // Width card + gap
    
    // Hitung jumlah kategori asli (bukan clone)
    const totalCategories = Math.floor(cards.length / 3); // Karena ada 3 clone set
    const cloneSetWidth = cardWidth * totalCategories;

    // Set posisi awal di tengah (set kedua)
    currentTranslate = -cloneSetWidth;
    prevTranslate = currentTranslate;
    setSliderPosition();

    // Auto scroll otomatis per card
    function startAutoScroll() {
        stopAutoScroll();
        autoScrollInterval = setInterval(() => {
            currentTranslate -= cardWidth;
            checkInfiniteLoop();
            slider.style.transition = 'transform 0.5s ease-out';
            setSliderPosition();
        }, 2500);
    }

    function stopAutoScroll() {
        if (autoScrollInterval) {
            clearInterval(autoScrollInterval);
            autoScrollInterval = null;
        }
    }

    // Check infinite loop & reset posisi tanpa terlihat
    function checkInfiniteLoop() {
        if (currentTranslate <= -cloneSetWidth * 2) {
            slider.style.transition = 'none';
            currentTranslate = -cloneSetWidth;
            prevTranslate = currentTranslate;
            setSliderPosition();
        } else if (currentTranslate >= 0) {
            slider.style.transition = 'none';
            currentTranslate = -cloneSetWidth;
            prevTranslate = currentTranslate;
            setSliderPosition();
        }
    }

    function setSliderPosition() {
        slider.style.transform = `translateX(${currentTranslate}px)`;
    }

    // Snap ke card terdekat
    function snapToNearestCard() {
        const nearestCard = Math.round(currentTranslate / cardWidth) * cardWidth;
        currentTranslate = nearestCard;
        prevTranslate = currentTranslate;
        slider.style.transition = 'transform 0.3s ease-out';
        setSliderPosition();
        checkInfiniteLoop();
        
        setTimeout(() => {
            if (!isDragging) startAutoScroll();
        }, 300);
    }

    // Mouse Events
    container.addEventListener('mouseenter', stopAutoScroll);
    
    container.addEventListener('mouseleave', () => {
        if (!isDragging) startAutoScroll();
    });

    container.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX;
        stopAutoScroll();
        container.classList.add('grabbing-323');
        slider.style.transition = 'none';
    });

    container.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const currentX = e.pageX;
        const diff = currentX - startX;
        currentTranslate = prevTranslate + diff;
    });

    container.addEventListener('mouseup', () => {
        if (!isDragging) return;
        isDragging = false;
        container.classList.remove('grabbing-323');
        snapToNearestCard();
    });

    container.addEventListener('mouseleave', () => {
        if (!isDragging) return;
        isDragging = false;
        container.classList.remove('grabbing-323');
        snapToNearestCard();
    });

    // Touch Events
    container.addEventListener('touchstart', (e) => {
        isDragging = true;
        startX = e.touches[0].clientX;
        stopAutoScroll();
        slider.style.transition = 'none';
    });

    container.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        const currentX = e.touches[0].clientX;
        const diff = currentX - startX;
        currentTranslate = prevTranslate + diff;
    });

    container.addEventListener('touchend', () => {
        isDragging = false;
        snapToNearestCard();
    });

    // Animation loop untuk smooth dragging
    function animation() {
        setSliderPosition();
        if (isDragging) requestAnimationFrame(animation);
    }

    container.addEventListener('mousedown', animation);
    container.addEventListener('touchstart', animation);

    // Start auto scroll
    startAutoScroll();
    
    console.log('✅ Desktop category slider initialized with', totalCategories, 'categories');
}

// ========================================
// MOBILE SLIDER - INFINITE LOOP (2 CARDS FIXED)
// ========================================
function initCategorySlider323Mobile() {
    const container = document.querySelector('.category-slider-container-323-mobile');
    const slider = document.querySelector('.category-slider-323-mobile');
    if (!container || !slider) return;

    let isDragging = false;
    let startX = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    let autoScrollInterval;

    setTimeout(() => {
        const cards = slider.querySelectorAll('.category-card-323-mobile');
        if (cards.length === 0) {
            console.warn('No category cards found in mobile slider');
            return;
        }

        const firstCard = cards[0];
        const cardStyle = window.getComputedStyle(firstCard);
        const cardWidth = firstCard.offsetWidth;
        const gap = 15;
        
        const cardTotalWidth = cardWidth + gap;
        const scrollAmount = cardTotalWidth * 2; // Scroll 2 cards
        
        const totalCategories = Math.floor(cards.length / 3);
        const cloneSetWidth = cardTotalWidth * totalCategories;

        currentTranslate = -cloneSetWidth;
        prevTranslate = currentTranslate;
        setSliderPosition();

        function startAutoScroll() {
            stopAutoScroll();
            autoScrollInterval = setInterval(() => {
                currentTranslate = prevTranslate - scrollAmount;
                prevTranslate = currentTranslate;
                
                slider.style.transition = 'transform 0.6s ease-out';
                setSliderPosition();
                
                setTimeout(() => {
                    checkInfiniteLoop();
                }, 600);
            }, 3000);
        }

        function stopAutoScroll() {
            if (autoScrollInterval) {
                clearInterval(autoScrollInterval);
                autoScrollInterval = null;
            }
        }

        function checkInfiniteLoop() {
            const position = Math.abs(currentTranslate);
            const cloneSetEnd = cloneSetWidth * 2;
            
            if (position >= cloneSetEnd - scrollAmount) {
                slider.style.transition = 'none';
                currentTranslate = -cloneSetWidth;
                prevTranslate = currentTranslate;
                setSliderPosition();
            } else if (currentTranslate > -scrollAmount) {
                slider.style.transition = 'none';
                currentTranslate = -cloneSetWidth;
                prevTranslate = currentTranslate;
                setSliderPosition();
            }
        }

        function setSliderPosition() {
            slider.style.transform = `translateX(${currentTranslate}px)`;
        }

        function snapToNearestPosition() {
            const snapPosition = Math.round(currentTranslate / scrollAmount) * scrollAmount;
            
            currentTranslate = snapPosition;
            prevTranslate = currentTranslate;
            
            slider.style.transition = 'transform 0.3s ease-out';
            setSliderPosition();
            
            setTimeout(() => {
                checkInfiniteLoop();
                if (!isDragging) startAutoScroll();
            }, 300);
        }

        let touchStartTime = 0;
        
        container.addEventListener('touchstart', (e) => {
            isDragging = true;
            startX = e.touches[0].clientX;
            touchStartTime = Date.now();
            stopAutoScroll();
            container.classList.add('grabbing-323-mobile');
            slider.style.transition = 'none';
        });

        container.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            e.preventDefault();
            const currentX = e.touches[0].clientX;
            const diff = currentX - startX;
            currentTranslate = prevTranslate + diff;
            setSliderPosition();
        });

        container.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            
            isDragging = false;
            container.classList.remove('grabbing-323-mobile');
            
            const touchDuration = Date.now() - touchStartTime;
            const diff = currentTranslate - prevTranslate;
            
            if (Math.abs(diff) > 50 && touchDuration < 300) {
                if (diff > 0) {
                    currentTranslate = prevTranslate + scrollAmount;
                } else {
                    currentTranslate = prevTranslate - scrollAmount;
                }
            }
            
            snapToNearestPosition();
        });

        startAutoScroll();
        
        console.log('✅ Mobile category slider initialized with', totalCategories, 'categories');
    }, 100);
}

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    initCategorySlider323();
    initCategorySlider323Mobile();
});