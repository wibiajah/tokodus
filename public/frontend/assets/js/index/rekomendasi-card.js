/**
 * PRODUCT CARDS INFINITE SCROLL - BULLETPROOF VERSION
 * Same smooth infinite scroll as category slider
 * Zero glitch, production-ready
 * NO CARD CROPPING - Only shows complete cards
 */

(function() {
    'use strict';
    
    const CFG = {
        grid: '.products-grid-441',
        card: '.product-card-441',
        autoMs: 3000,
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
    
    let grid, resizeTimer;
    
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setup);
        } else {
            setup();
        }
    }
    
    function setup() {
        grid = document.querySelector(CFG.grid);
        
        if (!grid) return;
        
        const cards = Array.from(grid.querySelectorAll(CFG.card));
        st.cardCount = cards.length;
        
        if (st.cardCount === 0) return;
        
        // Convert grid to slider format
        grid.style.display = 'flex';
        grid.style.flexWrap = 'nowrap';
        grid.style.overflow = 'hidden';
        grid.style.position = 'relative';
        
        // Create wrapper untuk infinite
        const wrapper = document.createElement('div');
        wrapper.style.display = 'flex';
        wrapper.style.gap = getComputedStyle(grid).gap || '20px';
        wrapper.className = 'products-wrapper-441';
        wrapper.style.justifyContent = 'center';
        
        // Move cards to wrapper
        const html = grid.innerHTML;
        wrapper.innerHTML = html + html + html; // Triple clone
        
        grid.innerHTML = '';
        grid.appendChild(wrapper);
        
        calcDim(wrapper);
        
        // Start at middle set (wrapper already centered by calcDim)
        const mid = -st.setWidth;
        st.translateX = mid;
        st.prevTranslate = mid;
        applyTransform(wrapper, false);
        
        bindEvents(wrapper);
        startAuto(wrapper);
    }
    
    function calcDim(wrapper) {
        const card = wrapper.querySelector(CFG.card);
        if (!card) return;
        
        const w = card.offsetWidth;
        const gap = parseFloat(getComputedStyle(wrapper).gap) || 20;
        st.cardW = w + gap;
        st.setWidth = st.cardW * st.cardCount;
        
        // Calculate how many cards can fit FULLY
        const containerWidth = grid.offsetWidth;
        const visibleCards = Math.floor(containerWidth / st.cardW);
        
        // Set wrapper to show only complete cards, but keep it flexible for infinite scroll
        if (visibleCards > 0) {
            const maxWrapperWidth = (visibleCards * st.cardW) - gap;
            wrapper.style.width = 'auto';
            wrapper.style.maxWidth = 'none';
            wrapper.style.margin = '0 auto';
            
            // Center the visible area within grid
            const paddingNeeded = (containerWidth - maxWrapperWidth) / 2;
            grid.style.paddingLeft = Math.max(0, paddingNeeded) + 'px';
            grid.style.paddingRight = Math.max(0, paddingNeeded) + 'px';
        }
    }
    
    function bindEvents(wrapper) {
        // Mouse events
        let mouseActive = false;
        
        grid.addEventListener('mousedown', e => {
            mouseActive = true;
            handleDragStart(e.pageX);
        });
        
        grid.addEventListener('mousemove', e => {
            if (!mouseActive) return;
            e.preventDefault();
            handleDragMove(e.pageX, wrapper);
        });
        
        const handleMouseEnd = () => {
            if (!mouseActive) return;
            mouseActive = false;
            handleDragEnd(wrapper);
        };
        
        grid.addEventListener('mouseup', handleMouseEnd);
        document.addEventListener('mouseup', handleMouseEnd);
        grid.addEventListener('mouseleave', handleMouseEnd);
        
        // Touch events
        grid.style.touchAction = 'pan-y pinch-zoom';
        wrapper.style.touchAction = 'none';
        
        let touchActive = false;
        
        grid.addEventListener('touchstart', e => {
            const touch = e.touches[0];
            if (!touch) return;
            e.preventDefault();
            touchActive = true;
            handleDragStart(touch.clientX);
        }, { passive: false });
        
        grid.addEventListener('touchmove', e => {
            if (!touchActive) return;
            const touch = e.touches[0];
            if (!touch) return;
            e.preventDefault();
            handleDragMove(touch.clientX, wrapper);
        }, { passive: false });
        
        const handleTouchEnd = () => {
            if (!touchActive) return;
            touchActive = false;
            handleDragEnd(wrapper);
        };
        
        grid.addEventListener('touchend', handleTouchEnd, { passive: false });
        grid.addEventListener('touchcancel', handleTouchEnd);
        
        // Hover pause
        grid.addEventListener('mouseenter', stopAuto);
        grid.addEventListener('mouseleave', () => {
            if (!st.drag) {
                st.autoOn = true;
                startAuto(wrapper);
            }
        });
        
        // Resize
        window.addEventListener('resize', () => handleResize(wrapper));
        
        grid.style.cursor = 'grab';
    }
    
    function handleDragStart(x) {
        st.drag = true;
        st.autoOn = false;
        st.startX = x;
        st.currentX = x;
        st.prevTranslate = st.translateX;
        
        stopAuto();
        cancelAnimationFrame(st.animFrame);
        
        grid.classList.add('grabbing');
        grid.style.cursor = 'grabbing';
    }
    
    function handleDragMove(x, wrapper) {
        if (!st.drag) return;
        
        st.currentX = x;
        const diff = st.currentX - st.startX;
        st.translateX = st.prevTranslate + diff;
        
        applyTransform(wrapper, false);
        checkLoop(wrapper);
    }
    
    function handleDragEnd(wrapper) {
        st.drag = false;
        grid.classList.remove('grabbing');
        grid.style.cursor = 'grab';
        
        snapToCard(wrapper);
    }
    
    function checkLoop(wrapper) {
        if (st.setWidth === 0) return;
        
        const midStart = -st.setWidth;
        const midEnd = -st.setWidth * 2;
        
        // Only reposition if outside middle set
        if (st.translateX > midStart) {
            st.translateX -= st.setWidth;
            st.prevTranslate = st.translateX;
            
            if (st.drag) {
                st.startX += st.setWidth;
            }
            
            applyTransform(wrapper, false);
        } else if (st.translateX < midEnd) {
            st.translateX += st.setWidth;
            st.prevTranslate = st.translateX;
            
            if (st.drag) {
                st.startX -= st.setWidth;
            }
            
            applyTransform(wrapper, false);
        }
    }
    
    function applyTransform(wrapper, animate) {
        cancelAnimationFrame(st.animFrame);
        
        st.animFrame = requestAnimationFrame(() => {
            wrapper.style.transition = animate ? `transform ${CFG.transMs}ms cubic-bezier(0.25, 0.46, 0.45, 0.94)` : 'none';
            wrapper.style.transform = `translate3d(${st.translateX}px, 0, 0)`;
        });
    }
    
    function snapToCard(wrapper) {
        const snapPos = Math.round(st.translateX / st.cardW) * st.cardW;
        st.translateX = snapPos;
        st.prevTranslate = snapPos;
        
        applyTransform(wrapper, true);
        
        setTimeout(() => {
            checkLoop(wrapper);
            
            if (!st.drag) {
                st.autoOn = true;
                startAuto(wrapper);
            }
        }, CFG.snapMs);
    }
    
    function startAuto(wrapper) {
        if (!st.autoOn || st.timer) return;
        
        st.timer = setInterval(() => {
            if (st.drag) return;
            
            st.translateX -= st.cardW;
            st.prevTranslate = st.translateX;
            
            applyTransform(wrapper, true);
            
            setTimeout(() => {
                checkLoop(wrapper);
            }, CFG.transMs);
        }, CFG.autoMs);
    }
    
    function stopAuto() {
        if (st.timer) {
            clearInterval(st.timer);
            st.timer = null;
        }
    }
    
    function handleResize(wrapper) {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            stopAuto();
            
            calcDim(wrapper); // This will recalculate max-width
            
            const mid = -st.setWidth;
            st.translateX = mid;
            st.prevTranslate = mid;
            
            applyTransform(wrapper, false);
            
            if (st.autoOn) {
                startAuto(wrapper);
            }
        }, 250);
    }
    
    // Cleanup
    window.addEventListener('beforeunload', () => {
        stopAuto();
        cancelAnimationFrame(st.animFrame);
    });
    
    // Public API
    window.productScroll_441 = {
        start: () => {
            st.autoOn = true;
            const wrapper = grid?.querySelector('.products-wrapper-441');
            if (wrapper) startAuto(wrapper);
        },
        stop: () => {
            st.autoOn = false;
            stopAuto();
        },
        reset: () => {
            stopAuto();
            const wrapper = grid?.querySelector('.products-wrapper-441');
            if (wrapper) {
                const mid = -st.setWidth;
                st.translateX = mid;
                st.prevTranslate = mid;
                applyTransform(wrapper, false);
                if (st.autoOn) startAuto(wrapper);
            }
        }
    };
    
    init();
})();