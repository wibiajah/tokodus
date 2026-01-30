/**
 * RECOMMENDED PRODUCT SLIDER - Version 325
 * SIMPLE & CLEAN - Mirip catalog, tanpa overcomplicated logic
 */

// =============================================
// COUNTDOWN TIMER
// =============================================
function startCountdown325() {
  const countdownDate = new Date();
  countdownDate.setDate(countdownDate.getDate() + 7);

  const timer = setInterval(() => {
    const now = new Date().getTime();
    const distance = countdownDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    const daysEl = document.getElementById('days-325');
    const hoursEl = document.getElementById('hours-325');
    const minutesEl = document.getElementById('minutes-325');
    const secondsEl = document.getElementById('seconds-325');

    if (daysEl) daysEl.textContent = days;
    if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
    if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
    if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');

    if (distance < 0) {
      clearInterval(timer);
      if (daysEl) daysEl.textContent = '0';
      if (hoursEl) hoursEl.textContent = '00';
      if (minutesEl) minutesEl.textContent = '00';
      if (secondsEl) secondsEl.textContent = '00';
    }
  }, 1000);
}

// =============================================
// SLIDER INIT - Desktop Auto-scroll, Mobile Simple
// =============================================
function initSliderDrag325() {
  const slider = document.getElementById('bestSellerSlider-325');
  if (!slider) return;

  const isMobile = window.innerWidth <= 768;
  
  if (isMobile) {
    // MOBILE: Simple scroll, no cloning
    slider.style.overflowX = 'auto';
    slider.style.webkitOverflowScrolling = 'touch';
    console.log('📱 Mobile slider ready');
    return;
  }

  // DESKTOP: Clone cards for infinite scroll
  const cardWidth = 240;
  const slideInterval = 2500;
  const originalCards = Array.from(slider.children).slice(0, 5);
  
  if (originalCards.length === 0) return;
  
  slider.innerHTML = '';
  
  for (let set = 0; set < 5; set++) {
    originalCards.forEach(card => {
      slider.appendChild(card.cloneNode(true));
    });
  }

  let currentScroll = cardWidth * 5;
  slider.scrollLeft = currentScroll;

  setInterval(() => {
    currentScroll += cardWidth;
    slider.scrollTo({ left: currentScroll, behavior: 'smooth' });

    setTimeout(() => {
      if (currentScroll >= cardWidth * 15) {
        slider.scrollTo({ left: cardWidth * 5, behavior: 'auto' });
        currentScroll = cardWidth * 5;
      }
    }, 400);
  }, slideInterval);

  slider.style.cursor = 'grab';
  console.log('💻 Desktop slider ready with auto-scroll');
}

// =============================================
// MOBILE FIX - Hapus icon yang blocking click
// =============================================
function fixMobileIcons() {
  if (window.innerWidth > 768) return;
  
  const slider = document.getElementById('bestSellerSlider-325');
  if (!slider) return;
  
  // Hapus semua icon di mobile
  const icons = slider.querySelectorAll('.card-icon-325, .shopping-cart-icon-325');
  icons.forEach(icon => icon.remove());
  
  console.log(`✅ Removed ${icons.length} icons from mobile`);
}

// =============================================
// INITIALIZE
// =============================================
document.addEventListener('DOMContentLoaded', () => {
  startCountdown325();
  
  setTimeout(() => {
    initSliderDrag325();
    fixMobileIcons();
  }, 100);
  
  if (typeof feather !== 'undefined') {
    feather.replace();
  }
});

console.log('✅ Recommended Product v325 loaded');