<div class="container mt-5">
    <h2 class="mb-4">Your Messages</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Message Navigation</h4>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" id="unread-tab" data-bs-toggle="tab" href="#unread">Unread Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="read-tab" data-bs-toggle="tab" href="#read">Read Messages</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="unread" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Unread Messages</h4>
                            <div class="list-group list-group-flush" id="unread-messages-list">
                                <?php
                                $hasUnread = false;
                                foreach ($messages as $message) :
                                    if (!$message['is_read']) :
                                        $hasUnread = true;
                                ?>
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start list-group-item-warning" data-message-id="<?= $message['id'] ?>">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">
                                                    Sender: <?= htmlspecialchars($message['sender_name'] ?? 'Unknown User') ?>
                                                </div>
                                                <p class="mb-1">
                                                    <?= htmlspecialchars($message['message']) ?>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    <?= date($tpl['date_format'] . ' H:i', $message['created_at']) ?>
                                                </small>
                                            </div>
                                            <button class="btn btn-sm btn-success mark-read" data-id="<?= $message['id'] ?>">
                                                <i class="mdi mdi-check"></i> Mark Read
                                            </button>
                                        </div>
                                <?php
                                    endif;
                                endforeach;
                                if (!$hasUnread) {
                                    echo '<div class="list-group-item">No unread messages.</div>';
                                }
                                ?>
                            </div>
                            <?php if ($hasUnread) : ?>
                                <div class="d-flex justify-content-end mt-3">
                                    <button id="markAllRead" class="btn btn-primary">
                                        <i class="mdi mdi-check-all"></i> Mark All as Read
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="read" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Read Messages</h4>
                            <div class="list-group list-group-flush" id="read-messages-list">
                                <?php
                                $hasRead = false;
                                foreach ($messages as $message) :
                                    if ($message['is_read']) :
                                        $hasRead = true;
                                ?>
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start bg-light" data-message-id="<?= $message['id'] ?>">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">
                                                    Sender: <?= htmlspecialchars($message['sender_name'] ?? 'Unknown User') ?>
                                                </div>
                                                <p class="mb-1">
                                                    <?= htmlspecialchars($message['message']) ?>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    <?= date($tpl['date_format'] . ' H:i', $message['created_at']) ?>
                                                </small>
                                            </div>
                                        </div>
                                <?php
                                    endif;
                                endforeach;
                                if (!$hasRead) {
                                    echo '<div class="list-group-item">No read messages.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const markAllReadButton = document.getElementById('markAllRead');
        const markReadButtons = document.querySelectorAll('.mark-read');
        const unreadMessagesList = document.getElementById('unread-messages-list');
        const readMessagesList = document.getElementById('read-messages-list');
        const unreadTabLink = document.getElementById('unread-tab');
        const readTabLink = document.getElementById('read-tab');


        markReadButtons.forEach(button => {
            button.addEventListener('click', function () {
                const messageId = this.dataset.id;
                markMessageAsRead(messageId);
            });
        });

        if (markAllReadButton) {
            markAllReadButton.addEventListener('click', function () {
                markAllMessagesAsRead();
            });
        }

        function markMessageAsRead(messageId) {
            fetch('<?= INSTALL_URL ?>?controller=Messages&action=markAsRead', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${messageId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const listItem = document.querySelector(`.list-group-item[data-message-id="${messageId}"]`);
                        if (listItem) {
                            // Remove from unread list
                            unreadMessagesList.removeChild(listItem);
                            //add to read list
                            readMessagesList.insertAdjacentHTML('beforeend', `<div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start bg-light" data-message-id="${messageId}">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold">
                                                                    ${listItem.querySelector('.fw-bold').textContent}
                                                                </div>
                                                                <p class="mb-1">
                                                                    ${listItem.querySelector('p').textContent}
                                                                </p>
                                                                <small class="text-muted">
                                                                <i class="mdi mdi-clock-outline"></i>
                                                                    ${listItem.querySelector('.text-muted').textContent}
                                                                </small>
                                                            </div>
                                                        </div>`);

                            // Check if there are any remaining unread messages
                            if (unreadMessagesList.children.length === 0) {
                                unreadMessagesList.innerHTML = '<div class="list-group-item">No unread messages.</div>';
                                if (markAllReadButton) {
                                    markAllReadButton.style.display = 'none';
                                }
                            }
                        }
                        // Update counters
                        updateMessageCounts();

                    } else {
                        alert('Could not mark message as read.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function markAllMessagesAsRead() {
            fetch('<?= INSTALL_URL ?>?controller=Messages&action=markAllRead', {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const unreadListItems = document.querySelectorAll('.list-group-item-warning');
                        unreadListItems.forEach(item => {
                            const messageId = item.dataset.messageId;
                            // Remove from unread list
                            unreadMessagesList.removeChild(item);
                            //add to read list
                            readMessagesList.insertAdjacentHTML('beforeend', `<div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start bg-light" data-message-id="${messageId}">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold">
                                                                    ${item.querySelector('.fw-bold').textContent}
                                                                </div>
                                                                <p class="mb-1">
                                                                    ${item.querySelector('p').textContent}
                                                                </p>
                                                                <small class="text-muted">
                                                                <i class="mdi mdi-clock-outline"></i>
                                                                    ${item.querySelector('.text-muted').textContent}
                                                                </small>
                                                            </div>
                                                        </div>`);
                        });
                        unreadMessagesList.innerHTML = '<div class="list-group-item">No unread messages.</div>';
                        if (markAllReadButton) {
                            markAllReadButton.style.display = 'none';
                        }
                        updateMessageCounts();
                    } else {
                        alert('Could not mark all messages as read.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function updateMessageCounts() {
            // Get the number of unread and read messages
            const unreadCount = unreadMessagesList.querySelectorAll('.list-group-item-warning').length;
            const readCount = readMessagesList.querySelectorAll('.list-group-item-action').length - (readMessagesList.querySelector('.list-group-item') ? 1 : 0) + 1;

            // Update the tab links with the counts
            unreadTabLink.textContent = `Unread Messages (${unreadCount})`;
            readTabLink.textContent = `Read Messages (${readCount})`;
        }
        updateMessageCounts();
    });
</script>
