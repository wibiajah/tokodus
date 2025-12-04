<x-admin-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">Edit Stok Produk</h2>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                {{-- Product Info --}}
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title border-bottom pb-2 mb-3">
                                <i class="bi bi-box-seam me-2"></i>Info Produk
                            </h5>

                            @if($product->photos && count($product->photos) > 0)
                                <img src="{{ Storage::url($product->photos[0]) }}" 
                                    alt="{{ $product->title }}"
                                    class="img-fluid rounded mb-3">
                            @endif

                            <div class="mb-2">
                                <h6 class="fw-bold">{{ $product->title }}</h6>
                                <code class="d-block">{{ $product->sku }}</code>
                            </div>

                            <div class="mb-2">
                                <small class="text-muted">Harga:</small>
                                <div class="fs-5 fw-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Current Stock Info --}}
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="bi bi-clipboard-data me-2"></i>Stok Toko Saat Ini</h6>
                            
                            @php
                                $myStock = $product->stockInToko(auth()->user()->toko_id);
                                $currentStock = $myStock ? $myStock->stock : 0;
                            @endphp
                            
                            <div class="text-center">
                                <div class="display-4 fw-bold">{{ $currentStock }}</div>
                                <small>unit</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Edit Stok --}}
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title border-bottom pb-2 mb-4">
                                <i class="bi bi-pencil-square me-2"></i>Edit Stok Toko
                            </h5>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            {{-- Info Toko --}}
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-shop fs-3 me-3"></i>
                                    <div>
                                        <div class="fw-bold">{{ auth()->user()->toko->nama_toko }}</div>
                                        <small>{{ auth()->user()->toko->alamat }}</small>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('kepala-toko.stocks.update', $product) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- Current vs New Stock --}}
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <small class="text-muted">Stok Sekarang</small>
                                                <div class="fs-3 fw-bold text-secondary">{{ $currentStock }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <small class="text-muted">Stok Awal Tersedia</small>
                                                <div class="fs-3 fw-bold text-success">{{ $product->remaining_initial_stock }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Type --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Aksi:</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="action_type" id="add" value="add" checked>
                                        <label class="btn btn-outline-success" for="add">
                                            <i class="bi bi-plus-circle"></i> Tambah Stok
                                        </label>

                                        <input type="radio" class="btn-check" name="action_type" id="reduce" value="reduce">
                                        <label class="btn btn-outline-danger" for="reduce">
                                            <i class="bi bi-dash-circle"></i> Kurangi Stok
                                        </label>

                                        <input type="radio" class="btn-check" name="action_type" id="set" value="set">
                                        <label class="btn btn-outline-primary" for="set">
                                            <i class="bi bi-pencil"></i> Set Manual
                                        </label>
                                    </div>
                                </div>

                                {{-- Stock Input --}}
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        Jumlah Stok <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="stock" id="stock-input" 
                                        class="form-control form-control-lg" 
                                        value="{{ old('stock', $currentStock) }}" 
                                        min="0" 
                                        required
                                        placeholder="Masukkan jumlah">
                                    <div id="stock-help" class="form-text"></div>
                                </div>

                                {{-- Preview --}}
                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3">📊 Preview Hasil:</h6>
                                        
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <small class="text-muted d-block">Stok Toko Sekarang</small>
                                                <div class="fs-4 fw-bold text-secondary">{{ $currentStock }}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted d-block">Perubahan</small>
                                                <div class="fs-4 fw-bold" id="change-preview">
                                                    <span class="text-success">+0</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <small class="text-muted d-block">Stok Baru</small>
                                                <div class="fs-4 fw-bold text-primary" id="new-stock">{{ $currentStock }}</div>
                                            </div>
                                        </div>

                                        <div class="mt-3 pt-3 border-top">
                                            <small class="text-muted d-block">Sisa Stok Awal Setelah Perubahan:</small>
                                            <div class="fs-5 fw-bold text-success" id="remaining-after">
                                                {{ $product->remaining_initial_stock }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Warning --}}
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>Perhatian!</strong> Perubahan stok akan langsung mempengaruhi stok awal. 
                                    Pastikan perhitungan sudah benar.
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex justify-content-between pt-3 border-top">
                                    <a href="{{ route('kepala-toko.stocks.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-save"></i> Update Stok
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const currentStock = {{ $currentStock }};
        const availableStock = {{ $product->remaining_initial_stock }};

        function updatePreview() {
            const actionType = document.querySelector('input[name="action_type"]:checked').value;
            const inputValue = parseInt(document.getElementById('stock-input').value) || 0;
            let newStock = currentStock;
            let changeValue = 0;
            let remainingAfter = availableStock;

            if (actionType === 'add') {
                newStock = currentStock + inputValue;
                changeValue = inputValue;
                remainingAfter = Math.max(0, availableStock - inputValue);
                document.getElementById('stock-help').textContent = `Tambah ${inputValue} unit ke stok toko`;
                document.getElementById('change-preview').innerHTML = `<span class="text-success">+${inputValue}</span>`;
            } else if (actionType === 'reduce') {
                newStock = Math.max(0, currentStock - inputValue);
                changeValue = -inputValue;
                remainingAfter = availableStock + inputValue;
                document.getElementById('stock-help').textContent = `Kurangi ${inputValue} unit dari stok toko`;
                document.getElementById('change-preview').innerHTML = `<span class="text-danger">-${inputValue}</span>`;
            } else {
                newStock = inputValue;
                changeValue = inputValue - currentStock;
                remainingAfter = Math.max(0, availableStock - changeValue);
                document.getElementById('stock-help').textContent = `Set stok toko menjadi ${inputValue} unit`;
                document.getElementById('change-preview').innerHTML = changeValue >= 0 
                    ? `<span class="text-success">+${Math.abs(changeValue)}</span>`
                    : `<span class="text-danger">${changeValue}</span>`;
            }

            document.getElementById('new-stock').textContent = newStock;
            document.getElementById('remaining-after').textContent = remainingAfter;
        }

        document.querySelectorAll('input[name="action_type"]').forEach(radio => {
            radio.addEventListener('change', updatePreview);
        });

        document.getElementById('stock-input').addEventListener('input', updatePreview);

        // Initial preview
        updatePreview();
    </script>

</x-admin-layout>