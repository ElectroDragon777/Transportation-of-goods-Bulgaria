<table class="table select-table" id="order-table-id">
    <thead>
        <tr>
            <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])): ?>
                <th>
                    <div class="form-check form-check-flat mt-0">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" id="select-all-orders">
                        </label>
                    </div>
                </th>
            <?php else: ?>
                <th>
                    <div class="form-check form-check-flat mt-0">
                        <label class="form-check-label">
                            #
                        </label>
                    </div>
                </th>
            <?php endif; ?>
            <th>Order ID</th>
            <th>Tracking Number</th>
            <th>Customer</th>
            <th>Courier</th>
            <th>Delivery Date</th>
            <th>Quantity</th>
            <th>Name of Parcel</th>
            <th>Total Price</th>
            <th>Start Point</th>
            <th>End Destination</th>
            <th>Created at</th>
            <th>Status</th>
            <th style="text-align: right;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($tpl['orders']) && !empty($tpl['orders'])) {
            foreach ($tpl['orders'] as $order) {
                ?>
                <tr>

                    <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])): ?>
                        <td>
                            <div class="form-check form-check-flat mt-0">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input order-checkbox"
                                        data-id="<?php echo $order['id']; ?>">
                                </label>
                            </div>
                        </td>
                    <?php else: ?>
                        <td>
                            #
                        </td>
                    <?php endif; ?>
                    <?php if (($order['customer_name'] == $_SESSION['user']['name']) || ($order['courier_name'] == $_SESSION['user']['name'])): ?>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['tracking_number'] ?? 'N/A'); ?></td>
                        <?php if (!in_array($_SESSION['user']['role'], ['courier'])): ?>
                            <td><?php echo htmlspecialchars($order['customer_name'] . ' (You)' ?? 'Unknown'); ?></td>
                            <td><?php echo htmlspecialchars($order['courier_name'] ?? 'Unknown'); ?></td>
                        <?php else: ?>
                            <td><?php echo htmlspecialchars($order['customer_name'] ?? 'Unknown'); ?></td>
                            <td><?php echo htmlspecialchars($order['courier_name'] . ' (You)' ?? 'Unknown'); ?></td>
                        <?php endif; ?>
                        <td><?php echo htmlspecialchars($order['delivery_date'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity'] ?? '0'); ?></td>
                        <td><?php echo htmlspecialchars($order['product_name'] ?? 'N/A'); ?></td>
                        <td><?php echo (class_exists('Utility') ? Utility::getDisplayableAmount(htmlspecialchars($order['total_amount'])) : htmlspecialchars($order['total_amount'])) ?? '0.00'; ?>
                        </td>
                        <td><?php echo htmlspecialchars($order['start_point'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($order['end_destination'] ?? 'N/A'); ?></td>
                        <td>
                            <?php
                            $timestamp = $order['created_at'];
                            $date = new DateTime('@' . $timestamp);
                            echo htmlspecialchars($date->format(str_replace('y', 'Y', $tpl['date_format'])) ?? 'N/A');
                            ?>
                        </td>
                        <td><?php
                        if (isset($order['status']) && class_exists('Utility') && isset(Utility::$order_status)) {
                            foreach (Utility::$order_status as $k => $v) {
                                if ($k == $order['status']) {
                                    echo $v;
                                    break;
                                }
                            }
                        } else {
                            echo htmlspecialchars($order['status'] ?? 'Unknown');
                        }
                        ?></td>
                        <td style="text-align: right;">
                            <a class="btn btn-light btn-circle mdc-ripple-upgraded"
                                href="<?php echo INSTALL_URL; ?>?controller=Order&action=details&id=<?php echo $order['id'] ?>">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])) { ?>
                                <a class="btn btn-info btn-circle mdc-ripple-upgraded"
                                    href="<?php echo INSTALL_URL; ?>?controller=Order&action=edit&order_id=<?php echo $order['id'] ?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-danger btn-circle delete-order" href="#" data-id="<?php echo $order['id']; ?>">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            <?php } ?>
                        </td>
                    <?php else: ?>
                        <td colspan="15" class="text-center">
                            <div class="alert alert-danger" role="alert">
                                You cannot view this order as it is not assigned to you.
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="15" class="text-center">
                    No orders found
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>