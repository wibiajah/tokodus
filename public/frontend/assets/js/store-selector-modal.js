// ========================================
// STORE SELECTOR MODAL - FIXED VERSION
// ========================================

// GLOBAL VARIABLES
let storesData789 = [];
let allStores789 = [];
let selectedStoreModal789 = null;
let filteredStores789 = [];
let currentFilter789 = null;
let customerPostalCode789 = null;

// ========================================
// INITIALIZE MODAL
// ========================================
function initStoreModal789(stores, customerPostalCode = null) {
    if (!stores || stores.length === 0) {
        console.log('⚠️ No stores data provided');
        return;
    }
    
    storesData789 = stores;
    allStores789 = stores;
    filteredStores789 = stores;
    customerPostalCode789 = customerPostalCode;
    
    console.log('✅ Store Modal initialized with', stores.length, 'stores');
    console.log('📍 Customer Postal Code:', customerPostalCode789 || 'Not logged in');
    
    const nearestBtn = document.getElementById('filterNearest-789');
    if (nearestBtn) {
        if (customerPostalCode789) {
            nearestBtn.disabled = false;
            nearestBtn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Nearest
            `;
        } else {
            nearestBtn.disabled = true;
            nearestBtn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                Nearest (Login)
            `;
        }
    }
}

// ========================================
// OPEN MODAL
// ========================================
function openStoreModal789() {
    const overlay = document.getElementById('storeModalOverlay-789');
    const modal = document.getElementById('storeModal-789');
    
    if (!overlay || !modal) {
        console.error('Store modal elements not found');
        return;
    }
    
    filteredStores789 = storesData789;
    currentFilter789 = null;
    updateFilterButtons789();
    
    renderStoreList789(filteredStores789);
    
    overlay.classList.add('active');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    console.log('🔓 Store modal opened');
}

// ========================================
// CLOSE MODAL
// ========================================
function closeStoreModal789() {
    const overlay = document.getElementById('storeModalOverlay-789');
    const modal = document.getElementById('storeModal-789');
    
    if (overlay) overlay.classList.remove('active');
    if (modal) modal.classList.remove('active');
    
    document.body.style.overflow = '';
    
    const searchInput = document.getElementById('storeSearchInput-789');
    if (searchInput) searchInput.value = '';
    
    console.log('🔒 Store modal closed');
}

// ========================================
// RENDER STORE LIST WITH NUMBER BADGE
// ========================================
function renderStoreList789(stores) {
    const container = document.getElementById('storeListContainer-789');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (stores.length === 0) {
        container.innerHTML = `
            <div class="store-empty-state-789">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <p>No stores found</p>
            </div>
        `;
        return;
    }
    
    const listDiv = document.createElement('div');
    listDiv.className = 'store-list-789';
    
    stores.forEach((store, index) => {
        const storeDiv = document.createElement('div');
        storeDiv.className = `store-item-789 ${selectedStoreModal789?.id === store.id ? 'selected-789' : ''}`;
        storeDiv.dataset.storeId = store.id;
        storeDiv.onclick = () => selectStoreInModal789(store.id);
        
        // 🔥 LOCATION INFO
        let locationHTML = '';
        if (store.location && store.location.full_address) {
            locationHTML = `
                <div class="store-location-info-789">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span title="${store.location.full_address}">${store.location.full_address}</span>
                </div>
            `;
        } else if (store.postal_code) {
            locationHTML = `
                <div class="store-location-info-789">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span>Kodepos: ${store.postal_code}</span>
                </div>
            `;
        }
        
        // 🔥 DISTANCE BADGE
        let distanceBadge = '';
        if (store.distance && store.distance.estimate) {
            const badgeClass = `distance-badge-${store.distance.category}-789`;
            distanceBadge = `
                <div class="store-distance-badge-789 ${badgeClass}" title="${store.distance.label}">
                    <span>${store.distance.estimate}</span>
                </div>
            `;
        }
        
        // 🔥 SIMPLE NUMBER BADGE - ALWAYS SHOW
        const numberBadge = currentFilter789 === 'nearest' 
    ? `<div class="store-rank-badge-789">#${index + 1}</div>` 
    : '';

storeDiv.innerHTML = `
    ${numberBadge}
    <div class="store-item-content-789">
                <div class="store-item-name-789">${store.nama_toko}</div>
                ${locationHTML}
            </div>
            ${distanceBadge}
        `;
        
        listDiv.appendChild(storeDiv);
    });
    
    container.appendChild(listDiv);
    
    console.log(`📋 Rendered ${stores.length} stores`);
}

// ========================================
// FILTER: NEAREST (FIXED SORTING)
// ========================================
function filterByNearest789() {
    if (!customerPostalCode789) {
        showNotification789('❌ Please login to use location filter', 'error');
        return;
    }
    
    console.log('🎯 Filtering by nearest location...');
    
    currentFilter789 = 'nearest';
    
    // 🔥 FIX: Sort by distance first, THEN move null postal to bottom
    filteredStores789 = [...storesData789].sort((a, b) => {
        const hasPostalA = a.postal_code && a.postal_code.trim() !== '';
        const hasPostalB = b.postal_code && b.postal_code.trim() !== '';
        
        // BOTH have no postal - keep original order
        if (!hasPostalA && !hasPostalB) return 0;
        
        // A has no postal - move to bottom (return positive)
        if (!hasPostalA) return 1;
        
        // B has no postal - A stays on top (return negative)
        if (!hasPostalB) return -1;
        
        // BOTH have postal codes - sort by distance (ASCENDING = nearest first)
        const distA = a.distance?.raw_distance ?? 999999;
        const distB = b.distance?.raw_distance ?? 999999;
        
        return distA - distB; // ✅ 0 < 166 < 216 = CORRECT ORDER
    });
    
    console.log('📊 Sorted stores:', filteredStores789.map((s, i) => ({
        index: i + 1,
        name: s.nama_toko,
        postal: s.postal_code,
        distance: s.distance?.raw_distance,
        estimate: s.distance?.estimate
    })));
    
    updateFilterButtons789();
    renderStoreList789(filteredStores789);
    
    showNotification789('✅ Stores sorted by nearest location', 'success');
}

// ========================================
// RESET FILTER
// ========================================
function resetFilter789() {
    console.log('🔄 Resetting filter...');
    
    currentFilter789 = null;
    filteredStores789 = [...storesData789];
    
    updateFilterButtons789();
    renderStoreList789(filteredStores789);
    
    showNotification789('✅ Filter reset', 'success');
}

// ========================================
// UPDATE FILTER BUTTONS STATE
// ========================================
function updateFilterButtons789() {
    document.querySelectorAll('.filter-btn-789').forEach(btn => {
        btn.classList.remove('active');
    });
    
    if (currentFilter789 === 'nearest') {
        const nearestBtn = document.getElementById('filterNearest-789');
        if (nearestBtn) nearestBtn.classList.add('active');
    }
}

// ========================================
// SEARCH STORES (FIXED)
// ========================================
function searchStores789(query) {
    const searchTerm = query.toLowerCase().trim();
    
    console.log(`🔍 Searching: "${searchTerm}"`);
    
    if (searchTerm === '') {
        if (currentFilter789 === 'nearest') {
            filterByNearest789();
        } else {
            filteredStores789 = [...storesData789];
            renderStoreList789(filteredStores789);
        }
    } else {
        const searchResults = storesData789.filter(store => 
            store.nama_toko.toLowerCase().includes(searchTerm)
        );
        
        if (currentFilter789 === 'nearest') {
            // Apply nearest sorting to search results
            filteredStores789 = searchResults.sort((a, b) => {
                const hasPostalA = a.postal_code && a.postal_code.trim() !== '';
                const hasPostalB = b.postal_code && b.postal_code.trim() !== '';
                
                if (!hasPostalA && hasPostalB) return 1;
                if (hasPostalA && !hasPostalB) return -1;
                if (!hasPostalA && !hasPostalB) return 0;
                
                const distA = a.distance ? (a.distance.raw_distance || 999999) : 999999;
                const distB = b.distance ? (b.distance.raw_distance || 999999) : 999999;
                return distA - distB;
            });
        } else {
            filteredStores789 = searchResults;
        }
        
        renderStoreList789(filteredStores789);
    }
    
    console.log(`📋 Search results: ${filteredStores789.length} stores`);
}

// ========================================
// SELECT STORE IN MODAL
// ========================================
function selectStoreInModal789(storeId) {
    const store = storesData789.find(s => s.id === storeId);
    if (!store) {
        console.error('Store not found:', storeId);
        return;
    }
    
    selectedStoreModal789 = store;
    
    document.querySelectorAll('.store-item-789').forEach(item => {
        item.classList.remove('selected-789');
    });
    
    const selectedItem = document.querySelector(`[data-store-id="${storeId}"]`);
    if (selectedItem) {
        selectedItem.classList.add('selected-789');
    }
    
    const confirmBtn = document.getElementById('confirmStoreBtn-789');
    if (confirmBtn) confirmBtn.disabled = false;
    
    console.log('✅ Store selected:', store.nama_toko);
}

// ========================================
// CONFIRM STORE SELECTION
// ========================================
function confirmStoreSelection789() {
    if (!selectedStoreModal789) {
        showNotification789('❌ Please select a store first', 'error');
        return;
    }
    
    if (typeof handleStoreSelected789 === 'function') {
        handleStoreSelected789(selectedStoreModal789);
    } else {
        console.error('handleStoreSelected789() not found');
    }
    
    closeStoreModal789();
    
    console.log('✅ Store confirmed:', selectedStoreModal789.nama_toko);
}

// ========================================
// SHOW NOTIFICATION
// ========================================
function showNotification789(message, type = 'success') {
    const existingToast = document.querySelector('.notification-toast-789');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.className = `notification-toast-789 ${type}-789`;
    
    const icon = type === 'success' ? '✓' : '✕';
    
    toast.innerHTML = `
        <i>${icon}</i>
        <span class="notification-message-789">${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show-789'), 100);
    
    setTimeout(() => {
        toast.classList.remove('show-789');
        toast.classList.add('hide-789');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

// ========================================
// EVENT LISTENERS
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Store Modal: Initializing event listeners...');
    
    const overlay = document.getElementById('storeModalOverlay-789');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) closeStoreModal789();
        });
    }
    
    const modal = document.getElementById('storeModal-789');
    if (modal) {
        modal.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    const searchInput = document.getElementById('storeSearchInput-789');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchStores789(e.target.value);
            }, 300);
        });
    }
    
    const closeBtn = document.getElementById('closeStoreModal-789');
    if (closeBtn) closeBtn.addEventListener('click', closeStoreModal789);
    
    const cancelBtn = document.getElementById('cancelStoreBtn-789');
    if (cancelBtn) cancelBtn.addEventListener('click', closeStoreModal789);
    
    const confirmBtn = document.getElementById('confirmStoreBtn-789');
    if (confirmBtn) confirmBtn.addEventListener('click', confirmStoreSelection789);
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('storeModal-789');
            if (modal && modal.classList.contains('active')) {
                closeStoreModal789();
            }
        }
    });
    
    console.log('✅ Store Modal: Event listeners ready');
});