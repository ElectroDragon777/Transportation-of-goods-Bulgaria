<video autoplay muted loop id="myVideo">
    <source src="Extras\Dashboard\Messages\bgvideo.mp4" type="video/mp4">
    Your browser does not support HTML5 video.
</video>
<div class="container-fluid d-flex flex-column h-100">
    <h2 class="mb-4">Your Notifications</h2>
    <div class="row d-flex">
        <div class="col-md-2 col-sm-12">
            <div class="card mb-3">
                <div class="card-body nav-card">
                    <h4 class="card-title nav-title">Notification Navigation</h4>
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            <a class="nav-link active w-100 text-center text-md-start" id="unseen-tab"
                                data-bs-toggle="tab" href="#unseen">Unseen
                                Notifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link w-100 text-center text-md-start" id="seen-tab" data-bs-toggle="tab"
                                href="#seen">Seen Notifications</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-10 col-sm-12">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="unseen" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Unseen Notifications</h4>
                                <button id="markAllSeen" class="btn btn-primary">
                                    <i class="mdi mdi-check-all"></i> <span class="mark-all-text">Mark All as
                                        Seen</span>
                                </button>
                            </div>
                            <div class="list-group list-group-flush" id="unseen-notifications-list">
                                <?php
                                $hasUnseen = false;
                                foreach ($notifications as $notif):
                                    if (!$notif['is_seen']):
                                        $hasUnseen = true;
                                        ?>
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start list-group-item-warning"
                                            data-notification-id="<?= $notif['id'] ?>">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">
                                                    <?= htmlspecialchars($notif['message']) ?>
                                                </div>
                                                <?php if (!empty($notif['link'])): ?>
                                                    <p class="mb-1">
                                                        <a href="<?= $notif['link'] ?>" class="btn btn-sm btn-outline-primary mt-2">
                                                            <i class="mdi mdi-link"></i> View Details
                                                        </a>
                                                    </p>
                                                <?php endif; ?>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    <?= date($tpl['date_format'] . ' H:i', $notif['created_at']) ?>
                                                </small>
                                            </div>
                                            <button class="btn btn-sm btn-success mark-seen" data-id="<?= $notif['id'] ?>">
                                                <i class="mdi mdi-check"></i> Mark Seen
                                            </button>
                                        </div>
                                        <?php
                                    endif;
                                endforeach;
                                if (!$hasUnseen) {
                                    echo '<div class="list-group-item">No unseen notifications.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="seen" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Seen Notifications</h4>
                            <div class="list-group list-group-flush" id="seen-notifications-list">
                                <?php
                                $hasSeen = false;
                                foreach ($notifications as $notif):
                                    if ($notif['is_seen']):
                                        $hasSeen = true;
                                        ?>
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start bg-light"
                                            data-notification-id="<?= $notif['id'] ?>">
                                            <div class="ms-2 me-auto">
                                                <div class="fw-bold">
                                                    <?= htmlspecialchars($notif['message']) ?>
                                                </div>
                                                <?php if (!empty($notif['link'])): ?>
                                                    <p class="mb-1">
                                                        <a href="<?= $notif['link'] ?>" class="btn btn-sm btn-outline-primary mt-2">
                                                            <i class="mdi mdi-link"></i> View Details
                                                        </a>
                                                    </p>
                                                <?php endif; ?>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-clock-outline"></i>
                                                    <?= date($tpl['date_format'] . ' H:i', $notif['created_at']) ?>
                                                </small>
                                            </div>
                                        </div>
                                        <?php
                                    endif;
                                endforeach;
                                if (!$hasSeen) {
                                    echo '<div class="list-group-item">No seen notifications.</div>';
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
        const markAllSeenButton = document.getElementById('markAllSeen');
        const markSeenButtons = document.querySelectorAll('.mark-seen');
        const unseenNotificationsList = document.getElementById('unseen-notifications-list');
        const seenNotificationsList = document.getElementById('seen-notifications-list');
        const unseenTabLink = document.getElementById('unseen-tab');
        const seenTabLink = document.getElementById('seen-tab');

        markSeenButtons.forEach(button => {
            button.addEventListener('click', function () {
                const notificationId = this.dataset.id;
                markNotificationAsSeen(notificationId);
            });
        });

        if (markAllSeenButton) {
            markAllSeenButton.addEventListener('click', function () {
                markAllNotificationsAsSeen();
            });
        }

        function markNotificationAsSeen(notificationId) {
            fetch('<?= INSTALL_URL ?>?controller=Notifications&action=markAsSeen', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${notificationId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const listItem = document.querySelector(`.list-group-item[data-notification-id="${notificationId}"]`);
                        if (listItem) {
                            // Remove from unseen list
                            unseenNotificationsList.removeChild(listItem);

                            // Remove "No seen notifications" if it exists
                            const noSeenNotifications = seenNotificationsList.querySelector('.list-group-item');
                            if (noSeenNotifications && noSeenNotifications.textContent === 'No seen notifications.') {
                                seenNotificationsList.removeChild(noSeenNotifications);
                            }

                            // Clone the item but without the mark-seen button
                            const newItem = listItem.cloneNode(true);
                            const markSeenButton = newItem.querySelector('.mark-seen');
                            if (markSeenButton) {
                                markSeenButton.parentNode.removeChild(markSeenButton);
                            }
                            newItem.classList.remove('list-group-item-warning');
                            newItem.classList.add('bg-light');
                            
                            // Add to seen list
                            seenNotificationsList.appendChild(newItem);

                            // Check if there are any remaining unseen notifications
                            if (unseenNotificationsList.children.length === 0) {
                                unseenNotificationsList.innerHTML = '<div class="list-group-item">No unseen notifications.</div>';
                                if (markAllSeenButton) {
                                    markAllSeenButton.disabled = true; // Disable instead of hiding
                                }
                            }
                        }
                        // Update counters
                        updateNotificationCounts();

                    } else {
                        alert('Could not mark notification as seen.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function markAllNotificationsAsSeen() {
            fetch('<?= INSTALL_URL ?>?controller=Notifications&action=markAllSeen', {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const unseenListItems = document.querySelectorAll('#unseen-notifications-list .list-group-item-warning');
                        unseenListItems.forEach(item => {
                            // Clone and modify each item
                            const newItem = item.cloneNode(true);
                            const markSeenButton = newItem.querySelector('.mark-seen');
                            if (markSeenButton) {
                                markSeenButton.parentNode.removeChild(markSeenButton);
                            }
                            newItem.classList.remove('list-group-item-warning');
                            newItem.classList.add('bg-light');
                            
                            // Add to seen list
                            seenNotificationsList.appendChild(newItem);
                        });
                        
                        // Clear unseen list and add "No unseen notifications" message
                        unseenNotificationsList.innerHTML = '<div class="list-group-item">No unseen notifications.</div>';
                        
                        // Remove "No seen notifications" if it exists
                        const noSeenNotifications = seenNotificationsList.querySelector('.list-group-item');
                        if (noSeenNotifications && noSeenNotifications.textContent === 'No seen notifications.') {
                            seenNotificationsList.removeChild(noSeenNotifications);
                        }
                        
                        if (markAllSeenButton) {
                            markAllSeenButton.disabled = true; // Disable instead of hiding
                        }
                        updateNotificationCounts();
                    } else {
                        alert('Could not mark all notifications as seen.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function updateNotificationCounts() {
            // Get the number of unseen and seen notifications
            const unseenCount = unseenNotificationsList.querySelectorAll('.list-group-item-warning').length;
            let seenCount = seenNotificationsList.querySelectorAll('.list-group-item-action').length;

            // Check if there is a "No seen notifications" item
            const noSeenNotifications = seenNotificationsList.querySelector('.list-group-item');
            if (noSeenNotifications && noSeenNotifications.textContent === 'No seen notifications.') {
                seenCount = 0; // Reset seenCount to 0 if there are no seen notifications
            }

            // Update the tab links with the counts
            unseenTabLink.textContent = `Unseen Notifications (${unseenCount})`;
            seenTabLink.textContent = `Seen Notifications (${seenCount})`;

            // Return the counts
            return { unseenCount, seenCount };
        }

        // Call updateNotificationCounts and check seenCount
        const counts = updateNotificationCounts();
        if (counts.seenCount > 0) {
            const noSeenNotificationsText = seenNotificationsList.querySelector('.list-group-item');
            if (noSeenNotificationsText && noSeenNotificationsText.textContent === 'No seen notifications.') {
                seenNotificationsList.removeChild(noSeenNotificationsText);
            }
        }

        // Disable "Mark All as Seen" button if there are no unseen notifications
        if (counts.unseenCount === 0 && markAllSeenButton) {
            markAllSeenButton.disabled = true;
        } else if (markAllSeenButton) {
            markAllSeenButton.disabled = false;
        }

        updateNotificationCounts();
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
            overflow-y: auto;
            padding-right: 10px;
            padding-bottom: 30px;
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
    #markAllSeen {
        white-space: nowrap;
    }

    /* Hide button text on small screens */
    @media (max-width: 575.98px) {
        .mark-all-text {
            display: none;
        }

        #markAllSeen {
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

        #unseen-tab,
        #seen-tab {
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