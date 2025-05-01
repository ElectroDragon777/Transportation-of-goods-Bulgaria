<video autoplay muted loop id="myVideo">
    <source src="Extras\Dashboard\Messages\bgvideo.mp4" type="video/mp4">
    Your browser does not support HTML5 video.
</video>
<div class="container-fluid d-flex flex-column h-100">
    <h2 class="mb-4">Your Messages</h2>
    <div class="row d-flex">
        <div class="col-md-2 col-sm-12"> <!-- Added col-sm-12 for better responsiveness -->
            <div class="card mb-3"> <!-- Added margin-bottom for mobile view -->
                <div class="card-body nav-card">
                    <h4 class="card-title nav-title">Message Navigation</h4>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link active w-100 text-center text-md-start" id="unread-tab"
                                data-bs-toggle="tab" href="#unread">Unread
                                Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link w-100 text-center text-md-start" id="read-tab" data-bs-toggle="tab"
                                href="#read">Read Messages</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-10 col-sm-12"> <!-- Added col-sm-12 for better responsiveness -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="unread" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Unread Messages</h4>
                                <button id="markAllRead" class="btn btn-primary">
                                    <i class="mdi mdi-check-all"></i> <span class="mark-all-text">Mark All as
                                        Read</span>
                                </button>
                            </div>
                            <div class="list-group list-group-flush" id="unread-messages-list">
                                <?php
                                $hasUnread = false;
                                foreach ($messages as $message):
                                    if (!$message['is_read']):
                                        $hasUnread = true;
                                        ?>
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start list-group-item-warning"
                                            data-message-id="<?= $message['id'] ?>">
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
                                foreach ($messages as $message):
                                    if ($message['is_read']):
                                        $hasRead = true;
                                        ?>
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start bg-light"
                                            data-message-id="<?= $message['id'] ?>">
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

                            // Remove "No read messages" if it exists
                            const noReadMessages = readMessagesList.querySelector('.list-group-item');
                            if (noReadMessages && noReadMessages.textContent === 'No read messages.') {
                                readMessagesList.removeChild(noReadMessages);
                            }

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
                                    markAllReadButton.disabled = true; // Disable instead of hiding
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
                            markAllReadButton.disabled = true; // Disable instead of hiding
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
            let readCount = readMessagesList.querySelectorAll('.list-group-item-action').length;

            // Check if there is a "No read messages" item
            const noReadMessages = readMessagesList.querySelector('.list-group-item');
            if (noReadMessages && noReadMessages.textContent === 'No read messages.') {
                readCount = 0; // Reset readCount to 0 if there are no read messages
            }

            // Update the tab links with the counts
            unreadTabLink.textContent = `Unread Messages (${unreadCount})`;
            readTabLink.textContent = `Read Messages (${readCount})`;

            // Return the counts
            return { unreadCount, readCount };
        }

        // Call updateMessageCounts and check readCount
        const counts = updateMessageCounts();
        if (counts.readCount > 0) {
            const noReadMessagesText = readMessagesList.querySelector('.list-group-item');
            if (noReadMessagesText && noReadMessagesText.textContent === 'No read messages.') {
                readMessagesList.removeChild(noReadMessagesText);
            }
        }

        // Disable "Mark All as Read" button if there are no unread messages
        if (counts.unreadCount === 0 && markAllReadButton) {
            markAllReadButton.disabled = true;
        } else if (markAllReadButton) {
            markAllReadButton.disabled = false;
        }

        updateMessageCounts();
    });
</script>

<style>
    .container-fluid {
        min-height: 100%;
        display: flex;
    }

    .row {
        flex: 1;
        display: flex;
        min-height: 100%;
    }

    /* Improved responsive behavior */
    @media (max-width: 767.98px) {
        .container-fluid {
            overflow-y: auto;
            height: auto;
        }

        .row {
            flex-direction: column;
            overflow: visible;
        }

        .col-md-2,
        .col-md-10 {
            width: 100%;
            max-height: none;
            overflow: visible;
        }

        .card {
            margin-bottom: 15px;
        }
    }

    /* For desktop view */
    @media (min-width: 992px) {
        .col-md-2 {
            position: sticky;
            top: 0;
            height: 100%;
            overflow-y: auto;
        }

        .col-md-10 {
            display: flex;
            flex-direction: column;
            flex: 1;
            max-height: 700px;
            /* Set a max height for the main content area */
            /* Remove height restriction */
            overflow-y: auto;
            padding-right: 10px;
            padding-bottom: 30px;
            /* Add padding at bottom */
        }

        /* Navigation sidebar specific styling */
        .nav-title {
            text-align: left;
        }

        .nav-link {
            text-align: left;
        }
    }

    .card {
        flex: 0 1 auto;
    }

    .tab-content {
        flex: 1;
        width: 100%;
    }

    .list-group-item {
        width: 100%;
        padding: 20px;
        font-size: 16px;
    }

    /* Fixed scrollbar styling */
    .col-md-10::-webkit-scrollbar {
        width: 8px;
    }

    .col-md-10::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.5);
        border-radius: 6px;
    }

    .col-md-10::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Button styling */
    #markAllRead {
        white-space: nowrap;
    }

    /* Hide button text on small screens */
    @media (max-width: 575.98px) {
        .mark-all-text {
            display: none;
        }

        #markAllRead {
            padding: 0.375rem 0.5rem;
            min-width: 38px;
        }

        .card-title {
            font-size: 1.2rem;
        }
    }

    /* Modify layout for tablet/medium sizes specifically */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .row {
            flex-direction: column;
        }

        .col-md-2,
        .col-md-10 {
            width: 100%;
            max-width: 100%;
        }

        .nav-pills {
            display: flex;
            flex-direction: row;
        }

        .nav-item {
            margin-right: 10px;
            margin-bottom: 0;
        }

        #unread-tab,
        #read-tab {
            min-width: 180px;
            text-align: center;
        }
    }

    /* Navigation card and buttons */
    .nav-card {
        padding: 0.75rem;
    }

    .nav-title {
        text-align: center;
        margin-bottom: 1rem;
    }

    .nav-item {
        margin-bottom: 0.5rem;
        width: 100%;
    }

    .nav-link {
        white-space: normal;
        word-wrap: break-word;
        text-align: center;
        padding: 0.5rem;
        width: 100%;
        font-size: 0.95rem;
    }

    /* Medium screens specific adjustments */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .col-md-2 {
            width: 100%;
            margin-bottom: 1rem;
        }

        .nav-pills {
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 10px;
        }

        .nav-item {
            width: auto;
            flex: 1;
            max-width: 200px;
        }

        /* Make buttons display inline on medium screens */
        .nav-link {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    }
</style>

<style>
    /* Style the video: 100% width and height to cover the entire window */
    #myVideo {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
    }

    /* Add some content at the bottom of the video/page */
    .content {
        position: fixed;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        color: #f1f1f1;
        width: 100%;
        padding: 20px;
    }

    /* Style the button used to pause/play the video */
    #myBtn {
        width: 200px;
        font-size: 18px;
        padding: 10px;
        border: none;
        background: #000;
        color: #fff;
        cursor: pointer;
    }

    #myBtn:hover {
        background: #ddd;
        color: black;
    }
</style>