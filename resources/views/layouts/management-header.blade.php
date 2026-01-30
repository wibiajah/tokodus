{{-- resources/views/layouts/management-header.blade.php --}}
@php
    $icon = $icon ?? 'fas fa-folder';
    $title = $title ?? 'Manajemen';
    $description = $description ?? 'Kelola data Anda';
    $buttonText = $buttonText ?? 'Tambah Data';
    $buttonRoute = $buttonRoute ?? '#';
    $buttonIcon = $buttonIcon ?? 'fas fa-plus-circle';
    $customButton = $customButton ?? null;
@endphp

<div class="management-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="{{ $icon }}"></i> {{ $title }}</h1>
            <p>{{ $description }}</p>
        </div>
        
        @if($customButton)
            {!! $customButton !!}
        @elseif($buttonText)
            <a href="{{ $buttonRoute }}" class="btn-add-management">
                <i class="{{ $buttonIcon }}"></i>
                <span>{{ $buttonText }}</span>
            </a>
        @endif
    </div>
</div>

<style>
/* ===========================
   REUSABLE MANAGEMENT HEADER - SCOPED
=========================== */
.management-header {
    background: #224abe;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    color: white;
    box-shadow: 0 10px 30px rgba(34, 74, 190, 0.3);
}

.management-header h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.management-header p {
    margin: 0;
    opacity: 0.9;
}

.btn-add-management {
    background: #224abe;
    color: white;
    padding: 14px 28px;
    border-radius: 12px;
    border: none;
    font-size: 15px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(34, 74, 190, 0.4);
    text-decoration: none;
}

.btn-add-management:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(34, 74, 190, 0.5);
    color: white;
    text-decoration: none;
}

/* ===========================
   RESPONSIVE - MOBILE
=========================== */
@media (max-width: 767px) {
    .management-header {
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 16px;
    }
    
    .management-header h1 {
        font-size: 18px;
        margin-bottom: 4px;
    }
    
    .management-header p {
        font-size: 12px;
    }
    
    .btn-add-management {
        padding: 10px 16px;
        font-size: 12px;
        border-radius: 8px;
        gap: 6px;
    }
    
    .btn-add-management i {
        font-size: 12px;
    }
}
</style>