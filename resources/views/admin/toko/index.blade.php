<x-admin-layout title="Manajemen Toko">
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Toko</h1>
            <a href="{{ route('toko.create') }}" class="btn btn-primary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Tambah Toko</span>
            </a>
        </div>

        <!-- Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- DataTales -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Toko</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama Toko</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th width="10%">Kepala Toko</th>
                                <th width="10%">Staff</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tokos as $index => $toko)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $toko->nama_toko }}</strong></td>
                                    <td>{{ $toko->alamat ?? '-' }}</td>
                                    <td>{{ $toko->telepon ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $toko->kepala_toko_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-warning">{{ $toko->staff_admin_count }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('toko.show', $toko) }}" class="btn btn-info btn-sm" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('toko.edit', $toko) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus" 
                                            onclick="confirmDelete({{ $toko->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        <form id="delete-form-{{ $toko->id }}" action="{{ route('toko.destroy', $toko) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada data toko</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus toko ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
    @endpush
</x-admin-layout>