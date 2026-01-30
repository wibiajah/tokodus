// ========================================
// SECTION: SPECIAL EDITION PRODUCT (438) - OPTIMIZED
// ========================================

(function() {
  'use strict';
  
  // =====================================================================
  // CONFIGURATION & CONSTANTS
  // =====================================================================
  const CONFIG = {
    selectors: {
      categoryItems: '.spesialedition-navbar-menu-438 li',
      productSections: '.spesial-edition-438',
      productCards: '.product-card-438',
      wishlistIcon: '.wishlist-icon-438',
      shareIcon: '.share-icon-438'
    },
    breakpoints: {
      mobile: 767
    },
    itemsPerPage: {
      mobile: 4,
      desktop: 8
    },
    debounceDelay: 250
  };
  
  // =====================================================================
  // UTILITY FUNCTIONS
  // =====================================================================
  
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
  
  function getItemsPerPage() {
    return window.innerWidth <= CONFIG.breakpoints.mobile 
      ? CONFIG.itemsPerPage.mobile 
      : CONFIG.itemsPerPage.desktop;
  }
  
  // =====================================================================
  // CATEGORY SWITCHING MODULE
  // =====================================================================
  
  const CategoryManager = {
    items: null,
    sections: null,
    
    init() {
      this.items = document.querySelectorAll(CONFIG.selectors.categoryItems);
      this.sections = document.querySelectorAll(CONFIG.selectors.productSections);
      
      if (!this.items.length || !this.sections.length) return;
      
      this.setupInitialState();
      this.attachEventListeners();
    },
    
    setupInitialState() {
      // Hide all sections first
      this.sections.forEach(section => section.classList.remove('active'));
      
      // Activate first category
      if (this.items.length > 0) {
        this.items[0].classList.add('active');
        
        const firstCategory = this.items[0].getAttribute('data-category');
        const firstSection = document.getElementById(`${firstCategory}-438`);
        
        if (firstSection) {
          firstSection.classList.add('active');
        }
        
        // Initialize pagination for first category
        PaginationManager.init(firstCategory);
      }
    },
    
    attachEventListeners() {
      // Event delegation for better performance
      const navContainer = this.items[0]?.parentElement;
      
      if (navContainer) {
        navContainer.addEventListener('click', (e) => {
          const categoryItem = e.target.closest('li');
          
          if (categoryItem && Array.from(this.items).includes(categoryItem)) {
            this.switchCategory(categoryItem);
          }
        });
      }
    },
    
    switchCategory(clickedItem) {
      const category = clickedItem.getAttribute('data-category');
      
      // Batch DOM updates
      requestAnimationFrame(() => {
        // Update navbar active state
        this.items.forEach(item => item.classList.remove('active'));
        clickedItem.classList.add('active');
        
        // Update sections active state
        this.sections.forEach(section => section.classList.remove('active'));
        
        const targetSection = document.getElementById(`${category}-438`);
        if (targetSection) {
          targetSection.classList.add('active');
        }
        
        // Reset pagination for new category
        PaginationManager.init(category);
      });
    }
  };
  
  // =====================================================================
  // PAGINATION MODULE
  // =====================================================================
  
  const PaginationManager = {
    currentPages: new Map(),
    
    init(category) {
      const section = document.getElementById(`${category}-438`);
      const paginationContainer = document.getElementById(`pagination-${category}-438`);
      
      if (!section || !paginationContainer) return;
      
      const allCards = Array.from(section.querySelectorAll(CONFIG.selectors.productCards));
      const itemsPerPage = getItemsPerPage();
      const totalPages = Math.ceil(allCards.length / itemsPerPage);
      
      // Hide pagination if only 1 page or no products
      if (totalPages <= 1) {
        paginationContainer.style.display = 'none';
        allCards.forEach(card => card.style.display = 'flex');
        return;
      }
      
      paginationContainer.style.display = 'flex';
      
      // Store current page
      this.currentPages.set(category, 1);
      
      // Show first page without scrolling
      this.showPage(category, 1, allCards, totalPages, paginationContainer, false);
    },
    
    showPage(category, pageNum, cards, totalPages, container, shouldScroll = true) {
      const itemsPerPage = getItemsPerPage();
      const startIndex = (pageNum - 1) * itemsPerPage;
      const endIndex = startIndex + itemsPerPage;
      
      // Batch DOM updates
      requestAnimationFrame(() => {
        cards.forEach((card, index) => {
          card.style.display = (index >= startIndex && index < endIndex) ? 'flex' : 'none';
        });
      });
      
      // Update pagination buttons
      this.renderPagination(category, pageNum, totalPages, cards, container);
      
      // Scroll to section if needed
      if (shouldScroll) {
        const section = document.getElementById(`${category}-438`);
        if (section) {
          section.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start' 
          });
        }
      }
      
      // Store current page
      this.currentPages.set(category, pageNum);
    },
    
    renderPagination(category, currentPage, totalPages, cards, container) {
      const fragment = document.createDocumentFragment();
      
      // Previous button
      const prevBtn = this.createButton('‹', currentPage === 1, () => {
        if (currentPage > 1) {
          this.showPage(category, currentPage - 1, cards, totalPages, container, true);
        }
      });
      fragment.appendChild(prevBtn);
      
      // Page number buttons
      for (let i = 1; i <= totalPages; i++) {
        const pageBtn = this.createButton(i, false, () => {
          this.showPage(category, i, cards, totalPages, container, true);
        });
        
        if (i === currentPage) {
          pageBtn.classList.add('active');
        }
        
        fragment.appendChild(pageBtn);
      }
      
      // Next button
      const nextBtn = this.createButton('›', currentPage === totalPages, () => {
        if (currentPage < totalPages) {
          this.showPage(category, currentPage + 1, cards, totalPages, container, true);
        }
      });
      fragment.appendChild(nextBtn);
      
      // Single DOM update
      container.innerHTML = '';
      container.appendChild(fragment);
    },
    
    createButton(text, disabled, onClick) {
      const btn = document.createElement('button');
      btn.textContent = text;
      btn.disabled = disabled;
      btn.addEventListener('click', onClick);
      return btn;
    },
    
    handleResize: debounce(function() {
      const activeCategory = document.querySelector('.spesialedition-navbar-menu-438 li.active');
      
      if (activeCategory) {
        const category = activeCategory.getAttribute('data-category');
        PaginationManager.init(category);
      }
    }, CONFIG.debounceDelay)
  };
  
  // =====================================================================
  // INTERACTION HANDLERS MODULE
  // =====================================================================
  
  const InteractionHandlers = {
    init() {
      // Event delegation for wishlist and share icons
      document.addEventListener('click', (e) => {
        const wishlistIcon = e.target.closest(CONFIG.selectors.wishlistIcon);
        const shareIcon = e.target.closest(CONFIG.selectors.shareIcon);
        
        if (wishlistIcon) {
          e.preventDefault();
          e.stopPropagation();
          this.handleWishlist();
        }
        
        if (shareIcon) {
          e.preventDefault();
          e.stopPropagation();
          this.handleShare();
        }
      });
    },
    
    handleWishlist() {
      console.log('Added to wishlist');
      // Optional: Add toast notification
      // this.showToast('Added to wishlist');
    },
    
    handleShare() {
      console.log('Share product');
      // Optional: Open share modal
      // this.showShareModal();
    },
    
    // Optional: Toast notification
    showToast(message) {
      const toast = document.createElement('div');
      toast.className = 'toast-438';
      toast.textContent = message;
      toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #333;
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 9999;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
      `;
      
      document.body.appendChild(toast);
      
      requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
      });
      
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
          if (document.body.contains(toast)) {
            document.body.removeChild(toast);
          }
        }, 300);
      }, 3000);
    }
  };
  
  // =====================================================================
  // MAIN INITIALIZATION
  // =====================================================================
  
  function init() {
    try {
      // Initialize modules
      CategoryManager.init();
      InteractionHandlers.init();
      
      // Setup resize handler
      window.addEventListener('resize', PaginationManager.handleResize);
      
      console.log('✅ Special Edition section 438 initialized');
    } catch (error) {
      console.error('❌ Error initializing section 438:', error);
    }
  }
  
  // DOM ready check
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
})();