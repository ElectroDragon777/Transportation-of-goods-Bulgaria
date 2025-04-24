<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo INSTALL_URL; ?>?controller=Order&action=list">Order
                            List</a>
                    </li>
                    <li class="nav-item">
                        <!-- ps-0 is no-spacing, set to ps-3! -->
                        <a class="nav-link active ps-3"
                            href="<?php echo INSTALL_URL; ?>?controller=Order&action=create">Create Order</a>
                    </li>
                </ul>
            </div>

            <div class="card card-rounded mt-3">
                <div class="card-body">
                    <h4 class="card-title">Create New Order</h4>

                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form method="POST" id="booking-frm-id"
                        action="<?php echo INSTALL_URL; ?>?controller=Order&action=create">
                        <input type="hidden" name="send" value="1" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer" class="form-label">Customer</label>
                                    <select name="user_id" id="userId" class="form-select" required>
                                        <option value=''>---</option>
                                        <?php
                                        foreach ($tpl['users'] as $user) {
                                            echo "<option value=\"{$user['id']}\" 
                                                data-address=\"" . htmlspecialchars($user['address']) . "\" 
                                                data-region=\"" . htmlspecialchars($user['region']) . "\">
                                                {$user['name']}</option>";
                                        }
                                        ?>
                                        <!-- Not required -->
                                        <!-- <?php
                                        foreach ($tpl['users'] as $user) {
                                            echo "<option value=\"{$user['id']}\" 
                                                data-address=\"" . htmlspecialchars($user['address']) . "\" 
                                                data-region=\"" . htmlspecialchars($user['region']) . "\">
                                                {$user['name']}</option>";
                                        }
                                        ?> -->
                                    </select>
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div> -->
                                <!-- Not required -->
                                <!-- <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="country" name="country" required>
                                </div> -->
                                <!-- <div class="mb-3">
                                    <label for="region" class="form-label">Region</label>
                                    <input type="text" class="form-control" id="region" name="region" required>
                                </div> -->
                                <div class="mb-3">
                                    <label for="courierId" class="form-label">Courier</label>
                                    <select name="courier_id" id="courierId" class="form-select" required>
                                        <option value=''>---</option>
                                        <?php
                                        foreach ($tpl['couriers'] as $courier) {
                                            echo "<option value=\"{$courier['id']}\">{$courier['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="deliveryDate" class="form-label">Date of Delivery</label>
                                    <input type="date" class="form-control" id="deliveryDate" name="delivery_date"
                                        value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="productPrice" class="form-label">Product Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><?php echo $tpl['currency']; ?></span>
                                        <input type="text" class="form-control" id="productPrice" name="product_price"
                                            readonly>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="tax" class="form-label">Tax</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><?php echo $tpl['currency']; ?></span>
                                        <input type="text" class="form-control" id="tax" name="tax" required readonly>
                                    </div>
                                </div>

                                <!-- Unneeded. -->
                                <!-- <div class="mb-3">
                                    <label for="shippingPrice" class="form-label">Shipping Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text"></* ?php echo $tpl['currency']; */ ?></span>
                                        <input type="text" class="form-control" id="shippingPrice"  name="shipping_price" required readonly>
                                    </div>
                                </div>  -->

                                <div class="mb-3">
                                    <label for="totalPrice" class="form-label">Total Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><?php echo $tpl['currency']; ?></span>
                                        <input type="text" class="form-control" id="totalPrice" name="total_price"
                                            readonly>
                                    </div>
                                </div>

                                <!-- Admins set orders to started or pending automatically, depending on delivery date. Write code later. -->
                                <!-- <div class="mb-3">
                                    <label for="status" class="form-label">Order Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value=''>---</option>
                                        </*?php
                                        foreach (Utility::$order_status as $k => $v) {
                                            ?>
                                            <option value="</*?php echo $k; ?>"></*?php echo $v; */?></option>
                                            </*?php
                                        }
                                        */?>
                                    </select>
                                </div> -->
                                <div id="parcelRows">
                                    <div class="row align-items-end mb-3 parcel-row">
                                        <div class="col-md-12">
                                            <label for="parcelIds" class="form-label">Pallet</label>
                                            <select name="parcel_id[]" id="parcelIds" class="form-select" required>
                                                <option value="">---</option>
                                                <?php
                                                foreach ($tpl['pallets'] as $item) {
                                                    echo "<option value=\"{$item['id']}\" data-max-quantity=\"{$item['stock']}\">{$item['name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Not a plan yet, but can be enhanced for pallets vvv -->
                                        <!-- <div class="col-md-4">
                                            <label for="quantities" class="form-label">Quantity</label>
                                            <input type="number" step="1" min="1" class="form-control" id="quantities"
                                                   name="quantity[]" required>
                                        </div> -->

                                        <!-- <div class="col-md-1 text-center d-flex justify-content-center align-items-center">
                                            <button type="button" class="btn btn-light d-flex justify-content-center align-items-center rounded-circle add-row" style="width: 36px; height: 36px;">+</button>
                                        </div> -->
                                        <!-- Not a plan yet, but can be enhanced for pallets ^^^ -->
                                    </div>
                                </div>

                                <!-- Time of Delivery -->
                                <div class="mb-3">
                                    <label for="timeOfDelivery" class="form-label">Time of Delivery: <span
                                            id="currentTimeLabel"></span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-clock text-primary"></i></span>
                                        <input type="text" class="form-control" id="timeOfDelivery"
                                            name="time_of_delivery" readonly autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mt-3">
                                <strong>Please note:</strong><br>
                                <span class="note-indicator"></span>
                                If delivery is far from current date (04/08/2025), e.g., in 7 days, we will notify via
                                email and in-site messages 3 days prior that your delivery will be processed soon. On
                                the day of the delivery, we will notify again that the palette is on its way.
                                <br>
                                <span class="note-indicator"></span>
                                If you see your time differing from the order delivery time, it is that you are making
                                it out of working hours.
                                <span id="dynamic-late-note"></span>
                                <br>
                                <strong>Reminder:</strong> You can keep track of couriers if you feel anxious (for just
                                curious), via the map tracking!
                            </p>
                        </div>
                        <!-- Reminder for paragraph, add link once map tracking is done. ^^^  -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary text-white me-0">Create Order</button>
                                <a href="javascript:" id="calculate-price-btn-id"
                                    class="btn btn-primary text-white me-0">Calculate Price</a>
                                <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"
                                    class="btn btn-outline-dark">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Visual appeal for notes -->
<style>
    .note-indicator {
        display: inline-block;
        /* Allows us to control width and height */
        width: 30px;
        /* Adjust the length of the line */
        height: 2px;
        /* Adjust the thickness of the line */
        background-color: #007bff;
        /* Choose your desired color (Bootstrap primary color used as an example) */
        vertical-align: middle;
        /* Align the line with the text */
        margin-right: 8px;
        /* Add some spacing between the line and the text */
    }
</style>

<!-- Get Time Dynamically -->
<!-- Removing default arrow formatting (shows an arrow above if this is gone) -->
<style>
    /* For Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* For Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<script>
    function updateTimeOfDelivery() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();

        // Format with leading zeros
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        const currentTimeWithSeconds = `${hours}:${minutes}:${seconds}`;
        const currentTimeWithMinutes = `${hours}:${minutes}`;

        document.getElementById('currentTimeLabel').textContent = `(Current Time: ${currentTimeWithSeconds})`;
        document.getElementById('timeOfDelivery').value = currentTimeWithMinutes;
    }

    // Update the time immediately
    updateTimeOfDelivery();

    // Update the time every second
    setInterval(updateTimeOfDelivery, 1000);
</script>

<!-- Time messages, based on local time -->
<script>
    function updateLateNightNote() {
        const now = new Date();
        const currentHour = now.getHours();
        const dynamicNoteElement = document.getElementById('dynamic-late-note');
        let message = "";

        if (currentHour >= 18 && currentHour < 21) {
            message = " You are a bit late, we schedule it for tomorrow, do not worry!";
        } else if (currentHour >= 21 || currentHour < 1) { // 21:00 to 00:59
            message = " It is a quiet evening, wishing you that if not! Order is automatically scheduled for tomorrow!";
        } else if (currentHour >= 1 && currentHour < 5.5) { // 01:00 to 05:29
            message = " Assuming you are local, get some sleep! What are you doing this late here? Order is automatically scheduled for later!";
        } else if (currentHour >= 5.5 && currentHour < 8) { // 05:30 to 07:59
            message = " Close to be open, you are eager to order! Order is automatically scheduled for later!";
        }

        dynamicNoteElement.textContent = message;
    }

    // Update the note immediately and then every minute (no need for second-level precision for this)
    updateLateNightNote();
    setInterval(updateLateNightNote, 60000); // 60000 milliseconds = 1 minute
</script>