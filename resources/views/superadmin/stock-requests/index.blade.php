{{-- resources/views/superadmin/stock-requests/index.blade.php --}}
<x-admin-layout title="Kelola Request Stok">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-inbox"></i> Kelola Request Stok
            </h1>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        @endif

        <!-- Filter Tabs -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                       <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" 
   href="{{ route('superadmin.stock-requests.index', ['status' => 'pending']) }}">
    <i class="fas fa-clock"></i> Pending
    @php
        $pendingCount = \App\Models\StockRequest::where('status', 'pending')->count();
    @endphp
    <span class="badge badge-warning ml-1">{{ $pendingCount }}</span>
</a>
                    </li>
                    <li class="nav-item">
                       <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" 
   href="{{ route('superadmin.stock-requests.index', ['status' => 'approved']) }}">
    <i class="fas fa-check-circle"></i> Approved
    @php
        $approvedCount = \App\Models\StockRequest::where('status', 'approved')->count();
    @endphp
    <span class="badge badge-success ml-1">{{ $approvedCount }}</span>
</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" 
   href="{{ route('superadmin.stock-requests.index', ['status' => 'rejected']) }}">
    <i class="fas fa-times-circle"></i> Rejected
    @php
        $rejectedCount = \App\Models\StockRequest::where('status', 'rejected')->count();
    @endphp
    <span class="badge badge-danger ml-1">{{ $rejectedCount }}</span>
</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" 
   href="{{ route('superadmin.stock-requests.index', ['status' => 'all']) }}">
    <i class="fas fa-list"></i> Semua
    @php
        $allCount = \App\Models\StockRequest::count();
    @endphp
    <span class="badge badge-secondary ml-1">{{ $allCount }}</span>
</a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Produk</th>
                                <th width="20%">Varian yang Direquest</th>
                                <th width="10%">Toko</th>
                                <th width="10%">Diminta Oleh</th>
                                <th width="8%">Status</th>
                                <th width="12%">Tanggal Request</th>
                                <th width="12%">Diproses Oleh</th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            @php
                                $items = is_array($request->items) ? $request->items : json_decode($request->items, true);
                                $totalVariants = is_array($items) ? count($items) : 0;
                                $totalQty = $request->total_quantity;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + $requests->firstItem() - 1 }}</td>
                                
                                <!-- Kolom Produk -->
                                <td>
                                    @if($request->product)
                                        @if($request->product->thumbnail)
                                        <img src="{{ $request->product->thumbnail }}" 
                                             alt="{{ $request->product->title }}" 
                                             class="img-thumbnail mr-2"
                                             style="width: 40px; height: 40px; object-fit: cover; display: inline-block; vertical-align: middle;">
                                        @endif
                                        <strong>{{ Str::limit($request->product->title, 30) }}</strong>
                                        <br>
                                        <small class="text-muted">SKU: {{ $request->product->sku }}</small>
                                    @else
                                        <strong class="text-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Produk Dihapus
                                        </strong>
                                        <br>
                                        <small class="text-muted">ID: {{ $request->product_id }}</small>
                                    @endif
                                </td>
                                
                                <!-- Kolom Varian -->
                                <td>
                                    @if($totalVariants > 0)
                                        <div class="d-flex align-items-start">
                                            @php
                                                $firstVariantId = $items[0]['variant_id'] ?? null;
                                                $firstVariant = $firstVariantId ? \App\Models\ProductVariant::find($firstVariantId) : null;
                                            @endphp
                                            
                                            @if($firstVariant && $firstVariant->photo)
                                            <img src="{{ asset('storage/' . $firstVariant->photo) }}" 
                                                 alt="Variant" 
                                                 class="img-thumbnail mr-2"
                                                 style="width: 35px; height: 35px; object-fit: cover;">
                                            @else
                                            <div class="bg-light mr-2 d-flex align-items-center justify-content-center rounded" 
                                                 style="width: 35px; height: 35px; min-width: 35px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                            @endif
                                            
                                            <div style="line-height: 1.3;">
                                                @if($totalVariants == 1)
                                                    {{-- Hanya 1 varian --}}
                                                    @if($firstVariant)
                                                        @if($firstVariant->parent_id)
                                                            <strong class="text-dark">{{ $firstVariant->parent->name }}</strong> - {{ $firstVariant->name }}
                                                        @else
                                                            <strong class="text-dark">{{ $firstVariant->name }}</strong>
                                                        @endif
                                                    @else
                                                        <span class="text-danger">[Varian Dihapus]</span>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-cube"></i> {{ $items[0]['quantity'] }} pcs
                                                    </small>
                                                @else
                                                    {{-- Lebih dari 1 varian --}}
                                                    <div class="mb-1">
                                                        <span class="badge badge-info">{{ $totalVariants }} Varian</span>
                                                        <span class="badge badge-primary ml-1">{{ $totalQty }} pcs</span>
                                                    </div>
                                                    <small class="text-muted">
                                                        @if($firstVariant)
                                                            @if($firstVariant->parent_id)
                                                                {{ $firstVariant->parent->name }} - {{ $firstVariant->name }}
                                                            @else
                                                                {{ $firstVariant->name }}
                                                            @endif
                                                            ({{ $items[0]['quantity'] }} pcs)
                                                        @else
                                                            Varian #1 ({{ $items[0]['quantity'] }} pcs)
                                                        @endif
                                                        <br>
                                                        <span class="text-info">
                                                            <i class="fas fa-plus-circle"></i> {{ $totalVariants - 1 }} varian lainnya
                                                        </span>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                
                                <!-- Kolom Toko -->
                                <td>
                                    <small>
                                        <i class="fas fa-store text-info"></i>
                                        {{ Str::limit($request->toko->nama_toko, 20) }}
                                    </small>
                                </td>
                                
                                <!-- Kolom Diminta Oleh -->
                                <td>
                                    <small>
                                        <i class="fas fa-user"></i>
                                        {{ Str::limit($request->requestedBy->name, 15) }}
                                        <br>
                                        <span class="text-muted">{{ ucfirst($request->requestedBy->role) }}</span>
                                    </small>
                                </td>
                                
                                <!-- Kolom Status -->
                                <td>
                                    <span class="badge badge-{{ $request->status_badge }}">
                                        {{ $request->status_label }}
                                    </span>
                                </td>
                                
                                <!-- Kolom Tanggal -->
                                <td>
                                    @if($request->requested_at)
                                    <small>
                                        <i class="far fa-calendar"></i>
                                        {{ $request->formatted_requested_at }}
                                    </small>
                                    @if($request->processedBy && $request->processed_at)
                                    <br>
                                    <small class="text-{{ $request->status_badge }}">
                                        <i class="far fa-clock"></i>
                                        {{ $request->formatted_processed_at }}
                                    </small>
                                    @endif
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                
                                <!-- Kolom Diproses Oleh -->
                                <td>
                                    @if($request->processedBy)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mr-2">
                                            <div class="avatar-circle bg-{{ $request->status_badge }}">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <small class="d-block font-weight-bold">{{ Str::limit($request->processedBy->name, 15) }}</small>
                                            <small class="text-muted">{{ ucfirst($request->processedBy->role) }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                
                                <!-- Kolom Aksi -->
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('superadmin.stock-requests.show', $request) }}" 
                                           class="btn btn-info" 
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($request->isPending())
                                        <button type="button" 
                                                class="btn btn-success quick-approve-btn" 
                                                data-id="{{ $request->id }}"
                                                title="Quick Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        <button type="button" 
                                                class="btn btn-danger quick-reject-btn" 
                                                data-id="{{ $request->id }}"
                                                title="Quick Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Menampilkan {{ $requests->firstItem() }} - {{ $requests->lastItem() }} dari {{ $requests->total() }} data
                    </div>
                    <div>
                        {{ $requests->links() }}
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada request stok dengan status "{{ $status }}"</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    </style>

    <script>
   document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // STATE MANAGEMENT - Prevent double submission
    // ============================================
    let isProcessing = false;

    // ============================================
    // UTILITY FUNCTIONS
    // ============================================
    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> 
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            $(alertDiv).alert('close');
        }, 5000);
    }

    function disableButton(btn, text = '<i class="fas fa-spinner fa-spin"></i>') {
        btn.disabled = true;
        btn.dataset.originalHtml = btn.innerHTML;
        btn.innerHTML = text;
    }

    function enableButton(btn) {
        btn.disabled = false;
        btn.innerHTML = btn.dataset.originalHtml || btn.innerHTML;
    }

    function logError(action, error, requestId) {
        console.error(`[Stock Request ${action}] Request ID: ${requestId}`, {
            error: error.message,
            stack: error.stack,
            timestamp: new Date().toISOString()
        });
    }

    // ============================================
    // QUICK APPROVE HANDLER
    // ============================================
    const approveButtons = document.querySelectorAll('.quick-approve-btn');
    
    approveButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Prevent double submission
            if (isProcessing) {
                return;
            }
            
            const requestId = this.getAttribute('data-id');
            
            if (!confirm('Approve request ini?\n\nStok akan langsung didistribusikan ke toko.')) {
                return;
            }
            
            isProcessing = true;
            disableButton(btn);
            
            // Disable other action buttons in same row
            const row = btn.closest('tr');
            const actionButtons = row.querySelectorAll('.btn-group button');
            actionButtons.forEach(b => b.disabled = true);
            
            fetch(`/superadmin/stock-requests/${requestId}/quick-approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                // Add timeout
                signal: AbortSignal.timeout(30000) // 30 seconds timeout
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Gagal approve request');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    
                    // Smooth reload after 1.5 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Gagal approve request');
                }
            })
            .catch(error => {
                logError('Approve', error, requestId);
                
                // Handle different error types
                let errorMessage = 'Terjadi kesalahan: ';
                if (error.name === 'AbortError') {
                    errorMessage += 'Request timeout. Silakan coba lagi.';
                } else if (error.message) {
                    errorMessage += error.message;
                } else {
                    errorMessage += 'Gagal menghubungi server.';
                }
                
                showAlert(errorMessage, 'danger');
                
                // Re-enable buttons
                isProcessing = false;
                enableButton(btn);
                actionButtons.forEach(b => b.disabled = false);
            });
        });
    });

    // ============================================
    // QUICK REJECT HANDLER WITH MODAL
    // ============================================
    const rejectButtons = document.querySelectorAll('.quick-reject-btn');
    
    rejectButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Prevent double submission
            if (isProcessing) {
                return;
            }
            
            const requestId = this.getAttribute('data-id');
            
            // Create modal if not exists
            let modal = document.getElementById('rejectModal');
            if (!modal) {
                modal = createRejectModal();
                document.body.appendChild(modal);
            }
            
            // Set request ID in modal
            modal.dataset.requestId = requestId;
            
            // Clear previous input
            const textarea = modal.querySelector('#rejectReason');
            textarea.value = '';
            textarea.classList.remove('is-invalid');
            
            const errorDiv = modal.querySelector('.invalid-feedback');
            errorDiv.textContent = '';
            
            // Show modal
            $(modal).modal('show');
            
            // Focus on textarea after modal shown
            $(modal).on('shown.bs.modal', function() {
                textarea.focus();
            });
        });
    });

    // ============================================
    // REJECT MODAL CREATION
    // ============================================
    function createRejectModal() {
        const modalHTML = `
            <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-times-circle"></i> Reject Stock Request
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="rejectReason">
                                    Alasan Reject <span class="text-danger">*</span>
                                </label>
                                <textarea 
                                    class="form-control" 
                                    id="rejectReason" 
                                    rows="4" 
                                    placeholder="Jelaskan alasan penolakan request ini..."
                                    maxlength="500"
                                    required
                                ></textarea>
                                <small class="form-text text-muted">
                                    Minimal 10 karakter, maksimal 500 karakter
                                </small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="button" class="btn btn-danger" id="confirmRejectBtn">
                                <i class="fas fa-check"></i> Ya, Reject Request
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const temp = document.createElement('div');
        temp.innerHTML = modalHTML.trim();
        return temp.firstChild;
    }

    // ============================================
    // CONFIRM REJECT HANDLER
    // ============================================
    document.addEventListener('click', function(e) {
        if (e.target.id === 'confirmRejectBtn' || e.target.closest('#confirmRejectBtn')) {
            e.preventDefault();
            
            const modal = document.getElementById('rejectModal');
            const textarea = modal.querySelector('#rejectReason');
            const errorDiv = modal.querySelector('.invalid-feedback');
            const confirmBtn = modal.querySelector('#confirmRejectBtn');
            const requestId = modal.dataset.requestId;
            
            const reason = textarea.value.trim();
            
            // Validation
            textarea.classList.remove('is-invalid');
            errorDiv.textContent = '';
            
            if (reason.length < 10) {
                textarea.classList.add('is-invalid');
                errorDiv.textContent = 'Alasan reject minimal 10 karakter';
                textarea.focus();
                return;
            }
            
            if (reason.length > 500) {
                textarea.classList.add('is-invalid');
                errorDiv.textContent = 'Alasan reject maksimal 500 karakter';
                textarea.focus();
                return;
            }
            
            // Prevent double submission
            if (isProcessing) {
                return;
            }
            
            isProcessing = true;
            
            // Disable modal buttons
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            modal.querySelector('[data-dismiss="modal"]').disabled = true;
            
            fetch(`/superadmin/stock-requests/${requestId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    rejection_reason: reason
                }),
                signal: AbortSignal.timeout(30000)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Gagal reject request');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Close modal
                    $(modal).modal('hide');
                    
                    showAlert(data.message, 'success');
                    
                    // Smooth reload after 1.5 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Gagal reject request');
                }
            })
            .catch(error => {
                logError('Reject', error, requestId);
                
                let errorMessage = 'Terjadi kesalahan: ';
                if (error.name === 'AbortError') {
                    errorMessage += 'Request timeout. Silakan coba lagi.';
                } else if (error.message) {
                    errorMessage += error.message;
                } else {
                    errorMessage += 'Gagal menghubungi server.';
                }
                
                textarea.classList.add('is-invalid');
                errorDiv.textContent = errorMessage;
                
                // Re-enable modal buttons
                isProcessing = false;
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fas fa-check"></i> Ya, Reject Request';
                modal.querySelector('[data-dismiss="modal"]').disabled = false;
            });
        }
    });

    // ============================================
    // CLEANUP ON MODAL CLOSE
    // ============================================
    $(document).on('hidden.bs.modal', '#rejectModal', function() {
        isProcessing = false;
    });
});
    </script>

</x-admin-layout>