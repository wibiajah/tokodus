/**
 * BULLETPROOF INFINITE SLIDER - ZERO GLITCH
 * Smooth, stable, production-ready
 */

(function() {
    'use strict';
    
    const CFG = {
        container: '.category-slider-container-434',
        slider: '.category-slider-434',
        card: '.category-card-434',
        autoMs: 2500,
        transMs: 500,
        snapMs: 300
    };
    
    const st = {
        drag: false,
        startX: 0,
        currentX: 0,
        translateX: 0,
        prevTranslate: 0,
        timer: null,
        autoOn: true,
        cardW: 0,
        cardCount: 0,
        setWidth: 0,
        animFrame: null
    };
    
    let con, sld, resizeTimer;
    
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setup);
        } else {
            setup();
        }
    }
    
    function setup() {
        con = document.querySelector(CFG.container);
        sld = document.querySelector(CFG.slider);
        
        if (!con || !sld) return;
        
        const cards = Array.from(sld.querySelectorAll(CFG.card));
        st.cardCount = cards.length;
        
        if (st.cardCount === 0) return;
        
        // Triple clone
        const html = sld.innerHTML;
        sld.innerHTML = html + html + html;
        
        calcDim();
        
        // Start at middle set
        const mid = -st.setWidth;
        st.translateX = mid;
        st.prevTranslate = mid;
        applyTransform(false);
        
        bindEvents();
        startAuto();
    }
    
    function calcDim() {
        const card = sld.querySelector(CFG.card);
        if (!card) return;
        
        const w = card.offsetWidth;
        const gap = parseFloat(getComputedStyle(sld).gap) || 20;
        st.cardW = w + gap;
        st.setWidth = st.cardW * st.cardCount;
    }
    
    function bindEvents() {
        // Mouse events
        let mouseActive = false;
        
        con.addEventListener('mousedown', e => {
            mouseActive = true;
            handleDragStart(e.pageX);
        });
        
        con.addEventListener('mousemove', e => {
            if (!mouseActive) return;
            e.preventDefault();
            handleDragMove(e.pageX);
        });
        
        const handleMouseEnd = () => {
            if (!mouseActive) return;
            mouseActive = false;
            handleDragEnd();
        };
        
        con.addEventListener('mouseup', handleMouseEnd);
        document.addEventListener('mouseup', handleMouseEnd);
        con.addEventListener('mouseleave', handleMouseEnd);
        
        // Touch events
        con.style.touchAction = 'pan-y pinch-zoom';
        sld.style.touchAction = 'none';
        
        let touchActive = false;
        
        con.addEventListener('touchstart', e => {
            const touch = e.touches[0];
            if (!touch) return;
            e.preventDefault();
            touchActive = true;
            handleDragStart(touch.clientX);
        }, { passive: false });
        
        con.addEventListener('touchmove', e => {
            if (!touchActive) return;
            const touch = e.touches[0];
            if (!touch) return;
            e.preventDefault();
            handleDragMove(touch.clientX);
        }, { passive: false });
        
        const handleTouchEnd = () => {
            if (!touchActive) return;
            touchActive = false;
            handleDragEnd();
        };
        
        con.addEventListener('touchend', handleTouchEnd, { passive: false });
        con.addEventListener('touchcancel', handleTouchEnd);
        
        // Hover
        con.addEventListener('mouseenter', stopAuto);
        con.addEventListener('mouseleave', () => {
            if (!st.drag) {
                st.autoOn = true;
                startAuto();
            }
        });
        
        // Resize
        window.addEventListener('resize', handleResize);
        
        sld.style.cursor = 'grab';
    }
    
    function handleDragStart(x) {
        st.drag = true;
        st.autoOn = false;
        st.startX = x;
        st.currentX = x;
        st.prevTranslate = st.translateX;
        
        stopAuto();
        cancelAnimationFrame(st.animFrame);
        
        con.classList.add('grabbing');
        sld.style.cursor = 'grabbing';
    }
    
    function handleDragMove(x) {
        if (!st.drag) return;
        
        st.currentX = x;
        const diff = st.currentX - st.startX;
        st.translateX = st.prevTranslate + diff;
        
        applyTransform(false);
        
        // Check bounds and loop
        checkLoop();
    }
    
    function handleDragEnd() {
        st.drag = false;
        con.classList.remove('grabbing');
        sld.style.cursor = 'grab';
        
        // Snap to nearest card
        snapToCard();
    }
    
    function checkLoop() {
        if (st.setWidth === 0) return;
        
        const midStart = -st.setWidth;
        const midEnd = -st.setWidth * 2;
        
        // Only reposition if outside middle set
        if (st.translateX > midStart) {
            // Scrolled too far right
            st.translateX -= st.setWidth;
            st.prevTranslate = st.translateX;
            
            // Adjust drag start point
            if (st.drag) {
                st.startX += st.setWidth;
            }
            
            applyTransform(false);
        } else if (st.translateX < midEnd) {
            // Scrolled too far left
            st.translateX += st.setWidth;
            st.prevTranslate = st.translateX;
            
            // Adjust drag start point
            if (st.drag) {
                st.startX -= st.setWidth;
            }
            
            applyTransform(false);
        }
    }
    
    function applyTransform(animate) {
        // Cancel any pending animation frame
        cancelAnimationFrame(st.animFrame);
        
        st.animFrame = requestAnimationFrame(() => {
            sld.style.transition = animate ? `transform ${CFG.transMs}ms cubic-bezier(0.25, 0.46, 0.45, 0.94)` : 'none';
            sld.style.transform = `translate3d(${st.translateX}px, 0, 0)`;
        });
    }
    
    function snapToCard() {
        // Snap to nearest card
        const snapPos = Math.round(st.translateX / st.cardW) * st.cardW;
        st.translateX = snapPos;
        st.prevTranslate = snapPos;
        
        applyTransform(true);
        
        // Check loop after snap animation
        setTimeout(() => {
            checkLoop();
            
            // Resume auto scroll
            if (!st.drag) {
                st.autoOn = true;
                startAuto();
            }
        }, CFG.snapMs);
    }
    
    function startAuto() {
        if (!st.autoOn || st.timer) return;
        
        st.timer = setInterval(() => {
            if (st.drag) return;
            
            // Move one card left
            st.translateX -= st.cardW;
            st.prevTranslate = st.translateX;
            
            applyTransform(true);
            
            // Check loop after animation
            setTimeout(() => {
                checkLoop();
            }, CFG.transMs);
        }, CFG.autoMs);
    }
    
    function stopAuto() {
        if (st.timer) {
            clearInterval(st.timer);
            st.timer = null;
        }
    }
    
    function handleResize() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            stopAuto();
            
            calcDim();
            
            // Reset to middle
            const mid = -st.setWidth;
            st.translateX = mid;
            st.prevTranslate = mid;
            
            applyTransform(false);
            
            if (st.autoOn) {
                startAuto();
            }
        }, 250);
    }
    
    // Cleanup
    window.addEventListener('beforeunload', () => {
        stopAuto();
        cancelAnimationFrame(st.animFrame);
    });
    
    // Public API
    window.categorySlider_434 = {
        start: () => {
            st.autoOn = true;
            startAuto();
        },
        stop: () => {
            st.autoOn = false;
            stopAuto();
        },
        reset: () => {
            stopAuto();
            const mid = -st.setWidth;
            st.translateX = mid;
            st.prevTranslate = mid;
            applyTransform(false);
            if (st.autoOn) startAuto();
        }
    };
    
    init();
})();