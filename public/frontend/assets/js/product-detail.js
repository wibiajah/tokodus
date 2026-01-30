// ========================================
// PRODUCT DETAIL PAGE - FIXED PRICE UPDATE
// Real-time price display per variant
// ========================================

// GLOBAL STATE
let selectedStore789 = null;
let selectedVariant789 = null;
let availableVariants789 = [];
let currentQuantity789 = 1;
let selectedColorVariant789 = null;
let selectedSizeVariant789 = null;
const CENTRAL_STORE_ID = 999;

//  NEW: Store original product price
let originalProductPrice789 = null;

const productId789 = window.location.pathname.split('/').pop();

// ========================================
// SHOW NOTIFICATION
// ========================================


// ========================================
// ADMIN WARNING
// ========================================
function showAdminWarning789() {
    showNotification('⚠️ Hanya customer yang dapat membeli produk!', 'error');
    setTimeout(() => {
        if (confirm('Anda login sebagai Admin/Staff.\n\nHanya customer yang dapat melakukan pembelian.\n\nApakah Anda ingin logout dan login sebagai customer?')) {
            window.location.href = '/logout';
        }
    }, 500);
}

// ========================================
// IMAGE GALLERY
// ========================================
function initImageGallery789() {
    const thumbnails = document.querySelectorAll('.thumbnail-789');
    const mainImage = document.getElementById('mainImage-789');
    
    if (!mainImage || thumbnails.length === 0) return;
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            thumbnails.forEach(t => t.classList.remove('active-789'));
            this.classList.add('active-789');
            
            const newImageSrc = this.getAttribute('data-image');
            mainImage.style.opacity = '0';
            
            setTimeout(() => {
                mainImage.src = newImageSrc;
                mainImage.style.opacity = '1';
            }, 150);
        });
    });
}

// ========================================
// STOCK SELECTION
// ========================================
function initStockSelection789() {
    const stockLocations = document.querySelectorAll('.stock-location-item-789');
    
    if (stockLocations.length === 0) return;
    
    stockLocations.forEach(item => {
        item.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (!radio) return;
            
            radio.checked = true;
            
            const tokoId = radio.getAttribute('data-toko-id');
            const tokoName = radio.getAttribute('data-toko-name');
            
            let variantsData = [];
            try {
                const variantsJson = radio.getAttribute('data-variants');
                variantsData = JSON.parse(variantsJson);
            } catch (e) {
                console.error('Failed to parse variants data:', e);
                return;
            }
            
            selectedStore789 = {
                id: tokoId,
                name: tokoName
            };
            
            availableVariants789 = variantsData;
            
            showVariantSelection789();
            
            console.log(' Store selected:', selectedStore789);
            console.log(' Available variants:', availableVariants789);
        });
    });
}

// ========================================
//  NEW: SAVE & RESTORE ORIGINAL PRICE
// ========================================
function saveOriginalPrice789() {
    if (originalProductPrice789) return; // Already saved
    
    const priceElement = document.querySelector('.price-main-789');
    if (priceElement) {
        originalProductPrice789 = priceElement.textContent.trim();
        console.log('💰 Original price saved:', originalProductPrice789);
    }
}

function restoreOriginalPrice789() {
    if (!originalProductPrice789) return;
    
    const priceElement = document.querySelector('.price-main-789');
    if (priceElement) {
        priceElement.textContent = originalProductPrice789;
        console.log('💰 Price restored to original:', originalProductPrice789);
    }
}

// ========================================
// SHOW VARIANT SELECTION
// ========================================
function showVariantSelection789() {
    //  Save original price before showing variants
    saveOriginalPrice789();
    
    const variantSection = document.getElementById('variantSelectionSection-789');
    const colorOptions = document.getElementById('colorOptions-789');
    const sizeGroup = document.getElementById('sizeVariantGroup-789');
    
    if (!variantSection || !colorOptions) return;
    
    colorOptions.innerHTML = '';
    sizeGroup.style.display = 'none';
    selectedColorVariant789 = null;
    selectedSizeVariant789 = null;
    selectedVariant789 = null;
    
    //  Reset price to original when re-selecting
    restoreOriginalPrice789();
    
    const infoDiv = document.getElementById('selectedVariantInfo-789');
    if (infoDiv) infoDiv.style.display = 'none';
    
    renderVariantPhotoThumbnails789();
    
    // Group variants by color
    const colorMap = new Map();
    
    availableVariants789.forEach(v => {
        const colorName = v.color || v.variant_name.split(' - ')[0];
        
        if (!colorMap.has(colorName)) {
            colorMap.set(colorName, {
                name: colorName,
                sizes: [],
                totalStock: 0
            });
        }
        
        const colorData = colorMap.get(colorName);
        colorData.sizes.push(v);
        colorData.totalStock += v.stock;
    });
    
    // Render color options
    colorMap.forEach((colorData, colorName) => {
        const btn = document.createElement('button');
        btn.className = 'variant-option-789';
        
        const stockText = colorData.totalStock > 0 ? `${colorData.totalStock} pcs` : '0 pcs';
        
        btn.innerHTML = `
            <span class="variant-color-name-789">${colorName}</span>
            <span class="variant-stock-badge-789">${stockText}</span>
        `;
        
        btn.dataset.color = colorName;
        btn.dataset.sizes = JSON.stringify(colorData.sizes);
        
        btn.addEventListener('click', function() {
            selectColor789(this);
        });
        
        colorOptions.appendChild(btn);
    });
    
    variantSection.style.display = 'block';
    enableCartActions789();
    updateQuantityDisplay789('Choose variant');
    
    showNotification(` ${selectedStore789.name} selected • Choose variant`, 'success');
}

// ========================================
// RENDER VARIANT PHOTO THUMBNAILS
// ========================================
function renderVariantPhotoThumbnails789() {
    const thumbnailsContainer = document.querySelector('.product-thumbnails-789');
    if (!thumbnailsContainer) return;
    
    const oldVariantThumbs = thumbnailsContainer.querySelectorAll('.variant-thumbnail-789');
    oldVariantThumbs.forEach(thumb => thumb.remove());
    
    const photoMap = new Map();
    
    availableVariants789.forEach(variant => {
        if (variant.photo && !photoMap.has(variant.photo)) {
            photoMap.set(variant.photo, variant);
        }
    });
    
    photoMap.forEach((variant, photoUrl) => {
        const thumbnailDiv = document.createElement('div');
        thumbnailDiv.className = 'thumbnail-789 variant-thumbnail-789';
        thumbnailDiv.dataset.variant = JSON.stringify(variant);
        
        const stockText = variant.stock > 0 ? `${variant.stock} pcs` : 'Out';
        
        thumbnailDiv.innerHTML = `
            <img src="${photoUrl}" alt="${variant.variant_name}">
            <div class="variant-thumbnail-info-789">
                <span class="variant-thumb-name-789">${variant.variant_name}</span>
                <span class="variant-thumb-stock-789">${stockText}</span>
            </div>
        `;
        
        thumbnailDiv.addEventListener('click', function() {
            selectVariantFromThumbnail789(variant);
        });
        
        thumbnailsContainer.appendChild(thumbnailDiv);
    });
}

// ========================================
// SELECT VARIANT FROM THUMBNAIL
// ========================================
function selectVariantFromThumbnail789(variant) {
    updateMainImage789(variant.photo);
    
    const colorName = variant.color || variant.variant_name.split(' - ')[0];
    const colorButtons = document.querySelectorAll('#colorOptions-789 .variant-option-789');
    
    colorButtons.forEach(btn => {
        if (btn.dataset.color === colorName) {
            btn.click();
        }
    });
    
    if (variant.size) {
        setTimeout(() => {
            const sizeButtons = document.querySelectorAll('#sizeOptions-789 .variant-option-789');
            sizeButtons.forEach(btn => {
                const btnVariant = JSON.parse(btn.dataset.variant);
                if (btnVariant.variant_id === variant.variant_id) {
                    btn.click();
                }
            });
        }, 100);
    } else {
        selectVariant789(variant);
    }
    
    document.querySelectorAll('.variant-thumbnail-789').forEach(thumb => {
        thumb.classList.remove('active-789');
    });
    event.currentTarget.classList.add('active-789');
}

// ========================================
// SELECT COLOR -  FIXED: UPDATE PRICE
// ========================================
function selectColor789(button) {
    document.querySelectorAll('#colorOptions-789 .variant-option-789').forEach(btn => {
        btn.classList.remove('active-789');
    });
    
    button.classList.add('active-789');
    
    const colorName = button.dataset.color;
    const sizes = JSON.parse(button.dataset.sizes);
    
    selectedColorVariant789 = colorName;
    selectedSizeVariant789 = null;
    selectedVariant789 = null;
    
    const firstVariantPhoto = sizes[0]?.photo;
    if (firstVariantPhoto) {
        updateMainImage789(firstVariantPhoto);
    }
    
    const sizeGroup = document.getElementById('sizeVariantGroup-789');
    const sizeOptions = document.getElementById('sizeOptions-789');
    
    if (sizes.length > 1) {
        sizeOptions.innerHTML = '';
        
        sizes.forEach(sizeData => {
            const sizeName = sizeData.size || sizeData.variant_name.split(' - ')[1] || sizeData.variant_name;
            const btn = document.createElement('button');
            btn.className = 'variant-option-789';
            
            const stockText = sizeData.stock > 0 ? `${sizeData.stock} pcs` : 'Out of stock';
            
            btn.innerHTML = `
                <div class="variant-size-name">${sizeName}</div>
                ${sizeData.formatted_price ? `<div class="variant-size-price">${sizeData.formatted_price}</div>` : ''}
                <span class="variant-size-stock-789">${stockText}</span>
            `;
            
            btn.dataset.variant = JSON.stringify(sizeData);
            
            btn.addEventListener('click', function() {
                selectSize789(this);
            });
            
            sizeOptions.appendChild(btn);
        });
        
        sizeGroup.style.display = 'block';
        updateQuantityDisplay789('Choose size');
        
        showNotification(` Color "${colorName}" selected • Choose size`, 'success');
        
    } else {
        //  FIXED: If only 1 size, select it immediately and update price
        sizeGroup.style.display = 'none';
        selectVariant789(sizes[0]);
    }
}

// ========================================
// SELECT SIZE -  FIXED: ALWAYS UPDATE PRICE
// ========================================
function selectSize789(button) {
    document.querySelectorAll('#sizeOptions-789 .variant-option-789').forEach(btn => {
        btn.classList.remove('active-789');
    });
    
    button.classList.add('active-789');
    
    const variantData = JSON.parse(button.dataset.variant);
    
    //  Update image if available
    if (variantData.photo) {
        updateMainImage789(variantData.photo);
    }
    
    //  CRITICAL FIX: Always update price when size is selected
    if (variantData.formatted_price) {
        updateProductPrice789(variantData.formatted_price);
        console.log('💰 Price updated to:', variantData.formatted_price);
    } else {
        // If no specific price for this variant, restore original
        restoreOriginalPrice789();
        console.log('💰 No variant price, restored to original');
    }
    
    selectVariant789(variantData);
}

// ========================================
// UPDATE MAIN IMAGE
// ========================================
function updateMainImage789(photoUrl) {
    const mainImage = document.getElementById('mainImage-789');
    if (!mainImage || !photoUrl) return;
    
    mainImage.style.opacity = '0';
    setTimeout(() => {
        mainImage.src = photoUrl;
        mainImage.style.opacity = '1';
    }, 200);
}

// ========================================
//  FIXED: UPDATE PRODUCT PRICE
// ========================================
function updateProductPrice789(formattedPrice) {
    const priceMain = document.querySelector('.price-main-789');
    if (!priceMain) {
        console.error(' Price element not found');
        return;
    }
    
    if (!formattedPrice) {
        console.warn('⚠️ No price provided, restoring original');
        restoreOriginalPrice789();
        return;
    }
    
    console.log('💰 Updating price to:', formattedPrice);
    
    priceMain.style.transition = 'opacity 0.2s';
    priceMain.style.opacity = '0.5';
    
    setTimeout(() => {
        priceMain.textContent = formattedPrice;
        priceMain.style.opacity = '1';
        
        // Visual feedback
        priceMain.style.transform = 'scale(1.05)';
        setTimeout(() => {
            priceMain.style.transform = 'scale(1)';
        }, 200);
    }, 150);
}

// ========================================
// FINAL VARIANT SELECTION
// ========================================
function selectVariant789(variantData) {
    selectedVariant789 = variantData;
    selectedSizeVariant789 = variantData.variant_name.split(' - ')[1] || null;
    
    //  CRITICAL: Update price immediately when variant is selected
    if (variantData.formatted_price) {
        updateProductPrice789(variantData.formatted_price);
    }
    
    const infoDiv = document.getElementById('selectedVariantInfo-789');
    const displaySpan = document.getElementById('selectedVariantDisplay-789');
    const stockSpan = document.getElementById('selectedVariantStock-789');
    
    if (infoDiv && displaySpan && stockSpan) {
        displaySpan.textContent = variantData.variant_name;
        
        //  PERJELAS INFO PENGIRIMAN DARI STOK PUSAT
        if (variantData.stock_source === 'central') {
            stockSpan.innerHTML = `
                <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                    <span style="color: #dc2626; font-weight: 700; font-size: 0.95rem;">
                        ⚠️ Akan dikirim dari Gudang Pusat
                    </span>
                    <span style="color: #666; font-size: 0.85rem; line-height: 1.4;">
                        Stok tidak tersedia di <strong>${selectedStore789.name}</strong>. 
                        Produk akan dikirim dari gudang pusat, atau ambil langsung ke toko.
                    </span>
                   
                </div>
            `;
        } else {
            stockSpan.innerHTML = `
                <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                    <span style="color: #16a34a; font-weight: 700; font-size: 0.95rem;">
                        ✓ Tersedia di ${selectedStore789.name}
                    </span>
                </div>
            `;
        }
        
        infoDiv.style.display = 'flex';
    }
    
    updateQuantityControls789();
    enableCartActions789();
    
    //  NOTIFIKASI JUGA DIPERJELAS
    const sourceNotif = variantData.stock_source === 'central' 
        ? '⚠️ Akan dikirim dari Gudang Pusat' 
        : `✓ Tersedia di ${selectedStore789.name}`;
    
    showNotification(
        ` Variant "${variantData.variant_name}" selected • ${sourceNotif}`,
        'success'
    );
    
    console.log(' Variant selected:', selectedVariant789);
}

// ========================================
// UPDATE QUANTITY DISPLAY
// ========================================
function updateQuantityDisplay789(message) {
    const qtyMax = document.getElementById('qtyMax-789');
    if (qtyMax) {
        qtyMax.textContent = message;
    }
}

// ========================================
// UPDATE QUANTITY CONTROLS
// ========================================
function updateQuantityControls789() {
    const quantityInput = document.getElementById('quantity-789');
    const qtyMax = document.getElementById('qtyMax-789');
    const decreaseBtn = document.getElementById('decreaseQty-789');
    const increaseBtn = document.getElementById('increaseQty-789');
    
    if (!quantityInput || !qtyMax) return;
    
    const maxStock = selectedVariant789.stock || 0;
    
    quantityInput.max = maxStock;
    quantityInput.value = 1;
    currentQuantity789 = 1;
    
    qtyMax.textContent = `Max: ${maxStock.toLocaleString()}`;
    
    if (decreaseBtn) decreaseBtn.disabled = false;
    if (increaseBtn) increaseBtn.disabled = false;
}

// ========================================
// QUANTITY CONTROLS
// ========================================
function initQuantityControls789() {
    const quantityInput = document.getElementById('quantity-789');
    const decreaseBtn = document.getElementById('decreaseQty-789');
    const increaseBtn = document.getElementById('increaseQty-789');
    
    if (!quantityInput || !decreaseBtn || !increaseBtn) return;
    
    decreaseBtn.addEventListener('click', function() {
        if (!selectedStore789 || !selectedVariant789) {
            showNotification(' Pilih toko dan varian terlebih dahulu!', 'error');
            return;
        }
        
        let currentQty = parseInt(quantityInput.value) || 1;
        
        if (currentQty > 1) {
            currentQty--;
            quantityInput.value = currentQty;
            currentQuantity789 = currentQty;
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        if (!selectedStore789 || !selectedVariant789) {
            showNotification(' Pilih toko dan varian terlebih dahulu!', 'error');
            return;
        }
        
        let currentQty = parseInt(quantityInput.value) || 1;
        const maxStock = selectedVariant789.stock || 0;
        
        if (currentQty < maxStock) {
            currentQty++;
            quantityInput.value = currentQty;
            currentQuantity789 = currentQty;
        } else {
            showNotification(` Stok maksimal ${maxStock} item`, 'error');
        }
    });
    
    quantityInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        let min = parseInt(this.min);
        
        if (value < min) {
            this.value = min;
        }
        
        currentQuantity789 = parseInt(this.value);
    });
}

// ========================================
// ENABLE CART ACTIONS
// ========================================
function enableCartActions789() {
    const addToCartBtn = document.getElementById('addToCartBtn-789');
    const buyNowBtn = document.getElementById('buyNowBtn-789');
    
    const isAuthenticated = addToCartBtn?.getAttribute('data-authenticated') === 'true';
    const canEnable = isAuthenticated && selectedStore789 && selectedVariant789;
    
    if (addToCartBtn) addToCartBtn.disabled = !canEnable;
    if (buyNowBtn) buyNowBtn.disabled = !canEnable;
}

// ========================================
// VALIDATE BEFORE SUBMIT
// ========================================
function validateCartAction789() {
    if (!selectedStore789) {
        showNotification(' Pilih toko terlebih dahulu!', 'error');
        return false;
    }
    
    if (!selectedColorVariant789) {
        showNotification(' Pilih warna terlebih dahulu!', 'error');
        return false;
    }
    
    if (!selectedVariant789) {
        showNotification(' Pilih varian lengkap terlebih dahulu!', 'error');
        return false;
    }
    
    const quantity = parseInt(document.getElementById('quantity-789')?.value || 0);
    
    if (quantity < 1) {
        showNotification(' Jumlah minimal 1 item!', 'error');
        return false;
    }
    
    return true;
}

// ========================================
// RESOLVE STORE ID
// ========================================
function resolveStoreId789() {
    if (selectedVariant789.stock_source === 'central') {
        return CENTRAL_STORE_ID;
    }
    return selectedStore789.id;
}

// ========================================
// ADD TO CART
// ========================================
function initAddToCart789() {
    const addToCartBtn = document.getElementById('addToCartBtn-789');
    const quantityInput = document.getElementById('quantity-789');
    
    if (!addToCartBtn) return;
    if (addToCartBtn.getAttribute('data-authenticated') !== 'true') return;
    
    console.log(' Customer authenticated - Adding event listener');
    
    addToCartBtn.addEventListener('click', function(e) {
        e.preventDefault();
        console.log('🛒 Add to Cart clicked!');
        
        if (!validateCartAction789()) return;
        
        const quantity = parseInt(quantityInput.value) || 1;
        
        addToCartBtn.disabled = true;
        const btnText = addToCartBtn.querySelector('.btn-text-789');
        const originalText = btnText ? btnText.textContent : 'Add to Cart';
        
        if (btnText) btnText.textContent = 'Adding...';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found!');
            showNotification(' Security token not found. Please refresh.', 'error');
            addToCartBtn.disabled = false;
            if (btnText) btnText.textContent = originalText;
            return;
        }
        
        const finalTokoId = resolveStoreId789();
        
        const requestData = {
            product_id: productId789,
            toko_id: finalTokoId,
            variant_id: selectedVariant789.variant_id,
            quantity: quantity
        };
        
        console.log('📦 Request Payload:', requestData);
        
        fetch('/customer/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => {
            console.log('Response Status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response Data:', data);
            
            if (data.success) {
                showNotification(' ' + (data.message || 'Produk ditambahkan ke keranjang!'), 'success');
                
                if (data.cart_count) {
                    updateCartCount789(data.cart_count);
                }
                
                if (quantityInput) {
                    quantityInput.value = 1;
                    currentQuantity789 = 1;
                }
            } else {
                showNotification(' ' + (data.message || 'Gagal menambahkan ke keranjang'), 'error');
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            showNotification(' Terjadi kesalahan. Silakan coba lagi.', 'error');
        })
        .finally(() => {
            addToCartBtn.disabled = false;
            if (btnText) btnText.textContent = originalText;
        });
    });
}

// ========================================
// BUY NOW
// ========================================
function initBuyNow789() {
    const buyNowBtn = document.getElementById('buyNowBtn-789');
    const quantityInput = document.getElementById('quantity-789');
    
    if (!buyNowBtn) return;
    if (buyNowBtn.getAttribute('data-authenticated') !== 'true') return;
    
    buyNowBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        if (!validateCartAction789()) return;
        
        const quantity = parseInt(quantityInput.value) || 1;
        
        buyNowBtn.disabled = true;
        const btnText = buyNowBtn.querySelector('.btn-text-789');
        const originalText = btnText ? btnText.textContent : 'Buy Now';
        
        if (btnText) btnText.textContent = 'Processing...';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showNotification(' Security token not found.', 'error');
            buyNowBtn.disabled = false;
            if (btnText) btnText.textContent = originalText;
            return;
        }
        
        const finalTokoId = resolveStoreId789();
        
        const requestData = {
            product_id: productId789,
            toko_id: finalTokoId,
            variant_id: selectedVariant789.variant_id,
            quantity: quantity
        };
        
        fetch('/customer/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '/customer/cart';
            } else {
                showNotification(' ' + (data.message || 'Gagal memproses pesanan'), 'error');
                buyNowBtn.disabled = false;
                if (btnText) btnText.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(' Terjadi kesalahan.', 'error');
            buyNowBtn.disabled = false;
            if (btnText) btnText.textContent = originalText;
        });
    });
}

// ========================================
// UPDATE CART COUNT (BOTH BADGES)
// ========================================
function updateCartCount789(count) {
    // Update badge di page (jika ada)
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        cartBadge.textContent = count;
        cartBadge.style.transform = 'scale(1.3)';
        setTimeout(() => {
            cartBadge.style.transform = 'scale(1)';
        }, 200);
    }
    
    // Update badge di navbar (REAL-TIME)
    const cartIcon = document.querySelector('#shopping-cart');
    if (cartIcon) {
        let navbarBadge = cartIcon.querySelector('.cart-badge');
        
        if (count > 0) {
            if (!navbarBadge) {
                navbarBadge = document.createElement('span');
                navbarBadge.className = 'cart-badge';
                cartIcon.appendChild(navbarBadge);
            }
            navbarBadge.textContent = count;
            
            // Animation
            navbarBadge.style.transform = 'scale(1.3)';
            setTimeout(() => {
                navbarBadge.style.transform = 'scale(1)';
            }, 200);
        } else {
            if (navbarBadge) navbarBadge.remove();
        }
    }
}


// ========================================
// PRODUCT TABS
// ========================================
function initProductTabs789() {
    const tabBtns = document.querySelectorAll('.tab-btn-789');
    const tabContents = document.querySelectorAll('.tab-content-789');
    
    if (tabBtns.length === 0) return;
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            tabBtns.forEach(b => b.classList.remove('active-789'));
            tabContents.forEach(c => c.classList.remove('active-789'));
            
            this.classList.add('active-789');
            const targetContent = document.getElementById(`tab-${targetTab}`);
            if (targetContent) {
                targetContent.classList.add('active-789');
            }
        });
    });
}
// ========================================
// SHARE FUNCTIONS
// ========================================
function shareToFacebook789() {
    const url = window.location.href;
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
}

function shareToTwitter789() {
    const url = window.location.href;
    const title = document.querySelector('.product-detail-title-789')?.textContent || 'Check this product';
    window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`, '_blank', 'width=600,height=400');
}

function shareToInstagram789() {
    showNotification('Copy link and share on Instagram!', 'success');
    const url = window.location.href;
    navigator.clipboard.writeText(url);
}

function shareToWhatsapp789() {
    const url = window.location.href;
    const title = document.querySelector('.product-detail-title-789')?.textContent || 'Check this product';
    window.open(`https://wa.me/?text=${encodeURIComponent(title + ' - ' + url)}`, '_blank');
}

// ========================================
// HANDLE STORE SELECTED FROM MODAL
// ========================================
function handleStoreSelected789(store) {
    console.log(' Store selected from modal:', store);
    
    const displayBtn = document.getElementById('selectedStoreDisplay-789');
    if (displayBtn) {
        displayBtn.textContent = store.nama_toko;
    }
    
    selectedStore789 = {
        id: store.id,
        name: store.nama_toko
    };
    
    availableVariants789 = store.variants_data;
    
    showVariantSelection789();
    
    showNotification(` ${store.nama_toko} selected • Choose variant`, 'success');
}

// ========================================
// INITIALIZE
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Product Detail Page 789 - FIXED PRICE UPDATE VERSION');
    
    //  Save original price on page load
    saveOriginalPrice789();
    
    initImageGallery789();
    initStockSelection789();
    initQuantityControls789();
    initProductTabs789();
    initAddToCart789();
    initBuyNow789();
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    console.log(' Product Detail Page Ready!');
    console.log('💰 Price will update in real-time per variant selection');
});