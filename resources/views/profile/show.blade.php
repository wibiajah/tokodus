<x-admin-layout title="Profil Saya">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 font-weight-bold text-dark">Profil Saya</h1>
                        <p class="text-muted mb-0">Kelola informasi akun dan pengaturan keamanan Anda</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Alert -->
       

        <!-- Profile Card -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Profile Header Card -->
                <div class="card border-0 shadow-sm mb-4" style="border-top: 4px solid #224abe;">
                    <div class="card-body p-4">
                        <!-- Foto Profil Section -->
                        <div class="text-center mb-5">
                            <img src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : asset('img/default-avatar.png') }}" 
                                 alt="Foto Profil" 
                                 class="rounded-circle border" 
                                 style="width: 180px; height: 180px; object-fit: cover; border: 4px solid #224abe;">
                            <div class="mt-3">
                                <h4 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h4>
                                <span class="badge" style="background-color: #224abe; color: white; padding: 6px 12px; font-size: 12px;">
                                    @if($user->isSuperAdmin())
                                        Super Admin
                                    @elseif($user->isAdmin())
                                        Admin
                                    @elseif($user->isKepalaToko())
                                        Kepala Toko
                                    @else
                                        Staff Admin
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Info Section -->
                        <hr class="my-4">

                        <!-- Email & Phone -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div style="color: #224abe;" class="mr-3">
                                        <i class="fas fa-envelope fa-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 small font-weight-bold">EMAIL</p>
                                        <p class="text-dark mb-0">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div style="color: #224abe;" class="mr-3">
                                        <i class="fas fa-phone fa-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 small font-weight-bold">NO. TELEPON</p>
                                        <p class="text-dark mb-0">{{ $user->no_telepon ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Role & Toko -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div style="color: #224abe;" class="mr-3">
                                        <i class="fas fa-user-tag fa-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 small font-weight-bold">ROLE</p>
                                        <p class="text-dark mb-0">
                                            @if($user->isSuperAdmin())
                                                Super Admin
                                            @elseif($user->isAdmin())
                                                Admin
                                            @elseif($user->isKepalaToko())
                                                Kepala Toko
                                            @else
                                                Staff Admin
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @if($user->toko)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div style="color: #224abe;" class="mr-3">
                                            <i class="fas fa-store fa-lg"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1 small font-weight-bold">TOKO</p>
                                            <p class="text-dark mb-0">{{ $user->toko->nama_toko ?? 'Head Office' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Status & Created -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div style="color: #224abe;" class="mr-3">
                                        <i class="fas fa-calendar fa-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 small font-weight-bold">AKUN DIBUAT</p>
                                        <p class="text-dark mb-0">{{ $user->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div style="color: #224abe;" class="mr-3">
                                        <i class="fas fa-sync fa-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted mb-1 small font-weight-bold">TERAKHIR DIUBAH</p>
                                        <p class="text-dark mb-0">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Actions -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm font-weight-bold" style="background-color: #224abe; color: white; border: none;">
                                <i class="fas fa-edit"></i> Edit Profil
                            </a>
                            <a href="{{ route('profile.password.edit') }}" class="btn btn-sm font-weight-bold" style="background-color: #224abe; color: white; border: none;">
                                <i class="fas fa-key"></i> Ubah Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="background-color: #f8fafb;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold text-dark mb-3">
                            <i class="fas fa-shield-alt" style="color: #224abe;"></i> Keamanan Akun
                        </h5>
                        <div class="alert alert-info border-0 mb-0" style="background-color: #e7f3ff; color: #224abe;">
                            <small>
                                <strong>Tips Keamanan:</strong><br>
                                • Gunakan password yang kuat dan unik<br>
                                • Jangan bagikan password Anda<br>
                                • Perbarui password bila diperlukan<br>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>