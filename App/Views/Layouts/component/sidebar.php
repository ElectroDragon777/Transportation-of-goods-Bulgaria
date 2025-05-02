<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <?php
        if (isset($_SESSION['user'])):
            $currentController = $_GET['controller'] ?? 'Dashboard';
            $currentAction = $_GET['action'] ?? 'index';
            ?>

            <!-- Dashboard -->
            <li class="nav-item <?php if ($currentController === 'Dashboard' && (!isset($_GET['controller']) || $_GET['controller'] === 'Dashboard'))
                echo 'active'; ?>">
                <a class="nav-link" href="<?php INSTALL_URL; ?>?">
                    <i class="mdi mdi-chart-line menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>

            <li class="nav-item nav-category">Personal</li>
            <li class="nav-item <?php if ($currentController === 'Order' && $currentAction === 'list')
                echo 'active'; ?>">
                <a class="nav-link"
                    href="<?php INSTALL_URL; ?>?controller=Order&action=list&user_id=<?php echo $_SESSION['user']['id']; ?>">
                    <i class="menu-icon mdi mdi-clipboard-list"></i>
                    <span class="menu-title">My Orders</span>
                </a>
            </li>
            <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])): ?>
                <li class="nav-item nav-category">Forms and Data</li>
                <li class="nav-item <?php if ($currentController === 'Order')
                    echo 'active'; ?>">
                    <a class="nav-link" data-bs-toggle="collapse" href="#orders"
                        aria-expanded="<?php echo ($currentController === 'Order') ? 'true' : 'false'; ?>"
                        aria-controls="orders">
                        <i class="menu-icon mdi mdi-cart-outline"></i>
                        <span class="menu-title">Orders</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse <?php if ($currentController === 'Order')
                        echo 'show'; ?>" id="orders">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item <?php if ($currentController === 'Order' && $currentAction === 'list')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Order&action=list">List Orders</a>
                            </li>
                            <li class="nav-item <?php if ($currentController === 'Order' && $currentAction === 'create')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Order&action=create">Create Order</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item <?php if ($currentController === 'Pallet')
                    echo 'active'; ?>">
                    <a class="nav-link" data-bs-toggle="collapse" href="#pallets"
                        aria-expanded="<?php echo ($currentController === 'Pallet') ? 'true' : 'false'; ?>"
                        aria-controls="pallets">
                        <i class="menu-icon mdi mdi-cube-outline"></i>
                        <span class="menu-title">Parcels</span> <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse <?php if ($currentController === 'Pallet')
                        echo 'show'; ?>" id="pallets">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item <?php if ($currentController === 'Pallet' && $currentAction === 'list')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Pallet&action=list">List Parcels</a>
                            </li>
                            <li class="nav-item <?php if ($currentController === 'Pallet' && $currentAction === 'create')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Pallet&action=create">Create
                                    Parcels</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item <?php if ($currentController === 'User')
                    echo 'active'; ?>">
                    <a class="nav-link" data-bs-toggle="collapse" href="#users"
                        aria-expanded="<?php echo ($currentController === 'User') ? 'true' : 'false'; ?>" aria-controls="users">
                        <i class="menu-icon mdi mdi-account-group"></i>
                        <span class="menu-title">Users</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse <?php if ($currentController === 'User')
                        echo 'show'; ?>" id="users">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item <?php if ($currentController === 'User' && $currentAction === 'list')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=User&action=list">List Users</a>
                            </li>
                            <li class="nav-item <?php if ($currentController === 'User' && $currentAction === 'create')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=User&action=create">Create User</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item <?php if ($currentController === 'Courier')
                    echo 'active'; ?>">
                    <a class="nav-link" data-bs-toggle="collapse" href="#couriers"
                        aria-expanded="<?php echo ($currentController === 'Courier') ? 'true' : 'false'; ?>"
                        aria-controls="couriers">
                        <i class="menu-icon mdi mdi-truck"></i>
                        <span class="menu-title">Couriers</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse <?php if ($currentController === 'Courier')
                        echo 'show'; ?>" id="couriers">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item <?php if ($currentController === 'Courier' && $currentAction === 'list')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Courier&action=list">List
                                    Couriers</a>
                            </li>
                            <li class="nav-item <?php if ($currentController === 'Courier' && $currentAction === 'create')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Courier&action=create">Create
                                    Courier</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Only root can edit global settings -->
                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'root'): ?>
                    <li class="nav-item nav-category">Control</li>
                    <li class="nav-item <?php if ($currentController === 'Settings')
                        echo 'active'; ?>">
                        <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Settings&action=index">
                            <i class="menu-icon mdi mdi-cog spin-wheel"></i>
                            <span class="menu-title">Settings</span>
                        </a>
                    </li>
                <?php endif; ?>
            <?php elseif ($_SESSION['user']['role'] === 'courier'): ?>
                <li class="nav-item nav-category">Deliveries</li>
                <li class="nav-item <?php if ($currentController === 'Order' && isset($_GET['courier_id']))
                    echo 'active'; ?>">
                    <a class="nav-link"
                        href="<?php INSTALL_URL; ?>?controller=Order&action=list&courier_id=<?php echo $_SESSION['user']['id']; ?>">
                        <i class="menu-icon mdi mdi-truck-delivery"></i>
                        <span class="menu-title">Orders to Deliver</span>
                    </a>
                </li>
                <!-- Add Orders and Parcel making (Create Pages only, no LISTS) for Users only v -->
            <?php else: ?>
                <!-- Only for users -->
                <li class="nav-item nav-category">Ordering</li>
                <li class="nav-item <?php if ($currentController === 'Pallet')
                    echo 'active'; ?>">
                    <a class="nav-link" data-bs-toggle="collapse" href="#pallets"
                        aria-expanded="<?php echo ($currentController === 'Pallet') ? 'true' : 'false'; ?>"
                        aria-controls="pallets">
                        <i class="menu-icon mdi mdi-cube-outline"></i>
                        <span class="menu-title">Parcels</span> <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse <?php if ($currentController === 'Pallet')
                        echo 'show'; ?>" id="pallets">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item <?php if ($currentController === 'Pallet' && $currentAction === 'list')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Pallet&action=list">List Parcels</a>
                            </li>
                            <li class="nav-item <?php if ($currentController === 'Pallet' && $currentAction === 'create')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Pallet&action=create">Create
                                    Parcels</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item <?php if ($currentController === 'Order')
                    echo 'active'; ?>">
                    <a class="nav-link" data-bs-toggle="collapse" href="#orders"
                        aria-expanded="<?php echo ($currentController === 'Order') ? 'true' : 'false'; ?>"
                        aria-controls="orders">
                        <i class="menu-icon mdi mdi-cart-outline"></i>
                        <span class="menu-title">Orders</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse <?php if ($currentController === 'Order')
                        echo 'show'; ?>" id="orders">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item <?php if ($currentController === 'Order' && $currentAction === 'list')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Order&action=list">List Orders</a>
                            </li>
                            <li class="nav-item <?php if ($currentController === 'Order' && $currentAction === 'create')
                                echo 'active'; ?>">
                                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=Order&action=create">Create Order</a>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>

            <!-- Courier Tracking. for all -->
            <li class="nav-item nav-category">Courier Tracking</li>
            <li class="nav-item <?php if ($currentController === 'CourierTracking')
                echo 'active'; ?>">
                <a class="nav-link" href="<?php INSTALL_URL; ?>?controller=CourierTracking&action=index">
                    <?php $currentController === 'CourierTracking' ?>
                    <i class="mdi mdi-map-marker-path menu-icon"></i>
                    <span class="menu-title">Courier Tracking</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>