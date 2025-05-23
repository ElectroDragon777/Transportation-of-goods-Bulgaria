<?php
$unread_count = 0;
if (!empty($tpl['notifications'])) {
    $unread_count = count(array_filter($tpl['notifications'], fn($n) => !$n['is_seen']));
}

// Messages count - get from database properly
$unread_message_count = 0;
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'root') {
    if (!empty($tpl['messages'])) {
        $unread_message_count = count(array_filter($tpl['messages'], fn($n) => !$n['is_read']));
    }
}

$message_model = new App\Models\Message();
$unread_msg_notification = 0;
if (!empty($messages)) {
    $unread_msg_notification = count(array_filter($messages, fn($n) => !$n['is_read']));
}
?>
<script>
    // Show console.log for unread message count
    console.log('Unread message count:', <?php echo $unread_message_count; ?>);
    console.log('Unread msg2 count:', <?php echo $unread_msg_notification; ?>);
</script>

<?php
// Get the current controller from the URL, default to 'Dashboard' if not set
$currentController = $_GET['controller'] ?? 'Dashboard';
?>

<nav class="navbar default-layout col-lg-12 col-12 p-0 d-flex align-items-top flex-row fixed-top">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="<?php echo INSTALL_URL; ?>">
                <img src="web/assets/images/logo.svg" alt="logo">
            </a>
            <a class="navbar-brand brand-logo-mini" href="<?php echo INSTALL_URL; ?>">
                <img src="web/assets/images/logo-mini.svg" alt="logo">
            </a>
        </div>
    </div>

    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav ms-auto">
            <?php if (!isset($_SESSION['user'])): ?>
                <li class="nav-item d-flex align-items-center me-2">
                    <a href="<?php echo INSTALL_URL; ?>?controller=Auth&action=login"
                        class="btn btn-primary d-flex align-items-center">
                        <i class="mdi mdi-login me-2"></i>
                        Login
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a href="<?php echo INSTALL_URL; ?>?controller=Auth&action=register"
                        class="btn btn-outline-primary d-flex align-items-center">
                        <i class="mdi mdi-account-plus me-2"></i>
                        Register
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <div id="current-date-container" class="navbar-date-picker">
                        <span id="current-date" class="text-muted"></span>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link count-indicator" id="countDropdown" href="#" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="icon-bell"></i>
                        <?php if ($unread_count > 0): ?>
                            <span class="count"><?= $unread_count ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0"
                        aria-labelledby="countDropdown">
                        <a class="dropdown-item py-3"
                            href="<?php echo INSTALL_URL; ?>?controller=Notification&action=index">
                            <p class="mb-0 font-weight-medium float-left">
                                You have <?= $unread_count ?> unread notifications
                            </p>
                            <span class="badge badge-pill badge-primary float-right">View all</span>
                        </a>
                        <div class="dropdown-divider"></div>

                        <?php
                        $notificationsToDisplay = array_slice($tpl['notifications'], 0, 5);
                        ?>

                        <?php if (count($notificationsToDisplay) > 0): ?>
                            <?php foreach ($notificationsToDisplay as $notif): ?>
                                <a class="dropdown-item preview-item notification-item <?php echo $notif['is_seen'] == 0 ? 'unseen' : ''; ?>"
                                    data-id="<?= $notif['id'] ?>" href="<?= $notif['link'] ?? '#' ?>">
                                    <div class="preview-thumbnail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#333333"
                                            style="width: 16px; height: 16px; max-width: 16px; max-height: 16px; border-radius: 0;"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                        </svg>
                                    </div>
                                    <div class="preview-item-content flex-grow py-2">
                                        <p class="preview-subject ellipsis font-weight-medium text-dark">
                                            <?= htmlspecialchars($notif['message']) ?>
                                        </p>
                                        <p class="fw-light small-text mb-0">
                                            <?php
                                            $timestamp = $notif['created_at'];
                                            $date = new DateTime('@' . $timestamp);
                                            echo htmlspecialchars($date->format(str_replace('y', 'Y', $tpl['date_format'])) ?? 'N/A');
                                            ?>
                                        </p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="dropdown-item text-center text-muted">No new notifications</p>
                        <?php endif; ?>
                    </div>
                </li>

                <li class="nav-item dropdown d-lg-block user-dropdown">
                    <a class="nav-link position-relative" id="UserDropdown" href="#" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="profile-image-container position-relative">
                            <?php if (!empty($_SESSION['user']['photo_path'])): ?>
                                <img id="profileImage" src="<?php echo htmlspecialchars($_SESSION['user']['photo_path']); ?>"
                                    alt="Profile Photo" class="img-xs rounded-circle">
                            <?php else: ?>
                                <div class="img-xs rounded-circle d-flex align-items-center justify-content-center bg-light"
                                    style="width: 32px; height: 32px;">
                                    <i class="mdi mdi-account" style="font-size: 16px; color: #6c757d;"></i>
                                </div>
                            <?php endif; ?>

                            <?php if ($_SESSION['user']['role'] === 'root' && $unread_message_count > 0): ?>
                                <span
                                    class="profile-message-badge"><?php echo $unread_message_count > 99 ? '99+' : $unread_message_count; ?></span>
                            <?php endif; ?>
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                        <div class="dropdown-header text-center d-flex flex-column align-items-center">
                            <?php if (!empty($_SESSION['user']['photo_path'])): ?>
                                <img src="<?php echo htmlspecialchars($_SESSION['user']['photo_path']); ?>" alt="Profile Photo"
                                    class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light rounded-circle"
                                    style="width: 64px; height: 64px;">
                                    <i class="mdi mdi-account" style="font-size: 32px; color: #6c757d;"></i>
                                </div>
                            <?php endif; ?>
                            <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['user']['name']; ?></p>
                            <p class="fw-light text-muted mb-0"><?php echo $_SESSION['user']['email']; ?></p>
                        </div>

                        <a class="dropdown-item"
                            href="<?php echo INSTALL_URL; ?>?controller=User&action=profile&id=<?php echo $_SESSION['user']['id']; ?>">
                            <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile
                        </a>

                        <?php if ($_SESSION['user']['role'] === 'root'): ?>
                            <a class="dropdown-item dropdown-item-with-badge position-relative"
                                href="<?php echo INSTALL_URL; ?>?controller=Messages&action=index">
                                <span class="dropdown-item-content">
                                    <i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Messages
                                </span>
                                <?php if ($unread_message_count > 0): ?>
                                    <span
                                        class="dropdown-message-badge"><?php echo $unread_message_count > 99 ? '99+' : $unread_message_count; ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>

                        <a class="dropdown-item" href="<?php echo INSTALL_URL; ?>?controller=Activity&action=index">
                            <i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Activity
                        </a>

                        <a class="dropdown-item" href="<?php echo INSTALL_URL; ?>?controller=Auth&action=logout">
                            <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out
                        </a>
                    </div>
                </li>
            <?php endif; ?>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-bs-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
        </ul>
    </div>
</nav>

<?php
if (($currentController !== 'Messages') && (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'root')) { ?>
    <div class="messages-notification animated-slide-down"> <strong>Hey there, me!</strong> So, I have new messages from
        people. Go to the
        <a href="<?php echo INSTALL_URL; ?>?controller=Messages&action=index" class="text-primary">Messages</a>
        section! And then, respond to them, as usual!
        <button type="button" class="btn-close" style="float: right; margin-left: 10px;"
            onclick="this.parentElement.style.display='none';"></button>
    </div>
<?php } ?>

<style>
    /* In your CSS file or <style> tag */

    .messages-notification {
        /* Base styles (moved from inline to class) */
        position: fixed;
        top: 70px;
        /* Make sure this matches your navbar height */
        left: 0;
        right: 0;
        z-index: 9999;
        background: linear-gradient(to right, rgba(96, 80, 241, 0.2), rgba(241, 80, 96, 0.2));
        color: #721c24;
        padding: 2px;
        text-align: center;
        width: 100%;
        /* Add a transition for hiding for the close button click */
        transition: transform 0.5s ease-out, opacity 0.5s ease-out;
    }

    /* Animation for smooth appearance */
    .animated-slide-down {
        animation: slideDown 0.5s ease-out forwards;
        /* 0.5s duration, ease-out timing, stays at final state */
    }

    @keyframes slideDown {
        from {
            transform: translateY(-100%);
            /* Start completely above its final position */
            opacity: 0;
            /* Start invisible */
        }

        to {
            transform: translateY(0);
            /* End at its defined 'top' position */
            opacity: 1;
            /* End fully visible */
        }
    }
</style>

<!-- Enhanced CSS for message badges -->
<style>
    /* Date container styling */
    #current-date-container {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    #current-date {
        font-weight: bold;
        color: #333;
    }

    /* Profile image container */
    .profile-image-container {
        display: inline-block;
        position: relative;
    }

    /* Message badge on profile image */
    .profile-message-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: linear-gradient(135deg, #dc3545, #ff6b7a);
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: bold;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
        z-index: 20;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Ensure the parent of the notification count is positioned */
    .nav-link.count-indicator {
        position: relative;
        /* This is crucial for positioning .count */
    }

    /* New/Modified: Styles for the notification count circle */
    /* MODIFIED: Styles for the notification count circle */
    /*.count {
        position: absolute;
        top: -5px;
        right: -5px;
        background: linear-gradient(135deg, #dc3545, #ff6b7a);
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: bold;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
        z-index: 20;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    } */


    /* Message badge in dropdown */
    .dropdown-message-badge {
        background: linear-gradient(135deg, #dc3545, #ff6b7a);
        color: white;
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 10px;
        font-weight: bold;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Dropdown item with badge styling */
    .dropdown-item-with-badge {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-right: 50px !important;
        /* Make room for badge */
    }

    .dropdown-item-content {
        display: flex;
        align-items: center;
        flex: 1;
    }

    /* Animation effects */
    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }

        50% {
            opacity: 1;
            transform: scale(1.1);
        }

        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
        }

        50% {
            box-shadow: 0 2px 12px rgba(220, 53, 69, 0.6);
            transform: scale(1.05);
        }

        100% {
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
        }
    }

    @keyframes slideIn {
        0% {
            opacity: 0;
            transform: translateX(20px);
        }

        100% {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Animation classes */
    .badge-updated {
        animation: bounceIn 0.6s ease-in-out;
    }

    .badge-pulse {
        animation: pulse 2s infinite;
    }

    .badge-new {
        animation: slideIn 0.5s ease-out;
    }

    /* Hover effects */
    .profile-message-badge:hover {
        transform: scale(1.1);
        box-shadow: 0 3px 12px rgba(220, 53, 69, 0.5);
    }

    .dropdown-message-badge:hover {
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 3px 10px rgba(220, 53, 69, 0.4);
    }
</style>

<!-- Improved JavaScript with faster AJAX -->
<script>
    let currentMessageCount = <?php echo $unread_message_count; ?>;
    let updateInterval;
    let isUpdating = false; // Prevent multiple simultaneous requests

    // Function to update message count with improved AJAX
    // function updateMessageCount() {
    //     <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'root'): ?>
        //         // Prevent multiple simultaneous requests
        //         if (isUpdating) {
        //             console.log('Update already in progress, skipping...');
        //             return;
        //         }

        //         isUpdating = true;

        //         // Use fetch for better performance and shorter timeout
        //         fetch('<?php echo INSTALL_URL; ?>?controller=Messages&action=getUnreadCount', {
        //             method: 'GET',
        //             headers: {
        //                 'Accept': 'application/json',
        //                 'Cache-Control': 'no-cache'
        //             },
        //             // Shorter timeout for faster feedback
        //             signal: AbortSignal.timeout(500) // 0.5 second timeout
        //         })
        //             .then(response => {
        //                 if (!response.ok) {
        //                     throw new Error(`HTTP error! status: ${response.status}`);
        //                 }
        //                 return response.json();
        //             })
        //             .then(data => {
        //                 console.log('Message count response:', data);

        //                 if (data && typeof data.count !== 'undefined') {
        //                     const newCount = parseInt(data.count);
        //                     updateBadges(newCount);
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error fetching message count:', error);
        //                 // Don't show user-facing errors for background updates
        //             })
        //             .finally(() => {
        //                 isUpdating = false;
        //             });
        //     <?php endif; ?>
    // }

    // Improved function to update badge elements
    function updateBadges(newCount) {
        const profileBadge = document.querySelector('.profile-message-badge');
        const dropdownBadge = document.querySelector('.dropdown-message-badge');

        // Check if count has changed
        const countChanged = newCount !== currentMessageCount;
        const isNewMessage = newCount > currentMessageCount;

        if (newCount > 0) {
            const displayCount = newCount > 99 ? '99+' : newCount.toString();

            // Update or create profile badge
            if (profileBadge) {
                profileBadge.textContent = displayCount;
                profileBadge.style.display = 'flex';
            } else {
                // Create profile badge if it doesn't exist
                const profileContainer = document.querySelector('.profile-image-container');
                if (profileContainer) {
                    const newProfileBadge = document.createElement('span');
                    newProfileBadge.className = 'profile-message-badge';
                    newProfileBadge.textContent = displayCount;
                    profileContainer.appendChild(newProfileBadge);
                }
            }

            // Update or create dropdown badge
            if (dropdownBadge) {
                dropdownBadge.textContent = displayCount;
                dropdownBadge.style.display = 'flex';
            } else {
                // Create dropdown badge if it doesn't exist
                const dropdownItem = document.querySelector('.dropdown-item-with-badge');
                if (dropdownItem) {
                    const newDropdownBadge = document.createElement('span');
                    newDropdownBadge.className = 'dropdown-message-badge';
                    newDropdownBadge.textContent = displayCount;
                    dropdownItem.appendChild(newDropdownBadge);
                }
            }

            // Apply animations if count changed
            if (countChanged) {
                const currentProfileBadge = document.querySelector('.profile-message-badge');
                const currentDropdownBadge = document.querySelector('.dropdown-message-badge');

                if (currentProfileBadge) {
                    // Remove existing animation classes
                    currentProfileBadge.classList.remove('badge-updated', 'badge-pulse', 'badge-new');

                    if (isNewMessage) {
                        // New message animation
                        currentProfileBadge.classList.add('badge-new');
                        setTimeout(() => {
                            currentProfileBadge.classList.add('badge-pulse');
                        }, 500);
                    } else {
                        // Updated count animation
                        currentProfileBadge.classList.add('badge-updated');
                    }
                }

                if (currentDropdownBadge) {
                    // Remove existing animation classes
                    currentDropdownBadge.classList.remove('badge-updated', 'badge-new');

                    if (isNewMessage) {
                        currentDropdownBadge.classList.add('badge-new');
                    } else {
                        currentDropdownBadge.classList.add('badge-updated');
                    }
                }
            }
        } else {
            // Hide badges when count is 0
            if (profileBadge) {
                profileBadge.style.display = 'none';
                profileBadge.classList.remove('badge-updated', 'badge-pulse', 'badge-new');
            }
            if (dropdownBadge) {
                dropdownBadge.style.display = 'none';
                dropdownBadge.classList.remove('badge-updated', 'badge-new');
            }
        }

        // Update current count
        currentMessageCount = newCount;
        console.log('Updated message count to:', newCount);
    }

    // Initialize everything when document is ready
    document.addEventListener('DOMContentLoaded', function () {
        // Set up date display
        <?php $currentDate = date($tpl['date_format'] ?? 'Y-m-d'); ?>
        const currentDateContainer = document.getElementById('current-date');
        if (currentDateContainer) {
            currentDateContainer.textContent = "<?php echo $currentDate; ?>";
        }

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'root'): ?>
            // Start periodic updates every 3 seconds (faster than before)
            updateInterval = setInterval(updateMessageCount, 3000);

            // Initial update after a short delay
            setTimeout(updateMessageCount, 500);

            console.log('Message counter AJAX updates initialized - checking every 3 seconds');
        <?php endif; ?>
    });

    // Clean up interval when page is about to unload
    window.addEventListener('beforeunload', function () {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
    });

    // Improved visibility change handling
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            if (updateInterval) {
                clearInterval(updateInterval);
            }
        } else {
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'root'): ?>
                // Resume updates when page becomes visible again
                updateInterval = setInterval(updateMessageCount, 3000);
                // Immediate update when tab becomes active
                setTimeout(updateMessageCount, 200);
            <?php endif; ?>
        }
    });
</script>