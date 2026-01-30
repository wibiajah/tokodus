/**
 * SECTION 440: COUNTDOWN TIMER - 440 PREFIX VERSION
 * Countdown Timer for Recommended Product Section
 * With Zoom Detection for Responsive Positioning
 */

(function() {
    'use strict';
    
    // Configuration
    const CONFIG_440 = {
        countdownSelector: {
            days: '#days-440',
            hours: '#hours-440',
            minutes: '#minutes-440',
            seconds: '#seconds-440'
        },
        countdownDuration: 24 * 60 * 60 * 1000 // 24 hours in ms
    };
    
    // State management
    const state_440 = {
        targetDate: null,
        countdownInterval: null,
        currentZoom: 1
    };
    
    /**
     * Detect browser zoom level
     */
    function detectZoom_440() {
        const zoom = Math.round((window.devicePixelRatio || 1) * 100) / 100;
        if (zoom !== state_440.currentZoom) {
            state_440.currentZoom = zoom;
            adjustLayout_440(zoom);
            console.log(`🔍 Zoom detected 440: ${(zoom * 100).toFixed(0)}%`);
        }
    }
    
    /**
     * Adjust layout based on zoom
     */
    function adjustLayout_440(zoom) {
        const inner = document.querySelector('.promo-product-440-inner');
        const countdown = document.querySelector('.countdown-440');
        
        if (!inner || !countdown) return;
        
        // Don't override CSS positioning - remove any inline styles
        countdown.style.right = '';
    }
    
    /**
     * Initialize all components
     */
    function init_440() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initComponents_440);
        } else {
            initComponents_440();
        }
    }
    
    /**
     * Initialize countdown and zoom detection
     */
    function initComponents_440() {
        initCountdown_440();
        initZoomDetection_440();
        
        console.log('✅ Countdown Section 440 initialized');
    }
    
    /**
     * Initialize zoom detection
     */
    function initZoomDetection_440() {
        // Initial detection
        detectZoom_440();
        
        // Detect on resize
        window.addEventListener('resize', detectZoom_440);
        
        // Detect on zoom (multiple methods for better compatibility)
        window.addEventListener('wheel', function(e) {
            if (e.ctrlKey) {
                setTimeout(detectZoom_440, 100);
            }
        }, { passive: true });
        
        // Periodic check (fallback)
        setInterval(detectZoom_440, 500);
        
        console.log('✅ Zoom detection 440 initialized');
    }
    
    /**
     * Initialize countdown timer
     */
    function initCountdown_440() {
        const elements = {
            days: document.querySelector(CONFIG_440.countdownSelector.days),
            hours: document.querySelector(CONFIG_440.countdownSelector.hours),
            minutes: document.querySelector(CONFIG_440.countdownSelector.minutes),
            seconds: document.querySelector(CONFIG_440.countdownSelector.seconds)
        };
        
        if (!elements.days || !elements.hours || !elements.minutes || !elements.seconds) {
            console.warn('⚠️ Countdown 440 elements not found');
            return;
        }
        
        // Set initial target date
        state_440.targetDate = new Date(Date.now() + CONFIG_440.countdownDuration);
        
        // Update countdown function
        function updateCountdown_440() {
            const now = Date.now();
            const distance = state_440.targetDate - now;
            
            // Reset if countdown finished
            if (distance < 0) {
                state_440.targetDate = new Date(Date.now() + CONFIG_440.countdownDuration);
                return;
            }
            
            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Update DOM
            elements.days.textContent = days;
            elements.hours.textContent = String(hours).padStart(2, '0');
            elements.minutes.textContent = String(minutes).padStart(2, '0');
            elements.seconds.textContent = String(seconds).padStart(2, '0');
        }
        
        // Initial update
        updateCountdown_440();
        
        // Start interval
        state_440.countdownInterval = setInterval(updateCountdown_440, 1000);
        
        console.log('✅ Countdown 440 timer started');
    }
    
    /**
     * Cleanup function
     */
    function cleanup_440() {
        if (state_440.countdownInterval) {
            clearInterval(state_440.countdownInterval);
            state_440.countdownInterval = null;
        }
        
        window.removeEventListener('resize', detectZoom_440);
        
        console.log('🧹 Cleanup 440 completed');
    }
    
    /**
     * Public API for custom target date
     */
    window.setCountdownTarget_440 = function(date) {
        if (!(date instanceof Date) || isNaN(date)) {
            console.error('❌ Invalid date provided for 440');
            return false;
        }
        
        state_440.targetDate = date;
        console.log('✅ Countdown 440 target updated:', date);
        return true;
    };
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', cleanup_440);
    
    // Initialize
    init_440();
    
})();