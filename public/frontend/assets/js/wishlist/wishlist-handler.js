/**
 * Wishlist Handler - Updated with Global Notification & Real-time Badge
 * File: public/frontend/assets/js/wishlist/wishlist-handler.js
 */

// Konfigurasi global
window.wishlistConfig = {
    isAuthenticated: false,
    loginUrl: '/',
    csrfToken: ''
};

/**
 * Toggle wishlist (add/remove)
 * @param {number} productId - ID produk
 * @param {HTMLElement} element - Element yang diklik (icon wishlist)
 */
function toggleWishlist(productId, element) {
    // ✅ Cek apakah user sudah login
    if (!window.wishlistConfig.isAuthenticated) {
        // ✅ Gunakan notifikasi global - TANPA REDIRECT
        if (typeof showNotification === 'function') {
            showNotification('Silakan login terlebih dahulu untuk menambahkan ke wishlist', 'error');
        } else {
            alert('Silakan login terlebih dahulu');
        }
        
        // ❌ HAPUS REDIRECT - Biarkan user di halaman yang sama
        return;
    }

    // ✅ Disable button sementara untuk mencegah double click
    const originalCursor = element.style.cursor;
    element.style.pointerEvents = 'none';

    // ✅ Kirim request ke server
    fetch(`/customer/wishlist/toggle/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.wishlistConfig.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // ✅ Toggle class active pada element
            if (data.action === 'added') {
                element.classList.add('active-789');
                element.classList.add('active');
            } else {
                element.classList.remove('active-789');
                element.classList.remove('active');
            }
            
            // ✅ Update sidebar badge (jika function tersedia)
            if (typeof window.updateWishlistBadgeSidebar === 'function') {
                window.updateWishlistBadgeSidebar(data.count);
            }
            
            // ✅ Tampilkan notifikasi global
            if (typeof showNotification === 'function') {
                showNotification(data.message, 'success');
            }

            // ✅ Trigger custom event untuk update UI lainnya
            const event = new CustomEvent('wishlistUpdated', {
                detail: {
                    productId: productId,
                    action: data.action,
                    count: data.count
                }
            });
            document.dispatchEvent(event);
            
            console.log('💗 Wishlist updated:', data);
            
        } else {
            // ✅ Tampilkan error dari server
            if (typeof showNotification === 'function') {
                showNotification(data.message || 'Gagal memperbarui wishlist', 'error');
            } else {
                alert(data.message || 'Gagal memperbarui wishlist');
            }
        }
    })
    .catch(error => {
        console.error('Wishlist Error:', error);
        
        // ✅ Tampilkan error notification
        if (typeof showNotification === 'function') {
            showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
        } else {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    })
    .finally(() => {
        // ✅ Re-enable button
        element.style.pointerEvents = 'auto';
    });
}

/**
 * Remove item from wishlist (untuk halaman wishlist)
 * @param {number} wishlistId - ID wishlist item
 * @param {HTMLElement} element - Element yang diklik
 */
function removeFromWishlist(wishlistId, element) {
    if (!confirm('Hapus produk ini dari wishlist?')) {
        return;
    }

    // Disable button
    element.disabled = true;
    const originalText = element.textContent;
    element.textContent = 'Menghapus...';

    fetch(`/customer/wishlist/${wishlistId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.wishlistConfig.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // ✅ Notifikasi sukses
            if (typeof showNotification === 'function') {
                showNotification('Produk berhasil dihapus dari wishlist', 'success');
            }

            // Remove element dari DOM dengan animation
            const card = element.closest('.wishlist-item-card');
            if (card) {
                card.style.transition = 'opacity 0.3s, transform 0.3s';
                card.style.opacity = '0';
                card.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    card.remove();
                    
                    // Check jika wishlist kosong
                    const remainingItems = document.querySelectorAll('.wishlist-item-card');
                    if (remainingItems.length === 0) {
                        location.reload(); // Reload untuk tampilkan empty state
                    }
                }, 300);
            }

            // ✅ Update sidebar badge
            if (data.count !== undefined) {
                if (typeof window.updateWishlistBadgeSidebar === 'function') {
                    window.updateWishlistBadgeSidebar(data.count);
                }
                
                // Trigger event
                const event = new CustomEvent('wishlistUpdated', {
                    detail: { count: data.count }
                });
                document.dispatchEvent(event);
            }
        } else {
            // ✅ Notifikasi error
            if (typeof showNotification === 'function') {
                showNotification(data.message || 'Gagal menghapus dari wishlist', 'error');
            }
            element.disabled = false;
            element.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Remove Wishlist Error:', error);
        
        if (typeof showNotification === 'function') {
            showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
        }
        
        element.disabled = false;
        element.textContent = originalText;
    });
}

/**
 * Clear all wishlist items
 */
function clearWishlist() {
    if (!confirm('Hapus semua item dari wishlist?')) {
        return;
    }

    fetch('/customer/wishlist/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.wishlistConfig.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showNotification === 'function') {
                showNotification('Semua wishlist berhasil dihapus', 'success');
            }
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            if (typeof showNotification === 'function') {
                showNotification(data.message || 'Gagal menghapus wishlist', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Clear Wishlist Error:', error);
        if (typeof showNotification === 'function') {
            showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
        }
    });
}

/**
 * Get current wishlist count
 */
function getWishlistCount() {
    if (!window.wishlistConfig.isAuthenticated) {
        return;
    }

    fetch('/customer/wishlist/count', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update sidebar badge
            if (typeof window.updateWishlistBadgeSidebar === 'function') {
                window.updateWishlistBadgeSidebar(data.count);
            }
            
            console.log('💗 Wishlist count loaded:', data.count);
        }
    })
    .catch(error => {
        console.error('Get Wishlist Count Error:', error);
    });
}

// ✅ Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Wishlist Handler Initialized');
    
    // Update badge saat halaman dimuat
    if (window.wishlistConfig.isAuthenticated) {
        getWishlistCount();
    }

    // Listen to wishlist update events
    document.addEventListener('wishlistUpdated', function(e) {
        console.log('💗 Wishlist updated event:', e.detail);
    });
});