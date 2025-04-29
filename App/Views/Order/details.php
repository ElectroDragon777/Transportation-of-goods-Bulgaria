<div class="container-scroller">
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                    <ul class="nav nav-tabs" role="tablist">
                        <?php if (in_array($_SESSION['user']['role'], ['admin', 'root'])): ?>
                            <li class="nav-item">
                            <a class="nav-link" href="<?php echo INSTALL_URL; ?>?controller=Order&action=list">Order
                                List</a>
                        </li>
                        <?php endif ?>                    
                        <li class="nav-item">
                            <a class="nav-link active ps-3" href="#">Order Details</a>
                        </li>
                    </ul>
                </div>
                <div class="card card-rounded mt-3">
                    <div class="card-body">
                        <h4 class="card-title">Order Details</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($tpl['order']['id']); ?></p>
                                <p><strong>Customer:</strong> <?php echo htmlspecialchars($tpl['customer']['name']); ?>
                                </p>
                                <p><strong>Start Point:</strong>
                                    <?php echo htmlspecialchars($tpl['order']['start_point']); ?>
                                </p>
                                <p><strong>End Destination:</strong>
                                    <?php echo htmlspecialchars($tpl['order']['end_destination']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tracking Number:</strong>
                                    <?php echo htmlspecialchars($tpl['order']['tracking_number']); ?></p>
                                <p><strong>Courier:</strong> <?php echo htmlspecialchars($tpl['courier']['name']); ?>
                                </p>
                                <p><strong>Delivery Date:</strong>
                                <?php
                                    // Convert the delivery date to a Unix timestamp
                                    $deliveryTimestamp = strtotime($tpl['order']['delivery_date']);
                                    
                                    // Check if the conversion was successful
                                    if ($deliveryTimestamp !== false) {
                                        echo htmlspecialchars(date($tpl['date_format'], $deliveryTimestamp));
                                    } else {
                                        echo 'Date was not set correctly';
                                    }
                                    ?>
                                </p>
                                <p><strong>Status:</strong>
                                    <?php echo htmlspecialchars(Utility::$order_status[$tpl['order']['status']]); ?></p>
                                <p><strong>Total Price:</strong>
                                    <?php echo Utility::getDisplayableAmount(htmlspecialchars(number_format($tpl['order']['total_amount'], 2))); ?>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <h5>Parcel:</h5>
                        <div class="table-responsive">
                            <table class="table select-table" id="order-products-table-id">
                                <thead>
                                    <tr>
                                        <th>Parcel Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tpl['pallets'] as $pallet) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($pallet['name']); ?></td>
                                            <td><?php echo htmlspecialchars($pallet['category']); ?></td>
                                            <td><?php echo htmlspecialchars($pallet['stock']); ?></td>
                                            <td><?php echo Utility::getDisplayableAmount(htmlspecialchars(number_format($pallet['total_amount'], 2))); ?>
                                            </td>
                                            <td><?php echo Utility::getDisplayableAmount(htmlspecialchars(number_format($pallet['subtotal'], 2))); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo INSTALL_URL; ?>?controller=Order&action=list"
                                class="btn btn-outline-dark">Back to Order List</a>
                            <?php if ($_SESSION['user']['id'] === $tpl['order']['user_id'] && in_array($tpl['order']['status'], ['pending', 'cancelled'])) { ?>
                                <a href="<?php echo INSTALL_URL; ?>?controller=Order&action=pay&order_id=<?php echo $tpl['order']['id']; ?>"
                                    class="btn btn-success">Pay</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>