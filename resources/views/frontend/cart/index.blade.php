<x-frontend-layout>
    <x-slot:title>Keranjang Belanja</x-slot:title>

    <style>
        html,
        body {
            overflow-x: clip !important;
            overflow-y: visible !important;
        }

        /* ========== CART PAGE CSS - PREFIX 111 ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }



        :root {
            --primary: #f9ef21;
            --subprimary: #f7b963;
            --thirdary: #e9078f;
            --edition: #1f4390;
            --secondary: #1f4390;
            --tertiary: #000;
            --contrast: #eeeeef;
            --Lebaran: #16a34a;
            --Christmas: #c7322f;
            --Imlek: #ffd700;
        }

        /* Breadcrumb 111 */
        /* Breadcrumb 111 */
.breadcrumb-container-111 {
    background: #f8f9fa;
    padding: 1rem 0;
    border-bottom: 1px solid #e5e5e5;
    margin-top: 80px;
}

        .breadcrumb-111 {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

.breadcrumb-111 a {
    color: #2b4c9f;
    text-decoration: none;
    transition: all 0.3s;
}

.breadcrumb-111 a:hover {
    text-decoration: underline;
}

        .separator-111 {
            color: #999;
        }

        .current-111 {
    color: #666;
}

        /* Cart Container 111 - CRITICAL STRUCTURE */
        .cart-container-111 {
            display: flex;
            gap: 30px;
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 20px;
            justify-content: center;
        }

        .cart-items-section-111 {
            flex: 2;
        }

        .cart-items-section-111>h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
            color: var(--tertiary);
        }

        /* Store Group 111 */
        .store-section-111 {
            background: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 16px;
            margin-bottom: 24px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .store-header-111 {
            background: var(--secondary);
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px solid var(--edition);
        }

        .store-checkbox-wrapper-111 {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .store-checkbox-wrapper-111 input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .store-icon-111 {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .store-icon-111 i {
            color: #ffffff;
            font-size: 24px;
        }

        .store-info-group-111 {
            flex: 1;
        }

        .store-name-111 {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .store-badge-111 {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .store-meta-111 {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
        }

        .store-meta-111 span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .store-meta-111 i {
            font-size: 16px;
            opacity: 0.8;
        }

        /* Cart Item 111 */
        .cart-item-111 {
            display: grid;
            grid-template-columns: auto 120px 1fr auto;
            gap: 20px;
            padding: 24px;
            border-bottom: 1px solid #f0f0f0;
            position: relative;
            background: #ffffff;
            transition: background 0.2s;
            align-items: center;
        }

        .cart-item-111:last-child {
            border-bottom: none;
        }

        .cart-item-111:hover {
            background: #fafbfc;
        }

        .cart-item-checkbox-111 {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-checkbox-111 input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--secondary);
        }

        .product-image-111 {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
            flex-shrink: 0;
        }

        .product-details-111 {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 0;
        }

        .product-details-111 h6 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            color: var(--tertiary);
            line-height: 1.3;
        }

        .product-variant-111 {
            font-size: 13px;
            color: #666;
        }

        .product-specs-111 {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .spec-badge-111 {
            background: #f3f4f6;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            color: #4b5563;
            font-weight: 500;
        }

        .stock-info-111 {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            font-size: 11px;
            color: var(--Lebaran);
            font-weight: 600;
            width: fit-content;
        }

        .stock-info-111 i {
            font-size: 12px;
        }

        .item-right-section-111 {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 16px;
            min-width: 180px;
        }

        .price-and-delete-111 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 12px;
        }

        .price-section-111 {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .current-price-111 {
            font-size: 20px;
            font-weight: 700;
            color: var(--secondary);
            line-height: 1;
        }

        .original-price-111 {
            font-size: 13px;
            color: #9ca3af;
            text-decoration: line-through;
            margin-top: 4px;
        }

        .btn-delete-111 {
            background: #fee;
            border: 1px solid #fecaca;
            color: var(--Christmas);
            cursor: pointer;
            transition: all 0.2s;
            padding: 8px;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-delete-111:hover {
            background: var(--Christmas);
            color: white;
            border-color: var(--Christmas);
            transform: scale(1.05);
        }

        .quantity-and-subtotal-111 {
            display: flex;
            align-items: center;
            gap: 20px;
            width: 100%;
            justify-content: flex-end;
        }

        .quantity-control-111 {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8f9fa;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .quantity-btn-111 {
            background: white;
            border: 1px solid #ddd;
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.2s;
            border-radius: 6px;
            color: #666;
            font-size: 16px;
        }

        .quantity-btn-111:hover:not(:disabled) {
            background: var(--secondary);
            color: white;
            border-color: var(--secondary);
        }

        .quantity-btn-111:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .quantity-input-111 {
            font-size: 16px;
            font-weight: 600;
            min-width: 40px;
            text-align: center;
            color: var(--tertiary);
            border: none;
            background: transparent;
            width: 40px;
        }

        .quantity-input-111:focus {
            outline: none;
        }

        .item-subtotal-111 {
            font-size: 22px;
            font-weight: 700;
            color: var(--secondary);
            white-space: nowrap;
        }

        /* Store Footer 111 */
        .store-footer-111 {
            background: #f8f9fb;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e5e5;
        }

        .store-subtotal-111 {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .store-subtotal-label-111 {
            font-size: 14px;
            color: #666;
        }

        .store-subtotal-value-111 {
            font-size: 20px;
            font-weight: 700;
            color: var(--secondary);
        }

        .store-item-count-111 {
            font-size: 13px;
            color: #999;
            background: white;
            padding: 6px 12px;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
        }

        /* Order Summary 111 - STICKY AMPUH! */
        .cart-summary-111 {
            flex: 1;
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 16px;
            padding: 24px;
            height: fit-content;
            position: sticky;
            top: 100px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .cart-summary-111 h5 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 24px;
            color: var(--tertiary);
        }

        .voucher-section-111 {
            padding: 16px;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .voucher-header-111 {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .voucher-header-111 i {
            color: #d97706;
            font-size: 20px;
        }

        .voucher-header-111 span {
            font-size: 14px;
            color: #92400e;
            font-weight: 600;
        }

        #voucherText {
            font-size: 13px;
            color: #78350f;
            font-weight: 500;
            line-height: 0.8;
            padding: 2px 0;
            margin-bottom: 3px;
        }

        #voucherText strong {
            display: block;
            margin-bottom: 1px;
            font-size: 14px;
        }

        .btn-select-voucher {
            width: 100%;
            padding: 12px 16px;
            background: white;
            border: 2px solid #d97706;
            color: #d97706;
            border-radius: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-select-voucher:hover:not(:disabled) {
            background: #d97706;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.3);
        }

        .btn-select-voucher:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: #e5e7eb;
            color: #9ca3af;
        }

        /* Voucher Modal Styles 111 */
        .modal-111 {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            animation: fadeIn-111 0.3s ease;
        }

        .modal-111.show-111 {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn-111 {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content-111 {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            max-height: 85vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp-111 0.3s ease;
        }

        @keyframes slideUp-111 {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header-111 {
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
        }

        .modal-header-111 h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #78350f;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header-111 h3 i {
            font-size: 24px;
            color: #d97706;
        }

        .modal-close-111 {
            background: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            color: #78350f;
            font-size: 20px;
        }

        .modal-close-111:hover {
            background: #dc2626;
            color: white;
            transform: rotate(90deg);
        }

        .modal-body-111 {
            padding: 24px;
            max-height: calc(85vh - 180px);
            overflow-y: auto;
        }

        .voucher-input-group-111 {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .voucher-input-group-111 input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: border 0.2s;
        }

        .voucher-input-group-111 input:focus {
            border-color: #d97706;
        }

        .voucher-input-group-111 input::placeholder {
            color: #9ca3af;
        }

        .btn-apply-code-111 {
            padding: 12px 24px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .btn-apply-code-111:hover {
            background: var(--edition);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(31, 67, 144, 0.3);
        }

        .voucher-list-111 {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .voucher-item-111 {
            display: flex;
            gap: 16px;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            transition: all 0.2s;
            cursor: pointer;
            background: white;
        }

        .voucher-item-111:hover {
            border-color: #d97706;
            background: #fffbeb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.15);
        }

        .voucher-icon-111 {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d97706;
            font-size: 28px;
            flex-shrink: 0;
        }

        .voucher-details-111 {
            flex: 1;
            min-width: 0;
        }

        .voucher-details-111 h6 {
            margin: 0 0 6px 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--tertiary);
        }

        .voucher-details-111 p {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #6b7280;
            line-height: 1.4;
        }

        .voucher-code-111 {
            display: inline-block;
            padding: 4px 10px;
            background: #f3f4f6;
            border: 1px dashed #d1d5db;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: var(--secondary);
            letter-spacing: 0.5px;
        }

        .voucher-validity-111 {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .voucher-validity-111 i {
            font-size: 10px;
        }

        .btn-use-voucher-111 {
            padding: 8px 20px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
            align-self: center;
        }

        .btn-use-voucher-111:hover {
            background: var(--edition);
            transform: scale(1.05);
        }

        .empty-voucher-111 {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
        }

        .empty-voucher-111 i {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 16px;
        }

        .empty-voucher-111 p {
            margin: 0;
            font-size: 14px;
        }

        .summary-row-111 {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 16px;
            color: #666;
        }

        .summary-row-111 span:last-child {
            font-weight: 600;
            color: var(--tertiary);
        }

        .summary-row-111.discount-111 {
            color: var(--Christmas);
        }

        .summary-row-111.discount-111 span:last-child {
            color: var(--Christmas);
        }

        .cart-summary-111 hr {
            border: none;
            border-top: 2px solid #e5e5e5;
            margin: 20px 0;
        }

        .summary-row-total-111 {
            font-size: 18px;
            font-weight: 700;
            color: var(--tertiary);
        }

        .summary-row-total-111 .total-price-111 {
            font-size: 24px;
            color: var(--secondary);
        }

        .btn-checkout-111 {
            width: 100%;
            padding: 16px;
            background: var(--secondary);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(31, 67, 144, 0.2);
            margin-top: 24px;
        }

        .btn-checkout-111:hover:not(:disabled) {
            background: var(--edition);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(31, 67, 144, 0.3);
        }

        .btn-checkout-111:disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-remove-voucher-111 {
            width: 100%;
            padding: 10px 16px;
            background: white;
            border: 2px solid #dc2626;
            color: #dc2626;
            border-radius: 12px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }

        .btn-remove-voucher-111:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        /* Empty Cart 111 */
        .empty-cart-111 {
            text-align: center;
            padding: 80px 40px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .empty-cart-111 i {
            font-size: 64px;
            color: #d1d5db;
            margin-bottom: 24px;
        }

        .empty-cart-111 h4 {
            font-size: 24px;
            color: #4b5563;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .empty-cart-111 p {
            color: #9ca3af;
            margin-bottom: 32px;
            font-size: 16px;
        }

        .btn-primary-111 {
            background: var(--secondary);
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 16px;
        }

        .btn-primary-111:hover {
            background: var(--edition);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(31, 67, 144, 0.2);
        }

        /* ✅ Voucher Section Headers */
        .voucher-section-header-111 {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 12px;
            margin-top: 24px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
        }

        .voucher-section-header-111:first-child {
            margin-top: 0;
        }

        .voucher-section-header-111 i {
            font-size: 20px;
            color: white;
        }

        .voucher-section-header-111 span:first-of-type {
            flex: 1;
            font-size: 15px;
            font-weight: 700;
            color: white;
            letter-spacing: 0.3px;
        }

        .voucher-count-badge-111 {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            min-width: 28px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        /* ✅ Unavailable Section Header */
        .voucher-section-header-111.unavailable {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        }

        /* ✅ Expired Section Header */
        .voucher-section-header-111.expired {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            box-shadow: 0 2px 8px rgba(107, 114, 128, 0.2);
        }

        /* ✅ Voucher Item States */
        .voucher-item-111.unavailable {
            opacity: 0.7;
            cursor: default;
            border-color: #e5e7eb;
        }

        .voucher-item-111.unavailable:hover {
            border-color: #e5e7eb;
            background: white;
            transform: none;
            box-shadow: none;
        }

        .voucher-item-111.expired {
            opacity: 0.5;
            cursor: default;
            border-color: #d1d5db;
            background: #f9fafb;
        }

        .voucher-item-111.expired:hover {
            border-color: #d1d5db;
            background: #f9fafb;
            transform: none;
            box-shadow: none;
        }

        /* ✅ Reason Badge */
        .voucher-reason-111 {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            font-size: 12px;
            color: #dc2626;
            font-weight: 600;
            margin-top: 10px;
            line-height: 1.4;
        }

        .voucher-reason-111 i {
            font-size: 13px;
            flex-shrink: 0;
        }

        .voucher-item-111.expired .voucher-reason-111 {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #6b7280;
        }

        /* ✅ Disabled Button */
        .btn-use-voucher-111:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #e5e7eb;
            color: #9ca3af;
        }

        .btn-use-voucher-111:disabled:hover {
            background: #e5e7eb;
            transform: none;
        }

        /* ✅ Empty State in Sections */
        .voucher-list-111 .empty-voucher-111 {
            padding: 20px;
            margin: 0;
        }

        .voucher-list-111 .empty-voucher-111 i {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .voucher-list-111 .empty-voucher-111 p {
            font-size: 13px;
            margin: 0;
        }

        .modal-body-111 {
            padding: 24px;
            max-height: calc(85vh - 180px);
            overflow-y: auto;
            /* ✅ Performance optimization */
            -webkit-overflow-scrolling: touch;
            will-change: scroll-position;
            scroll-behavior: smooth;
        }

        /* ✅ Optimize voucher items rendering */
        .voucher-item-111 {
            display: flex;
            gap: 16px;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            transition: all 0.2s ease-out;
            /* ✅ Faster transition */
            cursor: pointer;
            background: white;
            /* ✅ Hardware acceleration */
            transform: translateZ(0);
            backface-visibility: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .voucher-item-111:hover {
            border-color: #d97706;
            background: #fffbeb;
            transform: translateY(-2px) translateZ(0);
            /* ✅ Keep hardware acceleration */
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.15);
        }

        /* ✅ Disable hover effects on unavailable/expired */
        .voucher-item-111.unavailable,
        .voucher-item-111.expired {
            transition: none;
            /* ✅ No transition for disabled items */
        }

        .voucher-item-111.unavailable:hover,
        .voucher-item-111.expired:hover {
            transform: none;
        }

        /* Enhanced Voucher Info Display */
        .voucher-validity-111 {
            font-size: 12px;
            color: #4b5563;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            line-height: 1.5;
        }

        .voucher-validity-111 i {
            font-size: 12px;
            flex-shrink: 0;
            width: 14px;
            text-align: center;
        }

        /* Highlight discount info */


        /* Summary discount row enhancement */
        .summary-row-111.discount-111 {
            background: #fef2f2;
            padding: 12px 16px;
            margin: 0 -16px;
            border-left: 4px solid #dc2626;
            color: #dc2626;
        }

        .summary-row-111.discount-111 span {
            font-weight: 600;
        }

        /* Total payment emphasis */
        .summary-row-total-111 {
            background: #f0f9ff;
            padding: 16px;
            margin: 16px -16px -16px -16px;
            border-top: 2px solid #1f4390;
        }

        .summary-row-total-111 span {
            font-size: 16px;
        }

        .summary-row-total-111 .total-price-111 {
            font-size: 26px;
            font-weight: 700;
            color: #1f4390;
        }

        /* ========== MOBILE RESPONSIVE CSS ONLY ========== */
        /* Hanya mengubah CSS untuk mobile, desktop tetap sama */

        @media (max-width: 768px) {
            /* ✅ CART ITEM MOBILE - FOTO & TEKS SEJAJAR */

              .breadcrumb-container-111 {
        margin-top: 60px !important;  /* ✅ PAKSA 100px */
        padding: 1rem 0;
    }

.breadcrumb-111 {
    font-size: 0.8rem;
    padding: 0 1rem;
}

            /* ✅ FIX: Cart Container - PAKSA VERTICAL DI MOBILE */
            .cart-container-111 {
                flex-direction: column !important;
                /* Paksa vertical */
                padding: 0 1rem;
                margin: 1.5rem auto;
            }

            /* ✅ FIX: Cart Summary - POSISI STATIS DI BAWAH */
            .cart-summary-111 {
                position: static !important;
                /* Hilangkan sticky */
                top: auto !important;
                width: 100% !important;
                /* Full width */
                margin-top: 1.5rem;
                /* Jarak dari items */
            }

            .cart-items-section-111>h1 {
                font-size: 28px;
                margin-bottom: 20px;
            }

            .cart-item-111 {
                display: flex;
                flex-direction: column;
                gap: 12px;
                padding: 16px;
            }

            /* Wrapper baris pertama: Checkbox + Foto + Detail */
            .cart-item-first-row-111 {
                display: flex;
                gap: 10px;
                align-items: flex-start;
            }

            .cart-item-checkbox-111 {
                flex-shrink: 0;
                align-self: flex-start;
                padding-top: 3px;
            }

            .product-image-111 {
                width: 85px;
                height: 85px;
                flex-shrink: 0;
                border-radius: 10px;
            }

            .product-details-111 {
                flex: 1;
                min-width: 0;
                gap: 4px;
            }

            .product-details-111 h6 {
                font-size: 13px;
                line-height: 1.3;
                margin-bottom: 3px;
                font-weight: 600;
                /* Batasi 2 baris */
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .product-variant-111 {
                font-size: 11px;
                margin-bottom: 2px;
                line-height: 1.3;
            }

            .product-specs-111 {
                display: flex;
                gap: 4px;
                flex-wrap: wrap;
                margin-top: 3px;
            }

            .spec-badge-111 {
                font-size: 9px;
                padding: 2px 5px;
            }

            .stock-info-111 {
                font-size: 9px;
                padding: 3px 6px;
                margin-top: 4px;
            }

            .stock-info-111 i {
                font-size: 10px;
            }

            /* Item Right Section - Full Width Bawah */
            .item-right-section-111 {
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 10px;
                padding-left: 30px;
                /* Sejajar dengan text produk */
            }

            .price-and-delete-111 {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .price-section-111 {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }

            .current-price-111 {
                font-size: 16px;
            }

            .original-price-111 {
                font-size: 11px;
            }

            .btn-delete-111 {
                width: 30px;
                height: 30px;
                padding: 6px;
                font-size: 13px;
            }

            .quantity-and-subtotal-111 {
                display: flex;
                justify-content: flex-start;
                /* Ganti dari space-between */
                align-items: center;
                gap: 12px;
            }

            .quantity-control-111 {
                gap: 6px;
                padding: 4px 10px;
            }

            .quantity-btn-111 {
                width: 28px;
                height: 28px;
                font-size: 14px;
            }

            .quantity-input-111 {
                min-width: 35px;
                width: 35px;
                font-size: 14px;
            }

            .item-subtotal-111 {
                font-size: 17px;
            }

            /* Store Footer Mobile */
            /* Store Footer Mobile */
            .store-footer-111 {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
                padding: 14px 16px;
            }

            .store-subtotal-111 {
                width: 100%;
                justify-content: space-between;
            }

            .store-subtotal-label-111 {
                font-size: 13px;
            }

            .store-subtotal-value-111 {
                font-size: 18px;
            }

            .store-item-count-111 {
                font-size: 12px;
                padding: 5px 10px;
            }

            /* Cart Summary Mobile */
            .cart-summary-111 {
                padding: 20px;
                border-radius: 12px;
            }

            .cart-summary-111 h5 {
                font-size: 20px;
                margin-bottom: 20px;
            }

            /* Voucher Section Mobile */
            .voucher-section-111 {
                padding: 14px;
                margin-bottom: 20px;
            }

            .voucher-header-111 {
                gap: 10px;
                margin-bottom: 10px;
            }

            .voucher-header-111 i {
                font-size: 18px;
            }

            .voucher-header-111 span {
                font-size: 13px;
            }

            #voucherText {
                font-size: 12px;
                margin-bottom: 8px;
            }

            #voucherText strong {
                font-size: 13px;
            }

            .btn-select-voucher {
                padding: 10px 14px;
                font-size: 13px;
            }

            .btn-remove-voucher-111 {
                padding: 8px 14px;
                font-size: 12px;
                margin-top: 6px;
            }

            /* Summary Rows Mobile */
            .summary-row-111 {
                font-size: 14px;
                margin-bottom: 14px;
            }

            .summary-row-total-111 {
                font-size: 16px;
            }

            .summary-row-total-111 .total-price-111 {
                font-size: 20px;
            }

            /* Checkout Button Mobile */
            .btn-checkout-111 {
                padding: 14px;
                font-size: 15px;
                margin-top: 20px;
            }

            /* Modal Mobile */
            .modal-content-111 {
                width: 95%;
                max-height: 90vh;
                border-radius: 16px;
            }

            .modal-header-111 {
                padding: 20px;
            }

            .modal-header-111 h3 {
                font-size: 18px;
            }

            .modal-header-111 h3 i {
                font-size: 20px;
            }

            .modal-close-111 {
                width: 32px;
                height: 32px;
                font-size: 18px;
            }

            .modal-body-111 {
                padding: 20px;
                max-height: calc(90vh - 160px);
            }

            /* Voucher Input Group Mobile */
            .voucher-input-group-111 {
                flex-direction: column;
                gap: 10px;
                margin-bottom: 20px;
            }

            .voucher-input-group-111 input {
                width: 100%;
                padding: 10px 14px;
                font-size: 13px;
            }

            .btn-apply-code-111 {
                width: 100%;
                padding: 10px 20px;
                font-size: 13px;
            }

            /* Voucher Section Headers Mobile */
            .voucher-section-header-111 {
                padding: 12px 16px;
                gap: 10px;
                margin-top: 20px;
                margin-bottom: 14px;
            }

            .voucher-section-header-111:first-child {
                margin-top: 0;
            }

            .voucher-section-header-111 i {
                font-size: 18px;
            }

            .voucher-section-header-111 span:first-of-type {
                font-size: 14px;
            }

            .voucher-count-badge-111 {
                padding: 3px 10px;
                font-size: 11px;
            }

            /* Voucher Items Mobile */
            .voucher-item-111 {
                flex-direction: column;
                gap: 12px;
                padding: 14px;
            }

            .voucher-icon-111 {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }

            .voucher-details-111 h6 {
                font-size: 15px;
            }

            .voucher-details-111 p {
                font-size: 12px;
            }

            .voucher-code-111 {
                font-size: 11px;
                padding: 3px 8px;
            }

            .voucher-validity-111 {
                font-size: 10px;
            }

            .voucher-reason-111 {
                padding: 6px 10px;
                font-size: 11px;
                margin-top: 8px;
            }

            .btn-use-voucher-111 {
                width: 100%;
                padding: 10px 16px;
                font-size: 13px;
            }

            /* Empty States Mobile */
            .empty-cart-111 {
                padding: 60px 30px;
            }

            .empty-cart-111 i {
                font-size: 56px;
                margin-bottom: 20px;
            }

            .empty-cart-111 h4 {
                font-size: 22px;
                margin-bottom: 10px;
            }

            .empty-cart-111 p {
                font-size: 15px;
                margin-bottom: 28px;
            }

            .btn-primary-111 {
                padding: 12px 28px;
                font-size: 15px;
            }

            .empty-voucher-111 {
                padding: 30px 20px;
            }

            .empty-voucher-111 i {
                font-size: 40px;
                margin-bottom: 14px;
            }

            .empty-voucher-111 p {
                font-size: 13px;
            }
        }

        /* ========== EXTRA SMALL MOBILE (480px ke bawah) ========== */
        @media (max-width: 480px) {


.breadcrumb-111 {
    font-size: 0.9rem;
    padding: 0 0.75rem;
    gap: 0.4rem;
    font-weight: 500;
}

.separator-111 {
    font-size: 0.85rem;
}

.current-111 {
    font-size: 0.9rem;
}

            .cart-container-111 {
                padding: 0 12px;
                margin: 1rem auto;
            }

            .cart-items-section-111>h1 {
                font-size: 24px;
                margin-bottom: 16px;
            }

            /* Store Elements */
            .store-section-111 {
                margin-bottom: 20px;
                border-radius: 12px;
            }

            .store-header-111 {
                padding: 12px;
            }

            .store-icon-111 {
                width: 40px;
                height: 40px;
            }

            .store-icon-111 i {
                font-size: 20px;
            }

            .store-name-111 {
                font-size: 15px;
            }

            .store-badge-111 {
                font-size: 9px;
                padding: 2px 6px;
            }

            .store-meta-111 {
                font-size: 11px;
            }

            .store-meta-111 i {
                font-size: 14px;
            }

            /* Cart Item Extra Small */
            .cart-item-111 {
                padding: 16px 12px;
                gap: 12px;
            }

            .product-image-111 {
                max-width: 90px;
                height: 90px;
            }

            .product-details-111 h6 {
                font-size: 14px;
                line-height: 1.2;
            }

            .product-variant-111 {
                font-size: 11px;
            }

            .spec-badge-111 {
                font-size: 9px;
                padding: 2px 5px;
            }

            .stock-info-111 {
                font-size: 9px;
                padding: 2px 6px;
            }

            .stock-info-111 i {
                font-size: 10px;
            }

            /* Prices Extra Small */
            .current-price-111 {
                font-size: 16px;
            }

            .original-price-111 {
                font-size: 11px;
            }

            .item-subtotal-111 {
                font-size: 16px;
            }

            .btn-delete-111 {
                width: 30px;
                height: 30px;
                font-size: 13px;
            }

            /* Quantity Controls Extra Small */
            .quantity-control-111 {
                gap: 5px;
                padding: 3px 8px;
            }

            .quantity-btn-111 {
                width: 26px;
                height: 26px;
                font-size: 13px;
            }

            .quantity-input-111 {
                min-width: 32px;
                width: 32px;
                font-size: 13px;
            }

            /* Store Footer Extra Small */
            .store-footer-111 {
                padding: 12px;
            }

            .store-subtotal-label-111 {
                font-size: 12px;
            }

            .store-subtotal-value-111 {
                font-size: 16px;
            }

            .store-item-count-111 {
                font-size: 11px;
                padding: 4px 8px;
            }

            /* Cart Summary Extra Small */
            .cart-summary-111 {
                padding: 16px;
                border-radius: 10px;
            }

            .cart-summary-111 h5 {
                font-size: 18px;
                margin-bottom: 16px;
            }

            .voucher-section-111 {
                padding: 12px;
                border-radius: 10px;
            }

            .voucher-header-111 {
                gap: 8px;
                margin-bottom: 8px;
            }

            .voucher-header-111 i {
                font-size: 16px;
            }

            .voucher-header-111 span {
                font-size: 12px;
            }

            #voucherText {
                font-size: 11px;
            }

            #voucherText strong {
                font-size: 12px;
            }

            .btn-select-voucher {
                padding: 9px 12px;
                font-size: 12px;
                gap: 6px;
            }

            .btn-remove-voucher-111 {
                padding: 7px 12px;
                font-size: 11px;
            }

            .summary-row-111 {
                font-size: 13px;
                margin-bottom: 12px;
            }

            .summary-row-total-111 {
                font-size: 15px;
            }

            .summary-row-total-111 .total-price-111 {
                font-size: 18px;
            }

            .btn-checkout-111 {
                padding: 12px;
                font-size: 14px;
            }

            /* Modal Extra Small */
            .modal-content-111 {
                width: 96%;
                border-radius: 14px;
            }

            .modal-header-111 {
                padding: 16px;
            }

            .modal-header-111 h3 {
                font-size: 16px;
            }

            .modal-body-111 {
                padding: 16px;
            }

            .voucher-input-group-111 {
                gap: 8px;
                margin-bottom: 16px;
            }

            .voucher-input-group-111 input {
                padding: 9px 12px;
                font-size: 12px;
            }

            .btn-apply-code-111 {
                padding: 9px 16px;
                font-size: 12px;
            }

            .voucher-section-header-111 {
                padding: 10px 14px;
                gap: 8px;
                margin-top: 16px;
                margin-bottom: 12px;
            }

            .voucher-section-header-111 i {
                font-size: 16px;
            }

            .voucher-section-header-111 span:first-of-type {
                font-size: 13px;
            }

            .voucher-count-badge-111 {
                padding: 2px 8px;
                font-size: 10px;
            }

            .voucher-item-111 {
                padding: 12px;
                gap: 10px;
            }

            .voucher-icon-111 {
                width: 45px;
                height: 45px;
                font-size: 22px;
            }

            .voucher-details-111 h6 {
                font-size: 14px;
            }

            .voucher-details-111 p {
                font-size: 11px;
            }

            .voucher-code-111 {
                font-size: 10px;
                padding: 2px 6px;
            }

            .voucher-validity-111 {
                font-size: 9px;
            }

            .voucher-reason-111 {
                padding: 5px 8px;
                font-size: 10px;
            }

            .btn-use-voucher-111 {
                padding: 8px 14px;
                font-size: 12px;
            }

            /* Horizontal Scroll for Specs */
            .product-specs-111 {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .product-specs-111::-webkit-scrollbar {
                display: none;
            }
        }
    </style>
    <!-- Breadcrumb -->
    <div class="breadcrumb-container-111">
        <div class="breadcrumb-111">
            <a href="{{ route('home') }}">Home</a>
            <span class="separator-111">/</span>
            <span class="current-111">Keranjang Belanja</span>
        </div>
    </div>

    <div class="cart-container-111">
        @if ($groupedCart->isEmpty())
            <div class="empty-cart-111">
                <i class="fas fa-shopping-cart"></i>
                <h4>Keranjang Anda Kosong</h4>
                <p>Yuk, mulai belanja sekarang dan temukan produk favorit Anda!</p>
                <a href="{{ route('catalog') }}" class="btn-primary-111">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="cart-items-section-111">
                <h1 class="cart-title-111">KERANJANG BELANJA</h1>

                <!-- Store Sections -->
                @foreach ($groupedCart as $tokoId => $items)
                    @php
                        $toko = $items->first()->toko;
                    @endphp

                    @if (!$toko)
                        @continue
                    @endif

                    <div class="store-section-111" data-toko-id="{{ $tokoId }}">
                        <div class="store-header-111">
                            <div class="store-checkbox-wrapper-111">
                                <input type="checkbox" class="store-checkbox" data-toko-id="{{ $tokoId }}">
                            </div>
                            <div class="store-icon-111">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="store-info-group-111">
                                <div class="store-name-111">
                                    {{ $toko->nama_toko }}
                                    <span class="store-badge-111">Official Store</span>
                                </div>
                                <div class="store-meta-111">
                                    <span>
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $toko->alamat ?? 'Indonesia' }}
                                    </span>
                                    <span>
                                        <i class="fas fa-shipping-fast"></i>
                                        Fast Shipping
                                    </span>
                                </div>
                            </div>
                        </div>

                        @foreach ($items as $item)
                            @php
                                // Get product and variant info safely
                                $product = $item->product;
                                $variant = $item->variant;

                                if (!$product || !$variant) {
                                    continue; // Skip if product or variant not found
                                }

                                // Check if this is a size variant (has parent)
                                $hasParent = $variant->parent_id !== null;
                                $parentVariant = $hasParent ? $variant->parent : null;

                                // Get variant info
                                $variantType = $variant->type; // 'color' or 'size'
                                $variantName = $variant->name;

                                // Get color info
                                if ($hasParent && $parentVariant) {
                                    // This is a size variant, get color from parent
                                    $colorName = $parentVariant->name;
                                    $colorCode = $parentVariant->photo; // Assuming photo stores color code
                                    $sizeName = $variantName;
                                } elseif ($variantType === 'color') {
                                    // This is a color variant without size
                                    $colorName = $variantName;
                                    $colorCode = $variant->photo;
                                    $sizeName = null;
                                } else {
                                    $colorName = null;
                                    $colorCode = null;
                                    $sizeName = null;
                                }

                                // Get display photo
                                $displayPhoto = $variant->photo
                                    ? asset('storage/' . $variant->photo)
                                    : ($parentVariant && $parentVariant->photo
                                        ? asset('storage/' . $parentVariant->photo)
                                        : $product->thumbnail);
                            @endphp

                            <div class="cart-item-111" data-cart-id="{{ $item->id }}">
                                <div class="cart-item-checkbox-111">
                                    <input type="checkbox" class="item-checkbox" data-toko-id="{{ $tokoId }}"
                                        data-cart-id="{{ $item->id }}" data-price="{{ $item->final_price }}"
                                        data-quantity="{{ $item->quantity }}">
                                </div>

                                <img src="{{ $displayPhoto }}" alt="{{ $product->title }}" class="product-image-111"
                                    onerror="this.src='{{ asset('frontend/assets/img/placeholder-product.png') }}'">

                                <div class="product-details-111">
                                    <h6>{{ $product->title }}</h6>

                                    <div class="product-variant-111">
                                        SKU: {{ $product->sku }}
                                    </div>

                                    {{-- Display Color Info --}}
                                    @if ($colorName)
                                        <div class="product-variant-111">
                                            Warna: <strong>{{ $colorName }}</strong>
                                        </div>
                                    @endif

                                    {{-- Display Size Info --}}
                                    @if ($sizeName)
                                        <div class="product-variant-111">
                                            Ukuran: <strong>{{ $sizeName }}</strong>
                                        </div>
                                    @endif

                                    <div class="product-specs-111">
                                        @if ($product->ukuran)
                                            <span class="spec-badge-111">{{ $product->ukuran }}</span>
                                        @endif
                                        @if ($product->tipe)
                                            <span class="spec-badge-111">{{ $product->tipe_display }}</span>
                                        @endif
                                        @if ($product->jenis_bahan)
                                            <span class="spec-badge-111">{{ $product->jenis_bahan }}</span>
                                        @endif
                                    </div>

                                    <div class="stock-info-111">
                                        <i class="fas fa-check-circle"></i>
                                        <strong>In Stock</strong> • {{ $toko->nama_toko }}
                                    </div>
                                </div>

                                <div class="item-right-section-111">
                                    <div class="price-and-delete-111">
                                        <div class="price-section-111">
                                            <div class="current-price-111">Rp
                                                {{ number_format($item->final_price, 0, ',', '.') }}</div>
                                            @if ($product->has_discount)
                                                <span class="original-price-111">Rp
                                                    {{ number_format($item->price, 0, ',', '.') }}</span>
                                            @endif
                                        </div>

                                        <button class="btn-delete-111 btn-remove" type="button">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <div class="quantity-and-subtotal-111">
                                        <div class="quantity-control-111">
                                            <button class="quantity-btn-111 btn-decrease" type="button">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="quantity-input-111"
                                                value="{{ $item->quantity }}" min="1" readonly>
                                            <button class="quantity-btn-111 btn-increase" type="button">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="store-footer-111">
                            <div class="store-subtotal-111">
                                <span class="store-subtotal-label-111">Subtotal dari {{ $toko->nama_toko }}:</span>
                                <span class="store-subtotal-value-111">Rp
                                    {{ number_format($items->sum('final_subtotal'), 0, ',', '.') }}</span>
                            </div>
                            <span class="store-item-count-111">{{ $items->sum('quantity') }}
                                item{{ $items->sum('quantity') > 1 ? 's' : '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary-111">
                <h5>Ringkasan Belanja</h5>

                <div class="voucher-section-111">
                    <div class="voucher-header-111">
                        <i class="fas fa-ticket-alt"></i>
                        <span>Promo & Voucher</span>
                    </div>
                    <div id="voucherText">Tidak ada produk yang dipilih</div>
                    <button class="btn-select-voucher" id="selectVoucherBtn" disabled>
                        <i class="fas fa-tag"></i>
                        Pilih Voucher
                    </button>
                    <button class="btn-remove-voucher-111" id="removeVoucherBtn" style="display: none;">
                        <i class="fas fa-times"></i>
                        Hapus Voucher
                    </button>
                </div>

                <div class="summary-row-111">
                    <span>Subtotal (<span id="selectedCount">0</span> Produk):</span>
                    <span id="subtotalAmount">Rp0</span>
                </div>

                <div class="summary-row-111 discount-111" id="discountRow" style="display: none;">
                    <span>Potongan Voucher:</span>
                    <span id="discountAmount">-Rp0</span>
                </div>

                <hr>

                <div class="summary-row-111 summary-row-total-111">
                    <span>Total Pembayaran:</span>
                    <span class="total-price-111" id="grandTotal">Rp0</span>
                </div>

                <button class="btn-checkout-111" id="checkoutBtn" disabled>
                    Checkout (<span id="checkoutCount">0</span>)
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Voucher Modal -->
    <div id="voucherModal" class="modal-111">
        <div class="modal-content-111">
            <div class="modal-header-111">
                <h3>
                    <i class="fas fa-ticket-alt"></i>
                    Pilih Voucher
                </h3>
                <button class="modal-close-111" id="closeVoucherModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-111">
                <div class="voucher-input-group-111">
                    <input type="text" id="voucherCodeInput" placeholder="Masukkan kode voucher"
                        style="text-transform: uppercase;">
                    <button class="btn-apply-code-111" id="btnApplyCode">Pakai</button>
                </div>

                <!-- Loading State -->
                <div id="voucherListLoading" style="text-align: center; padding: 40px; display: none;">
                    <i class="fas fa-spinner fa-spin fa-3x text-muted"></i>
                    <p class="mt-3 text-muted">Memuat voucher...</p>
                </div>

                <!-- Voucher Sections -->
                <div id="voucherContainer">
                    <!-- Available Vouchers Section -->
                    <div id="availableSection" style="display: none;">
                        <div class="voucher-section-header-111">
                            <i class="fas fa-check-circle"></i>
                            <span>Dapat Digunakan</span>
                            <span class="voucher-count-badge-111" id="availableCount">0</span>
                        </div>
                        <div class="voucher-list-111" id="availableVouchers"></div>
                    </div>

                    <!-- Unavailable Vouchers Section -->
                    <div id="unavailableSection" style="display: none;">
                        <div class="voucher-section-header-111 unavailable">
                            <i class="fas fa-times-circle"></i>
                            <span>Tidak Dapat Digunakan</span>
                            <span class="voucher-count-badge-111" id="unavailableCount">0</span>
                        </div>
                        <div class="voucher-list-111" id="unavailableVouchers"></div>
                    </div>

                    <!-- Expired Vouchers Section -->
                    <div id="expiredSection" style="display: none;">
                        <div class="voucher-section-header-111 expired">
                            <i class="fas fa-clock"></i>
                            <span>Kadaluarsa</span>
                            <span class="voucher-count-badge-111" id="expiredCount">0</span>
                        </div>
                        <div class="voucher-list-111" id="expiredVouchers"></div>
                    </div>
                </div>

                <!-- Empty State -->
                <div class="empty-voucher-111" id="emptyVoucher" style="display: none;">
                    <i class="fas fa-ticket-alt"></i>
                    <p>Tidak ada voucher yang tersedia</p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('frontend/assets/js/cartkeranjang.js') }}"></script>

</x-frontend-layout>
