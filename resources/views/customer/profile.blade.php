<x-frontend-layout>
     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* ========================================
           CUSTOMER PROFILE EXCLUSIVE STYLES - 783
           ======================================== */

        /* Reset untuk container profile */
        #customer-profile-783 * {
            box-sizing: border-box;
        }

        #customer-profile-783 {
            min-height: 100vh;
            background-color: #f5f5f5;
            padding: 40px 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .cp783-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Section */
        .cp783-header {
            margin-bottom: 30px;
        }

        .cp783-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #212529;
            margin: 0 0 8px 0;
        }

        .cp783-header p {
            font-size: 15px;
            color: #6c757d;
            margin: 0;
        }

        /* Alert Boxes */
        .cp783-alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
        }

        .cp783-alert-success {
            background-color: #d1e7dd;
            border-left: 4px solid #198754;
            color: #0f5132;
        }

        .cp783-alert-error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #842029;
        }

        .cp783-alert svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            margin-right: 12px;
            margin-top: 2px;
        }

        .cp783-alert ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }

        .cp783-alert li {
            margin-bottom: 4px;
        }

        /* Main Card */
        .cp783-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .cp783-card {
                flex-direction: row;
            }
        }

        /* Sidebar - BIRU SOLID */
        .cp783-sidebar {
            background: #1f4390;
            padding: 50px 30px;
            color: white;
            text-align: center;
            width: 100%;
        }

        @media (min-width: 768px) {
            .cp783-sidebar {
                width: 350px;
                flex-shrink: 0;
            }
        }

        .cp783-avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 24px;
        }

        .cp783-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: block;
        }

        .cp783-avatar-btn {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 42px;
            height: 42px;
            background-color: #f9ef21;
            border-radius: 50%;
            border: 3px solid white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .cp783-avatar-btn:hover {
            background-color: #f7b963;
            transform: scale(1.08);
        }

        .cp783-avatar-btn svg {
            width: 20px;
            height: 20px;
            color: #000;
        }

        .cp783-name {
            font-size: 26px;
            font-weight: 700;
            margin: 0 0 6px 0;
            color: white;
        }

        .cp783-username {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.85);
            margin: 0 0 30px 0;
        }

        .cp783-contact-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }

        .cp783-contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.95);
        }

        .cp783-contact-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Content Area */
        .cp783-content {
            flex: 1;
            padding: 50px 40px;
        }

        @media (max-width: 767px) {
            .cp783-content {
                padding: 30px 20px;
            }
        }

        .cp783-title {
            font-size: 24px;
            font-weight: 600;
            color: #212529;
            margin: 0 0 24px 0;
            padding-bottom: 12px;
            border-bottom: 3px solid #f9ef21;
        }

        /* Form Styling */
        .cp783-form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (min-width: 576px) {
            .cp783-form-row.cp783-two-cols {
                grid-template-columns: 1fr 1fr;
            }
        }

        .cp783-form-group {
            display: flex;
            flex-direction: column;
        }

        .cp783-label {
            font-size: 14px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        .cp783-input,
        .cp783-textarea {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            color: #212529;
            background-color: white;
            border: 1px solid #ced4da;
            border-radius: 8px;
            transition: all 0.2s ease;
            outline: none;
        }

        .cp783-input:focus,
        .cp783-textarea:focus {
            border-color: #1f4390;
            box-shadow: 0 0 0 3px rgba(31, 67, 144, 0.1);
        }

        .cp783-textarea {
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        .cp783-error {
            font-size: 13px;
            color: #dc3545;
            margin-top: 6px;
            display: block;
        }

        /* Submit Section */
        .cp783-submit-section {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
        }

        .cp783-btn-submit {
            padding: 14px 40px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            background: #1f4390;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(31, 67, 144, 0.2);
        }

        .cp783-btn-submit:hover {
            background: #163066;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(31, 67, 144, 0.3);
        }

        .cp783-btn-submit:active {
            transform: translateY(0);
        }

        /* Info Box */
        .cp783-info-box {
            background-color: #e7f1ff;
            border-left: 4px solid #1f4390;
            border-radius: 8px;
            padding: 20px;
            margin-top: 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .cp783-info-box svg {
            width: 24px;
            height: 24px;
            color: #1f4390;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .cp783-info-box-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: #1f4390;
            margin: 0 0 8px 0;
        }

        .cp783-info-box-content p {
            font-size: 14px;
            color: #495057;
            margin: 0;
            line-height: 1.6;
        }

        /* Hidden input */
        .cp783-hidden {
            display: none !important;
        }
    </style>

    <div id="customer-profile-783">
        <div class="cp783-wrapper">
            <!-- Header -->
            <br>
            <br>
            <br>
            <div class="cp783-header">
                <h1>Profil Saya</h1>
                <p>Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="cp783-alert cp783-alert-success">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>{!! session('success') !!}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="cp783-alert cp783-alert-error">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Profile Card -->
            <div class="cp783-card">
                <!-- Sidebar -->
                <div class="cp783-sidebar">
                    <div class="cp783-avatar-container">
                        @if (auth('customer')->user()->foto_profil)
                            <img id="cp783-avatar-img"
                                src="{{ asset('storage/' . auth('customer')->user()->foto_profil) }}"
                                alt="Profile Picture" class="cp783-avatar"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <i class="bi bi-person-circle"
                                style="display: none; font-size: 150px; color: rgba(255,255,255,0.9);"></i>
                        @else
                            <i class="bi bi-person-circle" style="font-size: 150px; color: rgba(255,255,255,0.9);"></i>
                        @endif
                        <div class="cp783-avatar-btn" onclick="document.getElementById('cp783-file-input').click()">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <h2 class="cp783-name">{{ $customer->full_name ?? 'Nama Belum Diisi' }}</h2>
                    <p class="cp783-username">{{ '@' . ($customer->username ?? 'username') }}</p>
                    <div class="cp783-contact-info">
                        <div class="cp783-contact-item">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $customer->email ?? '-' }}</span>
                        </div>
                        @if ($customer->phone ?? null)
                            <div class="cp783-contact-item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span>{{ $customer->formatted_phone ?? $customer->phone }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="cp783-content">
                    <h3 class="cp783-title">Informasi Profil</h3>

                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="file" id="cp783-file-input" name="foto_profil"
                            accept="image/jpeg,image/png,image/jpg" class="cp783-hidden"
                            onchange="cp783PreviewImage(event)">

                        <!-- Name (EDITABLE) -->
                        <div class="cp783-form-row cp783-two-cols">
                            <div class="cp783-form-group">
                                <label for="cp783-firstname" class="cp783-label">Nama Depan</label>
                                <input type="text" id="cp783-firstname" name="firstname"
                                    value="{{ old('firstname', $customer->firstname ?? '') }}"
                                    placeholder="Masukkan nama depan" class="cp783-input">
                                @error('firstname')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="cp783-form-group">
                                <label for="cp783-lastname" class="cp783-label">Nama Belakang</label>
                                <input type="text" id="cp783-lastname" name="lastname"
                                    value="{{ old('lastname', $customer->lastname ?? '') }}"
                                    placeholder="Masukkan nama belakang" class="cp783-input">
                                @error('lastname')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Username & Email (EDITABLE) -->
                        <div class="cp783-form-row cp783-two-cols">
                            <div class="cp783-form-group">
                                <label for="cp783-username" class="cp783-label">Username</label>
                                <input type="text" id="cp783-username" name="username"
                                    value="{{ old('username', $customer->username ?? '') }}"
                                    placeholder="Masukkan username" class="cp783-input">
                                @error('username')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="cp783-form-group">
                                <label for="cp783-email" class="cp783-label">Email</label>
                                <input type="email" id="cp783-email" name="email"
                                    value="{{ old('email', $customer->email ?? '') }}" placeholder="Masukkan email"
                                    class="cp783-input">
                                @error('email')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Phone (Editable) -->
                        <div class="cp783-form-row">
                            <div class="cp783-form-group">
                                <label for="cp783-phone" class="cp783-label">Nomor Telepon</label>
                                <input type="text" id="cp783-phone" name="phone"
                                    value="{{ old('phone', $customer->phone ?? '') }}"
                                    placeholder="Contoh: 081234567890" class="cp783-input">
                                @error('phone')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Address (Editable) -->
                        <div class="cp783-form-row">
                            <div class="cp783-form-group">
                                <label for="cp783-address" class="cp783-label">Alamat Lengkap</label>
                                <textarea id="cp783-address" name="address" placeholder="Masukkan alamat lengkap Anda" class="cp783-textarea">{{ old('address', $customer->address ?? '') }}</textarea>
                                @error('address')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- City & Postal Code (Editable) -->
                        <div class="cp783-form-row cp783-two-cols">
                            <div class="cp783-form-group">
                                <label for="cp783-city" class="cp783-label">Kota</label>
                                <input type="text" id="cp783-city" name="city"
                                    value="{{ old('city', $customer->city ?? '') }}" placeholder="Contoh: Bandung"
                                    class="cp783-input">
                                @error('city')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="cp783-form-group">
                                <label for="cp783-postal" class="cp783-label">Kode Pos</label>
                                <input type="text" id="cp783-postal" name="postal_code"
                                    value="{{ old('postal_code', $customer->postal_code ?? '') }}"
                                    placeholder="Contoh: 40123" class="cp783-input">
                                @error('postal_code')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- 🆕 TAMBAH INI - Koordinat GPS -->
                        <div class="cp783-form-row">
                            <div class="cp783-form-group">
                                <label class="cp783-label">
                                    Titik Lokasi Anda <span style="color: #dc3545;">*</span>
                                </label>
                                <p style="font-size: 0.875rem; color: #6c757d; margin-bottom: 10px;">
                                    <i class="bi bi-info-circle"></i> Klik pada peta untuk menandai lokasi rumah Anda
                                    (diperlukan untuk menghitung ongkir)
                                </p>

                                <!-- Map Container -->
                                <div id="cp783-map"
                                    style="height: 400px; border-radius: 8px; border: 2px solid #e3e6f0; margin-bottom: 15px;">
                                </div>

                                <!-- Koordinat Display (Hidden) -->
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div>
                                        <label for="cp783-latitude"
                                            style="display: block; font-size: 0.875rem; color: #6c757d; margin-bottom: 5px;">Latitude</label>
                                        <input type="text" id="cp783-latitude" name="latitude"
                                            value="{{ old('latitude', $customer->latitude ?? '') }}"
                                            placeholder="-6.900977" readonly class="cp783-input"
                                            style="background-color: #f8f9fc; cursor: not-allowed;">
                                    </div>
                                    <div>
                                        <label for="cp783-longitude"
                                            style="display: block; font-size: 0.875rem; color: #6c757d; margin-bottom: 5px;">Longitude</label>
                                        <input type="text" id="cp783-longitude" name="longitude"
                                            value="{{ old('longitude', $customer->longitude ?? '') }}"
                                            placeholder="107.618856" readonly class="cp783-input"
                                            style="background-color: #f8f9fc; cursor: not-allowed;">
                                    </div>
                                </div>

                                @error('latitude')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                                @error('longitude')
                                    <span class="cp783-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="cp783-submit-section">
                            <button type="submit" class="cp783-btn-submit">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
              {{-- PASSWORD SECTION --}}
            <div class="cp783-card" style="margin-top: 24px;">
                <div class="cp783-content">
                    @if(!auth('customer')->user()->password)
                        {{-- SET PASSWORD (untuk customer Google) --}}
                        <h3 class="cp783-title">Set Password</h3>
                        <div class="cp783-info-box" style="margin-top: 0; background-color: #fff3cd; border-left-color: #ffc107;">
                            <svg fill="currentColor" viewBox="0 0 20 20" style="color: #ffc107;">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div class="cp783-info-box-content">
                                <h4 style="color: #856404;">Password Belum Diatur</h4>
                                <p style="color: #856404;">Anda login menggunakan Google. Buat password untuk bisa login dengan email & password juga.</p>
                            </div>
                        </div>

                        <form action="{{ route('customer.profile.set-password') }}" method="POST" style="margin-top: 24px;">
                            @csrf
                            <div class="cp783-form-row">
                                <div class="cp783-form-group">
                                    <label for="password" class="cp783-label">Password Baru</label>
                                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" class="cp783-input" required>
                                    @error('password')
                                        <span class="cp783-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="cp783-form-row">
                                <div class="cp783-form-group">
                                    <label for="password_confirmation" class="cp783-label">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password" class="cp783-input" required>
                                </div>
                            </div>
                            <div class="cp783-submit-section" style="margin-top: 20px; padding-top: 20px;">
                                <button type="submit" class="cp783-btn-submit">Buat Password</button>
                            </div>
                        </form>
                    @else
                        {{-- CHANGE PASSWORD (untuk customer yang sudah punya password) --}}
                        <h3 class="cp783-title">Ganti Password</h3>
                        <form action="{{ route('customer.profile.change-password') }}" method="POST">
                            @csrf
                            <div class="cp783-form-row">
                                <div class="cp783-form-group">
                                    <label for="current_password" class="cp783-label">Password Lama</label>
                                    <input type="password" id="current_password" name="current_password" placeholder="Masukkan password lama" class="cp783-input" required>
                                    @error('current_password')
                                        <span class="cp783-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="cp783-form-row">
                                <div class="cp783-form-group">
                                    <label for="new_password" class="cp783-label">Password Baru</label>
                                    <input type="password" id="new_password" name="new_password" placeholder="Minimal 8 karakter" class="cp783-input" required>
                                    @error('new_password')
                                        <span class="cp783-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="cp783-form-row">
                                <div class="cp783-form-group">
                                    <label for="new_password_confirmation" class="cp783-label">Konfirmasi Password Baru</label>
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" placeholder="Ketik ulang password baru" class="cp783-input" required>
                                </div>
                            </div>
                            <div class="cp783-submit-section" style="margin-top: 20px; padding-top: 20px;">
                                <button type="submit" class="cp783-btn-submit">Ubah Password</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info Box -->
            <div class="cp783-info-box">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div class="cp783-info-box-content">
                    <h4>Informasi Penting</h4>
                    <p>Pastikan semua data yang Anda masukkan sudah benar. Data yang akurat membantu kami memberikan
                        layanan terbaik untuk Anda.</p>
                </div>
            </div>
        </div>
    </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // ===========================
        // LEAFLET MAP FOR CUSTOMER
        // ===========================
        let customerMap;
        let customerMarker;
        
        // Default center: Bandung
        const defaultLat = -6.9175;
        const defaultLng = 107.6191;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Get saved coordinates or use default
            const savedLat = document.getElementById('cp783-latitude').value;
            const savedLng = document.getElementById('cp783-longitude').value;
            
            const centerLat = savedLat ? parseFloat(savedLat) : defaultLat;
            const centerLng = savedLng ? parseFloat(savedLng) : defaultLng;
            const zoomLevel = savedLat ? 15 : 13;
            
            // Create map
            customerMap = L.map('cp783-map').setView([centerLat, centerLng], zoomLevel);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(customerMap);
            
            // Add marker if coordinates exist
            if (savedLat && savedLng) {
                customerMarker = L.marker([centerLat, centerLng], {
                    draggable: true
                }).addTo(customerMap);
                
                // Update on drag
                customerMarker.on('dragend', function(e) {
                    const pos = customerMarker.getLatLng();
                    document.getElementById('cp783-latitude').value = pos.lat.toFixed(7);
                    document.getElementById('cp783-longitude').value = pos.lng.toFixed(7);
                });
            }
            
            // Add click event to map
            customerMap.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                
                // Update input fields
                document.getElementById('cp783-latitude').value = lat.toFixed(7);
                document.getElementById('cp783-longitude').value = lng.toFixed(7);
                
                // Remove old marker
                if (customerMarker) {
                    customerMap.removeLayer(customerMarker);
                }
                
                // Add new marker
                customerMarker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(customerMap);
                
                // Update on drag
                customerMarker.on('dragend', function(e) {
                    const pos = customerMarker.getLatLng();
                    document.getElementById('cp783-latitude').value = pos.lat.toFixed(7);
                    document.getElementById('cp783-longitude').value = pos.lng.toFixed(7);
                });
            });
        });
    </script>

    <script>
        function cp783PreviewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validasi ukuran file (max 2MB)
            if (file.size > 2048 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                event.target.value = '';
                return;
            }

            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                alert('Tipe file tidak didukung! Gunakan JPEG, PNG, atau JPG.');
                event.target.value = '';
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgElement = document.getElementById('cp783-avatar-img');
                if (imgElement) {
                    imgElement.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }
    </script>
</x-frontend-layout>
