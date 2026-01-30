// ========================================
// COMPLETE CART + VOUCHER MODAL SCRIPT
// Pure Vanilla JavaScript - No jQuery
// ========================================

(function () {
    'use strict';

    // ========================================
    // GLOBAL STATE
    // ========================================
    let selectedItems = new Map();
    window.appliedVoucher = null;

    // ========================================
    // HELPER FUNCTIONS
    // ========================================
    const $ = (selector) => document.querySelector(selector);
    const $$ = (selector) => document.querySelectorAll(selector);

    function showNotification(message, type = 'info') {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }

    function formatRupiah(number) {
        const num = typeof number === 'string' ? parseFloat(number) : number;
        return 'Rp' + (num || 0).toLocaleString('id-ID');
    }

    function formatNumber(num) {
        const number = typeof num === 'string' ? parseFloat(num) : num;
        return new Intl.NumberFormat('id-ID').format(number || 0);
    }

    function getCsrfToken() {
        const meta = $('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // ========================================
    // VOUCHER FUNCTIONS
    // ========================================

    /**
     * Load applied voucher from server
     */
    function loadAppliedVoucher() {
        const cartIds = Array.from(selectedItems.keys());

        if (cartIds.length === 0) {
            window.appliedVoucher = null;
            updateSummary();
            return;
        }

        fetch('/customer/cart/get-voucher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                cart_ids: cartIds
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.voucher) {
                    window.appliedVoucher = data.voucher;
                } else {
                    window.appliedVoucher = null;
                }
                updateSummary();
            })
            .catch(error => {
                console.error('Error loading voucher:', error);
                window.appliedVoucher = null;
                updateSummary();
            });
    }

    /**
     * Load available vouchers for selected items
     */
    function loadAvailableVouchers() {
        const selectedCartIds = Array.from(selectedItems.keys());

        console.log('🎫 Loading vouchers for cart IDs:', selectedCartIds);

        if (selectedCartIds.length === 0) {
            showNotification('Pilih produk terlebih dahulu!', 'warning');
            return;
        }

        const voucherListLoading = $('#voucherListLoading');
        const emptyVoucher = $('#emptyVoucher');

        if (voucherListLoading) voucherListLoading.style.display = 'block';

        // Clear previous content
        const availableVouchers = $('#availableVouchers');
        const unavailableVouchers = $('#unavailableVouchers');

        if (availableVouchers) availableVouchers.innerHTML = '';
        if (unavailableVouchers) unavailableVouchers.innerHTML = '';

        const availableSection = $('#availableSection');
        const unavailableSection = $('#unavailableSection');

        if (availableSection) availableSection.style.display = 'none';
        if (unavailableSection) unavailableSection.style.display = 'none';
        if (emptyVoucher) emptyVoucher.style.display = 'none';

        fetch('/customer/cart/available-vouchers', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                cart_ids: selectedCartIds
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log('📦 Voucher API Response:', data);

                if (voucherListLoading) voucherListLoading.style.display = 'none';

                if (data.success && data.vouchers) {
                    const { available, unavailable } = data.vouchers;

                    console.log('✅ Available:', available.length);
                    console.log('⚠️ Unavailable:', unavailable.length);

                    if (available.length > 0) {
                        const countBadge = $('#availableCount');
                        if (countBadge) countBadge.textContent = available.length;
                        if (availableSection) availableSection.style.display = 'block';
                        if (availableVouchers) {
                            console.log('✅ Found availableVouchers container');
                            renderVouchers(available, availableVouchers, 'available');
                        } else {
                            console.error('❌ availableVouchers container not found in DOM');
                        }
                    }

                    if (unavailable.length > 0) {
                        const countBadge = $('#unavailableCount');
                        if (countBadge) countBadge.textContent = unavailable.length;
                        if (unavailableSection) unavailableSection.style.display = 'block';
                        if (unavailableVouchers) {
                            renderVouchers(unavailable, unavailableVouchers, 'unavailable');
                        }
                    }

                    if (available.length === 0 && unavailable.length === 0) {
                        console.warn('⚠️ No vouchers found');
                        if (emptyVoucher) emptyVoucher.style.display = 'block';
                    }
                } else {
                    console.error('❌ API response invalid:', data);
                    if (emptyVoucher) emptyVoucher.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('❌ Error loading vouchers:', error);
                if (voucherListLoading) voucherListLoading.style.display = 'none';
                if (emptyVoucher) emptyVoucher.style.display = 'block';
                showNotification('Gagal memuat voucher', 'error');
            });
    }

    /**
     * Render vouchers
     */
    function renderVouchers(vouchers, container, type) {
        console.log(`🎨 Rendering ${vouchers.length} vouchers (type: ${type})`);

        if (!container) {
            console.error(`❌ Container is null for type ${type}`);
            return;
        }

        container.innerHTML = '';

        vouchers.forEach((voucher, index) => {
            console.log(`  Voucher ${index + 1}:`, voucher.name, voucher.code);

            const voucherItem = document.createElement('div');
            voucherItem.className = `voucher-item-111 ${type}`;

            const iconClass = voucher.discount_type === 'percentage' ? 'fa-percent' : 'fa-gift';

            let discountDisplay = '';
            if (voucher.discount_type === 'percentage') {
                const discountValue = parseFloat(voucher.discount_value);
                const maxDiscount = parseFloat(voucher.max_discount || 0);
                discountDisplay = `${discountValue}%`;
                if (maxDiscount > 0) {
                    discountDisplay += ` (Maks. Rp ${formatNumber(maxDiscount)})`;
                }
            } else {
                discountDisplay = `Rp ${formatNumber(parseFloat(voucher.discount_value))}`;
            }

            let buttonHTML = '';
            if (type === 'available' && voucher.code) {
                buttonHTML = `<button class="btn-use-voucher-111 btn-use-voucher" data-code="${voucher.code}">Pakai</button>`;
            } else if (type === 'available' && !voucher.code) {
                buttonHTML = `<button class="btn-use-voucher-111" disabled>Private</button>`;
            } else {
                buttonHTML = `<button class="btn-use-voucher-111" disabled><i class="fas fa-ban"></i></button>`;
            }

            let reasonHTML = '';
            if (voucher.reason) {
                reasonHTML = `<div class="voucher-reason-111"><i class="fas fa-info-circle"></i><span>${voucher.reason}</span></div>`;
            }

            voucherItem.innerHTML = `
                <div class="voucher-icon-111">
                    <i class="fas ${iconClass}"></i>
                </div>
                <div class="voucher-details-111">
                    <h6>${voucher.name}</h6>
                    <p>${voucher.description || 'Dapatkan potongan dengan voucher ini'}</p>
                    ${voucher.code ? `<span class="voucher-code-111">${voucher.code}</span>` : '<span class="voucher-code-111">PRIVATE</span>'}
                    <div class="voucher-validity-111">
                        <i class="far fa-calendar-alt"></i>
                        Berlaku sampai: ${voucher.end_date}
                    </div>
                    <div class="voucher-validity-111">
                        <i class="fas fa-shopping-cart"></i>
                        Min pembelian: ${voucher.min_purchase > 0 ? `Rp ${formatNumber(parseFloat(voucher.min_purchase))}` : 'Tanpa minimal'}
                    </div>
                    <div class="voucher-validity-111">
                        <i class="fas fa-tag"></i>
                        Potongan: ${discountDisplay}
                    </div>
                    ${reasonHTML}
                </div>
                ${buttonHTML}
            `;

            container.appendChild(voucherItem);
        });

        // Add click handlers for available vouchers
        if (type === 'available') {
            container.querySelectorAll('.btn-use-voucher').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    applyVoucherCode(this.dataset.code);
                });
            });
        }
    }

    /**
     * Apply voucher code
     */
    function applyVoucherCode(code) {
        const selectedCartIds = Array.from(selectedItems.keys());

        if (selectedCartIds.length === 0) {
            showNotification('Pilih produk terlebih dahulu!', 'warning');
            return;
        }

        const applyButton = document.querySelector(`[data-code="${code}"]`);
        const btnApplyCodeInput = $('#btnApplyCode');

        if (applyButton) {
            applyButton.disabled = true;
            applyButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        }
        if (btnApplyCodeInput) {
            btnApplyCodeInput.disabled = true;
            btnApplyCodeInput.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
        }

        fetch('/customer/cart/apply-voucher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                voucher_code: code,
                cart_ids: selectedCartIds
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.appliedVoucher = data.voucher;

                    updateSummary();

                    const modal = $('#voucherModal');
                    if (modal) modal.classList.remove('show-111');

                    const voucherInput = $('#voucherCodeInput');
                    if (voucherInput) voucherInput.value = '';

                    showNotification(data.message + ' 🎉', 'success');
                } else {
                    window.appliedVoucher = null;
                    updateSummary();
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.appliedVoucher = null;
                updateSummary();
                showNotification('Terjadi kesalahan', 'error');
            })
            .finally(() => {
                if (applyButton) {
                    applyButton.disabled = false;
                    applyButton.innerHTML = 'Pakai';
                }
                if (btnApplyCodeInput) {
                    btnApplyCodeInput.disabled = false;
                    btnApplyCodeInput.innerHTML = 'Pakai';
                }
            });
    }

    /**
     * Remove applied voucher
     */
    function removeVoucher() {
        fetch('/customer/cart/remove-voucher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.appliedVoucher = null;

                    updateSummary();
                    showNotification('Voucher berhasil dihapus', 'success');
                }
            })
            .catch(error => {
                console.error('Error removing voucher:', error);
            });
    }

    // ========================================
    // CART SUMMARY UPDATE
    // ========================================
    function updateSummary() {
        let totalItems = 0;
        let subtotal = 0;

        selectedItems.forEach((data) => {
            totalItems += data.quantity;
            subtotal += data.price * data.quantity;
        });

        const selectedCount = $('#selectedCount');
        const checkoutCount = $('#checkoutCount');
        const subtotalAmount = $('#subtotalAmount');
        const grandTotal = $('#grandTotal');
        const checkoutBtn = $('#checkoutBtn');
        const voucherText = $('#voucherText');

        if (selectedCount) selectedCount.textContent = totalItems;
        if (checkoutCount) checkoutCount.textContent = totalItems;
        if (subtotalAmount) subtotalAmount.textContent = formatRupiah(subtotal);

        // Calculate discount
        let discount = 0;
        const discountRow = $('#discountRow');
        const discountAmount = $('#discountAmount');
        const removeVoucherBtn = $('#removeVoucherBtn');

        if (window.appliedVoucher && totalItems > 0) {
            discount = typeof window.appliedVoucher.discount === 'string'
                ? parseFloat(window.appliedVoucher.discount)
                : (window.appliedVoucher.discount || 0);

            if (discountRow) {
                discountRow.style.display = 'flex';
                discountRow.style.opacity = '0';
                setTimeout(() => {
                    discountRow.style.transition = 'opacity 0.3s ease';
                    discountRow.style.opacity = '1';
                }, 10);
            }
            if (discountAmount) discountAmount.textContent = '-' + formatRupiah(discount);

            if (voucherText) {
                voucherText.innerHTML = `<strong>${window.appliedVoucher.name}</strong><br>
                    <span style="font-size: 12px; color: #059669; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Potongan ${formatRupiah(discount)}
                    </span>`;

                voucherText.style.transition = 'all 0.3s ease';
                voucherText.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    voucherText.style.transform = 'scale(1)';
                }, 300);
            }

            if (removeVoucherBtn) removeVoucherBtn.style.display = 'flex';
        } else {
            if (discountRow) discountRow.style.display = 'none';
            if (removeVoucherBtn) removeVoucherBtn.style.display = 'none';

            if (voucherText) {
                voucherText.innerHTML = totalItems > 0 ? 'Pilih voucher untuk mendapat potongan harga' : 'Tidak ada produk yang dipilih';
            }
        }

        const total = Math.max(0, subtotal - discount);
        if (grandTotal) {
            grandTotal.textContent = formatRupiah(total);
            grandTotal.style.transition = 'all 0.3s ease';
            grandTotal.style.transform = 'scale(1.1)';
            setTimeout(() => {
                grandTotal.style.transform = 'scale(1)';
            }, 300);
        }

        // Enable/disable buttons
        const voucherBtns = $$('.btn-select-voucher');

        if (totalItems > 0) {
            if (checkoutBtn) checkoutBtn.disabled = false;
            voucherBtns.forEach(btn => btn.disabled = false);
        } else {
            if (checkoutBtn) checkoutBtn.disabled = true;
            voucherBtns.forEach(btn => btn.disabled = true);
            if (removeVoucherBtn) removeVoucherBtn.style.display = 'none';
            window.appliedVoucher = null;
        }
    }

    // ========================================
    // STORE SUBTOTAL UPDATE - REAL-TIME
    // ========================================
    function updateStoreSubtotal(item) {
        const storeSection = item.closest('.store-section-111');
        if (!storeSection) return;

        updateStoreSubtotalBySection(storeSection);
    }

    function updateStoreSubtotalBySection(storeSection) {
        const items = storeSection.querySelectorAll('.cart-item-111');
        let totalSubtotal = 0;
        let totalQuantity = 0;

        items.forEach(cartItem => {
            const checkbox = cartItem.querySelector('.item-checkbox');
            if (checkbox) {
                const price = parseFloat(checkbox.dataset.price);
                const quantity = parseInt(checkbox.dataset.quantity);
                totalSubtotal += price * quantity;
                totalQuantity += quantity;
            }
        });

        // Update subtotal value
        const subtotalValue = storeSection.querySelector('.store-subtotal-value-111');
        if (subtotalValue) {
            subtotalValue.textContent = formatRupiah(totalSubtotal);

            // Animation effect
            subtotalValue.style.transition = 'all 0.3s ease';
            subtotalValue.style.transform = 'scale(1.1)';
            subtotalValue.style.color = '#16a34a';
            setTimeout(() => {
                subtotalValue.style.transform = 'scale(1)';
                subtotalValue.style.color = '';
            }, 300);
        }

        // Update item count
        const itemCount = storeSection.querySelector('.store-item-count-111');
        if (itemCount) {
            itemCount.textContent = `${totalQuantity} item${totalQuantity > 1 ? 's' : ''}`;
        }
    }
    // ========================================
    // CART ITEM HANDLERS
    // ========================================

    function updateStoreCheckbox(tokoId) {
        const tokoItems = $$(`.item-checkbox[data-toko-id="${tokoId}"]`);
        const checkedItems = Array.from(tokoItems).filter(cb => cb.checked);
        const storeCheckbox = $(`.store-checkbox[data-toko-id="${tokoId}"]`);

        if (storeCheckbox) {
            storeCheckbox.checked = tokoItems.length === checkedItems.length && tokoItems.length > 0;
        }
    }

    function handleStoreCheckbox(e) {
        const tokoId = e.target.dataset.tokoId;
        const isChecked = e.target.checked;
        const items = $$(`.item-checkbox[data-toko-id="${tokoId}"]`);

        // 🆕 CEK: Apakah sudah ada toko lain yang dipilih?
        if (isChecked && selectedItems.size > 0) {
            const firstSelectedCheckbox = document.querySelector('.item-checkbox:checked');
            if (firstSelectedCheckbox && firstSelectedCheckbox.dataset.tokoId !== tokoId) {
                e.target.checked = false;
                showNotification('❌ Hanya bisa checkout dari 1 toko! Hapus pilihan toko lain terlebih dahulu.', 'error');
                return;
            }
        }

        items.forEach(checkbox => {
            checkbox.checked = isChecked;
            const cartId = parseInt(checkbox.dataset.cartId);
            const price = parseFloat(checkbox.dataset.price);
            const quantity = parseInt(checkbox.dataset.quantity);

            if (isChecked) {
                selectedItems.set(cartId, { price, quantity, tokoId }); // 🆕 simpan tokoId
            } else {
                selectedItems.delete(cartId);
            }
        });

        loadAppliedVoucher();
    }

    function handleItemCheckbox(e) {
        const cartId = parseInt(e.target.dataset.cartId);
        const tokoId = e.target.dataset.tokoId;
        const price = parseFloat(e.target.dataset.price);
        const quantity = parseInt(e.target.dataset.quantity);

        // 🆕 CEK: Apakah sudah ada toko lain yang dipilih?
        if (e.target.checked) {
            const selectedTokoIds = new Set();
            selectedItems.forEach((data, itemCartId) => {
                const checkbox = document.querySelector(`.item-checkbox[data-cart-id="${itemCartId}"]`);
                if (checkbox) {
                    selectedTokoIds.add(checkbox.dataset.tokoId);
                }
            });

            // Jika sudah ada toko berbeda, BLOCK!
            if (selectedTokoIds.size > 0 && !selectedTokoIds.has(tokoId)) {
                e.target.checked = false;
                showNotification('❌ Hanya bisa checkout dari 1 toko! Hapus pilihan toko lain terlebih dahulu.', 'error');
                return;
            }

            selectedItems.set(cartId, { price, quantity, tokoId }); // 🆕 simpan tokoId
        } else {
            selectedItems.delete(cartId);
        }

        updateStoreCheckbox(tokoId);
        loadAppliedVoucher();
    }

    function handleIncrease(e) {
        e.preventDefault();
        const item = e.target.closest('.cart-item-111');
        const input = item.querySelector('.quantity-input-111');
        const currentVal = parseInt(input.value);
        const maxStock = parseInt(item.dataset.maxStock) || 999;

        if (currentVal < maxStock) {
            input.value = currentVal + 1;
            updateCart(item);
        } else {
            showNotification('Stok maksimal ' + maxStock + ' item', 'warning');
        }
    }

    function handleDecrease(e) {
        e.preventDefault();
        const item = e.target.closest('.cart-item-111');
        const input = item.querySelector('.quantity-input-111');
        const currentVal = parseInt(input.value);

        if (currentVal > 1) {
            input.value = currentVal - 1;
            updateCart(item);
        }
    }

    function handleRemove(e) {
        e.preventDefault();
        if (confirm('Hapus item ini dari keranjang?')) {
            removeCart(e.target.closest('.cart-item-111'));
        }
    }

    function updateCart(item) {
        const cartId = item.dataset.cartId;
        const quantity = item.querySelector('.quantity-input-111').value;

        fetch(`/customer/cart/${cartId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const checkbox = item.querySelector('.item-checkbox');
                    if (checkbox) {
                        checkbox.dataset.quantity = quantity;

                        if (checkbox.checked) {
                            const price = parseFloat(checkbox.dataset.price);
                            selectedItems.set(parseInt(cartId), {
                                price,
                                quantity: parseInt(quantity)
                            });

                            loadAppliedVoucher();
                        }
                    }

                    // 🔥 UPDATE STORE SUBTOTAL REAL-TIME
                    updateStoreSubtotal(item);
                } else {
                    showNotification(data.message, 'error');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
                location.reload();
            });
    }

    function removeCart(item) {
        const cartId = item.dataset.cartId;

        fetch(`/customer/cart/${cartId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    selectedItems.delete(parseInt(cartId));

                    item.style.opacity = '0';
                    item.style.transition = 'opacity 0.3s';

                    setTimeout(() => {
                        const storeSection = item.closest('.store-section-111');
                        item.remove();

                        // 🔥 UPDATE STORE SUBTOTAL SETELAH REMOVE
                        if (storeSection && storeSection.querySelectorAll('.cart-item-111').length > 0) {
                            updateStoreSubtotalBySection(storeSection);
                        } else if (storeSection) {
                            storeSection.remove();
                        }

                        if ($$('.cart-item-111').length === 0) {
                            location.reload();
                        } else {
                            loadAppliedVoucher();
                        }
                    }, 300);

                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan', 'error');
            });
    }

    function handleCheckout(e) {
        e.preventDefault();

        if (selectedItems.size === 0) {
            showNotification('Pilih minimal satu produk untuk checkout', 'warning');
            return;
        }

        const cartIds = Array.from(selectedItems.keys());
        let url = `/customer/checkout?items=${cartIds.join(',')}`;

        if (window.appliedVoucher) {
            url += `&voucher=${window.appliedVoucher.code}`;
        }

        window.location.href = url;
    }

    // ========================================
    // VOUCHER MODAL HANDLERS
    // ========================================
    function initVoucherModal() {
        const modal = $('#voucherModal');
        const openBtn = $('#selectVoucherBtn');
        const closeBtn = $('#closeVoucherModal');

        if (!modal || !openBtn) {
            console.error('❌ Voucher modal elements not found');
            return;
        }

        // Open modal
        openBtn.addEventListener('click', function () {
            if (!this.disabled) {
                modal.classList.add('show-111');
                loadAvailableVouchers();
            }
        });

        // Close button
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                modal.classList.remove('show-111');
            });
        }

        // Click outside to close
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.remove('show-111');
            }
        });

        // ESC key to close
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('show-111')) {
                modal.classList.remove('show-111');
            }
        });

        // Apply code button
        const btnApplyCode = $('#btnApplyCode');
        if (btnApplyCode) {
            btnApplyCode.addEventListener('click', function (e) {
                e.preventDefault();
                const codeInput = $('#voucherCodeInput');
                const code = codeInput ? codeInput.value.trim().toUpperCase() : '';

                if (code) {
                    applyVoucherCode(code);
                } else {
                    showNotification('Masukkan kode voucher', 'warning');
                }
            });
        }

        // Auto uppercase input
        const voucherCodeInput = $('#voucherCodeInput');
        if (voucherCodeInput) {
            voucherCodeInput.addEventListener('input', function () {
                this.value = this.value.toUpperCase();
            });

            voucherCodeInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (btnApplyCode) btnApplyCode.click();
                }
            });
        }

        console.log('✅ Voucher modal initialized');
    }

    // ========================================
    // INITIALIZE ALL EVENT LISTENERS
    // ========================================
    function initEventListeners() {
        // Store checkboxes
        $$('.store-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', handleStoreCheckbox);
        });

        // Item checkboxes
        $$('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', handleItemCheckbox);
        });

        // Quantity buttons
        $$('.btn-increase').forEach(btn => {
            btn.addEventListener('click', handleIncrease);
        });

        $$('.btn-decrease').forEach(btn => {
            btn.addEventListener('click', handleDecrease);
        });

        // Remove buttons
        $$('.btn-remove').forEach(btn => {
            btn.addEventListener('click', handleRemove);
        });

        // Checkout button
        const checkoutBtn = $('#checkoutBtn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', handleCheckout);
        }

        // Remove voucher button
        const removeVoucherBtn = $('#removeVoucherBtn');
        if (removeVoucherBtn) {
            removeVoucherBtn.addEventListener('click', removeVoucher);
        }

        // Initialize voucher modal
        initVoucherModal();
    }

    // ========================================
    // MAKE FUNCTIONS GLOBALLY ACCESSIBLE
    // ========================================
    window.applyVoucher = applyVoucherCode;
    window.removeVoucher = removeVoucher;
    window.updateSummary = updateSummary;

    // ========================================
    // MOBILE LAYOUT RESTRUCTURE
    // ========================================
    function restructureMobileLayout() {
        if (window.innerWidth <= 768) {
            // Mode Mobile: Wrap checkbox + foto + detail
            $$('.cart-item-111').forEach(item => {
                // Cek apakah sudah di-restructure
                if (item.querySelector('.cart-item-first-row-111')) return;

                const checkbox = item.querySelector('.cart-item-checkbox-111');
                const image = item.querySelector('.product-image-111');
                const details = item.querySelector('.product-details-111');

                if (checkbox && image && details) {
                    // Buat wrapper baru
                    const wrapper = document.createElement('div');
                    wrapper.className = 'cart-item-first-row-111';

                    // Pindahkan element ke wrapper
                    wrapper.appendChild(checkbox);
                    wrapper.appendChild(image);
                    wrapper.appendChild(details);

                    // Masukkan wrapper sebagai child pertama
                    item.insertBefore(wrapper, item.firstChild);
                }
            });
        } else {
            // Mode Desktop: Restore layout original
            $$('.cart-item-first-row-111').forEach(wrapper => {
                const parent = wrapper.parentElement;
                const checkbox = wrapper.querySelector('.cart-item-checkbox-111');
                const image = wrapper.querySelector('.product-image-111');
                const details = wrapper.querySelector('.product-details-111');

                if (parent && checkbox && image && details) {
                    // Kembalikan ke posisi semula
                    parent.insertBefore(checkbox, wrapper);
                    parent.insertBefore(image, wrapper);
                    parent.insertBefore(details, wrapper);
                    wrapper.remove();
                }
            });
        }
    }

    // ========================================
    // INITIALIZE ON PAGE LOAD
    // ========================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initEventListeners();
            restructureMobileLayout(); // 🔥 Panggil fungsi mobile layout
            setTimeout(loadAppliedVoucher, 100);
        });
    } else {
        initEventListeners();
        restructureMobileLayout(); // 🔥 Panggil fungsi mobile layout
        setTimeout(loadAppliedVoucher, 100);
    }

    // Handle window resize (desktop ↔ mobile)
    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            restructureMobileLayout();
        }, 250);
    });
})();