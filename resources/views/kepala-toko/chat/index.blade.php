<x-admin-layout title="Chat Inbox">
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">💬 Chat Inbox</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Chat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="statTotal">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Belum Dibaca</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="statUnread">{{ $stats['unread'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Order Chat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="statOrderChat">{{ $stats['order_chat'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">General Chat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="statGeneralChat">{{ $stats['general_chat'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat List -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Chat</h6>
            </div>
            <div class="card-body" id="chatListContainer">
                @forelse($chatRooms as $room)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    @if ($room->type === 'order_chat')
                                        📦 Order #{{ $room->order->order_number }}
                                    @else
                                        💬 General Chat
                                    @endif

                                    @php
                                        $customer = $room->participants->where('role', 'customer')->first();
                                        $unread = $room->getUnreadCount(\App\Models\User::class, auth()->id());
                                    @endphp

                                    @if ($unread > 0)
                                        <span class="badge badge-warning chat-room-badge"
                                            data-room-id="{{ $room->id }}">{{ $unread }} baru</span>
                                    @endif
                                </h6>

                                <p class="text-muted mb-1" style="font-size: 14px;">
                                    Customer: {{ $customer->participantable->full_name ?? 'Unknown' }}
                                </p>

                                @if ($room->latestMessage)
                                    <p class="mb-0" style="font-size: 13px; color: #666;">
                                        {{ Str::limit($room->latestMessage->message ?? '📎 Attachment', 50) }}
                                    </p>
                                @endif
                            </div>

                            <div class="text-right ml-3">
                                <small class="text-muted d-block mb-2">
                                    {{ $room->latestMessage ? $room->latestMessage->created_at->diffForHumans() : $room->updated_at->diffForHumans() }}
                                </small>
                                <a href="{{ route('kepala-toko.chat.show', $room->id) }}"
                                    class="btn btn-sm btn-primary">
                                    Buka Chat
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <p>Belum ada chat</p>
                    </div>
                @endforelse

                {{ $chatRooms->links() }}
            </div>
        </div>
    </div>

    <script>
        let chatListPollingInterval = null;

        function getRelativeTime(isoString) {
            try {
                const now = new Date();
                const past = new Date(isoString);
                if (isNaN(past.getTime())) return null;

                const diffMs = now - past;
                const diffSecs = Math.floor(diffMs / 1000);
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);

                if (diffSecs < 10) return 'Baru saja';
                if (diffSecs < 60) return `${diffSecs} detik yang lalu`;
                if (diffMins < 60) return `${diffMins} menit yang lalu`;
                if (diffHours < 24) return `${diffHours} jam yang lalu`;
                if (diffDays < 7) return `${diffDays} hari yang lalu`;
                if (diffDays < 30) return `${Math.floor(diffDays / 7)} minggu yang lalu`;
                if (diffDays < 365) return `${Math.floor(diffDays / 30)} bulan yang lalu`;
                return `${Math.floor(diffDays / 365)} tahun yang lalu`;
            } catch (e) {
                return null;
            }
        }

        async function updateChatList() {
            try {
                const response = await fetch('{{ route("kepala-toko.chat.list") }}');
                if (!response.ok) return;

                const data = await response.json();
                if (!data.success) return;

                // Update stats
                if (data.stats) {
                    const statTotal = document.getElementById('statTotal');
                    const statUnread = document.getElementById('statUnread');
                    const statOrderChat = document.getElementById('statOrderChat');
                    const statGeneralChat = document.getElementById('statGeneralChat');

                    if (statTotal) statTotal.textContent = data.stats.total;
                    if (statUnread) statUnread.textContent = data.stats.unread;
                    if (statOrderChat) statOrderChat.textContent = data.stats.order_chat;
                    if (statGeneralChat) statGeneralChat.textContent = data.stats.general_chat;
                }

                // Update chat list
                const chatContainer = document.getElementById('chatListContainer');
                if (!chatContainer) return;

                if (data.rooms.length === 0) {
                    chatContainer.innerHTML = `
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>Belum ada chat</p>
                        </div>
                    `;
                } else {
                    let html = '';
                    data.rooms.forEach(room => {
                        const relativeTime = getRelativeTime(room.updated_at_iso);
                        const unreadBadge = room.unread_count > 0
                            ? `<span class="badge badge-warning chat-room-badge" data-room-id="${room.id}">${room.unread_count} baru</span>`
                            : '';
                        const latestMessage = room.latest_message
                            ? `<p class="mb-0" style="font-size: 13px; color: #666;">${room.latest_message}</p>`
                            : '';

                        html += `
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            ${room.type_label}
                                            ${unreadBadge}
                                        </h6>
                                        <p class="text-muted mb-1" style="font-size: 14px;">
                                            Customer: ${room.customer_name}
                                        </p>
                                        ${latestMessage}
                                    </div>
                                    <div class="text-right ml-3">
                                        <small class="text-muted d-block mb-2">
                                            ${relativeTime}
                                        </small>
                                        <a href="${room.url}" class="btn btn-sm btn-primary">
                                            Buka Chat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    chatContainer.innerHTML = html;
                }
            } catch (error) {
                // Silent fail
            }
        }

        // Start polling
        setTimeout(() => {
            updateChatList();
            chatListPollingInterval = setInterval(updateChatList, 5000);
        }, 2000);

        // Pause when tab hidden
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (chatListPollingInterval) {
                    clearInterval(chatListPollingInterval);
                    chatListPollingInterval = null;
                }
            } else {
                if (!chatListPollingInterval) {
                    updateChatList();
                    chatListPollingInterval = setInterval(updateChatList, 5000);
                }
            }
        });

        // Cleanup
        window.addEventListener('beforeunload', function() {
            if (chatListPollingInterval) {
                clearInterval(chatListPollingInterval);
            }
        });
    </script>
</x-admin-layout>