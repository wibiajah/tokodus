<x-admin-layout title="Detail Toko">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-store text-primary"></i> Detail Toko
            </h1>
            <div style="display: flex; gap: 8px;">
                <a href="{{ route('toko.edit', $toko) }}" class="btn btn-warning btn-icon-split shadow-sm" style="border-radius: 8px;">
                    <span class="icon text-white-50">
                        <i class="fas fa-edit"></i>
                    </span>
                    <span class="text">Edit</span>
                </a>
                <a href="{{ route('toko.index') }}" class="btn btn-secondary btn-icon-split shadow-sm" style="border-radius: 8px;">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                    <span class="text">Kembali</span>
                </a>
            </div>
        </div>

        <!-- Info Cards Grid -->
        <div class="row mb-4">
            <!-- Status Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card shadow-sm h-100" style="border-radius: 15px; border: none;">
                    <div class="card-body p-4 text-center">
                        <div class="text-muted mb-2" style="font-size: 0.9rem;">
                            <i class="fas fa-info-circle"></i> Status
                        </div>
                        @if($toko->status === 'aktif')
                            <span class="badge badge-success px-4 py-2" style="font-size: 1rem;">
                                <i class="fas fa-circle" style="font-size: 7px;"></i> Aktif
                            </span>
                        @else
                            <span class="badge badge-secondary px-4 py-2" style="font-size: 1rem;">
                                <i class="fas fa-circle" style="font-size: 7px;"></i> Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Staff Count -->
            <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
                <div class="card shadow-sm h-100" style="border-radius: 15px; border: none;">
                    <div class="card-body p-4 text-center">
                        <div class="text-muted mb-2" style="font-size: 0.9rem;">
                            <i class="fas fa-users"></i> Jumlah Staff
                        </div>
                        <h3 class="text-info mb-0" style="font-size: 2rem;">{{ $staffAdmin->count() }}</h3>
                    </div>
                </div>
            </div>

            <!-- Kepala Toko Card -->
            <div class="col-xl-6 col-lg-12 col-md-12 mb-3">
                <div class="card shadow-sm h-100" style="border-radius: 15px; border: none;">
                    <div class="card-body p-4">
                        <div class="text-muted mb-3" style="font-size: 0.9rem;">
                            <i class="fas fa-user-tie"></i> Kepala Toko
                        </div>
                        @if($kepalaToko)
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="fas fa-user" style="font-size: 1.2rem;"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 font-weight-bold text-dark" style="font-size: 1rem;">{{ $kepalaToko->name }}</p>
                                    <p class="mb-0 small text-muted">{{ $kepalaToko->email }}</p>
                                </div>
                            </div>
                        @else
                            <div class="text-muted" style="font-size: 0.95rem;">
                                <i class="fas fa-times-circle text-danger"></i> Belum ada Kepala Toko
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Detail Card -->
        <div class="card shadow-sm mb-4" style="border-radius: 15px; border: none;">
            <div class="card-header py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0; border: none;">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-info-circle"></i> Informasi Toko
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <!-- Foto Section -->
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            @if($toko->foto)
                                <img src="{{ asset('storage/' . $toko->foto) }}" 
                                     alt="Foto {{ $toko->nama_toko }}" 
                                     style="width: 100%; height: 280px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            @else
                                <div style="width: 100%; height: 280px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <div class="text-center">
                                        <i class="fas fa-image" style="font-size: 48px; color: rgba(255,255,255,0.5);"></i>
                                        <p class="text-white mt-2" style="font-size: 0.9rem;">Tidak ada foto</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info Section -->
                    <div class="col-md-8">
                        <div class="row">
                            <!-- Nama Toko -->
                            <div class="col-md-12 mb-4">
                                <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                    <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-store"></i> Nama Toko
                                    </div>
                                    <div class="font-weight-bold text-dark" style="font-size: 1.2rem;">
                                        {{ $toko->nama_toko }}
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-12 mb-4">
                                <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                    <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-map-marker-alt"></i> Alamat
                                    </div>
                                    <div class="text-dark" style="font-size: 0.95rem; line-height: 1.6;">
                                        {{ $toko->alamat ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Telepon & Email (2 Kolom) -->
                            <div class="col-md-6 mb-4">
                                <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                    <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-phone"></i> Telepon
                                    </div>
                                    <div class="text-dark" style="font-size: 0.95rem;">
                                        {{ $toko->telepon ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                    <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-envelope"></i> Email
                                    </div>
                                    <div class="text-dark" style="font-size: 0.95rem;">
                                        @if($toko->email)
                                            <a href="mailto:{{ $toko->email }}" class="text-decoration-none">
                                                {{ $toko->email }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Google Maps -->
                            <div class="col-md-12 mb-4">
                                <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                    <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-map"></i> Lokasi
                                    </div>
                                    @if($toko->googlemap)
                                        <a href="{{ $toko->googlemap }}" target="_blank" class="btn btn-sm btn-danger" style="border-radius: 6px;">
                                            <i class="fas fa-map-marked-alt"></i> Lihat Lokasi di Maps
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Tanggal -->
                            <div class="col-md-6 mb-4">
                                <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                    <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-calendar-plus"></i> Tanggal Dibuat
                                    </div>
                                    <div class="text-dark" style="font-size: 0.95rem;">
                                        {{ $toko->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="p-3" style="background-color: #f8f9fc; border-radius: 8px;">
                                    <div class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-calendar-check"></i> Terakhir Diupdate
                                    </div>
                                    <div class="text-dark" style="font-size: 0.95rem;">
                                        {{ $toko->updated_at->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Admin Table -->
        @if($staffAdmin->count() > 0)
            <div class="card shadow-sm mb-4" style="border-radius: 15px; border: none;">
                <div class="card-header py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px 15px 0 0; border: none;">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-users"></i> Staff Admin ({{ $staffAdmin->count() }})
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f8f9fc;">
                                <tr>
                                    <th width="5%" class="text-muted" style="border: none; font-size: 0.85rem;">No</th>
                                    <th class="text-muted" style="border: none; font-size: 0.85rem;">Nama</th>
                                    <th class="text-muted" style="border: none; font-size: 0.85rem;">Email</th>
                                    <th class="text-muted" style="border: none; font-size: 0.85rem;">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staffAdmin as $index => $staff)
                                    <tr style="border-top: 1px solid #e3e6f0;">
                                        <td style="border: none; padding: 1rem;">{{ $index + 1 }}</td>
                                        <td style="border: none; padding: 1rem;">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px;">
                                                    <i class="fas fa-user text-primary" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <span class="font-weight-bold text-dark">{{ $staff->name }}</span>
                                            </div>
                                        </td>
                                        <td style="border: none; padding: 1rem;">
                                            <a href="mailto:{{ $staff->email }}" class="text-decoration-none">{{ $staff->email }}</a>
                                        </td>
                                        <td style="border: none; padding: 1rem;">
                                            <span class="badge badge-warning px-3 py-2" style="font-size: 0.8rem;">
                                                <i class="fas fa-briefcase"></i> Staff Admin
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card shadow-sm" style="border-radius: 15px; border: none;">
                <div class="card-body p-4 text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                    <h5 class="text-muted mb-2">Belum Ada Staff Admin</h5>
                    <p class="text-muted" style="font-size: 0.95rem;">Toko ini belum memiliki staff admin yang ditugaskan.</p>
                </div>
            </div>
        @endif
    </div>

    <style>
        .card:hover {
            transition: all 0.3s ease;
        }
        
        .btn {
            transition: all 0.2s ease;
        }
        
        .btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
        }

        a {
            color: #667eea;
        }

        a:hover {
            color: #764ba2;
        }
    </style>
</x-admin-layout>