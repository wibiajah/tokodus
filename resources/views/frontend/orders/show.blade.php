<x-frontend-layout>
    <x-slot:title>Detail Pesanan</x-slot:title>

    <style>
        /* ===== 384 MODAL STYLES ===== */
        /* ===== 384 MODAL STYLES ===== */

        /* Modal Overlay */
        .modal-384 {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            transition: opacity 0.3s ease, backdrop-filter 0.3s ease;
        }

        .modal-384.fade {
            opacity: 0;
        }

        .modal-384.show {
            display: block;
            opacity: 1;
        }

        /* Modal Dialog */
        .modal-dialog-384 {
            position: relative;
            width: auto;
            max-width: 500px;
            margin: 1.75rem auto;
            pointer-events: none;
            transition: transform 0.3s ease-out;
        }

        .modal-dialog-centered-384 {
            display: flex;
            align-items: center;
            min-height: calc(100% - 3.5rem);
        }

        /* Modal Content */
        .modal-content-384 {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            outline: 0;
        }

        /* Modal Header */
        .modal-header-384 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            background-color: white;
        }

        .modal-header-danger-384 {
            background-color: #dc2626;
            color: white;
            border-bottom-color: #b91c1c;
        }

        .modal-header-success-384 {
            background-color: #10b981;
            color: white;
            border-bottom-color: #059669;
        }

        .modal-header-warning-384 {
            background-color: #f59e0b;
            color: #78350f;
            border-bottom-color: #d97706;
        }

        .modal-title-384 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1.5;
        }

        /* Modal Close Button */
        .modal-close-384 {
            padding: 0.5rem;
            margin: -0.5rem -0.5rem -0.5rem auto;
            background-color: transparent;
            border: 0;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            opacity: 0.5;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .modal-close-384:hover {
            opacity: 0.75;
        }

        .modal-close-white-384 {
            color: white;
            opacity: 0.8;
        }

        .modal-close-white-384:hover {
            opacity: 1;
        }

        /* Modal Body */
        .modal-body-384 {
            position: relative;
            flex: 1 1 auto;
            padding: 1.5rem;
        }

        .modal-body-center-384 {
            text-align: center;
            padding: 2rem 1.5rem;
        }

        .modal-body-384 p {
            margin-bottom: 0.75rem;
            color: #374151;
            line-height: 1.6;
        }

        .modal-subtitle-384 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.75rem;
        }

        /* Modal Text Utilities */
        .modal-text-muted-384 {
            color: #6b7280;
        }

        .modal-help-text-384 {
            display: inline-block;
            margin-top: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .modal-help-text-block-384 {
            display: block;
            margin-top: 1rem;
        }

        /* Modal Form Elements */
        .modal-form-group-384 {
            margin-bottom: 1.5rem;
        }

        .modal-label-384 {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .modal-textarea-384 {
            display: block;
            width: 100%;
            padding: 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #374151;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            resize: vertical;
        }

        .modal-textarea-384:focus {
            color: #374151;
            background-color: #fff;
            border-color: #3b82f6;
            outline: 0;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .modal-textarea-384::placeholder {
            color: #9ca3af;
        }

        /* Modal Alerts */
        .modal-alert-384 {
            padding: 0.875rem 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .modal-alert-warning-384 {
            background-color: #fef3c7;
            border: 1px solid #fcd34d;
            color: #78350f;
        }

        .modal-alert-info-384 {
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e3a8a;
        }

        /* Modal Icons */
        .modal-icon-large-384 {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .modal-icon-warning-384 {
            color: #f59e0b;
        }

        .modal-icon-success-384 {
            color: #10b981;
        }

        .modal-icon-primary-384 {
            color: #3b82f6;
        }

        /* Modal Divider */
        .modal-divider-384 {
            margin: 1.5rem 0;
            border: 0;
            border-top: 1px solid #e5e7eb;
        }

        /* Modal List Group */
        .modal-list-group-384 {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
            gap: 0.5rem;
        }

        .modal-list-item-384 {
            position: relative;
            display: block;
            padding: 0.875rem 1.25rem;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            color: #374151;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        .modal-list-item-384:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
            color: #111827;
            text-decoration: none;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .modal-list-item-384 i {
            margin-right: 0.5rem;
        }

        /* Modal Footer */
        .modal-footer-384 {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            padding: 1.25rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            border-bottom-right-radius: 12px;
            border-bottom-left-radius: 12px;
            gap: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .modal-dialog-384 {
                max-width: 90%;
                width: 90%;
                margin: auto;
            }

            .modal-dialog-centered-384 {
                min-height: 100%;
                align-items: center;
                display: flex;
            }

            .modal-content-384 {
                border-radius: 16px;
                max-height: 80vh;
                overflow-y: auto;
            }

            .modal-header-384 {
                padding: 18px 20px;
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .modal-title-384 {
                font-size: 17px;
            }

            .modal-body-384,
            .modal-body-center-384 {
                padding: 20px;
            }

            .modal-body-384 p {
                font-size: 14px;
                line-height: 22px;
                margin-bottom: 10px;
            }

            .modal-footer-384 {
                padding: 16px 20px;
                flex-direction: column;
                position: sticky;
                bottom: 0;
                background: white;
                border-top: 2px solid #e5e7eb;
                gap: 10px;
            }

            .modal-footer-384 .btn-384 {
                width: 100%;
                padding: 14px 20px;
                font-size: 15px;
                min-height: 46px;
            }

            .modal-icon-large-384 {
                font-size: 48px;
                margin-bottom: 14px;
            }

            .modal-list-item-384 {
                padding: 14px 18px;
                font-size: 14px;
                min-height: 48px;
            }

            .modal-form-group-384 {
                margin-bottom: 18px;
            }

            .modal-label-384 {
                font-size: 13px;
                margin-bottom: 8px;
            }

            .modal-textarea-384 {
                padding: 12px 14px;
                font-size: 14px;
                min-height: 100px;
                line-height: 20px;
            }

            .modal-alert-384 {
                padding: 14px 16px;
                font-size: 13px;
                line-height: 19px;
                margin-bottom: 14px;
            }

            .modal-help-text-384 {
                font-size: 12px;
                margin-top: 6px;
            }

            .modal-subtitle-384 {
                font-size: 17px;
                margin-bottom: 10px;
            }

            .modal-divider-384 {
                margin: 20px 0;
            }

            .modal-text-muted-384 {
                font-size: 13px;
            }

            .modal-close-384 {
                font-size: 26px;
                padding: 6px;
            }
        }

        /* Extra small devices (iOS SE, small Android) */
        @media (max-width: 375px) {
            .modal-dialog-384 {
                max-width: 92%;
                width: 92%;
            }

            .modal-content-384 {
                max-height: 85vh;
            }

            .modal-header-384 {
                padding: 16px 18px;
            }

            .modal-title-384 {
                font-size: 16px;
            }

            .modal-body-384,
            .modal-body-center-384 {
                padding: 18px;
            }

            .modal-footer-384 {
                padding: 14px 18px;
            }

            .modal-footer-384 .btn-384 {
                padding: 13px 18px;
                font-size: 14px;
                min-height: 44px;
            }

            .modal-icon-large-384 {
                font-size: 44px;
            }

            .modal-textarea-384 {
                min-height: 90px;
            }
        }

        /* Animation */
        @keyframes modal-fade-in-384 {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-384.show .modal-content-384 {
            animation: modal-fade-in-384 0.3s ease-out;
        }

        /* ===== 384 ORDER DETAIL STYLES ===== */
        .order-detail-container-384 {
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 80px;
            /* Turunin dari navbar */
            padding: 1.5rem;
        }

        /* Back Button */
        .back-btn-384 {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: #f3f4f6;
            color: #374151;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            transition: all 0.2s;
        }

        .back-btn-384:hover {
            background: #e5e7eb;
            color: #111827;
            text-decoration: none;
        }

        /* Combined Badge */
        .combined-badge-384 {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 1rem;
            border: 1px solid #bfdbfe;
        }

        /* Detail Section */
        .detail-section-384 {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Order Header */
        .order-header-384 {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .order-number-384 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .order-date-384 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .status-group-384 {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        /* Badges */
        .badge-384 {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-warning-384 {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-info-384 {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-primary-384 {
            background: #e0e7ff;
            color: #4338ca;
        }

        .badge-success-384 {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-danger-384 {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Alert */
        .alert-384 {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .alert-warning-384 {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            color: #78350f;
        }

        .alert-info-384 {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e3a8a;
        }

        .alert-primary-384 {
            background: #e0e7ff;
            border: 1px solid #c7d2fe;
            color: #3730a3;
        }

        .alert-success-384 {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }

        .alert-danger-384 {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        /* Buttons */
        .btn-384 {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            display: inline-block;
        }

        .btn-lg-384 {
            padding: 1rem 2rem;
            font-size: 1rem;
            width: 100%;
        }

        .btn-danger-384 {
            background: #dc2626;
            color: white;
        }

        .btn-danger-384:hover {
            background: #b91c1c;
        }

        .btn-warning-384 {
            background: #f59e0b;
            color: white;
        }

        .btn-warning-384:hover {
            background: #d97706;
        }

        .btn-success-384 {
            background: #10b981;
            color: white;
        }

        .btn-success-384:hover {
            background: #059669;
        }

        .btn-secondary-384 {
            background: #6b7280;
            color: white;
        }

        .btn-secondary-384:hover {
            background: #4b5563;
        }

        /* Store Order Group */
        .store-order-group-384 {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
        }

        .store-header-384 {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e5e7eb;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .store-name-384 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .store-order-number-384 {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        /* Section Title */
        .section-title-384 {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Product Item */
        .product-item-384 {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            border: 1px solid #e5e7eb;
        }

        .product-image-384 {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .product-info-384 {
            flex: 1;
            min-width: 0;
        }

        .product-name-384 {
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .product-variant-384 {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .product-price-info-384 {
            text-align: right;
            flex-shrink: 0;
        }

        .product-qty-384 {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .product-price-384 {
            font-weight: 700;
            color: #111827;
            font-size: 1rem;
        }

        /* Info Row */
        .info-row-384 {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
            gap: 1rem;
        }

        .info-row-384:last-child {
            border-bottom: none;
        }

        .info-label-384 {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .info-value-384 {
            color: #111827;
            font-size: 0.875rem;
            text-align: right;
        }

        /* Resi Box */
        .resi-box-384 {
            background: #f0fdf4;
            border: 2px dashed #10b981;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 0.75rem;
            text-align: center;
        }

        .resi-label-384 {
            font-size: 0.75rem;
            font-weight: 600;
            color: #065f46;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .resi-number-384 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #047857;
            letter-spacing: 0.1em;
        }

        /* ===== TIMELINE RIWAYAT STATUS - PROGRESS TRACKING UI (NO DOT) ===== */

        /* Timeline Container */
        .timeline-384 {
            position: relative;
            padding: 0;
            margin-top: 1rem;
        }

        /* Timeline Item (Step) */
        .timeline-item-384 {
            display: flex;
            position: relative;
            margin-bottom: 30px;
            min-height: 40px;
        }

        .timeline-item-384:last-child {
            margin-bottom: 0;
        }

        /* Garis Penghubung Vertikal - FIXED POSITION */
        .timeline-item-384:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 14.5px;
            /* Tepat di tengah icon box 30px */
            top: 30px;
            /* Mulai dari bawah icon box */
            width: 3px;
            height: calc(100% + 0px);
            background-color: #e0e0e0;
            z-index: 1;
        }

        /* Garis hijau jika completed */
        .timeline-item-384.completed:not(:last-child)::before {
            background-color: #28a745;
        }

        /* Icon Box - FIXED */
        .timeline-item-384 .icon-box-384 {
            width: 30px;
            height: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e0e0e0;
            font-size: 16px;
            font-weight: bold;
            background: white;
            z-index: 2;
            position: relative;
            flex-shrink: 0;
        }

        /* Semua checkbox HIJAU */
        .timeline-item-384 .icon-box-384 {
            color: #28a745 !important;
            border-color: #28a745 !important;
            background: white !important;
        }

        /* Remove default ::after for icon */
        .timeline-item-384::after {
            display: none;
        }

        /* DOT DIHAPUS */
        .timeline-item-384 .dot-384 {
            display: none;
        }

        /* Timeline Content - FIXED */
        .timeline-content-384 {
            position: relative;
            margin-left: 15px;
            /* Spacing dari icon box */
            padding: 0;
            background: transparent;
            border: none;
            box-shadow: none;
            flex: 1;
        }

        /* Remove default ::before for dot */
        .timeline-content-384::before {
            display: none;
        }

        .timeline-content-384:hover {
            transform: none;
            box-shadow: none;
        }

        /* Status Text */
        .timeline-status-384 {
            margin: 0;
            padding: 0;
            font-size: 16px;
            color: #333;
            font-weight: 600;
            line-height: 1.4;
        }

        .timeline-status-384 i,
        .timeline-status-384 span {
            display: none;
        }

        /* Date */
        .timeline-date-384 {
            margin: 4px 0 0;
            padding: 0;
            font-size: 13px;
            color: #777;
            line-height: 1.3;
        }

        .timeline-date-384 i {
            display: none;
        }

        /* Notes */
        .timeline-notes-384 {
            font-size: 13px;
            color: #777;
            line-height: 1.4;
            padding: 0;
            background: transparent;
            border: none;
            margin: 4px 0 0;
            font-style: italic;
        }

        /* User Info */
        .timeline-user-384 {
            font-size: 12px;
            color: #999;
            font-style: italic;
            margin-top: 4px;
            padding: 0;
        }

        /* Warna pudar untuk yang belum selesai */
        /* Semua teks tetap warna normal (tidak pudar) */
        .timeline-item-384 .timeline-status-384 {
            color: #333 !important;
        }

        .timeline-item-384 .timeline-date-384,
        .timeline-item-384 .timeline-notes-384 {
            color: #777 !important;
        }

        .timeline-item-384 .timeline-user-384 {
            color: #999 !important;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .timeline-item-384 {
                margin-bottom: 25px;
                min-height: 35px;
            }

            .timeline-item-384:not(:last-child)::before {
                left: 13.5px;
                top: 28px;
            }

            .timeline-item-384 .icon-box-384 {
                width: 28px;
                height: 28px;
                font-size: 14px;
            }

            .timeline-content-384 {
                margin-left: 12px;
            }

            .timeline-status-384 {
                font-size: 15px;
            }

            .timeline-date-384,
            .timeline-notes-384 {
                font-size: 12px;
            }

            .timeline-user-384 {
                font-size: 11px;
            }
        }

        @media (max-width: 576px) {
            .timeline-item-384 {
                margin-bottom: 22px;
                min-height: 32px;
            }

            .timeline-item-384:not(:last-child)::before {
                left: 12.5px;
                top: 26px;
            }

            .timeline-item-384 .icon-box-384 {
                width: 26px;
                height: 26px;
                font-size: 13px;
            }

            .timeline-content-384 {
                margin-left: 10px;
            }

            .timeline-status-384 {
                font-size: 14px;
            }

            .timeline-date-384,
            .timeline-notes-384 {
                font-size: 11px;
            }

            .timeline-user-384 {
                font-size: 10px;
            }
        }

        /* Summary */
        .summary-row-384 {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .summary-row-384.total-384 {
            border-top: 2px solid #111827;
            border-bottom: none;
            padding-top: 1rem;
            margin-top: 0.5rem;
            font-weight: 700;
            font-size: 1.125rem;
        }

        .total-amount-384 {
            color: #dc2626;
            font-size: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .order-detail-container-384 {
                margin-top: 70px;
                /* Turunin dari navbar di tablet */
                padding: 1rem;
            }


            .order-header-384 {
                flex-direction: column;
            }

            .product-item-384 {
                flex-direction: column;
                text-align: center;
            }

            .product-image-384 {
                width: 100%;
                height: 200px;
            }

            .product-price-info-384 {
                text-align: center;
            }

            .info-row-384 {
                flex-direction: column;
                gap: 0.25rem;
            }

            .info-value-384 {
                text-align: left;
            }

            .btn-lg-384 {
                padding: 0.875rem 1.5rem;
                font-size: 0.875rem;
            }
        }
    </style>

    <div class="order-detail-container-384">
        <a href="{{ route('customer.orders.index') }}" class="back-btn-384">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>

        <!-- Order Header -->
        <div class="detail-section-384">
            @if ($isCombined)
                <div class="combined-badge-384">
                    <i class="fas fa-layer-group"></i> Pembayaran Gabungan - {{ count($orders) }} Pesanan
                </div>
            @endif

            <div class="order-header-384">
                <div>
                    <div class="order-number-384">#{{ $paymentReference ?? $orders->first()->order_number }}</div>
                    <div class="order-date-384">
                        <i class="far fa-calendar"></i> {{ $orders->first()->formatted_created_at }}
                    </div>
                </div>
                <div class="status-group-384">
                    @php
                        $firstOrder = $orders->first();
                    @endphp

                    @if ($firstOrder->status === 'pending')
                        <span class="badge-384 badge-warning-384">Pending</span>
                    @elseif($firstOrder->status === 'paid')
                        <span class="badge-384 badge-info-384">Sudah Dibayar</span>
                    @elseif($firstOrder->status === 'shipped')
                        <span class="badge-384 badge-primary-384">Dikirim</span>
                    @elseif($firstOrder->status === 'completed')
                        <span class="badge-384 badge-success-384">Selesai</span>
                    @elseif($firstOrder->status === 'cancelled')
                        <span class="badge-384 badge-danger-384">Dibatalkan</span>
                    @endif

                    @if ($firstOrder->payment_status === 'unpaid')
                        <span class="badge-384 badge-warning-384">Belum Dibayar</span>
                    @elseif($firstOrder->payment_status === 'paid')
                        <span class="badge-384 badge-success-384">Sudah Dibayar</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="detail-section-384">
            @php $firstOrder = $orders->first(); @endphp

            @if ($firstOrder->status === 'pending')
                <div class="alert-384 alert-warning-384">
                    <strong>⏳ Menunggu Pembayaran</strong><br>
                    Silakan lakukan pembayaran ke rekening berikut:<br>
                    <strong>Bank BCA</strong><br>No. Rek: 1234567890<br>A/n: PT Example<br>
                    <small>Setelah transfer, admin akan mengkonfirmasi pembayaran Anda.</small>
                </div>

                <button type="button" class="btn-384 btn-danger-384 btn-lg-384" data-toggle="modal"
                    data-target="#cancelModal">
                    ❌ Batalkan Pesanan
                </button>
            @elseif($firstOrder->status === 'paid')
                <div class="alert-384 alert-info-384">
                    ✅ Pembayaran Anda telah dikonfirmasi!<br>Admin sedang memproses pesanan Anda.
                </div>

                <form action="{{ route('customer.orders.report-problem', $firstOrder) }}" method="POST"
                    style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-384 btn-warning-384 btn-lg-384">
                        💬 Laporkan Masalah / Live Chat
                    </button>
                </form>
            @elseif($firstOrder->status === 'shipped')
                <div class="alert-384 alert-primary-384" style="margin-bottom: 1rem;">
                    📦 <strong>Pesanan Anda sudah dikirim!</strong>
                </div>

                <div class="row">
                    <div class="col-md-6" style="margin-bottom: 0.5rem;">
                        <button type="button" class="btn-384 btn-success-384 btn-lg-384" data-toggle="modal"
                            data-target="#completeModal">
                            ✅ Selesaikan Orderan / Sudah Diterima
                        </button>
                    </div>
                    <div class="col-md-6" style="margin-bottom: 0.5rem;">
                       <!-- GANTI INI: -->
<button type="button" class="btn-384 btn-warning-384" data-toggle="modal" data-target="#reportModal">
    ⚠️ Laporkan Masalah
</button>

<!-- JADI: -->
<form action="{{ route('customer.orders.report-problem', $firstOrder) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" class="btn-384 btn-warning-384">
        💬 Laporkan Masalah / Live Chat
    </button>
</form>
                    </div>
                </div>
            @elseif($firstOrder->status === 'completed')
                <div class="alert-384 alert-success-384" style="margin-bottom: 1rem;">
                    ✅ <strong>Pesanan Selesai!</strong> Terima kasih telah berbelanja.
                </div>

               <!-- GANTI INI: -->
<button type="button" class="btn-384 btn-warning-384" data-toggle="modal" data-target="#reportModal">
    ⚠️ Laporkan Masalah
</button>

<!-- JADI: -->
<form action="{{ route('customer.orders.report-problem', $firstOrder) }}" method="POST" style="display: inline;">
    @csrf
    <button type="submit" class="btn-384 btn-warning-384">
        💬 Laporkan Masalah / Live Chat
    </button>
</form>
            @elseif($firstOrder->status === 'cancelled')
                <div class="alert-384 alert-danger-384">
                    ❌ <strong>Pesanan Dibatalkan</strong>
                    @if ($firstOrder->statusLogs()->where('status_to', 'cancelled')->first())
                        @php
                            $cancelLog = $firstOrder->statusLogs()->where('status_to', 'cancelled')->first();
                        @endphp
                        @if ($cancelLog->notes)
                            <br><small>{{ $cancelLog->notes }}</small>
                        @endif
                    @endif
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Products by Store -->
                @foreach ($orders as $order)
                    <div class="store-order-group-384">
                        <div class="store-header-384">
                            <div>
                                <div class="store-name-384">
                                    <i class="fas fa-store"></i>
                                    {{ $order->toko?->nama_toko ?? 'Central Store' }}
                                </div>
                                <div class="store-order-number-384">Order #{{ $order->order_number }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-size: 0.875rem; color: #6b7280;">Subtotal</div>
                                <div style="font-weight: 700; font-size: 1.125rem; color: #111;">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="detail-section-384" style="margin-bottom: 1rem; box-shadow: none;">
                            <h5 class="section-title-384">
                                <i class="fas fa-box"></i> Produk ({{ $order->total_items }} Item)
                            </h5>

                            @foreach ($order->items as $item)
                                <div class="product-item-384">
                                    <img src="{{ $item->variant_photo }}" alt="{{ $item->product_title }}"
                                        class="product-image-384">
                                    <div class="product-info-384">
                                        <div class="product-name-384">{{ $item->product_title }}</div>
                                        @if ($item->variant_name)
                                            <div class="product-variant-384">
                                                <i class="fas fa-tag"></i> {{ $item->variant_name }}
                                            </div>
                                        @endif
                                        <div class="product-variant-384">SKU: {{ $item->product_sku }}</div>
                                    </div>
                                    <div class="product-price-info-384">
                                        <div class="product-qty-384">{{ $item->quantity }} x
                                            {{ $item->formatted_final_price }}</div>
                                        <div class="product-price-384">{{ $item->formatted_subtotal }}</div>
                                    </div>
                                </div>

                                @if ($order->status === 'completed')
                                    @php
                                        $review = $item->product
                                            ->reviews()
                                            ->where('customer_id', auth('customer')->id())
                                            ->where('order_id', $order->id)
                                            ->first();
                                    @endphp

                                    <div class="row" style="margin-top: 0.75rem;">
                                        <div class="col-md-6" style="margin-bottom: 0.5rem;">
                                            @if ($review)
                                                <a href="{{ route('customer.reviews.show', $review->id) }}"
                                                    class="btn-384 btn-success-384 btn-lg-384">
                                                    <i class="fas fa-eye"></i> Lihat Review Saya
                                                </a>
                                            @else
                                                <a href="{{ route('customer.reviews.create', [
                                                    'order' => $order->id,
                                                    'product' => $item->product_id,
                                                ]) }}"
                                                    class="btn-384 btn-success-384 btn-lg-384">
                                                    <i class="fas fa-star"></i> Tulis Review
                                                </a>
                                            @endif
                                        </div>
                                        <div class="col-md-6" style="margin-bottom: 0.5rem;">
                                            <a href="{{ route('customer.my-reviews') }}"
                                                class="btn-384 btn-success-384 btn-lg-384">
                                                <i class="fas fa-list"></i> Semua Review Saya
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Shipping Info -->
                        <div class="detail-section-384" style="box-shadow: none;">
                            <h5 class="section-title-384">
                                <i class="fas fa-shipping-fast"></i> Informasi Pengiriman
                            </h5>
                            <div class="info-row-384">
                                <div class="info-label-384">Metode</div>
                                <div class="info-value-384">
                                    @if ($order->isPickup())
                                        <span style="color: #1f4390; font-weight: 600;">
                                            <i class="fas fa-store"></i> Ambil di Toko
                                        </span>
                                    @else
                                        <span style="color: #059669; font-weight: 600;">
                                            <i class="fas fa-truck"></i> Dikirim ke Alamat
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if ($order->isDelivery())
                                <div class="info-row-384">
                                    <div class="info-label-384">Alamat</div>
                                    <div class="info-value-384">{{ $order->shipping_address }}</div>
                                </div>

                                @if ($order->resi_number)
                                    <div class="resi-box-384">
                                        <div class="resi-label-384">NOMOR RESI PENGIRIMAN</div>
                                        <div class="resi-number-384">{{ $order->resi_number }}</div>
                                        @if ($order->courier_name)
                                            <div style="margin-top: 0.5rem; font-size: 0.875rem;">
                                                <i class="fas fa-user"></i> Kurir: {{ $order->courier_name }}
                                                @if ($order->courier_phone)
                                                    | <i class="fas fa-phone"></i> {{ $order->courier_phone }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="info-row-384">
                                    <div class="info-label-384">Lokasi Pickup</div>
                                    <div class="info-value-384">
                                        <strong>{{ $order->pickupToko?->nama_toko ?? 'Central Store' }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($order->statusLogs->count() > 0)
                            <div class="detail-section-384" style="box-shadow: none;">
                                <h5 class="section-title-384">
                                    <i class="fas fa-history"></i> Riwayat Status
                                </h5>
                                <div class="timeline-384">
                                    @foreach ($order->statusLogs as $log)
                                        @php
                                            $isCompleted = !$loop->last; // Semua kecuali terakhir = hijau
                                        @endphp
                                        <div class="timeline-item-384 {{ $isCompleted ? 'completed' : '' }}">

                                            <div class="icon-box-384">✓</div>
                                            <div class="timeline-content-384">
                                                <div class="timeline-status-384">
                                                    {{ $log->status_to_text }}
                                                </div>
                                                <div class="timeline-date-384">
                                                    {{ $log->formatted_created_at }}
                                                </div>
                                                @if ($log->notes)
                                                    <div class="timeline-notes-384">
                                                        "{{ $log->notes }}"
                                                    </div>
                                                @endif
                                                <div class="timeline-user-384">
                                                    oleh: {{ $log->changed_by_name }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach

                <!-- Customer Info -->
                <div class="detail-section-384">
                    <h5 class="section-title-384">
                        <i class="fas fa-user"></i> Informasi Customer
                    </h5>
                    @php
                        $firstOrder = $orders->first();
                    @endphp
                    <div class="info-row-384">
                        <div class="info-label-384">Nama</div>
                        <div class="info-value-384">{{ $firstOrder->customer_name }}</div>
                    </div>
                    <div class="info-row-384">
                        <div class="info-label-384">Telepon</div>
                        <div class="info-value-384">{{ $firstOrder->customer_phone }}</div>
                    </div>
                    <div class="info-row-384">
                        <div class="info-label-384">Email</div>
                        <div class="info-value-384">{{ $firstOrder->customer_email }}</div>
                    </div>
                    @if ($firstOrder->notes)
                        <div class="info-row-384">
                            <div class="info-label-384">Catatan</div>
                            <div class="info-value-384">{{ $firstOrder->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="detail-section-384">
                    <h5 class="section-title-384">
                        <i class="fas fa-receipt"></i> Ringkasan Pembayaran
                    </h5>

                    @if ($isCombined)
                        @foreach ($orders as $order)
                            <div class="summary-row-384">
                                <span>{{ $order->toko?->nama_toko ?? 'Central Store' }}</span>
                                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    @else
                        @php $order = $orders->first(); @endphp
                        <div class="summary-row-384">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if ($order->discount_amount > 0)
                            <div class="summary-row-384" style="color: #10b981;">
                                <span><i class="fas fa-tag"></i> Diskon</span>
                                <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        @if ($order->shipping_cost > 0)
                            <div class="summary-row-384">
                                <span>Ongkir</span>
                                <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @endif

                    <div class="summary-row-384 total-384">
                        <span>Total Pembayaran</span>
                        <span class="total-amount-384">Rp {{ number_format($totalPayment, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: CANCEL ORDER -->
    <div class="modal-384 fade" id="cancelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog-384 modal-dialog-centered-384" role="document">
            <div class="modal-content-384">
                <div class="modal-header-384 modal-header-danger-384">
                    <h5 class="modal-title-384">Batalkan Pesanan</h5>
                    <button type="button" class="modal-close-384 modal-close-white-384" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('customer.orders.cancel', $firstOrder) }}" method="POST">
                    @csrf
                    <div class="modal-body-384">
                        <p>Apakah Anda yakin ingin membatalkan pesanan ini?</p>
                        <p class="modal-text-muted-384">
                            <small>
                                Order #{{ $firstOrder->order_number }}<br>
                                Total: Rp {{ number_format($firstOrder->total, 0, ',', '.') }}
                            </small>
                        </p>

                        <div class="modal-form-group-384">
                            <label class="modal-label-384">Alasan Pembatalan (Opsional)</label>
                            <textarea name="reason" class="modal-textarea-384" rows="3"
                                placeholder="Tuliskan alasan Anda membatalkan pesanan..."></textarea>
                            <small class="modal-help-text-384">Anda bisa mengosongkan jika tidak ingin memberikan
                                alasan</small>
                        </div>

                        <div class="modal-alert-384 modal-alert-warning-384">
                            <small>
                                ⚠️ <strong>Perhatian:</strong> Setelah dibatalkan, pesanan tidak dapat dikembalikan.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer-384">
                        <button type="button" class="btn-384 btn-secondary-384" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-384 btn-danger-384">✅ Ya, Batalkan Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: COMPLETE ORDER -->
    <div class="modal-384 fade" id="completeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog-384 modal-dialog-centered-384" role="document">
            <div class="modal-content-384">
                <div class="modal-header-384 modal-header-success-384">
                    <h5 class="modal-title-384">Selesaikan Pesanan</h5>
                    <button type="button" class="modal-close-384 modal-close-white-384" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('customer.orders.complete', $firstOrder) }}" method="POST">
                    @csrf
                    <div class="modal-body-384">
                        <p>Apakah Anda sudah menerima pesanan ini dengan baik?</p>
                        <p class="modal-text-muted-384">
                            <small>Order #{{ $firstOrder->order_number }}</small>
                        </p>

                        <div class="modal-alert-384 modal-alert-info-384">
                            <small>ℹ️ Setelah diselesaikan, Anda dapat memberikan ulasan untuk produk.</small>
                        </div>
                    </div>
                    <div class="modal-footer-384">
                        <button type="button" class="btn-384 btn-secondary-384" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-384 btn-success-384">✅ Ya, Pesanan Sudah Diterima</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: REPORT PROBLEM -->


    <script>
        // ===== 384 MODAL JAVASCRIPT =====

        (function() {
            'use strict';

            // Modal Manager
            const Modal384 = {
                // Open Modal
                open: function(modalId) {
                    const modal = document.getElementById(modalId);
                    if (!modal) return;

                    // Add show class
                    modal.classList.add('show');
                    modal.style.display = 'block';

                    // Add body class to prevent scrolling
                    document.body.classList.add('modal-open-384');
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = this.getScrollbarWidth() + 'px';

                    // Focus on modal
                    modal.focus();

                    // Trigger event
                    const event = new CustomEvent('modal384:opened', {
                        detail: {
                            modalId
                        }
                    });
                    modal.dispatchEvent(event);
                },

                // Close Modal
                close: function(modalId) {
                    const modal = document.getElementById(modalId);
                    if (!modal) return;

                    // Remove show class
                    modal.classList.remove('show');

                    // Wait for animation then hide
                    setTimeout(() => {
                        modal.style.display = 'none';
                    }, 300);

                    // Remove body class
                    document.body.classList.remove('modal-open-384');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';

                    // Trigger event
                    const event = new CustomEvent('modal384:closed', {
                        detail: {
                            modalId
                        }
                    });
                    modal.dispatchEvent(event);
                },

                // Close all modals
                closeAll: function() {
                    const modals = document.querySelectorAll('.modal-384.show');
                    modals.forEach(modal => {
                        this.close(modal.id);
                    });
                },

                // Get scrollbar width
                getScrollbarWidth: function() {
                    const outer = document.createElement('div');
                    outer.style.visibility = 'hidden';
                    outer.style.overflow = 'scroll';
                    document.body.appendChild(outer);

                    const inner = document.createElement('div');
                    outer.appendChild(inner);

                    const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;
                    outer.parentNode.removeChild(outer);

                    return scrollbarWidth;
                }
            };

            // Initialize when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {

                // Handle data-toggle="modal" buttons
                document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetModal = this.getAttribute('data-target');
                        if (targetModal) {
                            const modalId = targetModal.replace('#', '');
                            Modal384.open(modalId);
                        }
                    });
                });

                // Handle data-dismiss="modal" buttons
                document.querySelectorAll('[data-dismiss="modal"]').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const modal = this.closest('.modal-384');
                        if (modal) {
                            Modal384.close(modal.id);
                        }
                    });
                });

                // Handle close button (.modal-close-384)
                document.querySelectorAll('.modal-close-384').forEach(closeBtn => {
                    closeBtn.addEventListener('click', function() {
                        const modal = this.closest('.modal-384');
                        if (modal) {
                            Modal384.close(modal.id);
                        }
                    });
                });

                // Close modal when clicking outside (backdrop)
                document.querySelectorAll('.modal-384').forEach(modal => {
                    modal.addEventListener('click', function(e) {
                        // Only close if clicking on the modal backdrop itself, not the content
                        if (e.target === this) {
                            Modal384.close(this.id);
                        }
                    });
                });

                // Close modal with ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' || e.keyCode === 27) {
                        Modal384.closeAll();
                    }
                });

                // Prevent modal content clicks from closing modal
                document.querySelectorAll('.modal-content-384').forEach(content => {
                    content.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });
                });

            });

            // Expose Modal384 globally
            window.Modal384 = Modal384;

            // Add body class for modal state
            const style = document.createElement('style');
            style.textContent = `
        .modal-open-384 {
            overflow: hidden !important;
        }
    `;
            document.head.appendChild(style);

        })();

        // ===== HELPER FUNCTIONS =====

        // Open modal by ID
        function openModal384(modalId) {
            window.Modal384.open(modalId);
        }

        // Close modal by ID
        function closeModal384(modalId) {
            window.Modal384.close(modalId);
        }

        // Close all modals
        function closeAllModals384() {
            window.Modal384.closeAll();
        }

        // Example usage:
        // openModal384('cancelModal');
        // closeModal384('cancelModal');
        // closeAllModals384();
    </script>
</x-frontend-layout>
