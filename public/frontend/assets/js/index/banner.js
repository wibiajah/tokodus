/**
 * BANNER SLIDER - 100% Responsive & Adaptive
 * Fully optimized for all devices, screen sizes, and zoom levels
 * Prefix: 322
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ===================================
    // DOM Elements
    // ===================================
    const slidesContainer = document.querySelector('.banner-322-container');
    const dots = document.querySelectorAll('.banner-322-dot');
    const prevBtn = document.querySelector('.banner-322-prev');
    const nextBtn = document.querySelector('.banner-322-next');
    const bannerSection = document.querySelector('.banner-322-section');

    if (!slidesContainer) {
        console.error('❌ Banner container not found!');
        return;
    }

    // ===================================
    // Responsive Configuration
    // ===================================
    function getFlickityConfig() {
        const isMobile = window.innerWidth <= 768;
        const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        
        return {
            cellAlign: 'center',
            contain: true,
            wrapAround: true,
            autoPlay: isMobile ? 4000 : 3000, // Longer on mobile
            prevNextButtons: false,
            pageDots: false,
            draggable: true,
            freeScroll: false,
            percentPosition: true,
            setGallerySize: true,
            resize: true,
            watchCSS: false,
            pauseAutoPlayOnHover: !isTouch, // Only pause on hover for desktop
            friction: 0.28,
            selectedAttraction: 0.025,
            accessibility: true,
            dragThreshold: isTouch ? 10 : 3, // More sensitive on touch
            adaptiveHeight: false
        };
    }

    // ===================================
    // Initialize Flickity with Responsive Config
    // ===================================
    let flkty = new Flickity(slidesContainer, getFlickityConfig());

    // ===================================
    // Sync Custom Dots with Flickity
    // ===================================
    function updateDots(index) {
        dots.forEach((dot, i) => {
            dot.classList.toggle('banner-322-dot-active', i === index);
            dot.setAttribute('aria-current', i === index ? 'true' : 'false');
        });
    }

    // Update dots when slide changes
    flkty.on('change', function(index) {
        updateDots(index);
    });

    // ===================================
    // Custom Navigation Buttons
    // ===================================
    if (prevBtn) {
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            flkty.stopPlayer();
            flkty.previous();
            flkty.playPlayer();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            flkty.stopPlayer();
            flkty.next();
            flkty.playPlayer();
        });
    }

    // ===================================
    // Custom Dots Click Handler
    // ===================================
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function(e) {
            e.preventDefault();
            flkty.stopPlayer();
            flkty.select(index);
            flkty.playPlayer();
        });
    });

    // ===================================
    // Keyboard Navigation (Accessibility)
    // ===================================
    document.addEventListener('keydown', function(e) {
        // Only navigate if focus is on banner or navigation
        const focusedElement = document.activeElement;
        const isInBanner = bannerSection.contains(focusedElement) || 
                          focusedElement === prevBtn || 
                          focusedElement === nextBtn ||
                          Array.from(dots).includes(focusedElement);

        if (isInBanner || focusedElement === document.body) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                flkty.stopPlayer();
                flkty.previous();
                flkty.playPlayer();
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                flkty.stopPlayer();
                flkty.next();
                flkty.playPlayer();
            }
        }
    });

    // ===================================
    // Responsive Resize Handler
    // ===================================
    let resizeTimer;
    let lastWidth = window.innerWidth;

    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        
        resizeTimer = setTimeout(function() {
            const currentWidth = window.innerWidth;
            
            // Only reinitialize if width changed significantly (not just zoom)
            if (Math.abs(currentWidth - lastWidth) > 50) {
                lastWidth = currentWidth;
                
                // Destroy and reinitialize with new config
                const currentIndex = flkty.selectedIndex;
                flkty.destroy();
                flkty = new Flickity(slidesContainer, getFlickityConfig());
                flkty.select(currentIndex, false, true);
                
                // Reattach event listeners
                flkty.on('change', function(index) {
                    updateDots(index);
                });
                
                console.log('🔄 Banner slider adapted to new screen size');
            }
            
            // Always resize Flickity on any resize event
            flkty.resize();
        }, 250);
    });

    // ===================================
    // Orientation Change Handler (Mobile)
    // ===================================
    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            flkty.resize();
            console.log('📱 Banner slider adapted to orientation change');
        }, 300);
    });

    // ===================================
    // Visibility Change Handler (Performance)
    // ===================================
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            flkty.stopPlayer();
        } else {
            flkty.playPlayer();
        }
    });

    // ===================================
    // Touch Swipe Enhancement
    // ===================================
    let touchStartX = 0;
    let touchEndX = 0;
    
    slidesContainer.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    slidesContainer.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left
                flkty.next();
            } else {
                // Swipe right
                flkty.previous();
            }
        }
    }

    // ===================================
    // Preload Images for Smooth Transitions
    // ===================================
    function preloadImages() {
        const images = slidesContainer.querySelectorAll('img');
        let loadedCount = 0;
        
        images.forEach(img => {
            if (img.complete) {
                loadedCount++;
            } else {
                img.addEventListener('load', function() {
                    loadedCount++;
                    if (loadedCount === images.length) {
                        flkty.resize();
                    }
                });
            }
        });
        
        if (loadedCount === images.length) {
            flkty.resize();
        }
    }
    
    preloadImages();

    // ===================================
    // Lazy Loading Support (if needed)
    // ===================================
    if ('IntersectionObserver' in window) {
        const lazyImages = slidesContainer.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    }

    // ===================================
    // Performance Monitoring (Optional)
    // ===================================
    function logPerformance() {
        if (window.performance && window.performance.now) {
            const loadTime = window.performance.now();
            console.log(`⚡ Banner slider initialized in ${loadTime.toFixed(2)}ms`);
        }
    }

    logPerformance();

    // ===================================
    // Initialize Complete
    // ===================================
    console.log('✅ Banner slider fully responsive mode active');
    console.log(`📊 Viewport: ${window.innerWidth}x${window.innerHeight}`);
    console.log(`📱 Device: ${window.innerWidth <= 768 ? 'Mobile' : 'Desktop'}`);

    // ===================================
    // Cleanup on Page Unload
    // ===================================
    window.addEventListener('beforeunload', function() {
        if (flkty) {
            flkty.destroy();
            console.log('🧹 Banner slider cleaned up');
        }
    });

    // ===================================
    // Expose API for External Control (Optional)
    // ===================================
    window.bannerSlider322 = {
        next: () => flkty.next(),
        previous: () => flkty.previous(),
        select: (index) => flkty.select(index),
        pause: () => flkty.stopPlayer(),
        play: () => flkty.playPlayer(),
        getCurrentIndex: () => flkty.selectedIndex,
        getTotalSlides: () => flkty.slides.length,
        resize: () => flkty.resize()
    };

});