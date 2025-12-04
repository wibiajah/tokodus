<x-admin-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-folder-plus"></i> Tambah Kategori Baru
        </h1>
    </x-slot>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Form Kategori</h6>
                    </div>

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 pl-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('superadmin.categories.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label class="font-weight-bold">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Contoh: Kardus Besar"
                                       required
                                       autofocus>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Deskripsi</label>
                                <textarea class="form-control" 
                                          name="description" 
                                          rows="3"
                                          placeholder="Deskripsi kategori (opsional)">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           checked>
                                    <label class="custom-control-label" for="is_active">Kategori Aktif</label>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('superadmin.categories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>