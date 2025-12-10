// ===================================
// CATALOG FILTER - OPTIMIZED VERSION
// Production Ready & Lightweight
// ===================================

// Utility Functions
const $ = (s, p = document) => p.querySelector(s);
const $$ = (s, p = document) => p.querySelectorAll(s);

// ===================================
// FILTER MANAGER - Module Pattern
// ===================================
const FilterManager = (() => {
    let debounceTimer;
    
    const elements = {
        sidebar: null,
        toggleBtn: null,
        closeBtn: null,
        searchInput: null,
        sortSelect: null,
        productsGrid: null,
        resetBtn: null,
        applyPriceBtn: null,
        minPrice: null,
        maxPrice: null
    };

    // Initialize DOM references
    const init = () => {
        elements.sidebar = $('#filterSidebar');
        elements.toggleBtn = $('#filterToggleBtn');
        elements.closeBtn = $('#closeFilterBtn');
        elements.searchInput = $('#searchInput');
        elements.sortSelect = $('#sortSelect');
        elements.productsGrid = $('#productsGrid');
        elements.resetBtn = $('#resetFilterBtn');
        elements.applyPriceBtn = $('#applyPriceBtn');
        elements.minPrice = $('#minPrice');
        elements.maxPrice = $('#maxPrice');

        if (!elements.sidebar || !elements.productsGrid) return false;

        attachEvents();
        attachClearAllEvent();
        return true;
    };

    // Show/Hide Loading
    const setLoading = (show) => {
        if (!elements.productsGrid) return;
        elements.productsGrid.style.opacity = show ? '0.5' : '1';
        elements.productsGrid.style.pointerEvents = show ? 'none' : 'auto';
    };

    // Collect Filter Data
    const getFilterData = () => {
        const data = {};

        // Search
        if (elements.searchInput?.value.trim()) {
            data.search = elements.searchInput.value.trim();
        }

        // Categories
        const categories = Array.from($$('input[name="category"]:checked')).map(cb => cb.value);
        if (categories.length) data.categories = categories;

        // Tokos
        const tokos = Array.from($$('input[name="toko"]:checked')).map(cb => cb.value);
        if (tokos.length) data.tokos = tokos;

        // Price Range
        if (elements.minPrice?.value) data.min_price = elements.minPrice.value;
        if (elements.maxPrice?.value) data.max_price = elements.maxPrice.value;

        // Availability
        const availability = $('input[name="availability"]:checked');
        if (availability?.value !== 'all') data.availability = availability.value;

        // Stock Range
        const stockRange = $('input[name="stock-range"]:checked');
        if (stockRange?.value !== 'all') data.stock_range = stockRange.value;

        // Discount
        if ($('input[name="discount"]')?.checked) data.discount = 'true';

        // Sort
        if (elements.sortSelect?.value) data.sort = elements.sortSelect.value;

        return data;
    };

    // Update URL without reload
    const updateURL = (data) => {
        const url = new URL(window.location);
        url.search = '';
        
        Object.entries(data).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach(v => url.searchParams.append(`${key}[]`, v));
            } else {
                url.searchParams.set(key, value);
            }
        });
        
        history.pushState({}, '', url);
    };

    // Update DOM with new content
    const updateDOM = (html) => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Update products grid
        const newGrid = doc.getElementById('productsGrid');
        if (newGrid && elements.productsGrid) {
            elements.productsGrid.innerHTML = newGrid.innerHTML;
        }
        
        // Update pagination
        const newPagination = doc.querySelector('.pagination');
        const currentPagination = $('.pagination');
        
        if (newPagination && currentPagination) {
            currentPagination.innerHTML = newPagination.innerHTML;
        } else if (newPagination) {
            $('.products-container')?.appendChild(newPagination);
        } else if (currentPagination) {
            currentPagination.remove();
        }
        
        // Update active filters
        const newFilters = doc.getElementById('activeFilters');
        const currentFilters = $('#activeFilters');
        
        if (newFilters) {
            if (currentFilters) {
                currentFilters.outerHTML = newFilters.outerHTML;
            } else {
                elements.productsGrid?.insertAdjacentHTML('beforebegin', newFilters.outerHTML);
            }
            attachClearAllEvent();
        } else if (currentFilters) {
            currentFilters.remove();
        }
        
        // Refresh icons
        feather?.replace();
    };

    // Apply Filters (AJAX)
    const applyFilters = () => {
        setLoading(true);
        
        const data = getFilterData();
        updateURL(data);

        const params = new URLSearchParams();
        Object.entries(data).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach(v => params.append(`${key}[]`, v));
            } else {
                params.set(key, value);
            }
        });

        fetch(`${window.location.pathname}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            updateDOM(html);
            setLoading(false);
            
            // Close mobile filter
            if (window.innerWidth <= 1024) {
                elements.sidebar?.classList.remove('active');
            }
        })
        .catch(err => {
            console.error('Filter error:', err);
            setLoading(false);
        });
    };

    // Debounced Filter
    const debouncedFilter = () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilters, 500);
    };

    // Reset All Filters
    const resetFilters = () => {
        if (elements.searchInput) elements.searchInput.value = '';
        $$('input[name="category"]').forEach(cb => cb.checked = false);
        $$('input[name="toko"]').forEach(cb => cb.checked = false);
        if (elements.minPrice) elements.minPrice.value = '';
        if (elements.maxPrice) elements.maxPrice.value = '';
        const availAll = $('input[name="availability"][value="all"]');
        if (availAll) availAll.checked = true;
        const stockAll = $('input[name="stock-range"][value="all"]');
        if (stockAll) stockAll.checked = true;
        const discount = $('input[name="discount"]');
        if (discount) discount.checked = false;
        if (elements.sortSelect) elements.sortSelect.value = 'newest';
        
        applyFilters();
    };

    // Attach Events
    const attachEvents = () => {
        // Mobile Toggle
        elements.toggleBtn?.addEventListener('click', () => {
            elements.sidebar?.classList.add('active');
        });

        elements.closeBtn?.addEventListener('click', () => {
            elements.sidebar?.classList.remove('active');
        });

        // Search (debounced)
        elements.searchInput?.addEventListener('input', debouncedFilter);

        // Category/Toko checkboxes
        $$('input[name="category"], input[name="toko"]').forEach(cb => {
            cb.addEventListener('change', applyFilters);
        });

        // Radio buttons
        $$('input[name="availability"], input[name="stock-range"]').forEach(radio => {
            radio.addEventListener('change', applyFilters);
        });

        // Discount
        $('input[name="discount"]')?.addEventListener('change', applyFilters);

        // Price Range
        elements.applyPriceBtn?.addEventListener('click', applyFilters);

        // Sort
        elements.sortSelect?.addEventListener('change', applyFilters);

        // Reset
        elements.resetBtn?.addEventListener('click', resetFilters);

        // Pagination (Event Delegation)
        document.addEventListener('click', handlePaginationClick);
    };

    // Clear All Event
    const attachClearAllEvent = () => {
        $('#clearAllFilters')?.addEventListener('click', resetFilters);
    };

    // Pagination Handler
    const handlePaginationClick = (e) => {
        const link = e.target.closest('.pagination a');
        if (!link) return;
        
        e.preventDefault();
        const url = new URL(link.href);
        
        setLoading(true);
        
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            updateDOM(html);
            history.pushState({}, '', url);
            elements.productsGrid?.scrollIntoView({ behavior: 'smooth' });
            setLoading(false);
        })
        .catch(err => {
            console.error('Pagination error:', err);
            setLoading(false);
        });
    };

    return { init };
})();

// ===================================
// INITIALIZATION
// ===================================
const initCatalog = () => {
    try {
        feather?.replace();
        
        if (FilterManager.init()) {
            console.log('✅ Catalog Filter initialized');
        } else {
            console.warn('⚠️ Catalog elements not found');
        }
    } catch (err) {
        console.error('❌ Catalog init failed:', err);
    }
};

// Run on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCatalog);
} else {
    initCatalog();
}