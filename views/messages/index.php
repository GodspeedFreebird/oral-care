<?php
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$role = $_SESSION['role'];
$base_url = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if (substr($base_url, -1) !== '/') $base_url .= '/';

if ($role === 'patient') {
    $current_page = 'messages';
    require_once BASE_PATH . '/views/layouts/patient_sidebar.php';
    ?>
    <!-- Patient Header Card -->
    <div class="patient-header-card d-flex align-items-center mb-4 p-4 bg-white rounded-4 shadow-sm border border-light">
        <div class="profile-avatar me-4 fs-3 fw-bold text-secondary text-uppercase overflow-hidden rounded-circle d-flex align-items-center justify-content-center bg-light" style="width: 80px; height: 80px;">
            <?php if (!empty($patient['profile_picture'])): ?>
                <img src="<?= htmlspecialchars($patient['profile_picture']) ?>" alt="Avatar" class="w-100 h-100" style="object-fit: cover;">
            <?php else: ?>
                <?= substr($patient['full_name'] ?? 'P', 0, 1) ?>
            <?php endif; ?>
        </div>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center mb-2">
                <h4 class="mb-0 fw-bold me-3"><?= htmlspecialchars($patient['full_name'] ?? 'Patient') ?></h4>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill small">Active</span>
            </div>
            <div class="text-muted small d-flex gap-4">
                <span><i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($patient['email'] ?? '') ?></span>
                <span><i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($patient['phone'] ?? '') ?></span>
            </div>
        </div>
    </div>
    <!-- Tabs -->
    <ul class="nav custom-tabs mb-4">
        <li class="nav-item"><a class="nav-link" href="patient/dashboard">Overview</a></li>
        <li class="nav-item"><a class="nav-link" href="patient/appointments">Appointments</a></li>
        <li class="nav-item"><a class="nav-link" href="patient/records">Medical History</a></li>
        <li class="nav-item"><a class="nav-link active bg-light text-dark fw-bold border-0 rounded-top" href="messages">Messages</a></li>
        <li class="nav-item"><a class="nav-link" href="patient/notifications">Notification History</a></li>
    </ul>
    <?php
} elseif ($role === 'practitioner') {
    $current_page = 'messages';
    require_once BASE_PATH . '/views/layouts/header.php';
    echo '<div class="container-fluid"><div class="row">';
    require_once BASE_PATH . '/views/layouts/practitioner_sidebar.php';
    echo '<div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4 main-content">';
} elseif ($role === 'admin') {
    $current_page = 'messages';
    require_once BASE_PATH . '/views/admin/dashboard.php';
    echo '<main class="main-content" style="margin-left: 260px; padding: 2rem;"><div class="container-fluid py-4">';
} elseif ($role === 'receptionist') {
    $page_title = 'Messages';
    require_once BASE_PATH . '/views/layouts/reception_new_top.php';
    echo '<div class="container-fluid py-4">';
} else {
    echo '<!DOCTYPE html><html lang="en"><head><title>Messages</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"></head><body>';
}
?>

<style>

        

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Sidebar */
        
        
        
        .sidebar-menu {
            list-style: none;
            padding: 0 1rem;
            margin: 0;
            flex: 1;
            overflow-y: auto;
        }
        .sidebar-item {
            margin-bottom: 0.25rem;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: #6b7280;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-link:hover { color: #111827; background-color: #f3f4f6; }
        .sidebar-link.active {
            background-color: #ccfbf1;
            color: #0f766e;
            font-weight: 600;
        }
        .sidebar-link i { font-size: 1.25rem; }
        
        /* Unlock Card */
        .unlock-card {
            margin: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #1e3a8a 0%, #172554 100%);
            border-radius: 16px;
            color: white;
            flex-shrink: 0;
        }
        .unlock-card h6 { font-weight: 700; margin-bottom: 0.5rem; }
        .unlock-card p { font-size: 0.75rem; opacity: 0.8; margin-bottom: 1rem; }
        .unlock-card .btn-light {
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.4rem 1rem;
        }

        /* ------------------ PATIENT SIDEBAR STYLES ------------------ */
        
        
        /* ------------------ MESSAGES UI ------------------ */
        
        
        

        /* Contacts Panel */
        .contacts-panel {
            width: 350px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }
        
        .contacts-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .search-box {
            position: relative;
        }
        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        .search-box input {
            padding-left: 2.5rem;
            border-radius: 2rem;
            background-color: #f3f4f6;
            border: none;
        }
        .search-box input:focus {
            background-color: #ffffff;
            border-color: #14b8a6;
            box-shadow: 0 0 0 0.25rem rgba(20, 184, 166, 0.25);
        }

        .contacts-list {
            flex-grow: 1;
            overflow-y: auto;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: all 0.2s;
        }
        .contact-item:hover, .contact-item.active {
            background-color: #f9fafb;
        }
        
        .contact-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #14b8a6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.25rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .contact-details {
            flex-grow: 1;
            min-width: 0;
        }
        
        .contact-name {
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .contact-preview {
            font-size: 0.875rem;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .contact-meta {
            text-align: right;
            flex-shrink: 0;
            margin-left: 0.5rem;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
        }
        
        .role-badge {
            font-size: 0.7rem;
            padding: 0.15rem 0.5rem;
            border-radius: 1rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .role-patient { background-color: #e0f2fe; color: #0284c7; }
        .role-doctor { background-color: #fee2e2; color: #ef4444; }
        .role-admin { background-color: #f3e8ff; color: #9333ea; }
        .role-receptionist { background-color: #fef3c7; color: #d97706; }

        .unread-badge {
            background-color: #ef4444;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            min-width: 1.5rem;
            height: 1.5rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 0.4rem;
        }

        /* Chat Panel */
        .chat-panel {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background-color: #f9fafb;
        }
        
        .chat-header {
            padding: 1.5rem;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
        }
        
        .chat-header-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: #14b8a6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.25rem;
            margin-right: 1rem;
        }
        
        .chat-messages {
            flex-grow: 1;
            padding: 2rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            position: relative;
            line-height: 1.5;
        }
        
        .message-received {
            align-self: flex-start;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-bottom-left-radius: 0.25rem;
            color: #111827;
        }
        
        .message-sent {
            align-self: flex-end;
            background-color: #ccfbf1;
            color: #0f766e;
            border-bottom-right-radius: 0.25rem;
        }
        
        .message-time {
            font-size: 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.25rem;
            opacity: 0.8;
        }
        
        .chat-input-area {
            padding: 1.5rem;
            background: #ffffff;
            border-top: 1px solid #e5e7eb;
        }
        
        .chat-input-wrapper {
            display: flex;
            gap: 1rem;
            align-items: center;
            background: #f3f4f6;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
        }
        
        .chat-input-wrapper input {
            flex-grow: 1;
            border: none;
            background: transparent;
            padding: 0.5rem;
            outline: none;
        }
        
        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            color: #6b7280;
            transition: all 0.2s;
        }
        .btn-icon:hover {
            background-color: #e5e7eb;
            color: #111827;
        }
        .btn-send {
            background-color: #14b8a6;
            color: white;
        }
        .btn-send:hover {
            background-color: #0d9488;
            color: white;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #9ca3af;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d1d5db;
        }
    
.chat-container-wrap {
    display: flex;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    overflow: hidden;
    height: 70vh;
    min-height: 500px;
    border: 1px solid #e5e7eb;
}
</style>

<div class="chat-container-wrap">
<!-- Contacts Panel -->
        <div class="contacts-panel">
            <div class="contacts-header">
                <h4 class="fw-bold mb-4">Messages</h4>
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search name, chat, etc">
                </div>
            </div>
            
            <ul class="contacts-list" id="contactsList">
                <?php foreach ($contacts as $contact): ?>
                    <li class="contact-item" data-id="<?php echo $contact['user_id']; ?>" onclick="loadChat(this, <?php echo $contact['user_id']; ?>)">
                        <div class="contact-avatar">
                            <?php echo strtoupper(substr($contact['full_name'], 0, 1)); ?>
                        </div>
                        <div class="contact-details">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="contact-name"><?php echo htmlspecialchars($contact['full_name']); ?></div>
                            </div>
                            <div class="contact-preview"><?php echo htmlspecialchars($contact['last_message'] ?: 'Start a conversation'); ?></div>
                        </div>
                        <div class="contact-meta">
                            <?php 
                                $roleClass = 'role-patient';
                                if ($contact['role'] === 'practitioner') $roleClass = 'role-doctor';
                                if ($contact['role'] === 'admin') $roleClass = 'role-admin';
                                if ($contact['role'] === 'receptionist') $roleClass = 'role-receptionist';
                            ?>
                            <span class="role-badge <?php echo $roleClass; ?>"><?php echo ucfirst($contact['role'] === 'practitioner' ? 'Doctor' : $contact['role']); ?></span>
                            <?php if ($contact['unread_count'] > 0): ?>
                                <span class="unread-badge"><?php echo $contact['unread_count']; ?></span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="p-3 border-top text-center" style="background: #f9fafb;">
                <button class="btn btn-primary w-100 fw-bold rounded-pill" onclick="showNewMessageModal()">
                    <i class="bi bi-pencil-square me-2"></i> New Message
                </button>
            </div>
        </div>
        
        <!-- Chat Panel -->
        <div class="chat-panel" id="chatPanel">
            <div class="empty-state">
                <i class="bi bi-chat-dots"></i>
                <h5>Select a conversation</h5>
                <p>Choose a contact from the list to start messaging.</p>
            </div>
        </div>

    <!-- CSRF Token -->
<input type="hidden" id="csrfToken" value="<?php echo $_SESSION['csrf_token']; ?>">

<!-- New Message Modal -->
<div class="modal fade" id="newMessageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold">New Message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="search-box mb-3">
            <i class="bi bi-search"></i>
            <input type="text" id="userSearchInput" class="form-control" placeholder="Search users...">
        </div>
        <div class="list-group list-group-flush" id="userSearchResults" style="max-height: 250px; overflow-y: auto;">
            <!-- Search results populate here -->
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let currentContactId = null;
    let chatPollInterval = null;

    function loadChat(element, contactId) {
        // Update active class
        document.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active'));
        if (element) {
            element.classList.add('active');
            // Remove unread badge
            const badge = element.querySelector('.unread-badge');
            if (badge) badge.remove();
        }

        if (currentContactId !== contactId) {
            const input = document.getElementById('messageInput');
            if (input) input.value = '';
        }

        currentContactId = contactId;
        fetchChat();
        
        // Start polling for new messages every 5s
        if (chatPollInterval) clearInterval(chatPollInterval);
        chatPollInterval = setInterval(fetchChat, 5000);
    }

    function fetchChat() {
        if (!currentContactId) return;

        fetch(`messages/load?contact_id=${currentContactId}`)
            .then(res => res.json())
            .then(data => {
                if(data.error) {
                    alert(data.error);
                    return;
                }
                renderChat(data.contact, data.messages);
            })
            .catch(err => console.error('Error fetching chat:', err));
    }

    function renderChat(contact, messages) {
        const chatPanel = document.getElementById('chatPanel');
        
        // Build the skeleton if it doesn't exist yet (e.g. replacing the empty state)
        if (!document.getElementById('chatMessages')) {
            chatPanel.innerHTML = `
                <div class="chat-header" id="chatHeader"></div>
                <div class="chat-messages" id="chatMessages"></div>
                <div class="chat-input-area">
                    <div class="chat-input-wrapper">
                        <button class="btn-icon"><i class="bi bi-paperclip"></i></button>
                        <input type="text" id="messageInput" placeholder="Type a message..." onkeypress="handleKeyPress(event)">
                        <button class="btn-icon btn-send" onclick="sendMessage()"><i class="bi bi-send-fill"></i></button>
                    </div>
                </div>
            `;
        }

        // Update Header
        document.getElementById('chatHeader').innerHTML = `
            <div class="chat-header-avatar">${contact.full_name.charAt(0).toUpperCase()}</div>
            <div>
                <h5 class="fw-bold mb-0">${contact.full_name}</h5>
                <div class="text-muted small">${contact.role.charAt(0).toUpperCase() + contact.role.slice(1)}</div>
            </div>
        `;

        // Update Messages
        let messagesHtml = '';
        messages.forEach(msg => {
            const isSent = (msg.sender_id != currentContactId);
            const timeStr = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            
            if (isSent) {
                messagesHtml += `
                    <div class="message-bubble message-sent">
                        ${escapeHtml(msg.content)}
                        <div class="message-time">
                            ${timeStr} 
                            <i class="bi bi-check2-all text-primary"></i>
                        </div>
                    </div>
                `;
            } else {
                messagesHtml += `
                    <div class="message-bubble message-received">
                        ${escapeHtml(msg.content)}
                        <div class="message-time">${timeStr}</div>
                    </div>
                `;
            }
        });

        const msgContainer = document.getElementById('chatMessages');
        const isScrolledToBottom = msgContainer ? (msgContainer.scrollHeight - msgContainer.scrollTop <= msgContainer.clientHeight + 50) : true;

        msgContainer.innerHTML = messagesHtml;

        if (isScrolledToBottom) {
            msgContainer.scrollTop = msgContainer.scrollHeight;
        }
    }

    function handleKeyPress(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    }

    function sendMessage() {
        if (!currentContactId) return;
        const input = document.getElementById('messageInput');
        const content = input.value.trim();
        if (!content) return;

        input.value = ''; // clear immediately

        const csrfToken = document.getElementById('csrfToken').value;

        fetch('messages/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({
                receiver_id: currentContactId,
                content: content
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                fetchChat(); // Reload to show new message
            }
        });
    }

    // New Message Modal Logic
    const newMessageModal = new bootstrap.Modal(document.getElementById('newMessageModal'));
    
    function showNewMessageModal() {
        newMessageModal.show();
        searchUsers('');
    }

    document.getElementById('userSearchInput').addEventListener('input', (e) => {
        searchUsers(e.target.value);
    });

    function searchUsers(query) {
        fetch(`messages/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(users => {
                const resultsContainer = document.getElementById('userSearchResults');
                resultsContainer.innerHTML = '';
                users.forEach(u => {
                    resultsContainer.innerHTML += `
                        <button type="button" class="list-group-item list-group-item-action d-flex align-items-center" onclick="startChatWith(${u.user_id})">
                            <div class="contact-avatar mx-0 me-3" style="width:40px; height:40px; font-size:1rem;">
                                ${u.full_name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">${u.full_name}</h6>
                                <small class="text-muted">${u.role}</small>
                            </div>
                        </button>
                    `;
                });
            });
    }

    function startChatWith(userId) {
        newMessageModal.hide();
        // Just reload the page to have them show up in contacts list
        // Actually, we can fetch their chat by passing the ID to our load function, but they aren't in the left side list yet
        // So reloading or navigating to a specific contact state is best.
        
        // A quick hack: send an empty message or just insert them into the list if they aren't there.
        // Easiest is to send a hidden init message or just call loadChat directly and manually append.
        
        // Let's just create an empty message to initialize the conversation
        const csrfToken = document.getElementById('csrfToken').value;
        fetch('messages/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            },
            body: JSON.stringify({
                receiver_id: userId,
                content: 'Hello!' // Send a greeting to start it
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    }

    function escapeHtml(unsafe) {
        return unsafe
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }
</script>
</body>
</html>

</div>

<?php
if ($role === 'patient') {
    require_once BASE_PATH . '/views/layouts/patient_footer.php';
} elseif ($role === 'practitioner') {
    echo '</div></div></div>';
} elseif ($role === 'admin') {
    echo '</div></main></body></html>';
} elseif ($role === 'receptionist') {
    echo '</div>';
    require_once BASE_PATH . '/views/layouts/reception_new_bottom.php';
} else {
    echo '</body></html>';
}
?>
