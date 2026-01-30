<x-frontend-layout>
    <style>
        /* Read Receipt Styles */
        .message-status-901 {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            font-size: 11px;
            margin-left: 5px;
            vertical-align: middle;
        }

        .message-status-901.sent {
            color: #999;
        }

        .message-status-901.read {
            color: #1f4390;
        }

        .message-status-icon-901 {
            font-size: 13px;
            line-height: 1;
        }

        /* Search Highlight */
        .search-highlight {
            background: rgb(97, 97, 90);
            font-weight: bold;
            padding: 2px 0;
        }

        /* Simple Chat Styles - 901 */
        .chat-container-901 {
            max-width: 1000px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }

        .chat-header-901 {
            background: #1f4390;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
        }

        .chat-header-901 h3 {
            margin: 0 0 5px 0;
            font-size: 20px;
        }

        .chat-header-901 p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        /* Search Box */
        .chat-search-901 {
            background: white;
            padding: 12px 20px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .chat-search-901 input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .chat-search-901 input:focus {
            outline: none;
            border-color: #1f4390;
        }

        .search-controls-901 {
            display: none;
            gap: 5px;
            align-items: center;
        }

        .search-controls-901 button {
            padding: 6px 12px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .search-controls-901 button:hover {
            background: #f0f0f0;
        }

        .search-controls-901 .clear-btn {
            background: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .search-controls-901 .clear-btn:hover {
            background: #c82333;
        }

        .search-controls-901 span {
            font-size: 13px;
            color: #666;
            white-space: nowrap;
        }

        .chat-messages-901 {
            background: #f5f5f5;
            height: 500px;
            overflow-y: auto;
            padding: 20px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .message-901 {
            display: flex;
            margin-bottom: 15px;
            gap: 10px;
        }

        .message-901.me {
            flex-direction: row-reverse;
        }

        .message-avatar-901 {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #1f4390;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .message-901.me .message-avatar-901 {
            background: #f9ef21;
            color: #000;
        }

        .message-content-901 {
            max-width: 70%;
        }

        .message-bubble-901 {
            background: white;
            padding: 12px 16px;
            border-radius: 12px;
            word-wrap: break-word;
        }

        .message-901.me .message-bubble-901 {
            background: #1f4390;
            color: white;
        }

        .message-image-901 {
            max-width: 200px;
            max-height: 200px;
            width: auto;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            display: block;
        }

        .message-time-901 {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }

        /* Image Preview Area */
        .image-preview-901 {
            background: #f8f9fa;
            padding: 10px 20px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            display: none;
        }

        .preview-content-901 {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .preview-content-901 img {
            max-width: 80px;
            max-height: 80px;
            border-radius: 5px;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        .preview-content-901 .filename {
            flex: 1;
            font-size: 13px;
            color: #666;
        }

        .preview-content-901 button {
            padding: 6px 12px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        .preview-content-901 button:hover {
            background: #c82333;
        }

        .chat-input-901 {
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 0 0 10px 10px;
        }

        .input-group-901 {
            display: flex;
            gap: 10px;
        }

        .input-group-901 textarea {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: none;
            font-family: inherit;
        }

        .input-group-901 textarea:focus {
            outline: none;
            border-color: #1f4390;
        }

        .btn-901 {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary-901 {
            background: #1f4390;
            color: white;
        }

        .btn-primary-901:hover {
            background: #163066;
        }

        .btn-secondary-901 {
            background: #6c757d;
            color: white;
        }

        .alert-901 {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success-901 {
            background: #d1e7dd;
            color: #0f5132;
        }

        .alert-error-901 {
            background: #f8d7da;
            color: #842029;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .back-btn-901 {
            display: inline-block;
            margin-bottom: 20px;
            color: #1f4390;
            text-decoration: none;
            font-weight: 600;
        }

        .back-btn-901:hover {
            text-decoration: underline;
        }

        /* Photo Modal for Chat */
        .chat-photo-modal-901 {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            cursor: zoom-out;
        }

        .chat-photo-modal-content-901 {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 8px;
        }

        .chat-photo-modal-close-901 {
            position: absolute;
            top: 20px;
            right: 40px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
        }

        .chat-photo-modal-close-901:hover {
            color: #bbb;
        }
    </style>

    <div class="chat-container-901">
        <a href="{{ route('customer.orders.show', $chatRoom->order_id) }}" class="back-btn-901">
            ← Kembali ke Order
        </a>

        @if (session('success'))
            <div class="alert-901 alert-success-901">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-901 alert-error-901">{{ session('error') }}</div>
        @endif

        <div class="chat-header-901">
            <h3>Live Chat - Order #{{ $chatRoom->order->order_number }}</h3>
            <p>{{ $chatRoom->order->toko->nama_toko }}</p>
        </div>

        <!-- Search Box -->
        <div class="chat-search-901">
            <input type="text" id="searchMessages" placeholder="Cari pesan...">
            <div class="search-controls-901" id="searchControls">
                <button type="button" id="searchUp">▲</button>
                <button type="button" id="searchDown">▼</button>
                <span id="searchCount"></span>
                <button type="button" id="clearSearch" class="clear-btn">✕</button>
            </div>
        </div>

        <div class="chat-messages-901" id="chatMessages">
            @forelse($messages as $message)
                @php
                    $isMe =
                        $message->sender_type === 'App\Models\Customer' &&
                        $message->sender_id == auth('customer')->id();
                    $senderName = $isMe ? 'Anda' : $message->sender->name ?? 'Admin';
                    $initial = strtoupper(substr($senderName, 0, 1));
                @endphp

                <div class="message-901 {{ $isMe ? 'me' : '' }}">
                    @php
                        $avatarUrl = null;

                        // Untuk customer yang login (Anda)
                        if ($isMe) {
                            $customer = auth('customer')->user();
                            if ($customer && !empty($customer->foto_profil)) {
                                $avatarUrl = asset('storage/' . $customer->foto_profil);
                            }
                        }
                        // Untuk admin/toko
                        else {
                            if ($message->sender && !empty($message->sender->foto_profil)) {
                                $avatarUrl = asset('storage/' . $message->sender->foto_profil);
                            }
                        }
                    @endphp

                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $senderName }}" class="message-avatar-901"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"
                            onerror="this.onerror=null; this.outerHTML='<div class=\'message-avatar-901\'>{{ addslashes($initial) }}</div>';">
                    @else
                        <div class="message-avatar-901">{{ $initial }}</div>
                    @endif
                    <div class="message-content-901">
                        <div class="message-bubble-901">
                            @if ($message->hasAttachment())
                                @if ($message->isImage())
                                    <a href="{{ asset('storage/' . $message->attachment_path) }}"
                                        data-lightbox="chat-images"
                                        data-title="{{ $senderName }} - {{ $message->formatted_time }}">
                                        <img src="{{ asset('storage/' . $message->attachment_path) }}"
                                            class="message-image-901" alt="Image">
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $message->attachment_path) }}" target="_blank"
                                        style="color: inherit;">
                                        📎 {{ basename($message->attachment_path) }}
                                    </a>
                                @endif
                            @endif
                            @if ($message->message)
                                <div>{{ $message->message }}</div>
                            @endif
                        </div>
                        <div class="message-time-901" data-timestamp="{{ $message->created_at->toIso8601String() }}"
                            data-message-id="{{ $message->id }}"
                            data-is-read="{{ $message->is_read ? 'true' : 'false' }}">
                            {{ $message->formatted_time }}
                            @if ($isMe)
                                <span class="message-status-901 {{ $message->is_read ? 'read' : 'sent' }}"
                                    data-status="{{ $message->is_read ? 'read' : 'sent' }}">
                                    <span class="message-status-icon-901">
                                        {{ $message->is_read ? '✓✓' : '✓' }}
                                    </span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #999; padding: 40px;">
                    Belum ada pesan. Mulai percakapan Anda.
                </div>
            @endforelse
        </div>

        <!-- Image Preview Area -->
        <div class="image-preview-901" id="imagePreview">
            <div class="preview-content-901">
                <img id="previewImg" src="" alt="Preview">
                <span class="filename" id="fileName"></span>
                <button type="button" onclick="removePreview()">✕ Hapus</button>
            </div>
        </div>

        <div class="chat-input-901">
            <form id="chatForm" enctype="multipart/form-data">
                @csrf
                <div class="input-group-901">
                    <input type="file" id="fileInput" name="attachment" accept="image/*,.pdf" style="display: none;">
                    <button type="button" class="btn-901 btn-secondary-901"
                        onclick="document.getElementById('fileInput').click()">
                        📎
                    </button>
                    <textarea id="messageInput" name="message" rows="2" placeholder="Ketik pesan..."></textarea>
                    <button type="submit" class="btn-901 btn-primary-901">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ============================================
        // FULL JAVASCRIPT FOR: customer/chat/show.blade.php
        // ============================================

        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        let lastMessageTimestamp = null;
        let pollingInterval = null;
        let isPolling = false;
        let displayedMessageIds = new Set();
        let consecutiveErrors = 0;
        const MAX_ERRORS = 3;

        // Get last message timestamp
        function getLastMessageTimestamp() {
            const messages = document.querySelectorAll('.message-901');
            if (messages.length > 0) {
                const lastMessage = messages[messages.length - 1];
                const timeElement = lastMessage.querySelector('.message-time-901');
                if (timeElement && timeElement.dataset.timestamp) {
                    return timeElement.dataset.timestamp;
                }
            }
            return new Date().toISOString();
        }

        // Scroll to bottom
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Initial setup
        scrollToBottom();
        lastMessageTimestamp = getLastMessageTimestamp();

        // Track existing messages on page load
        document.querySelectorAll('.message-901').forEach(msg => {
            const timeElement = msg.querySelector('.message-time-901[data-message-id]');
            if (timeElement && timeElement.dataset.messageId) {
                displayedMessageIds.add(parseInt(timeElement.dataset.messageId));
            }
        });

        // Show file preview
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                fileName.textContent = file.name;

                // Show image preview if it's an image
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewImg.style.display = 'block';
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    // For non-image files, just show the file name
                    previewImg.style.display = 'none';
                    imagePreview.style.display = 'block';
                }
            } else {
                fileName.textContent = '';
                imagePreview.style.display = 'none';
            }
        });

        // Remove preview function
        function removePreview() {
            fileInput.value = '';
            fileName.textContent = '';
            imagePreview.style.display = 'none';
            previewImg.style.display = 'block';
        }

        // Enhanced polling with error handling
        async function pollNewMessages() {
            if (isPolling) return;
            isPolling = true;

            try {
                const url =
                    `${window.location.pathname}/new-messages?after=${encodeURIComponent(lastMessageTimestamp)}`;
                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.messages.length > 0) {
                    // Append new messages
                    data.messages.forEach(msg => {
                        appendMessage(msg);
                    });

                    // Update read status
                    data.messages.forEach(msg => {
                        if (msg.is_me && msg.is_read) {
                            updateReadStatus(msg.id, msg.is_read);
                        }
                    });

                    // Update last timestamp
                    lastMessageTimestamp = data.messages[data.messages.length - 1].created_at;

                    // Scroll to bottom
                    scrollToBottom();

                    // Play sound (optional)
                    playNotificationSound();
                }

                // Reset error counter on success
                consecutiveErrors = 0;
                hideErrorBanner();

            } catch (error) {
                console.error('Polling error:', error);

                // Track consecutive errors
                consecutiveErrors++;

                if (consecutiveErrors >= MAX_ERRORS) {
                    console.error('Max errors reached, showing reconnect banner');
                    showErrorBanner();
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            } finally {
                isPolling = false;
            }
        }

        // Update read status for existing messages
        function updateReadStatus(messageId, isRead) {
            const timeElement = document.querySelector(`.message-time-901[data-message-id="${messageId}"]`);
            if (!timeElement) return;

            const statusElement = timeElement.querySelector('.message-status-901');
            if (!statusElement) return;

            timeElement.setAttribute('data-is-read', isRead ? 'true' : 'false');

            if (isRead) {
                statusElement.classList.remove('sent');
                statusElement.classList.add('read');
                statusElement.setAttribute('data-status', 'read');
                statusElement.querySelector('.message-status-icon-901').textContent = '✓✓';
            }
        }

        // Append message with duplicate prevention
        function appendMessage(msg) {
            // Prevent duplicate
            if (displayedMessageIds.has(msg.id)) {
                console.log('Duplicate message detected, skipping:', msg.id);
                return;
            }
            displayedMessageIds.add(msg.id);

            const messageDiv = document.createElement('div');
            messageDiv.className = 'message-901' + (msg.is_me ? ' me' : '');

            let avatarHTML = '';
            if (msg.avatar_url) {
                avatarHTML =
                    `<img src="${msg.avatar_url}" alt="${msg.sender_name}" class="message-avatar-901" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">`;
            } else {
                avatarHTML = `<div class="message-avatar-901">${msg.sender_initial}</div>`;
            }

            let attachmentHTML = '';
            if (msg.has_attachment) {
                if (msg.is_image) {
                    attachmentHTML = `<img src="${msg.attachment_url}" class="message-image-901" alt="Image">`;
                } else {
                    attachmentHTML =
                        `<a href="${msg.attachment_url}" target="_blank" style="color: inherit;">📎 ${msg.attachment_name}</a>`;
                }
            }

            let messageText = msg.message ? `<div>${escapeHtml(msg.message)}</div>` : '';

            // Read status HTML
            const readStatusHTML = msg.is_me ? `
        <span class="message-status-901 ${msg.is_read ? 'read' : 'sent'}" data-status="${msg.is_read ? 'read' : 'sent'}">
            <span class="message-status-icon-901">${msg.is_read ? '✓✓' : '✓'}</span>
        </span>
    ` : '';

            messageDiv.innerHTML = `
        ${avatarHTML}
        <div class="message-content-901">
            <div class="message-bubble-901">
                ${attachmentHTML}
                ${messageText}
            </div>
            <div class="message-time-901" 
                 data-timestamp="${msg.created_at}" 
                 data-message-id="${msg.id}"
                 data-is-read="${msg.is_read ? 'true' : 'false'}">
                ${msg.formatted_time}${readStatusHTML}
            </div>
        </div>
    `;

            chatMessages.appendChild(messageDiv);
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Play notification sound (optional)
        function playNotificationSound() {
            const audio = new Audio(
                'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGGi68OScTgwOUKzn77tnHAU7k9r0y3oqBSh+zPLaizsKE12z6+yrWRQKRaDk9L9xJAYpcM7y1YU3CBdns+rjpFUPDVKv6fK6ZRsGOpXa9Mp7KgUofsvx2Ik7ChNds+rsrFoUCkSf5fS+cSQGKHDO8tWENwgXZ7Pq46RWDw1Rs+nzumUaBjqV2vTKeisFJ37L8tiKPAsTXbTs7axbFQpEn+X0vnEkBiZvzvLVhDcIF2ex6uOkVw8NUbPp8rplGgY6ldr0ynoxBSd+y/LYijwLE12z7O2sWxUKQ5/l9L5xJAYmb87y1YQ3CBdmserjpFcPDVCz6fK6ZRoGOpXa9Mp6MQUnfsvy2Is8CxNds+ztrVsVCkOf5fS+cSQGJm7O8dWENwgXZbHq46RXEQ1Qs+nyumUaBjqV2vTKejEFJ37L8tiKPAsTXbPs7axbFQpDn+X0vnEmBiVuz/HVhDgIF2Sx6uOkVxENUbPp8rpmGgY6ldr0ynoUBSh+y/LYijwLE12z7O2sWxUKQ5/l9L5xJgYmb87x1YQ3CBdkserjpFcRDVCz6fK6ZhoGOpXa9Mp6FAYnfsvy2Io8CxNds+ztrFsVCkKe5fS+cSYGJm7O8dWENwgXZLHq46RXEQ1Rs+nyumYaBjqU2vTKehQGKH7L8tiKPAsTXbPs7axbFQpCnuX0vnEmBiZuzvHVhDcIF2Sx6uOkVxENULPp8rpmGwU5ldr0ynoUBih+y/LYijwLE12z7O2tWxUKQp7l9L5xJgYmbs7x1YQ3CBdkr+rjpFcRDVCy6fK6ZhsFOZXa9Mp6FAYpfsvx2Io8CxNds+ztrVsVCkKe5fS+cSYGJm7O8dWENwgXZK/q46RXDw1QsunytmYbBTmV2vTKehQGKH7L8tiKPAsTXbPs7a1bFQpCnuX0vnEmBiZuzvHVhDYIF2Sv6uOkVw8NU7Lp8rZmGwU5ldr0ynoUBil+y/LYijwLE12z7O2tWxUKQp7l9L5xJgYmb87x1YQ3CBdkr+rjpFcPDVOy6fK2ZhsFOZXa9Mp6FAYofsvx2Io8CxNds+ztrVsVCkKe5fS+cSYGJm/O8dWENwgXZK/q46RXEQ1Rsunyuq'
            );
            audio.volume = 0.3;
            audio.play().catch(e => {});
        }

        // Error banner functions
        function showErrorBanner() {
            hideErrorBanner();

            const banner = document.createElement('div');
            banner.id = 'errorBanner';
            banner.className = 'alert-901 alert-error-901';
            banner.innerHTML = `
        <span>⚠️ Koneksi terputus.</span>
        <button type="button" class="btn-901 btn-secondary-901" onclick="reconnectChat()" style="padding: 8px 16px;">
            🔄 Coba Lagi
        </button>
    `;

            const container = document.querySelector('.chat-container-901');
            const firstChild = container.querySelector('.back-btn-901').nextElementSibling;
            container.insertBefore(banner, firstChild);
        }

        function hideErrorBanner() {
            const banner = document.getElementById('errorBanner');
            if (banner) {
                banner.remove();
            }
        }

        function reconnectChat() {
            consecutiveErrors = 0;
            hideErrorBanner();

            if (!pollingInterval) {
                pollingInterval = setInterval(pollNewMessages, 3000);
                pollNewMessages();
            }
        }

        // Start polling
        pollingInterval = setInterval(pollNewMessages, 3000);

        // Stop polling when tab is not visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            } else {
                if (!pollingInterval) {
                    pollingInterval = setInterval(pollNewMessages, 3000);
                    pollNewMessages();
                }
            }
        });

        // Send message
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData();
            if (messageInput.value.trim()) {
                formData.append('message', messageInput.value.trim());
            }
            if (fileInput.files[0]) {
                formData.append('attachment', fileInput.files[0]);
            }

            if (!messageInput.value.trim() && !fileInput.files[0]) {
                alert('Tulis pesan atau pilih file');
                return;
            }

            try {
                const response = await fetch(`${window.location.pathname}/message`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    appendMessage(data.message);
                    lastMessageTimestamp = data.message.created_at;

                    messageInput.value = '';
                    fileInput.value = '';
                    fileName.textContent = '';
                    imagePreview.style.display = 'none';

                    scrollToBottom();
                } else {
                    alert(data.message || 'Gagal mengirim pesan');
                }
            } catch (error) {
                alert('Terjadi kesalahan');
            }
        });


        // ============================================
        // 🔥 VS Code Style Search Functionality
        // ============================================
        const searchInput = document.getElementById('searchMessages');
        const searchControls = document.getElementById('searchControls');
        const searchUp = document.getElementById('searchUp');
        const searchDown = document.getElementById('searchDown');
        const searchCount = document.getElementById('searchCount');
        const clearSearch = document.getElementById('clearSearch');

        let searchResults = [];
        let currentSearchIndex = -1;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                performSearch();
            });

            if (searchUp) searchUp.addEventListener('click', () => navigateSearch(-1));
            if (searchDown) searchDown.addEventListener('click', () => navigateSearch(1));
            if (clearSearch) {
                clearSearch.addEventListener('click', () => {
                    searchInput.value = '';
                    performSearch();
                });
            }

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (e.shiftKey) {
                        navigateSearch(-1);
                    } else {
                        navigateSearch(1);
                    }
                } else if (e.key === 'Escape') {
                    searchInput.value = '';
                    performSearch();
                }
            });
        }

        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const messages = chatMessages.querySelectorAll('.message-901');

            // Reset semua pesan - restore dari backup
            messages.forEach(msg => {
                msg.style.opacity = '1';
                msg.style.display = 'flex';

                const bubble = msg.querySelector('.message-bubble-901');
                if (bubble && bubble.hasAttribute('data-original-html')) {
                    bubble.innerHTML = bubble.getAttribute('data-original-html');
                    bubble.removeAttribute('data-original-html');
                }
            });

            searchResults = [];
            currentSearchIndex = -1;

            if (searchTerm === '') {
                if (searchControls) searchControls.style.display = 'none';
                return;
            }

            // Cari pesan yang match
            messages.forEach((msg) => {
                const textContent = msg.textContent.toLowerCase();

                if (textContent.includes(searchTerm)) {
                    searchResults.push(msg);
                    msg.style.opacity = '0.4';

                    // Backup original HTML sebelum highlight
                    const bubble = msg.querySelector('.message-bubble-901');
                    if (bubble && !bubble.hasAttribute('data-original-html')) {
                        bubble.setAttribute('data-original-html', bubble.innerHTML);
                    }

                    // Hanya highlight text di message div (skip images/attachments)
                    const messageTextDiv = bubble.querySelector('div:last-child');
                    if (messageTextDiv && !messageTextDiv.querySelector('img') && !messageTextDiv.querySelector('a')) {
                        const originalText = messageTextDiv.textContent;
                        const highlightedText = originalText.replace(
                            new RegExp(`(${escapeRegex(searchTerm)})`, 'gi'),
                            '<span class="search-highlight">$1</span>'
                        );
                        messageTextDiv.innerHTML = highlightedText;
                    }
                }
            });

            if (searchResults.length > 0) {
                if (searchControls) searchControls.style.display = 'flex';
                currentSearchIndex = 0;
                highlightCurrentResult();
                updateSearchCount();
            } else {
                if (searchControls) searchControls.style.display = 'none';
            }
        }

        function navigateSearch(direction) {
            if (searchResults.length === 0) return;

            if (currentSearchIndex >= 0 && searchResults[currentSearchIndex]) {
                searchResults[currentSearchIndex].style.opacity = '0.4';
            }

            currentSearchIndex += direction;
            if (currentSearchIndex < 0) currentSearchIndex = searchResults.length - 1;
            if (currentSearchIndex >= searchResults.length) currentSearchIndex = 0;

            highlightCurrentResult();
            updateSearchCount();
        }

        function highlightCurrentResult() {
            if (currentSearchIndex >= 0 && searchResults[currentSearchIndex]) {
                const current = searchResults[currentSearchIndex];
                current.style.opacity = '1';
                current.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }

        function updateSearchCount() {
            if (searchCount) {
                searchCount.textContent = `${currentSearchIndex + 1}/${searchResults.length}`;
            }
        }

        function escapeRegex(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        // Enter to send (Shift+Enter for new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        });
    </script>
</x-frontend-layout>