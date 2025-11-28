<x-app-layout title="Dashboard">
    <!-- Welcome Card -->
    <br><br><br>
    <div class="container-fluid">
        <div class="card bg-primary text-white shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="fw-semibold mb-1">Selamat datang, {{ auth()->user()->name }}!</h4>
                        <p class="mb-0">Anda telah login sebagai
                            <span class="badge bg-light text-primary">{{ auth()->user()->role }}</span>
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        <p class="mb-0">{{ now()->format('l, d F Y') }}</p>
                        <small class="opacity-75">{{ now()->format('H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
