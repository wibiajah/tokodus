<x-admin-layout title="Detail Toko">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Toko</h1>
            <a href="{{ route('toko.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Info Toko -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Toko</h6>
                <a href="{{ route('toko.edit', $toko) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Nama Toko</th>
                                <td>: {{ $toko->nama_toko }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>: {{ $toko->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>: {{ $toko->telepon ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Dibuat Pada</th>
                                <td>: {{ $toko->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Diupdate Pada</th>
                                <td>: {{ $toko->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kepala Toko -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kepala Toko</h6>
            </div>
            <div class="card-body">
                @if($toko->kepalaToko)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Bergabung</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $toko->kepalaToko->name }}</td>
                                    <td>{{ $toko->kepalaToko->email }}</td>
                                    <td>{{ $toko->kepalaToko->created_at->format('d M Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada kepala toko</p>
                @endif
            </div>
        </div>

        <!-- Staff Admin -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Staff Admin</h6>
            </div>
            <div class="card-body">
                @if($toko->staffAdmin->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-warning text-white">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Bergabung</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($toko->staffAdmin as $index => $staff)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $staff->name }}</td>
                                        <td>{{ $staff->email }}</td>
                                        <td>{{ $staff->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada staff admin</p>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>