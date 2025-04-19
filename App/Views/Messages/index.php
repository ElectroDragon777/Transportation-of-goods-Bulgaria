<div class="container mt-5">
    <h2 class="mb-4">Your Messages</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <button id="markAllRead" class="btn btn-primary">
            <i class="mdi mdi-check-all"></i> Mark All as Read
        </button>
    </div>

    <?php if (empty($messages)): ?>
        <div class="alert alert-info" role="alert">
            <i class="mdi mdi-information"></i> No messages found.
        </div>
    <?php else: ?>
        <div class="card">
            <div class="list-group list-group-flush">
                <?php foreach ($messages as $message): ?>
                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start
                        <?= $message['is_read'] ? 'bg-light' : 'list-group-item-warning' ?>"
                        data-message-id="<?= $message['id'] ?>">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">
                                Sender: <?= htmlspecialchars($message['sender_id']) ?>
                            </div>
                            <p class="mb-1">
                                <?= htmlspecialchars($message['message']) ?>
                            </p>
                            <small class="text-muted">
                                <i class="mdi mdi-clock-outline"></i>
                                <?= date($tpl['date_format'] . ' H:i', strtotime($message['created_at'])) ?>
                            </small>
                        </div>
                        <?php if (!$message['is_read']): ?>
                            <button class="btn btn-sm btn-success mark-read" data-id="<?= $message['id'] ?>">
                                <i class="mdi mdi-check"></i> Mark Read
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const markAllReadButton = document.getElementById('markAllRead');
        const markReadButtons = document.querySelectorAll('.mark-read');

        markReadButtons.forEach(button => {
            button.addEventListener('click', function () {
                const messageId = this.dataset.id;
                markMessageAsRead(messageId);
            });
        });

        markAllReadButton.addEventListener('click', function () {
            markAllMessagesAsRead();
        });

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
                            listItem.classList.remove('list-group-item-warning');
                            listItem.classList.add('bg-light');
                            const readButton = listItem.querySelector('.mark-read');
                            if (readButton) {
                                readButton.remove();
                            }
                        }
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
                        const listItems = document.querySelectorAll('.list-group-item-warning');
                        listItems.forEach(item => {
                            item.classList.remove('list-group-item-warning');
                            item.classList.add('bg-light');
                            const readButton = item.querySelector('.mark-read');
                            if (readButton) {
                                readButton.remove();
                            }
                        });
                    } else {
                        alert('Could not mark all messages as read.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });
</script>