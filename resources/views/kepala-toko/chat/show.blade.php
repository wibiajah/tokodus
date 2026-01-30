<x-admin-layout title="Chat Room">

    <style>
        /* Read Receipt Styles */
        .message-status {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            font-size: 11px;
            margin-left: 5px;
            vertical-align: middle;
        }

        .message-status.sent {
            color: #999;
        }

        .message-status.read {
            color: #4CAF50;
        }

        .message-status-icon {
            font-size: 13px;
            line-height: 1;
        }

        .search-highlight {
            background: yellow;
            font-weight: bold;
            padding: 2px 0;
        }

        #searchControls {
            display: flex;
            white-space: nowrap;
        }

        /* ✅ FIX 1.3: Error banner styling */
        .alert-reconnect {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 350px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
    <div class="container-fluid">
        <a href="{{ route('kepala-toko.chat.index') }}" class="btn btn-secondary mb-3">
            ← Kembali ke Inbox
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow">
            <!-- Header -->
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    @if ($chatRoom->type === 'order_chat')
                         Order #{{ $chatRoom->order->order_number }}
                    @else
                         General Chat
                    @endif
                </h5>
                <small>
                    @php
                        $customer = $chatRoom->participants->where('role', 'customer')->first();
                    @endphp
                    Customer: {{ $customer->participantable->full_name ?? 'Unknown' }}
                </small>
            </div>

            <!-- Search Box -->
            <div class="card-body border-bottom" style="padding: 12px 20px;">
                <div class="d-flex gap-2 align-items-center">
                    <input type="text" id="searchMessages" class="form-control" placeholder="Cari pesan...">
                    <div id="searchControls" style="display: none; gap: 5px;">
                        <button type="button" id="searchUp" class="btn btn-sm btn-outline-secondary">▲</button>
                        <button type="button" id="searchDown" class="btn btn-sm btn-outline-secondary">▼</button>
                        <span id="searchCount" class="text-muted small"></span>
                        <button type="button" id="clearSearch" class="btn btn-sm btn-outline-danger">✕</button>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="card-body" style="height: 500px; overflow-y: auto; background: #f8f9fc;" id="chatMessages">
                @forelse($messages as $message)
                    @php
                        $isMe = $message->sender_type === 'App\Models\User' && $message->sender_id == auth()->id();
                    @endphp

                    <div class="d-flex mb-3 {{ $isMe ? 'flex-row-reverse' : '' }}">
                        <div style="max-width: 70%;">
                            <div class="p-3 rounded {{ $isMe ? 'bg-primary text-white' : 'bg-white' }}"
                                style="box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                                @if ($message->hasAttachment())
                                    @if ($message->isImage())
                                        <a href="{{ asset('storage/' . $message->attachment_path) }}"
                                            data-lightbox="chat-images"
                                            data-title="{{ $isMe ? 'You' : 'Customer' }} - {{ $message->formatted_time }}">
                                            <img src="{{ asset('storage/' . $message->attachment_path) }}"
                                                style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 8px; cursor: pointer;"
                                                alt="Image">
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $message->attachment_path) }}" target="_blank"
                                            class="{{ $isMe ? 'text-white' : '' }}">
                                            📎 {{ basename($message->attachment_path) }}
                                        </a>
                                    @endif
                                @endif
                                @if ($message->message)
                                    <div>{{ $message->message }}</div>
                                @endif
                            </div>
                            <small class="text-muted d-block mt-1 {{ $isMe ? 'text-right' : '' }}"
                                data-timestamp="{{ $message->created_at->toIso8601String() }}"
                                data-message-id="{{ $message->id }}"
                                data-is-read="{{ $message->is_read ? 'true' : 'false' }}">
                                {{ $message->formatted_time }}
                                @if ($isMe)
                                    <span class="message-status {{ $message->is_read ? 'read' : 'sent' }}"
                                        data-status="{{ $message->is_read ? 'read' : 'sent' }}">
                                        <span class="message-status-icon">
                                            {{ $message->is_read ? '✓✓' : '✓' }}
                                        </span>
                                    </span>
                                @endif
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        Belum ada pesan
                    </div>
                @endforelse
            </div>
            
            <!-- Image Preview - Pindah ke sini, SEBELUM card-footer -->
            <div id="imagePreview"
                style="padding: 10px 15px; background: #f8f9fa; border-top: 1px solid #dee2e6; display: none;">
                <div class="d-flex align-items-center gap-2">
                    <img id="previewImg" src=""
                        style="max-width: 80px; max-height: 80px; border-radius: 5px; border: 1px solid #ddd; object-fit: cover;">
                    <span id="fileName" class="text-muted small flex-grow-1"></span>
                    <button type="button" onclick="removePreview()" class="btn btn-sm btn-danger">✕</button>
                </div>
            </div>
            
            <!-- Input -->
            <div class="card-footer">
                <form id="chatForm" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="file" id="fileInput" name="attachment" accept="image/*,.pdf"
                            style="display: none;">
                        <button type="button" class="btn btn-secondary"
                            onclick="document.getElementById('fileInput').click()">
                            📎
                        </button>
                        <textarea id="messageInput" name="message" class="form-control" rows="2" placeholder="Ketik pesan..."></textarea>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // FULL JAVASCRIPT FOR: kepala-toko/chat/show.blade.php
        // ============================================

        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const fileInput = document.getElementById('fileInput');
        const fileName = document.getElementById('fileName');

        let lastMessageTimestamp = null;
        let pollingInterval = null;
        let isPolling = false;
        let isSending = false;
        let displayedMessageIds = new Set();
        let consecutiveErrors = 0;
        const MAX_ERRORS = 3;

        // Get last message timestamp
        function getLastMessageTimestamp() {
            const timeElements = document.querySelectorAll('small[data-timestamp]');
            if (timeElements.length > 0) {
                const lastTimeElement = timeElements[timeElements.length - 1];
                return lastTimeElement.dataset.timestamp;
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
        document.querySelectorAll('small[data-message-id]').forEach(element => {
            if (element.dataset.messageId) {
                displayedMessageIds.add(parseInt(element.dataset.messageId));
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
                        document.getElementById('previewImg').src = e.target.result;
                        document.getElementById('imagePreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    // For non-image files, just show the file name
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('previewImg').style.display = 'none';
                }
            } else {
                fileName.textContent = '';
                document.getElementById('imagePreview').style.display = 'none';
                document.getElementById('previewImg').style.display = 'block';
            }
        });

        // Remove preview function
        function removePreview() {
            fileInput.value = '';
            fileName.textContent = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('previewImg').style.display = 'block';
        }

        // Enhanced polling with error handling
        async function pollNewMessages() {
            if (isPolling) return;
            isPolling = true;

            try {
                const url =
                    `${window.location.pathname}/new-messages?after=${encodeURIComponent(lastMessageTimestamp)}`;
                const response = await fetch(url);

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                if (data.success && data.messages && data.messages.length > 0) {
                    // Remove empty state if exists
                    const emptyDiv = document.querySelector('.text-center.text-muted.py-5');
                    if (emptyDiv) {
                        emptyDiv.remove();
                    }

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
            const timeElement = document.querySelector(`small[data-message-id="${messageId}"]`);
            if (!timeElement) return;

            const statusElement = timeElement.querySelector('.message-status');
            if (!statusElement) return;

            timeElement.setAttribute('data-is-read', isRead ? 'true' : 'false');

            if (isRead) {
                statusElement.classList.remove('sent');
                statusElement.classList.add('read');
                statusElement.setAttribute('data-status', 'read');
                statusElement.querySelector('.message-status-icon').textContent = '✓✓';
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

            const messageWrapper = document.createElement('div');
            messageWrapper.className = 'd-flex mb-3' + (msg.is_me ? ' flex-row-reverse' : '');

            let attachmentHTML = '';
            if (msg.has_attachment) {
                if (msg.is_image) {
                    attachmentHTML =
                        `<a href="${msg.attachment_url}" data-lightbox="chat-images"><img src="${msg.attachment_url}" style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 8px; cursor: pointer;" alt="Image"></a>`;
                } else {
                    const textColor = msg.is_me ? 'text-white' : '';
                    attachmentHTML =
                        `<a href="${msg.attachment_url}" target="_blank" class="${textColor}">📎 ${msg.attachment_name}</a>`;
                }
            }

            let messageText = msg.message ? `<div>${escapeHtml(msg.message)}</div>` : '';

            const bubbleClass = msg.is_me ? 'bg-primary text-white' : 'bg-white';
            const timeClass = msg.is_me ? 'text-right' : '';

            // Read status HTML
            const readStatusHTML = msg.is_me ? `
        <span class="message-status ${msg.is_read ? 'read' : 'sent'}" data-status="${msg.is_read ? 'read' : 'sent'}">
            <span class="message-status-icon">${msg.is_read ? '✓✓' : '✓'}</span>
        </span>
    ` : '';

            messageWrapper.innerHTML = `
        <div style="max-width: 70%;">
            <div class="p-3 rounded ${bubbleClass}" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                ${attachmentHTML}
                ${messageText}
            </div>
            <small class="text-muted d-block mt-1 ${timeClass}" 
                   data-timestamp="${msg.created_at}" 
                   data-message-id="${msg.id}"
                   data-is-read="${msg.is_read ? 'true' : 'false'}">
                ${msg.formatted_time}${readStatusHTML}
            </small>
        </div>
    `;

            chatMessages.appendChild(messageWrapper);
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
            banner.className = 'alert alert-warning alert-reconnect';
            banner.innerHTML = `
        <span><strong>⚠️ Koneksi Terputus</strong><br><small>Pesan baru tidak akan muncul</small></span>
        <button type="button" class="btn btn-sm btn-primary" onclick="reconnectChat()">
            🔄 Sambungkan Ulang
        </button>
    `;

            document.body.appendChild(banner);
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

        // Start polling every 3 seconds
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

            if (isSending) return;

            const messageText = messageInput.value.trim();
            const file = fileInput.files[0];

            if (!messageText && !file) {
                alert('Tulis pesan atau pilih file');
                return;
            }

            isSending = true;

            const formData = new FormData();
            if (messageText) formData.append('message', messageText);
            if (file) formData.append('attachment', file);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                    document.querySelector('input[name="_token"]')?.value || '';

                const response = await fetch(`${window.location.pathname}/message`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    const emptyDiv = document.querySelector('.text-center.text-muted.py-5');
                    if (emptyDiv) {
                        emptyDiv.remove();
                    }

                    appendMessage(data.message);
                    lastMessageTimestamp = data.message.created_at;

                    messageInput.value = '';
                    fileInput.value = '';
                    fileName.textContent = '';
                    document.getElementById('imagePreview').style.display = 'none';

                    scrollToBottom();
                } else {
                    alert(data.message || 'Gagal mengirim pesan');
                }
            } catch (error) {
                console.error('Send error:', error);
                alert('Terjadi kesalahan saat mengirim pesan');
            } finally {
                isSending = false;
            }
        });


        // ============================================
        // 🔥 VS Code Style Search Functionality - FIXED
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
            const messages = chatMessages.querySelectorAll('.d-flex.mb-3');

            // Reset semua pesan - restore dari backup
            messages.forEach(msg => {
                msg.style.opacity = '1';
                msg.style.display = 'flex';
                
                const bubble = msg.querySelector('.p-3');
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
                    const bubble = msg.querySelector('.p-3');
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
</x-admin-layout>