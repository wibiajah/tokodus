<x-admin-layout>
    <x-slot name="header">
        <h2 class="h3 mb-0">{{ __('Manajemen Produk') }}</h2>
    </x-slot>

    <style>
        /* Product Management Page Scoped Styles */
        .product-management-page .products-header {
            background: #224abe;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 30px rgba(34, 74, 190, 0.3);
        }

        .product-management-page .products-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .product-management-page .products-header p {
            margin: 0;
            opacity: 0.9;
        }

        /* Filter Section */
        .product-management-page .filter-section {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .product-management-page .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .product-management-page .filter-item {
            display: flex;
            flex-direction: column;
        }

        .product-management-page .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .product-management-page .filter-label i {
            margin-right: 8px;
            color: #224abe;
        }

        .product-management-page .filter-input {
            padding: 12px 16px;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .product-management-page .filter-input:focus {
            outline: none;
            border-color: #224abe;
            box-shadow: 0 0 0 3px rgba(34, 74, 190, 0.1);
        }

        .product-management-page .filter-stats {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
        }

        .product-management-page .filter-result {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }

        .product-management-page .filter-result strong {
            color: #224abe;
            font-size: 18px;
        }

        .product-management-page .btn-reset-filter {
            background: #f8f9fa;
            border: 2px solid #e0e6ed;
            color: #666;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
        }

        .product-management-page .btn-reset-filter:hover {
            background: #224abe;
            border-color: #224abe;
            color: white;
        }

        .product-management-page .btn-add-product {
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

        .product-management-page .btn-add-product:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 74, 190, 0.5);
            color: white;
            text-decoration: none;
        }

        .product-management-page .btn-view-mode {
            background: white;
            border: 2px solid #e0e6ed;
            color: #666;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .product-management-page .btn-view-mode:hover,
        .product-management-page .btn-view-mode.active {
            border-color: #224abe;
            background: #224abe;
            color: white;
            text-decoration: none;
        }

        /* Table Styles */
        .product-management-page .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
        }

        .product-management-page .table {
            margin-bottom: 0;
        }

        .product-management-page .table thead {
            background: linear-gradient(135deg, #f8f9fc 0%, #eef2f7 100%);
        }

        .product-management-page .table thead th {
            font-weight: 700;
            color: #224abe;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 16px 12px;
            border: none;
        }

        .product-management-page .table tbody tr {
            transition: all 0.2s;
        }

        .product-management-page .table tbody tr:hover {
            background: #f8f9fc;
            cursor: pointer;
        }

        .product-management-page .table tbody td {
            padding: 16px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-management-page .action-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
            text-decoration: none;
            font-size: 1rem;
        }

        .product-management-page .action-icon:hover {
            transform: scale(1.1);
            text-decoration: none;
        }

        .product-management-page .action-icon.view {
            background: #e3f2fd;
            color: #2196f3;
        }

        .product-management-page .action-icon.edit {
            background: #fff3e0;
            color: #ff9800;
        }

        .product-management-page .action-icon.stock {
            background: #e8f5e9;
            color: #4caf50;
        }

        .product-management-page .action-icon.delete {
            background: #ffebee;
            color: #f44336;
        }

        /* CARD STYLES - FLEXBOX SOLUTION */
        #cardView {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 24px !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* CRITICAL: Hide cardView when not active */
        #cardView[data-hidden="true"] {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            overflow: hidden !important;
        }

        #cardView[data-hidden="true"] * {
            display: none !important;
        }

        #cardView[style*="display: none"],
        #cardView[style*="display:none"] {
            display: none !important;
        }

        #cardView[style*="display: none"] .product-card-item,
        #cardView[style*="display:none"] .product-card-item {
            display: none !important;
        }

        .product-card-item {
            flex: 0 0 calc(20% - 19.2px) !important;
            width: calc(20% - 19.2px) !important;
            max-width: calc(20% - 19.2px) !important;
            min-width: 220px !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        @media (max-width: 1600px) {
            .product-card-item {
                flex: 0 0 calc(25% - 18px) !important;
                width: calc(25% - 18px) !important;
                max-width: calc(25% - 18px) !important;
            }
        }

        @media (max-width: 1200px) {
            .product-card-item {
                flex: 0 0 calc(33.333% - 16px) !important;
                width: calc(33.333% - 16px) !important;
                max-width: calc(33.333% - 16px) !important;
            }
        }

        @media (max-width: 768px) {
            .product-card-item {
                flex: 0 0 calc(50% - 12px) !important;
                width: calc(50% - 12px) !important;
                max-width: calc(50% - 12px) !important;
            }
        }

        .modern-product-card {
            width: 100% !important;
            height: 280px !important;
            max-height: 280px !important;
            min-height: 280px !important;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 20px -8px rgba(34, 74, 190, 0.15);
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .modern-product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px -10px rgba(34, 74, 190, 0.3);
        }

        .card-image-wrapper {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: #E9D5FF;
            border-bottom-right-radius: 60px;
            overflow: hidden;
        }

        .card-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            user-select: none;
        }

        .card-image-wrapper::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(34, 74, 190, 0.1);
            mix-blend-mode: overlay;
            z-index: 1;
        }

        .card-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #224abe;
        }

        .card-image-placeholder i {
            font-size: 42px;
            color: rgba(255, 255, 255, 0.3);
        }

        .product-management-page .card-status-overlay {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 600;
            backdrop-filter: blur(10px);
            z-index: 10;
            letter-spacing: 0.5px;
            color: white;
        }

        .product-management-page .status-active {
            background: rgba(40, 167, 69, 0.95);
        }

        .product-management-page .status-inactive {
            background: rgba(108, 117, 125, 0.95);
        }

        .product-management-page .card-discount-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 700;
            background: rgba(220, 53, 69, 0.95);
            color: white;
            z-index: 10;
        }

        .card-slide-panel {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #FFFFFF;
            border-radius: 20px;
            padding: 14px 16px 16px;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 15;
            transform: translateY(calc(100% - 85px));
        }

        .modern-product-card:hover .card-slide-panel {
            transform: translateY(calc(100% - 160px));
        }

        .card-title-section {
            margin-bottom: 0;
        }

        .product-management-page .card-product-title {
            color: #2d3748;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 0.02em;
            line-height: 1.3;
            margin: 0 0 6px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-divider {
            width: 100%;
            height: 1px;
            background: #e2e8f0;
            margin-bottom: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modern-product-card:hover .card-divider {
            opacity: 1;
        }

        .card-hidden-info {
            margin-bottom: 0;
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: opacity 0.3s ease 0.2s, max-height 0.4s ease;
        }

        .modern-product-card:hover .card-hidden-info {
            opacity: 1;
            max-height: 100px;
        }

        .info-row {
            display: flex;
            flex-direction: column;
            margin-bottom: 8px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .product-management-page .info-label {
            font-size: 9px;
            font-weight: 600;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .product-management-page .info-value {
            font-size: 11px;
            font-weight: 500;
            color: #4A5568;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-quick-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
            padding-top: 0;
            border-top: none;
            margin-bottom: 12px;
        }

        .quick-action-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 50%;
            color: #224abe;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            cursor: pointer;
        }

        .quick-action-icon:focus,
        .quick-action-icon:active,
        .quick-action-icon:focus-visible {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }

        .quick-action-icon:hover {
            background: #224abe;
            color: white;
            transform: scale(1.1);
            text-decoration: none;
            border: none !important;
            outline: none !important;
        }

        .quick-action-icon.edit-icon:hover {
            background: #ff9800;
            color: white;
        }

        .quick-action-icon.stock-icon:hover {
            background: #28a745;
            color: white;
        }

        .quick-action-icon.delete-icon:hover {
            background: #dc3545;
            color: white;
        }

        .quick-action-icon i {
            font-size: 0.9rem;
        }

        .product-management-page .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .product-management-page .no-results i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .product-management-page .no-results h4 {
            font-size: 20px;
            font-weight: 600;
            color: #666;
            margin-bottom: 10px;
        }

        .product-management-page .no-results p {
            font-size: 14px;
            color: #999;
        }

        .mobile-simple-card {
            display: none;
        }

        @media (min-width: 1600px) {
            #cardView {
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            }
        }

        @media (min-width: 1400px) and (max-width: 1599px) {
            #cardView {
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            }
        }

        @media (min-width: 1200px) and (max-width: 1399px) {
            #cardView {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {
            #cardView {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 20px;
            }

            .modern-product-card {
                height: 240px;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            #cardView {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 18px;
            }

            .modern-product-card {
                height: 230px;
            }

            .product-management-page .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 767px) {
            .modern-product-card {
                display: none !important;
            }

            .mobile-simple-card {
                display: block !important;
            }

            #cardView {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .mobile-simple-card {
                background: white;
                border-radius: 12px;
                padding: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                cursor: pointer;
            }

            .mobile-card-image {
                width: 100%;
                aspect-ratio: 1/1;
                border-radius: 10px;
                overflow: hidden;
                background: #224abe;
                margin-bottom: 10px;
                position: relative;
            }

            .mobile-card-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .mobile-card-name {
                font-size: 12px;
                font-weight: 700;
                color: #2d3748;
                margin: 0 0 4px 0;
                min-height: 30px;
            }

            .mobile-card-price {
                font-size: 10px;
                color: #224abe;
                font-weight: 700;
                margin: 0 0 8px 0;
            }

            /* ===========================
            MOBILE CARD VIEW FIX - CONSISTENT WITH TOKO/USER
            Add these styles to existing <style> section
            =========================== */

            /* Desktop/Mobile Toggle Classes */
            .desktop-only {
                display: block;
            }

            .mobile-only {
                display: none !important;
            }

            /* Card View Container - Desktop */
            #cardView {
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 24px !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            #cardView[data-hidden="true"] {
                display: none !important;
            }

            /* Product Card Item - Desktop */
            .product-card-item {
                flex: 0 0 calc(20% - 19.2px) !important;
                width: calc(20% - 19.2px) !important;
                max-width: calc(20% - 19.2px) !important;
                min-width: 220px !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* ===========================
            MOBILE MODE (< 768px)
            =========================== */
            @media (max-width: 767px) {

                /* Hide Desktop Card, Show Mobile Card */
                .desktop-only {
                    display: none !important;
                }

                .mobile-only {
                    display: block !important;
                }

                /* Card View Container - Mobile Grid (Same as Toko/User) */
                #cardView {
                    display: grid !important;
                    grid-template-columns: repeat(2, 1fr) !important;
                    gap: 12px !important;
                    padding: 0 !important;
                    margin: 0 !important;
                }

                /* Product Card Item - Mobile */
                .product-card-item {
                    flex: none !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    min-width: 0 !important;
                    padding: 0 !important;
                    margin: 0 !important;
                }

                /* Mobile Simple Card */
                .mobile-simple-card {
                    background: white;
                    border-radius: 12px;
                    padding: 12px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    cursor: pointer;
                    transition: all 0.3s ease;
                    -webkit-tap-highlight-color: transparent;
                }

                .mobile-simple-card:active {
                    transform: scale(0.97);
                    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
                }

                /* Mobile Card Image */
                .mobile-card-image {
                    width: 100%;
                    aspect-ratio: 1/1;
                    border-radius: 10px;
                    overflow: hidden;
                    background: #224abe;
                    margin-bottom: 10px;
                    position: relative;
                }

                .mobile-card-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .mobile-card-image .placeholder {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .mobile-card-image .placeholder i {
                    font-size: 32px;
                    color: rgba(255, 255, 255, 0.4);
                }

                /* Status Badge */
                .mobile-status-badge {
                    position: absolute;
                    top: 6px;
                    right: 6px;
                    padding: 4px 8px;
                    border-radius: 8px;
                    font-size: 8px;
                    font-weight: 700;
                    color: white;
                    backdrop-filter: blur(5px);
                    text-transform: uppercase;
                }

                .mobile-status-badge.status-active {
                    background: rgba(40, 167, 69, 0.95);
                }

                .mobile-status-badge.status-inactive {
                    background: rgba(108, 117, 125, 0.95);
                }

                /* Discount Badge */
                .mobile-discount-badge {
                    position: absolute;
                    top: 6px;
                    left: 6px;
                    padding: 4px 8px;
                    border-radius: 8px;
                    font-size: 8px;
                    font-weight: 700;
                    background: rgba(220, 53, 69, 0.95);
                    color: white;
                }

                /* Mobile Card Content */
                .mobile-card-content {
                    text-align: center;
                }

                .mobile-card-name {
                    font-size: 12px;
                    font-weight: 700;
                    color: #2d3748;
                    margin: 0 0 4px 0;
                    line-height: 1.3;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    min-height: 30px;
                }

                .mobile-card-price {
                    font-size: 10px;
                    color: #224abe;
                    font-weight: 700;
                    margin: 0 0 8px 0;
                }

                /* Mobile Card Actions */
                .mobile-card-actions {
                    display: flex;
                    gap: 6px;
                    justify-content: center;
                }

                .mobile-action-btn {
                    width: 28px;
                    height: 28px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #f1f5f9;
                    border-radius: 50%;
                    color: #224abe;
                    border: none;
                    cursor: pointer;
                    transition: all 0.2s;
                    -webkit-tap-highlight-color: transparent;
                }

                .mobile-action-btn:active {
                    transform: scale(0.9);
                }

                .mobile-action-btn.edit {
                    color: #ff9800;
                }

                .mobile-action-btn.stock {
                    color: #28a745;
                }

                .mobile-action-btn.delete {
                    color: #dc3545;
                }

                .mobile-action-btn i {
                    font-size: 12px;
                }

                /* Filter Section Mobile Adjustments */
                .product-management-page .filter-section {
                    padding: 16px;
                    border-radius: 12px;
                    margin-bottom: 16px;
                }

                .product-management-page .filter-grid {
                    grid-template-columns: 1fr;
                    gap: 12px;
                    margin-bottom: 12px;
                }

                .product-management-page .filter-label {
                    font-size: 12px;
                }

                .product-management-page .filter-input {
                    padding: 10px 12px;
                    font-size: 13px;
                    border-radius: 8px;
                }

                .product-management-page .filter-stats {
                    flex-direction: column;
                    gap: 10px;
                    align-items: stretch;
                }

                .product-management-page .filter-result {
                    font-size: 12px;
                    text-align: center;
                }

                .product-management-page .btn-reset-filter {
                    width: 100%;
                    padding: 10px;
                    font-size: 12px;
                }

                /* Header Mobile Adjustments */
                .product-management-page .products-header {
                    padding: 16px;
                    border-radius: 12px;
                    margin-bottom: 16px;
                }

                .product-management-page .products-header h1 {
                    font-size: 18px;
                    margin-bottom: 4px;
                }

                .product-management-page .products-header p {
                    font-size: 12px;
                }

                .product-management-page .btn-add-product {
                    padding: 10px 16px;
                    font-size: 12px;
                    border-radius: 8px;
                    gap: 6px;
                }

                .product-management-page .btn-view-mode {
                    padding: 8px 14px;
                    font-size: 12px;
                    border-radius: 8px;
                    gap: 6px;
                }

                /* No Results Mobile */
                .product-management-page .no-results {
                    padding: 40px 16px;
                }

                .product-management-page .no-results i {
                    font-size: 48px;
                }

                .product-management-page .no-results h4 {
                    font-size: 16px;
                }

                .product-management-page .no-results p {
                    font-size: 12px;
                }
            }

            /* Extra Small Mobile (< 400px) */
            @media (max-width: 399px) {
                #cardView {
                    gap: 10px !important;
                }

                .mobile-simple-card {
                    padding: 10px;
                    border-radius: 10px;
                }

                .mobile-card-image {
                    margin-bottom: 8px;
                    border-radius: 8px;
                }

                .mobile-card-name {
                    font-size: 11px;
                    min-height: 28px;
                }

                .mobile-card-price {
                    font-size: 9px;
                    margin-bottom: 6px;
                }

                .mobile-action-btn {
                    width: 26px;
                    height: 26px;
                }

                .mobile-action-btn i {
                    font-size: 11px;
                }
            }
        }
    </style>

    <div class="container-fluid product-management-page">
        <div class="products-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1><i class="fas fa-box-open"></i> Manajemen Produk</h1>
                    <p>Kelola semua produk dan informasi terkait</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="switchView('list')" class="btn-view-mode" id="btnListView">
                        <i class="fas fa-list"></i>
                        <span>List</span>
                    </button>
                    <button onclick="switchView('card')" class="btn-view-mode" id="btnCardView">
                        <i class="fas fa-th-large"></i>
                        <span>Card</span>
                    </button>
                    <a href="{{ route('superadmin.products.create') }}" class="btn-add-product">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Produk</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-search"></i>
                        Cari Produk
                    </label>
                    <input type="text" id="filterName" class="filter-input"
                        placeholder="Ketik nama produk atau SKU...">
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-tags"></i>
                        Filter Kategori
                    </label>
                    <select id="filterCategory" class="filter-input">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ strtolower($category->name) }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-toggle-on"></i>
                        Filter Status
                    </label>
                    <select id="filterStatus" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="filter-item">
                    <label class="filter-label">
                        <i class="fas fa-sort"></i>
                        Urutkan
                    </label>
                    <select id="filterSort" class="filter-input">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="name_asc">Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                        <option value="price_asc">Harga Terendah</option>
                        <option value="price_desc">Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            <div class="filter-stats">
                <div class="filter-result">
                    Menampilkan <strong id="resultCount">{{ $products->count() }}</strong> dari
                    <strong>{{ $products->total() }}</strong> produk
                </div>
                <button class="btn-reset-filter" onclick="resetFilters()">
                    <i class="fas fa-redo"></i> Reset Filter
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- LIST VIEW -->
        <div id="listView" style="display: none;">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Produk</th>
                                <th>SKU</th>
                                <th>Tipe</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Total Stok</th>
                                <th>Varian</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="listViewBody">
                            @foreach ($products as $product)
                                <tr class="product-item" data-name="{{ strtolower($product->title) }}"
                                    data-sku="{{ strtolower($product->sku) }}"
                                    data-categories="{{ strtolower($product->categories->pluck('name')->join(',')) }}"
                                    data-status="{{ $product->is_active ? '1' : '0' }}"
                                    data-created="{{ $product->created_at->timestamp }}"
                                    data-price="{{ $product->discount_price ?? $product->price }}"
                                    onclick="showProductDetail({{ $product->id }})">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($product->photos && count($product->photos) > 0)
                                            <img src="{{ asset('storage/' . $product->photos[0]) }}"
                                                alt="{{ $product->title }}" class="rounded"
                                                style="width: 64px; height: 64px; object-fit: cover;">
                                        @else
                                            <div style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center; background: #f0f0f0;"
                                                class="rounded">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $product->title }}</div>
                                        @if ($product->tags)
                                            <div class="d-flex gap-1 mt-1 flex-wrap">
                                                @foreach (array_slice($product->tags, 0, 3) as $tag)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary"
                                                        style="font-size: 0.7rem;">{{ $tag }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="font-monospace small">{{ $product->sku }}</span>
                                    </td>
                                    <td>
                                        @if ($product->tipe === 'innerbox')
                                            <span class="badge"
                                                style="background: #1976d2; color: #ffffff; font-weight: 600; padding: 6px 12px; border-radius: 8px; font-size: 0.85rem;">
                                                <i class="fas fa-box"></i> Inner Box
                                            </span>
                                        @elseif($product->tipe === 'masterbox')
                                            <span class="badge"
                                                style="background: #7b1fa2; color: #ffffff; font-weight: 600; padding: 6px 12px; border-radius: 8px; font-size: 0.85rem;">
                                                <i class="fas fa-boxes"></i> Master Box
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse($product->categories as $category)
                                                <span class="badge"
                                                    style="background-color: #7c3aed; color: #ffffff; padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 600;">
                                                    {{ $category->name }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        @if ($product->discount_price)
                                            <div class="text-decoration-line-through text-muted small">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </div>
                                            <div class="fw-bold text-danger">
                                                Rp {{ number_format($product->discount_price, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <div class="fw-semibold">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $stockPusat = 0;
                                            $allColorVariants = $product
                                                ->variants()
                                                ->whereNull('parent_id')
                                                ->with('children')
                                                ->get();
                                            foreach ($allColorVariants as $cv) {
                                                if ($cv->children && $cv->children->count() > 0) {
                                                    $stockPusat += $cv->children->sum('stock_pusat');
                                                } else {
                                                    $stockPusat += $cv->stock_pusat;
                                                }
                                            }

                                            $stockToko = $product->variantStocks()->sum('stock') ?? 0;
                                            $totalStock = $stockPusat + $stockToko;
                                            $badgeClass =
                                                $totalStock > 50
                                                    ? 'success'
                                                    : ($totalStock > 10
                                                        ? 'warning'
                                                        : 'danger');
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }} text-white fw-semibold">
                                            {{ number_format($totalStock) }} pcs
                                        </span>
                                        <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                            <div><strong>Pusat:</strong> {{ number_format($stockPusat) }} pcs</div>
                                            <div><strong>Toko:</strong> {{ number_format($stockToko) }} pcs</div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $colorCount = $product->variants()->whereNull('parent_id')->count();
                                            $sizeCount = $product->variants()->whereNotNull('parent_id')->count();
                                        @endphp
                                        <div class="text-gray-700 fw-semibold">{{ $colorCount }} warna</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $sizeCount }}
                                            ukuran</div>
                                    </td>
                                    <td>
                                        @if ($product->is_active)
                                            <span class="badge bg-success text-white" style="cursor: pointer;"
                                                onclick="event.stopPropagation(); toggleProductStatus({{ $product->id }}, this)"
                                                title="Klik untuk nonaktifkan">
                                                <i class="fas fa-toggle-on me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white" style="cursor: pointer;"
                                                onclick="event.stopPropagation(); toggleProductStatus({{ $product->id }}, this)"
                                                title="Klik untuk aktifkan">
                                                <i class="fas fa-toggle-off me-1"></i> Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td onclick="event.stopPropagation()">
                                        <div class="d-flex gap-2">
                                            <button onclick="showProductDetail({{ $product->id }})"
                                                class="action-icon view" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('superadmin.products.edit', $product) }}"
                                                class="action-icon edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('superadmin.stocks.detail', $product) }}"
                                                class="action-icon stock" title="Kelola Stok">
                                                <i class="fas fa-box"></i>
                                            </a>
                                            <form action="{{ route('superadmin.products.destroy', $product) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-icon delete border-0"
                                                    title="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- CARD VIEW -->
        <!-- CARD VIEW - Replace existing #cardView section -->
        <div id="cardView" style="display: none;">
            @foreach ($products as $product)
                <div class="product-card-item product-item" data-name="{{ strtolower($product->title) }}"
                    data-sku="{{ strtolower($product->sku) }}"
                    data-categories="{{ strtolower($product->categories->pluck('name')->join(',')) }}"
                    data-status="{{ $product->is_active ? '1' : '0' }}"
                    data-created="{{ $product->created_at->timestamp }}"
                    data-price="{{ $product->discount_price ?? $product->price }}">

                    <!-- DESKTOP CARD (Hidden on Mobile) -->
                    <div class="modern-product-card desktop-only" onclick="showProductDetail({{ $product->id }})">
                        <div class="card-image-wrapper">
                            @if ($product->photos && count($product->photos) > 0)
                                <img src="{{ asset('storage/' . $product->photos[0]) }}"
                                    alt="{{ $product->title }}">
                            @else
                                <div class="card-image-placeholder">
                                    <i class="fas fa-box-open"></i>
                                </div>
                            @endif

                            <div class="card-status-overlay {{ $product->is_active ? 'status-active' : 'status-inactive' }}"
                                onclick="event.stopPropagation(); toggleProductStatus({{ $product->id }}, this)"
                                style="cursor: pointer;"
                                title="{{ $product->is_active ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}">
                                {{ $product->is_active ? '● Aktif' : '● Nonaktif' }}
                            </div>

                            @if ($product->discount_price)
                                @php
                                    $discount = round(
                                        (($product->price - $product->discount_price) / $product->price) * 100,
                                    );
                                @endphp
                                <div class="card-discount-badge">-{{ $discount }}%</div>
                            @endif
                        </div>

                        <div class="card-slide-panel">
                            <div class="card-title-section">
                                <h6 class="card-product-title">{{ Str::upper($product->title) }}</h6>
                            </div>

                            <div class="card-divider"></div>

                            <div class="card-hidden-info">
                                <div class="info-row">
                                    <span class="info-label">SKU</span>
                                    <span class="info-value">{{ $product->sku }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">Harga</span>
                                    <span class="info-value">
                                        Rp
                                        {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}
                                        @if ($product->discount_price)
                                            <span style="text-decoration: line-through; font-size: 9px; color: #999;">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="card-quick-actions">
                                <button onclick="event.stopPropagation(); showProductDetail({{ $product->id }})"
                                    class="quick-action-icon" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <a href="{{ route('superadmin.products.edit', $product) }}"
                                    class="quick-action-icon edit-icon" onclick="event.stopPropagation()"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ route('superadmin.stocks.detail', $product) }}"
                                    class="quick-action-icon stock-icon" onclick="event.stopPropagation()"
                                    title="Kelola Stok">
                                    <i class="fas fa-box"></i>
                                </a>

                                <form action="{{ route('superadmin.products.destroy', $product) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="event.stopPropagation(); return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="quick-action-icon delete-icon" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- MOBILE SIMPLE CARD (Visible on Mobile) -->
                    <div class="mobile-simple-card mobile-only" onclick="showProductDetail({{ $product->id }})">
                        <div class="mobile-card-image">
                            @if ($product->photos && count($product->photos) > 0)
                                <img src="{{ asset('storage/' . $product->photos[0]) }}"
                                    alt="{{ $product->title }}">
                            @else
                                <div class="placeholder">
                                    <i class="fas fa-box-open"></i>
                                </div>
                            @endif

                            <div
                                class="mobile-status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </div>

                            @if ($product->discount_price)
                                @php
                                    $discount = round(
                                        (($product->price - $product->discount_price) / $product->price) * 100,
                                    );
                                @endphp
                                <div class="mobile-discount-badge">-{{ $discount }}%</div>
                            @endif
                        </div>

                        <div class="mobile-card-content">
                            <h6 class="mobile-card-name">{{ Str::upper($product->title) }}</h6>
                            <p class="mobile-card-price">Rp
                                {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}</p>

                            <div class="mobile-card-actions">
                                <a href="{{ route('superadmin.products.edit', $product) }}"
                                    class="mobile-action-btn edit" onclick="event.stopPropagation()">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="{{ route('superadmin.stocks.detail', $product) }}"
                                    class="mobile-action-btn stock" onclick="event.stopPropagation()">
                                    <i class="fas fa-box"></i>
                                </a>

                                <form action="{{ route('superadmin.products.destroy', $product) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="event.stopPropagation(); return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="mobile-action-btn delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="no-results" id="noResults" style="display: none;">
            <i class="fas fa-search"></i>
            <h4>Tidak Ada Hasil</h4>
            <p>Tidak ditemukan produk yang sesuai dengan filter Anda</p>
        </div>

        @if ($products->hasPages())
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    @include('superadmin.products.partials.detail-modal')

    <script>
        // ===========================
        // MOBILE CARD VIEW FIX - CONSISTENT GRID SYSTEM
        // Add/Replace these functions in existing <script> section
        // ===========================

        const productsData = @json($productsData);
        const productDetailCache = {};
        let currentView = 'list';

        // ===========================
        // IMPROVED SWITCH VIEW FUNCTION
        // ===========================
        function switchView(view) {
            currentView = view;
            const listView = document.getElementById('listView');
            const cardView = document.getElementById('cardView');
            const btnListView = document.getElementById('btnListView');
            const btnCardView = document.getElementById('btnCardView');

            if (view === 'list') {
                listView.style.display = 'block';
                cardView.style.display = 'none';
                cardView.setAttribute('data-hidden', 'true');
                btnListView.classList.add('active');
                btnCardView.classList.remove('active');
            } else {
                listView.style.display = 'none';

                // Mobile vs Desktop Grid Layout
                if (window.innerWidth <= 767) {
                    // Mobile: Use CSS Grid (2 columns)
                    cardView.style.cssText = 'display: grid !important;';
                } else {
                    // Desktop: Use Flexbox
                    cardView.style.cssText =
                        'display: flex !important; flex-wrap: wrap !important; gap: 24px !important; width: 100% !important;';
                }

                cardView.removeAttribute('data-hidden');
                btnListView.classList.remove('active');
                btnCardView.classList.add('active');
            }

            localStorage.setItem('productViewMode', view);
            filterProducts();
        }

        // ===========================
        // RESPONSIVE HANDLER
        // ===========================
        (function() {
            'use strict';

            let resizeTimeout;

            function handleResize() {
                if (currentView === 'card') {
                    const cardView = document.getElementById('cardView');
                    if (!cardView) return;

                    // Re-apply correct display style based on screen size
                    if (window.innerWidth <= 767) {
                        cardView.style.cssText = 'display: grid !important;';
                    } else {
                        cardView.style.cssText =
                            'display: flex !important; flex-wrap: wrap !important; gap: 24px !important; width: 100% !important;';
                    }
                }
            }

            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(handleResize, 150);
            });

            // Initial check on page load
            document.addEventListener('DOMContentLoaded', function() {
                const savedView = localStorage.getItem('productViewMode') || 'list';
                switchView(savedView);
            });
        })();

        // ===========================
        // FILTER PRODUCTS (Keep Existing Logic)
        // ===========================
        function filterProducts() {
            const nameFilter = document.getElementById('filterName').value.toLowerCase();
            const categoryFilter = document.getElementById('filterCategory').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const sortFilter = document.getElementById('filterSort').value;

            const noResults = document.getElementById('noResults');
            const listView = document.getElementById('listView');
            const cardView = document.getElementById('cardView');

            let visibleCount = 0;
            let items = [];

            if (currentView === 'list') {
                items = Array.from(document.querySelectorAll('#listViewBody .product-item'));
            } else {
                items = Array.from(document.querySelectorAll('#cardView .product-card-item'));
            }

            items.forEach(item => {
                const name = item.getAttribute('data-name') || '';
                const sku = item.getAttribute('data-sku') || '';
                const categories = item.getAttribute('data-categories') || '';
                const status = item.getAttribute('data-status') || '';

                const matchName = !nameFilter || name.includes(nameFilter) || sku.includes(nameFilter);
                const matchCategory = !categoryFilter || categories.includes(categoryFilter);
                const matchStatus = !statusFilter || status === statusFilter;

                if (matchName && matchCategory && matchStatus) {
                    item.style.display = currentView === 'list' ? 'table-row' : 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            const sortedItems = items.sort((a, b) => {
                if (sortFilter === 'newest') {
                    return parseInt(b.getAttribute('data-created') || 0) - parseInt(a.getAttribute(
                        'data-created') || 0);
                } else if (sortFilter === 'oldest') {
                    return parseInt(a.getAttribute('data-created') || 0) - parseInt(b.getAttribute(
                        'data-created') || 0);
                } else if (sortFilter === 'name_asc') {
                    return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
                } else if (sortFilter === 'name_desc') {
                    return (b.getAttribute('data-name') || '').localeCompare(a.getAttribute('data-name') || '');
                } else if (sortFilter === 'price_asc') {
                    return parseFloat(a.getAttribute('data-price') || 0) - parseFloat(b.getAttribute(
                        'data-price') || 0);
                } else if (sortFilter === 'price_desc') {
                    return parseFloat(b.getAttribute('data-price') || 0) - parseFloat(a.getAttribute(
                        'data-price') || 0);
                }
                return 0;
            });

            if (currentView === 'list') {
                const tbody = document.getElementById('listViewBody');
                sortedItems.forEach(item => {
                    tbody.appendChild(item);
                });
            } else {
                sortedItems.forEach(item => {
                    cardView.appendChild(item);
                });
            }

            document.getElementById('resultCount').textContent = visibleCount;

            if (visibleCount === 0) {
                listView.style.display = 'none';
                cardView.style.display = 'none';
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
                if (currentView === 'list') {
                    listView.style.display = 'block';
                    cardView.style.display = 'none';
                } else {
                    listView.style.display = 'none';
                    if (window.innerWidth <= 767) {
                        cardView.style.cssText = 'display: grid !important;';
                    } else {
                        cardView.style.cssText =
                            'display: flex !important; flex-wrap: wrap !important; gap: 24px !important; width: 100% !important;';
                    }
                }
            }
        }

        // ===========================
        // RESET FILTERS
        // ===========================
        function resetFilters() {
            document.getElementById('filterName').value = '';
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterSort').value = 'newest';
            filterProducts();
        }

        // ===========================
        // EVENT LISTENERS
        // ===========================
        document.getElementById('filterName').addEventListener('input', filterProducts);
        document.getElementById('filterCategory').addEventListener('change', filterProducts);
        document.getElementById('filterStatus').addEventListener('change', filterProducts);
        document.getElementById('filterSort').addEventListener('change', filterProducts);

        // ===========================
        // PRODUCT DETAIL MODAL (Keep Existing)
        // ===========================
        async function showProductDetail(productId) {
            $('#productDetailModal').modal('show');
            $('#productContent').hide();
            $('#loadingProductState').show();

            try {
                if (productDetailCache[productId]) {
                    renderProductDetail(productDetailCache[productId]);
                    $('#loadingProductState').hide();
                    $('#productContent').fadeIn();
                    return;
                }

                const response = await fetch(`/superadmin/products/${productId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load product details');
                }

                const product = await response.json();
                productDetailCache[productId] = product;

                renderProductDetail(product);
                $('#loadingProductState').hide();
                $('#productContent').fadeIn();

            } catch (error) {
                console.error('Error loading product detail:', error);
                $('#loadingProductState').html(`
            <div class="alert alert-danger m-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Gagal memuat detail produk. Silakan coba lagi.
            </div>
        `);
            }
        }

        // ===========================
        // TOGGLE PRODUCT STATUS (Keep Existing)
        // ===========================
        async function toggleProductStatus(productId, element) {
            element.style.pointerEvents = 'none';
            element.style.opacity = '0.6';

            try {
                const response = await fetch(`/superadmin/products/${productId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to toggle status');
                }

                // Update badge
                if (element.classList.contains('badge')) {
                    if (data.is_active) {
                        element.className = 'badge bg-success text-white';
                        element.innerHTML = '<i class="fas fa-toggle-on me-1"></i> Aktif';
                        element.title = 'Klik untuk nonaktifkan';
                    } else {
                        element.className = 'badge bg-danger text-white';
                        element.innerHTML = '<i class="fas fa-toggle-off me-1"></i> Nonaktif';
                        element.title = 'Klik untuk aktifkan';
                    }
                    element.style.cursor = 'pointer';
                }

                if (element.classList.contains('card-status-overlay') || element.classList.contains(
                        'mobile-status-badge')) {
                    if (data.is_active) {
                        element.className = element.classList.contains('mobile-status-badge') ?
                            'mobile-status-badge status-active' : 'card-status-overlay status-active';
                        element.textContent = data.is_active ? '● Aktif' : 'Aktif';
                        element.title = 'Klik untuk nonaktifkan';
                    } else {
                        element.className = element.classList.contains('mobile-status-badge') ?
                            'mobile-status-badge status-inactive' : 'card-status-overlay status-inactive';
                        element.textContent = data.is_active ? '● Aktif' : 'Nonaktif';
                        element.title = 'Klik untuk aktifkan';
                    }
                    element.style.cursor = 'pointer';
                }

                const cardItem = element.closest('.product-card-item') || element.closest('.product-item');
                if (cardItem) {
                    cardItem.setAttribute('data-status', data.is_active ? '1' : '0');
                }

                showAlert('success', `Produk berhasil diubah menjadi ${data.is_active ? 'Aktif' : 'Nonaktif'}`);

            } catch (error) {
                console.error('Error toggling status:', error);
                showAlert('danger', 'Gagal mengubah status produk. Silakan coba lagi.');
            } finally {
                element.style.pointerEvents = 'auto';
                element.style.opacity = '1';
            }
        }

        // ===========================
        // SHOW ALERT (Keep Existing)
        // ===========================
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            if (!alertContainer) return;

            let icon = 'fa-check-circle';
            if (type === 'danger') icon = 'fa-times-circle';
            else if (type === 'warning') icon = 'fa-exclamation-triangle';
            else if (type === 'info') icon = 'fa-info-circle';

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-custom alert-dismissible fade show`;
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
        <i class="fas ${icon} mr-2"></i>
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;

            alertContainer.appendChild(alertDiv);

            setTimeout(() => {
                $(alertDiv).alert('close');
            }, 3000);
        }
    </script>
</x-admin-layout>
