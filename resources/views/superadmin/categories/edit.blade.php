<x-admin-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit"></i> Edit Kategori: {{ $category->name }}
        </h1>
    </x-slot>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">Form Edit Kategori</h6>
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

                        <div class="alert alert-info">
                            <small>
                                <strong>Slug:</strong> <code>{{ $category->slug }}</code> |
                                <strong>Produk:</strong> {{ $category->products()->count() }}
                            </small>
                        </div>

                        <form action="{{ route('superadmin.categories.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name', $category->name) }}" 
                                       required
                                       autofocus>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Deskripsi</label>
                                <textarea class="form-control" 
                                          name="description" 
                                          rows="3"
                                          placeholder="Deskripsi kategori (opsional)">{{ old('description', $category->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Kategori Aktif</label>
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('superadmin.categories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Delete Card --}}
                <div class="card shadow mb-4 border-left-danger">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-danger mb-2">
                            <i class="fas fa-trash"></i> Hapus Kategori
                        </h6>
                        <p class="small mb-3">
                            @if($category->products()->count() > 0)
                                Kategori ini digunakan oleh {{ $category->products()->count() }} produk.
                            @else
                                Kategori ini tidak digunakan produk manapun.
                            @endif
                        </p>
                        <form action="{{ route('superadmin.categories.destroy', $category) }}" 
                              method="POST" 
                              onsubmit="return confirm('Yakin hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Hapus Kategori
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>