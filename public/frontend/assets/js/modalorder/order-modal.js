// ========================================
// ORDER DETAIL MODALS - JAVASCRIPT
// ========================================

// Open Cancel Modal
function openCancelModal() {
    const modal = document.getElementById('cancelModal');
    const overlay = document.getElementById('cancelModalOverlay');
    
    if (modal && overlay) {
        overlay.classList.add('active');
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }
}

// Close Cancel Modal
function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    const overlay = document.getElementById('cancelModalOverlay');
    
    if (modal && overlay) {
        overlay.classList.remove('active');
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
        
        // Reset form
        const form = document.getElementById('cancelForm');
        if (form) {
            form.reset();
        }
    }
}

// Open Complete Modal
function openCompleteModal() {
    const modal = document.getElementById('completeModal');
    const overlay = document.getElementById('completeModalOverlay');
    
    if (modal && overlay) {
        overlay.classList.add('active');
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }
}

// Close Complete Modal
function closeCompleteModal() {
    const modal = document.getElementById('completeModal');
    const overlay = document.getElementById('completeModalOverlay');
    
    if (modal && overlay) {
        overlay.classList.remove('active');
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }
}

// Open Report Modal
function openReportModal() {
    const modal = document.getElementById('reportModal');
    const overlay = document.getElementById('reportModalOverlay');
    
    if (modal && overlay) {
        overlay.classList.add('active');
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }
}

// Close Report Modal
function closeReportModal() {
    const modal = document.getElementById('reportModal');
    const overlay = document.getElementById('reportModalOverlay');
    
    if (modal && overlay) {
        overlay.classList.remove('active');
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }
}

// Close modal when clicking overlay
document.addEventListener('DOMContentLoaded', function() {
    // Cancel Modal Overlay
    const cancelOverlay = document.getElementById('cancelModalOverlay');
    if (cancelOverlay) {
        cancelOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });
    }
    
    // Complete Modal Overlay
    const completeOverlay = document.getElementById('completeModalOverlay');
    if (completeOverlay) {
        completeOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCompleteModal();
            }
        });
    }
    
    // Report Modal Overlay
    const reportOverlay = document.getElementById('reportModalOverlay');
    if (reportOverlay) {
        reportOverlay.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReportModal();
            }
        });
    }
    
    // Close modals with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCancelModal();
            closeCompleteModal();
            closeReportModal();
        }
    });
});