<x-admin-layout title="Detail User">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail User</h1>
            <a href="{{ route('user.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Kembali</span>
            </a>
        </div>

        <!-- Info User -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Informasi User</h6>
                @if(auth()->user()->role === 'super_admin' || $user->role !== 'super_admin')
                    <a href="{{ route('user.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Nama</th>
                                <td>: {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>: 
                                    @if($user->role === 'super_admin')
                                        <span class="badge badge-danger">Super Admin</span>
                                    @elseif($user->role === 'admin')
                                        <span class="badge badge-success">Admin</span>
                                    @elseif($user->role === 'kepala_toko')
                                        <span class="badge badge-info">Kepala Toko</span>
                                    @else
                                        <span class="badge badge-warning">Staff Admin</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Toko</th>
                                <td>: {{ $user->toko->nama_toko ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="150">Bergabung Pada</th>
                                <td>: {{ $user->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir Update</th>
                                <td>: {{ $user->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($user->toko)
        <!-- Info Toko -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Toko</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Nama Toko</th>
                        <td>: {{ $user->toko->nama_toko }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: {{ $user->toko->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Telepon</th>
                        <td>: {{ $user->toko->telepon ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>
</x-admin-layout>