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
        initCategoryClicks();
        return true;
    };

    // Show/Hide Loading
    const setLoading = (show) => {
        if (!elements.productsGrid) return;
        elements.productsGrid.style.opacity = show ? '0.5' : '1';
        elements.productsGrid.style.pointerEvents = show ? 'none' : 'auto';
    };

    // Collect Filter Data - UPDATED FOR DROPDOWNS
    const getFilterData = () => {
        const data = {};

        // Search
        if (elements.searchInput?.value.trim()) {
            data.search = elements.searchInput.value.trim();
        }

        // Tipe (dari dropdown select, bukan radio)
        const tipeSelect = $('select[name="tipe"]');
        if (tipeSelect?.value) data.tipe = tipeSelect.value;

        // Jenis Bahan (dari dropdown select, bukan radio)
        const bahanSelect = $('select[name="jenis_bahan"]');
        if (bahanSelect?.value) data.jenis_bahan = bahanSelect.value;

        // Categories (dari checkboxes)
        const categories = Array.from($$('input[name="category"]:checked')).map(cb => cb.value);
        if (categories.length) data.categories = categories;

        // Tokos (jika ada)
        const tokos = Array.from($$('input[name="toko"]:checked')).map(cb => cb.value);
        if (tokos.length) data.tokos = tokos;

        // Price Range
        if (elements.minPrice?.value) data.min_price = elements.minPrice.value;
        if (elements.maxPrice?.value) data.max_price = elements.maxPrice.value;

        // Availability (dari dropdown select)
        const availabilitySelect = $('select[name="availability"]');
        if (availabilitySelect?.value && availabilitySelect.value !== 'all') {
            data.availability = availabilitySelect.value;
        }

        // Stock Range (dari dropdown select)
        const stockSelect = $('select[name="stock-range"]');
        if (stockSelect?.value && stockSelect.value !== 'all') {
            data.stock_range = stockSelect.value;
        }

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
        const newPagination = doc.querySelector('.pagination-323');
        const currentPagination = $('.pagination-323');
        
        if (newPagination && currentPagination) {
            currentPagination.innerHTML = newPagination.innerHTML;
        } else if (newPagination) {
            $('.products-container-323')?.appendChild(newPagination);
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
        if (typeof feather !== 'undefined') feather.replace();
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

    // Reset All Filters - UPDATED FOR DROPDOWNS
    const resetFilters = () => {
        if (elements.searchInput) elements.searchInput.value = '';
        
        // Reset dropdown ke nilai default
        const tipeSelect = $('select[name="tipe"]');
        if (tipeSelect) tipeSelect.value = '';
        
        const bahanSelect = $('select[name="jenis_bahan"]');
        if (bahanSelect) bahanSelect.value = '';
        
        const availSelect = $('select[name="availability"]');
        if (availSelect) availSelect.value = 'all';
        
        const stockSelect = $('select[name="stock-range"]');
        if (stockSelect) stockSelect.value = 'all';
        
        // Reset checkboxes
        $$('input[name="category"]').forEach(cb => cb.checked = false);
        $$('input[name="toko"]').forEach(cb => cb.checked = false);
        
        // Reset visual category items
        $$('.category-item-323').forEach(item => item.classList.remove('active'));
        
        if (elements.minPrice) elements.minPrice.value = '';
        if (elements.maxPrice) elements.maxPrice.value = '';
        
        const discount = $('input[name="discount"]');
        if (discount) discount.checked = false;
        
        if (elements.sortSelect) elements.sortSelect.value = 'newest';
        
        applyFilters();
    };

    // Initialize Category Clicks - NEW
    const initCategoryClicks = () => {
        $$('.category-item-323').forEach(item => {
            item.addEventListener('click', function() {
                const checkbox = this.querySelector('input[name="category"]');
                
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    this.classList.toggle('active', checkbox.checked);
                    applyFilters();
                }
            });
        });
    };

    // Attach Events - UPDATED FOR DROPDOWNS
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

        // Dropdown selects (bukan radio buttons lagi)
        $$('select[name="tipe"], select[name="jenis_bahan"], select[name="availability"], select[name="stock-range"]').forEach(select => {
            select.addEventListener('change', applyFilters);
        });

        // Category/Toko checkboxes
        $$('input[name="category"], input[name="toko"]').forEach(cb => {
            cb.addEventListener('change', applyFilters);
        });

        // Discount
        $('input[name="discount"]')?.addEventListener('change', applyFilters);

        // Price Range
        elements.applyPriceBtn?.addEventListener('click', applyFilters);
        elements.minPrice?.addEventListener('change', applyFilters);
        elements.maxPrice?.addEventListener('change', applyFilters);

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
        const link = e.target.closest('.pagination-323 a');
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
            window.scrollTo({ top: 0, behavior: 'smooth' });
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
        if (typeof feather !== 'undefined') feather.replace();
        
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