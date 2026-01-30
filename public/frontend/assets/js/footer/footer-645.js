/* ========================================
   FOOTER JAVASCRIPT - PREFIX 645
   File: footer-645.js
   ======================================== */

(function() {
  'use strict';

  /* ===== CONFIGURATION ===== */
  const FOOTER_645_CONFIG = {
    formId: 'footer-645-form',
    yearSelector: '.footer-645__year',
    linkSelector: '.footer-645__link',
    formInputSelector: '.footer-645__input',
    formButtonSelector: '.footer-645__btn',
    // API endpoint untuk newsletter (ganti sesuai backend Anda)
    newsletterEndpoint: '/api/newsletter/subscribe'
  };

  /* ===== INITIALIZATION ===== */
  function footer645Init() {
    footer645SetCurrentYear();
    footer645SetupNewsletterForm();
    footer645SetupSmoothScroll();
    footer645SetupFormValidation();
  }

  /* ===== SET CURRENT YEAR ===== */
  function footer645SetCurrentYear() {
    const yearElement = document.querySelector(FOOTER_645_CONFIG.yearSelector);
    if (yearElement) {
      const currentYear = new Date().getFullYear();
      yearElement.textContent = currentYear;
    }
  }

  /* ===== NEWSLETTER FORM HANDLER ===== */
  function footer645SetupNewsletterForm() {
    const form = document.getElementById(FOOTER_645_CONFIG.formId);
    
    if (!form) return;

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const emailInput = form.querySelector(FOOTER_645_CONFIG.formInputSelector);
      const submitBtn = form.querySelector(FOOTER_645_CONFIG.formButtonSelector);
      const email = emailInput.value.trim();

      // Validasi email
      if (!footer645ValidateEmail(email)) {
        footer645ShowMessage('Email tidak valid!', 'error');
        return;
      }

      // Disable button saat submit
      const originalBtnText = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = 'Loading...';

      // Simulate API call (ganti dengan real API call)
      footer645SubmitNewsletter(email)
        .then(response => {
          footer645ShowMessage('Terima kasih! Email Anda telah terdaftar.', 'success');
          emailInput.value = '';
        })
        .catch(error => {
          footer645ShowMessage('Terjadi kesalahan. Silakan coba lagi.', 'error');
          console.error('Newsletter error:', error);
        })
        .finally(() => {
          submitBtn.disabled = false;
          submitBtn.textContent = originalBtnText;
        });
    });
  }

  /* ===== EMAIL VALIDATION ===== */
  function footer645ValidateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  /* ===== SUBMIT NEWSLETTER (API CALL) ===== */
  function footer645SubmitNewsletter(email) {
    // Simulasi API call dengan Promise
    // GANTI DENGAN REAL API CALL ANDA
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        // Simulate success
        if (email) {
          resolve({ success: true, message: 'Email registered' });
        } else {
          reject(new Error('Invalid email'));
        }
      }, 1000);
    });

    /* CONTOH REAL API CALL:
    return fetch(FOOTER_645_CONFIG.newsletterEndpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
      },
      body: JSON.stringify({ email: email })
    })
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.json();
    });
    */
  }

  /* ===== SHOW MESSAGE (TOAST/ALERT) ===== */
  function footer645ShowMessage(message, type = 'info') {
    // Check if notification function exists from main app
    if (typeof showNotification === 'function') {
      showNotification(message, type);
      return;
    }

    // Fallback: Create simple toast
    const existingToast = document.querySelector('.footer-645-toast');
    if (existingToast) existingToast.remove();

    const toast = document.createElement('div');
    toast.className = `footer-645-toast footer-645-toast--${type}`;
    toast.textContent = message;
    
    // Inject styles if not exists
    if (!document.getElementById('footer-645-toast-styles')) {
      const style = document.createElement('style');
      style.id = 'footer-645-toast-styles';
      style.textContent = `
        .footer-645-toast {
          position: fixed;
          bottom: 20px;
          right: 20px;
          padding: 15px 25px;
          background: #28a745;
          color: white;
          border-radius: 8px;
          box-shadow: 0 4px 12px rgba(0,0,0,0.15);
          z-index: 10000;
          font-size: 14px;
          animation: footer645SlideIn 0.3s ease;
        }
        .footer-645-toast--error { background: #dc3545; }
        .footer-645-toast--info { background: #17a2b8; }
        @keyframes footer645SlideIn {
          from { transform: translateX(400px); opacity: 0; }
          to { transform: translateX(0); opacity: 1; }
        }
      `;
      document.head.appendChild(style);
    }

    document.body.appendChild(toast);

    setTimeout(() => {
      toast.style.animation = 'footer645SlideIn 0.3s ease reverse';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  /* ===== SMOOTH SCROLL FOR FOOTER LINKS ===== */
  function footer645SetupSmoothScroll() {
    const links = document.querySelectorAll(FOOTER_645_CONFIG.linkSelector);
    
    links.forEach(link => {
      const href = link.getAttribute('href');
      
      // Only apply to anchor links
      if (href && href.startsWith('#')) {
        link.addEventListener('click', function(e) {
          const targetId = href.substring(1);
          const targetElement = document.getElementById(targetId);
          
          if (targetElement) {
            e.preventDefault();
            targetElement.scrollIntoView({ 
              behavior: 'smooth',
              block: 'start'
            });
            
            // Update URL without triggering page reload
            if (history.pushState) {
              history.pushState(null, null, href);
            }
          }
        });
      }
    });
  }

  /* ===== FORM REAL-TIME VALIDATION ===== */
  function footer645SetupFormValidation() {
    const emailInput = document.querySelector(FOOTER_645_CONFIG.formInputSelector);
    
    if (!emailInput) return;

    emailInput.addEventListener('blur', function() {
      const email = this.value.trim();
      
      if (email && !footer645ValidateEmail(email)) {
        this.style.borderBottomColor = '#dc3545';
      } else {
        this.style.borderBottomColor = '';
      }
    });

    emailInput.addEventListener('input', function() {
      // Reset error state on input
      this.style.borderBottomColor = '';
    });
  }

  /* ===== INIT ON DOM READY ===== */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', footer645Init);
  } else {
    footer645Init();
  }

  /* ===== EXPOSE PUBLIC API (OPTIONAL) ===== */
  window.Footer645 = {
    init: footer645Init,
    showMessage: footer645ShowMessage,
    validateEmail: footer645ValidateEmail
  };

})();