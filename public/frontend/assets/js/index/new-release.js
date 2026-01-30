// ========================================
// SECTION 6: PRODUCT NEW RELEASE JAVASCRIPT (437) - OPTIMIZED
// ========================================

(function() {
  'use strict';
  
  // Cache DOM selectors
  const selectors = {
    navItems: '.spesialedition-navbar-menu-437 li',
    sections: '.spesial-edition-437',
    ctaButtons: '.pcta-437'
  };
  
  // Utility function: debounce untuk prevent rapid clicks
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
  
  // Tab Navigation Module
  function initTabNavigation() {
    const navItems = document.querySelectorAll(selectors.navItems);
    const sections = document.querySelectorAll(selectors.sections);
    
    if (!navItems.length || !sections.length) return;
    
    function showContent(category) {
      const categoryLower = category.toLowerCase();
      
      // Batch DOM updates untuk better performance
      requestAnimationFrame(() => {
        navItems.forEach(item => item.classList.remove('active'));
        sections.forEach(section => section.classList.remove('active'));
        
        const selectedSection = document.getElementById(categoryLower + '-437');
        if (selectedSection) {
          selectedSection.classList.add('active');
        }
        
        const activeNavItem = Array.from(navItems).find(item => 
          item.textContent.trim().toLowerCase() === categoryLower
        );
        if (activeNavItem) {
          activeNavItem.classList.add('active');
        }
      });
    }
    
    // Delegated event listener (lebih efficient)
    const navContainer = navItems[0]?.parentElement;
    if (navContainer) {
      navContainer.addEventListener('click', function(e) {
        const navItem = e.target.closest('li');
        if (navItem && navItems.length && Array.from(navItems).includes(navItem)) {
          const category = navItem.textContent.trim();
          showContent(category);
        }
      });
    }
    
    // Initialize first tab
    if (sections.length > 0) sections[0].classList.add('active');
    if (navItems.length > 0) navItems[0].classList.add('active');
  }
  
  // Pagination Module
  function initPagination(containerId, paginationId, itemsPerPage = 8) {
    const container = document.getElementById(containerId);
    const pagination = document.getElementById(paginationId);
    
    if (!container || !pagination) return;
    
    const items = Array.from(container.children);
    const totalPages = Math.ceil(items.length / itemsPerPage);
    let currentPage = 1;
    
    // Cache calculations
    const pageRanges = new Map();
    for (let page = 1; page <= totalPages; page++) {
      pageRanges.set(page, {
        start: (page - 1) * itemsPerPage,
        end: page * itemsPerPage
      });
    }
    
    function showPage(page) {
      const range = pageRanges.get(page);
      if (!range) return;
      
      // Batch DOM updates
      requestAnimationFrame(() => {
        items.forEach((item, index) => {
          item.style.display = (index >= range.start && index < range.end) ? 'block' : 'none';
        });
      });
      
      updatePaginationButtons(page);
    }
    
    function updatePaginationButtons(page) {
      const fragment = document.createDocumentFragment();
      
      // Previous button
      const prevBtn = createButton('‹', page === 1, () => {
        if (currentPage > 1) {
          currentPage--;
          showPage(currentPage);
        }
      });
      fragment.appendChild(prevBtn);
      
      // Page numbers
      for (let i = 1; i <= totalPages; i++) {
        const btn = createButton(i, false, () => {
          currentPage = i;
          showPage(currentPage);
        });
        if (i === page) btn.classList.add('active');
        fragment.appendChild(btn);
      }
      
      // Next button
      const nextBtn = createButton('›', page === totalPages, () => {
        if (currentPage < totalPages) {
          currentPage++;
          showPage(currentPage);
        }
      });
      fragment.appendChild(nextBtn);
      
      // Single DOM update
      pagination.innerHTML = '';
      pagination.appendChild(fragment);
    }
    
    function createButton(text, disabled, onClick) {
      const btn = document.createElement('button');
      btn.textContent = text;
      btn.disabled = disabled;
      btn.addEventListener('click', onClick);
      return btn;
    }
    
    // Initialize
    if (items.length > 0) showPage(1);
  }
  
  // Smooth Scroll Module
  function initSmoothScroll() {
    const ctaButtons = document.querySelectorAll(selectors.ctaButtons);
    
    ctaButtons.forEach(button => {
      button.addEventListener('click', debounce(function(e) {
        const target = this.getAttribute('href');
        
        if (target && target.startsWith('#')) {
          e.preventDefault();
          const element = document.querySelector(target);
          
          if (element) {
            element.scrollIntoView({ 
              behavior: 'smooth',
              block: 'start'
            });
          }
        }
      }, 100));
    });
  }
  
  // Main initialization
  function init() {
    try {
      initTabNavigation();
      
      // Initialize pagination for each category
      const categories = [
        'newrelease',
        'lebaran',
        'christmas',
        'imlek'
      ];
      
      categories.forEach(category => {
        initPagination(
          `${category}-437`,
          `pagination-${category}-437`,
          8
        );
      });
      
      initSmoothScroll();
      
      console.log('✅ New Release section 437 initialized');
    } catch (error) {
      console.error('❌ Error initializing section 437:', error);
    }
  }
  
  // DOM ready check
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
})();