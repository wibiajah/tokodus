<x-admin-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-bell mr-2"></i>Semua Notifikasi
                        </h6>
                        @if($notifications->total() > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light">
                                <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                            </button>
                        </form>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @forelse($notifications as $notification)
                            @php
                                $data = $notification->data;
                                $isUnread = is_null($notification->read_at);
                            @endphp
                            <div class="d-flex align-items-start p-3 border-bottom hover-bg-light {{ $isUnread ? 'bg-light' : '' }}">
                                <div class="mr-3 mt-1">
                                    <i class="{{ $data['icon'] ?? 'fas fa-info-circle' }}" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <p class="mb-1 {{ $isUnread ? 'font-weight-bold text-dark' : 'text-muted' }}">
                                                {{ $data['message'] }}
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-user-circle"></i> oleh {{ $data['actor_name'] }} • 
                                                <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="ml-3">
                                            @if($isUnread)
                                                <span class="badge badge-primary badge-pill">Baru</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- @if(isset($data['url']) && $data['url'] !== route('user.index') && $data['url'] !== route('toko.index') && $data['url'] !== '#')
                                        <a href="{{ $data['url'] }}" class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                    @endif -->
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-bell-slash fa-4x mb-3 text-secondary"></i>
                                <h5>Tidak ada notifikasi</h5>
                                <p class="mb-0">Semua aktivitas akan muncul di sini</p>
                            </div>
                        @endforelse
                    </div>
                    @if($notifications->hasPages())
                    <div class="card-footer py-3">
                        <div class="d-flex justify-content-center">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-bg-light:hover {
            background-color: #f8f9fc !important;
            cursor: pointer;
        }
    </style>
</x-admin-layout>