<?php

namespace App\Controllers;

use Core\Controller;

class OrderController extends Controller {

    var $layout = 'admin';
    var $settings;

    public function __construct() {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }

        // Only restrict access to certain actions for regular users, not the entire controller
        $currentAction = $_GET['action'] ?? 'index';

        // Admin-only actions - adjust this list as needed
        $adminOnlyActions = ['list', 'edit', 'delete', 'bulkDelete', 'export', 'print'];

        // If user is trying to access an admin-only action
        if ($_SESSION['user']['role'] == 'user' && in_array($currentAction, $adminOnlyActions)) {
            header("Location: " . INSTALL_URL, true, 301);
            exit;
        }

        $this->settings = $this->loadSettings();
    }

    function loadSettings() {
        $settingModel = new \App\Models\Setting();
        $settings = $settingModel->getAll();
        $app_settings = [];
        foreach ($settings as $setting) {
            $app_settings[$setting['key']] = $setting['value'];
        }
        return $app_settings;
    }

    function list($layout = 'admin') {
        $orderModel = new \App\Models\Order();
        $userModel = new \App\Models\User();
        $courierModel = new \App\Models\Courier();

        $opts = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['customerName'])) {
                $opts["user_id IN (SELECT id FROM users WHERE name LIKE '%" . $_POST['customerName'] . "%')"] = "1";
            }
            if (!empty($_POST['courierName'])) {
                $opts["courier_id IN (SELECT id FROM couriers WHERE name LIKE '%" . $_POST['courierName'] . "%')"] = "1";
            }
            if (!empty($_POST['status'])) {
                $opts["status LIKE '%" . $_POST['status'] . "%' AND 1 "] = "1";
            }
            if (!empty($_POST['trackingNumber'])) {
                $opts["tracking_number LIKE '%" . $_POST['trackingNumber'] . "%' AND 1 "] = "1";
            }
            if (!empty($_POST['orderDateFrom'])) {
                $opts["delivery_date >= '" . strtotime($_POST['orderDateFrom']) . "'"] = "1";
            }
            if (!empty($_POST['orderDateTo'])) {
                $opts["delivery_date <= '" . strtotime($_POST['orderDateTo']) . "'"] = "1";
            }
            if (!empty($_POST['minTotalPrice'])) {
                $opts["total_amount >= '" . $_POST['minTotalPrice'] . "'"] = "1";
            }
            if (!empty($_POST['maxTotalPrice'])) {
                $opts["total_amount <= '" . $_POST['maxTotalPrice'] . "'"] = "1";
            }
        }

        // Retrieve all orders from the database
        if (!empty($_GET['user_id']) && $_GET['user_id'] == $_SESSION['user']['id']) { //User role checking orders
            $opts['user_id'] = $_GET['user_id'];
        }

        // Retrieve all orders from the database
        if (!empty($_GET['courier_id']) && $_GET['courier_id'] == $_SESSION['user']['id']) { //User role checking orders
            $opts['courier_id'] = $courierModel->get(['user_id' => $_GET['courier_id']])['id'];
        }

        $orders = $orderModel->getAll($opts);

        // Format orders for display
        foreach ($orders as &$order) {
            $order['customer_name'] = $userModel->get($order['user_id'])['name'] ?? 'Unknown';
            $order['courier_name'] = $userModel->get($order['courier_id'])['name'] ?? 'Unknown';

            if (is_numeric($order['delivery_date'])) {
                $order['delivery_date'] = date($this->settings['date_format'], $order['delivery_date']);
            } elseif ($order['delivery_date'] !== null && $order['delivery_date'] !== 'N/A') {
                // Attempt to format the existing date string
                try {
                    $date = new \DateTime($order['delivery_date']);
                    $order['delivery_date'] = $date->format($this->settings['date_format']);
                } catch (\Exception $e) {
                    $order['delivery_date'] = 'N/A'; // If formatting fails, set to N/A
                }
            } else {
                $order['delivery_date'] = 'N/A'; // Or some other default value
            }

            // --- DEBUGGING ---
            // echo "<b>Order ID:</b> " . $order['id'] . "<br>";
            // echo "<b>Quantity from DB:</b> ";
            // var_dump($order['quantity']);
            // echo "<br>";
            // echo "<b>\$order['quantity'] before utility:</b> ";
            // var_dump($order['quantity']);
            // echo "<br>";
            // $displayableQuantity = $order['quantity']; // Direct assignment for quantity
            // echo "<b>Quantity after direct assignment:</b> ";
            // var_dump($displayableQuantity);
            // echo "<br>";
            // $order['quantity'] = $displayableQuantity;
            // echo "<b>\$order['quantity'] after assignment:</b> ";
            // var_dump($order['quantity']);
            // echo "<br><br>";
            // --- END DEBUGGING ---
        }

        // Pass the data to the view
        $arr = [
            'orders' => $orders,
            'currency' => $this->settings['currency']  // $this->settings['currency_code'], set manually to currency, since local+modded.
        ];

        $this->view($layout, $arr);
    }

    function filter() {
        $this->list('ajax');
    }

    public function completeOrder() {
        // Basic input validation
        if (!isset($_GET['order_id']) || !ctype_digit((string) $_GET['order_id']) || (int) $_GET['order_id'] <= 0) {
            // Handle invalid or missing order_id
            // You might redirect back with an error message, or show an error page
            // For simplicity, we'll just echo an error and exit.
            // In a real app, use a proper error handling/templating system.
            http_response_code(400); // Bad Request
            echo "Error: Invalid or missing Order ID.";
            // You might want to log this error
            // error_log("Attempt to complete order with invalid ID: " . ($_GET['order_id'] ?? 'NONE'));
            exit;
        }

        $orderId = (int) $_GET['order_id'];
        $orderModel = new \App\Models\Order(); // Ensure this namespace and class exist
        $order = $orderModel->get($orderId);

        if (!$order) {
            // Handle order not found
            http_response_code(404); // Not Found
            echo "Error: Order with ID {$orderId} not found.";
            // error_log("Attempt to complete non-existent order ID: " . $orderId);
            exit;
        }

        // Check if the order is already delivered to prevent redundant updates
        if (isset($order['status']) && $order['status'] === 'delivered') {
            // Optionally, redirect to an order details page or show a message
            // echo "Order {$orderId} is already marked as delivered.";
            // For now, just proceed as if it was a fresh update or redirect to a "success" page
            // header('Location: index.php?controller=Order&action=view&order_id=' . $orderId . '&status=already_delivered');
            // exit;
        }

        try {
            // Prepare the update data
            // It's safer to only update specific fields rather than the whole $order array
            // if your $orderModel->update() method expects an array of changes.
            // If $orderModel->update() expects the full object and you've modified it, that's fine too.

            $updateData = [
                'id' => $orderId, // Assuming 'id' is the primary key used by update
                'status' => 'delivered',
                    // Potentially update other fields like 'delivery_date'
                    // 'delivery_date' => date('Y-m-d H:i:s')
            ];

            $success = $orderModel->update($updateData); // Or $orderModel->update($orderId, ['status' => 'delivered']);
            // Or if your update method is: $order['status'] = 'delivered'; $orderModel->update($order);

            if ($success) {
                // Redirect to a success page, order details, or dashboard
                // Add a success message to session flash data if your framework supports it
                // $_SESSION['flash_message'] = "Order #{$orderId} has been successfully marked as delivered.";
                header("Location: " . INSTALL_URL . "?controller=Order&action=details&id=$orderId"); // Example redirect
                exit;
            } else {
                // Handle update failure
                http_response_code(500); // Internal Server Error
                echo "Error: Failed to update order status. Please try again.";
                // error_log("Failed to update order ID: " . $orderId . " to delivered status.");
                exit;
            }
        } catch (Exception $e) {
            // Handle any exceptions during the update process
            http_response_code(500);
            echo "An unexpected error occurred: " . $e->getMessage();
            // error_log("Exception during completeOrder for ID {$orderId}: " . $e->getMessage
            exit;
        }
    }

    function create() {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . " ? controller = Auth&action = login", true, 301);
            exit;
        }
        // if ($_SESSION['user']['role'] == 'user') {
        //     header("Location: " . INSTALL_URL, true, 301);
        //     exit;
        // }
        // Only restrict access to certain actions for regular users, not the entire controller
        $currentAction = $_GET['action'] ?? 'index';

        // Admin-only actions - adjust this list as needed
        $adminOnlyActions = ['list', 'edit', 'delete', 'bulkDelete', 'export', 'print'];

        // If user is trying to access an admin-only action
        if ($_SESSION['user']['role'] == 'user' && in_array($currentAction, $adminOnlyActions)) {
            header("Location: " . INSTALL_URL, true, 301);
            exit;
        }

        date_default_timezone_set($this->settings['timezone']);

        $orderModel = new \App\Models\Order();
        $OrderPalletsModel = new \App\Models\OrderPallets();
        $palletModel = new \App\Models\Pallet();
        $userModel = new \App\Models\User();
        $courierModel = new \App\Models\Courier();
        $notificationModel = new \App\Models\Notification();
        $courierTrackingModel = new \App\Models\CourierTracking(); // Add this line
        $mailer = new \App\Helpers\mailer\Mailer();
        $currency = $this->settings['currency']; // $this->settings['currency_code'], set manually to currency, since local+modded.

        if (!empty($_POST['send'])) {
            $palletIds = $_POST['parcel_id'] ?? [];
            $quantities = $_POST['quantity'] ?? [];

            // Validate start and end locations
            $startLocationType = $_POST['startLocationType'] ?? null;
            $endLocationType = $_POST['endLocationType'] ?? null;

            if ($startLocationType === 'office' && empty($_POST['startOfficeCoords'])) {
                $error_message = "Please select a start office.";
            } elseif ($startLocationType === 'address' && empty($_POST['startAddressCoords'])) {
                $error_message = "Please enter a start address.";
            }

            if ($endLocationType === 'office' && empty($_POST['endOfficeCoords'])) {
                $error_message = "Please select an end office.";
            } elseif ($endLocationType === 'address' && empty($_POST['endAddressCoords'])) {
                $error_message = "Please enter an end address.";
            }
            // Coords extraction
            // Extract coordinates
            $startCoords = null;
            $endCoords = null;

            if ($startLocationType === 'office' && !empty($_POST['startOfficeCoords'])) {
                $startCoords = explode(',', $_POST['startOfficeCoords']);
            } elseif ($startLocationType === 'address' && !empty($_POST['startAddressCoords'])) {
                $startCoords = explode(',', $_POST['startAddressCoords']);
            }

            if ($endLocationType === 'office' && !empty($_POST['endOfficeCoords'])) {
                $endCoords = explode(',', $_POST['endOfficeCoords']);
            } elseif ($endLocationType === 'address' && !empty($_POST['endAddressCoords'])) {
                $endCoords = explode(',', $_POST['endAddressCoords']);
            }

            // Validate quantities against available pallet quantities
            $quantityError = false;
            $error_message = null;

            // Log the pallet IDs and quantities for debugging
            error_log("Pallet IDs: " . print_r($palletIds, true));
            error_log("Quantities: " . print_r($quantities, true));
            if (count($palletIds) !== count($quantities)) {
                $error_message = "Error: Pallet IDs and Quantities arrays are not aligned!";
                error_log($error_message); // Log the error
                // Consider adding a return here to stop processing and prevent further errors
            }

            foreach ($palletIds as $key => $palletId) {
                $pallet = $palletModel->get($palletId);
                if ($quantities[$key] > $pallet['stock']) {
                    $error_message = "Quantity for {$pallet['name']} exceeds available stock.";
                    $quantityError = true;
                    break;
                }
            }

            // Check delivery date
            $deliveryDateStr = $_POST['delivery_date'] ?? date($this->settings['date_format']);
            $dateFormat = $this->settings['date_format'];
            $date = \DateTime::createFromFormat($dateFormat, $deliveryDateStr);

            if (!$date) {
                $error_message = "Invalid delivery date format. Please use " . $dateFormat;
                $quantityError = true;
            } else {
                $deliveryDate = $date->getTimestamp();
                $today = strtotime(date('Y-m-d'));
                $closingTime = strtotime($this->settings['closing_time']);

                if ($deliveryDate < $today) {
                    $error_message = "Delivery date must be at least " . date($dateFormat, $today);
                    $quantityError = true;
                } elseif ($deliveryDate == $today && date('H:i') > $closingTime) {
                    $tomorrow = strtotime('+1 day', $today);
                    $error_message = "It's past closing time. Delivery date must be at least " . date($dateFormat, $tomorrow);
                    $quantityError = true;
                }
            }

            if (!$quantityError && !$error_message) {
                // Initialize variables
                $productPrice = 0;
                $totalQuantity = 0;
                $cashOnDelivery = isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash';

                // Debug value to loop-around.
                $ItemCost = 0;

                // First pass: calculate product price and total quantity
                foreach ($palletIds as $key => $palletId) {
                    $pallet = $palletModel->get($palletId);
                    $quantity = is_numeric($quantities[$key]) ? intval($quantities[$key]) : 0;
                    if ($quantity <= 0) {
                        $error_message = "Invalid quantity provided. Please check your input.";
                        // Handle the error (e.g., log it, display a message to the user)
                        $quantityError = true;
                        break; // Exit the loop since there's an error
                    }

                    // For document category, quantity is always 1
                    if ($pallet['category'] === 'document') {
                        $quantity = 1;
                        $quantities[$key] = 1;
                    }

                    // Get weight and dimensions
                    $weight = $pallet['weight_kg'] ?? 0;
                    $length = $pallet['size_x_cm'] ?? 0;
                    $width = $pallet['size_y_cm'] ?? 0;
                    $height = $pallet['size_z_cm'] ?? 0;

                    // Calculate price
                    $itemPrice = $this->calculatePalletPrice($weight, $length, $width, $height);
                    $ItemCost = $itemPrice; // Debug value to loop-around.
                    $itemTotal = $itemPrice * $quantity;

                    // Add to totals
                    $productPrice += $itemTotal;
                    $totalQuantity += $quantity;
                    $orderData['quantity'] = $totalQuantity; // Set the quantity in the order data
                }

                // Calculate COD fee if applicable
                $codFee = $cashOnDelivery ? $productPrice * 0.015 : 0;
                $totalAmount = $productPrice + $codFee;
                // $totalQuantity = ; // Total quantity is the sum of all quantities. Hardcoded since no discounts, we couriers.
                // Create order data
                $orderData = [
                    'last_processed' => time(),
                    'tracking_number' => \Utility::generateRandomString(),
                    'category' => $pallet['category'] ?? 'Existing',
                    'delivery_date' => $deliveryDate,
                    'cash_on_delivery' => $cashOnDelivery ? 1 : 0,
                    'start_point' => $startLocationType === 'office' ? ($_POST['startOfficeName'] . " (Office)") : $_POST['startAddressName'],
                    'end_destination' => $endLocationType === 'office' ? ($_POST['endOfficeName'] . " (Office)") : $_POST['endAddressName'],
                    'status' => $_POST['status'] ?? 'pending',
                    'product_name' => ($pallet['name'] . " (" . ucfirst($pallet ['category']) . ")") ?? 'Whatever, now it is a parcel and it is a product. Therefore, it is a product.',
                    'product_price' => $productPrice,
                    'total_amount' => $totalAmount,
                    'created_at' => time()
                ];

                // Save the order
                $orderId = $orderModel->save($orderData + $_POST);

                if ($orderId) {
                    // Now process each pallet in the order
                    foreach ($palletIds as $key => $palletId) {
                        $pallet = $palletModel->get($palletId);
                        $quantity = intval($quantities[$key]);

                        // For document category, ensure quantity is 1
                        if ($pallet['category'] === 'document') {
                            $quantity = 1;
                        }

                        // Get weight and dimensions
                        $weight = $pallet['weight_kg'] ?? 0;
                        $length = $pallet['size_x_cm'] ?? 0;
                        $width = $pallet['size_y_cm'] ?? 0;
                        $height = $pallet['size_z_cm'] ?? 0;

                        // Calculate price for this item
                        $itemPrice = $this->calculatePalletPrice($weight, $length, $width, $height);
                        $itemTotal = $itemPrice * $quantity;

                        // Calculate this item's contribution to the COD fee
                        $itemCodFee = $cashOnDelivery ? $itemTotal * 0.015 : 0;

                        // Create the order pallet record
                        $orderPalletData = [
                            'order_id' => $orderId,
                            'pallet_id' => $palletId,
                            'quantity' => $quantity,
                            'category' => $pallet['category'], // Category of the pallet
                            'price' => $itemPrice, // Individual pallet price (no COD)
                            'subtotal' => $itemCodFee  // This item's COD fee
                        ];

                        if (!$OrderPalletsModel->save($orderPalletData)) {
                            $error_message = "Failed to save order pallets. Please try again.";
                            break;
                        }

                        // Update the pallet stock
                        $updatedQuantity = $pallet['stock'] - $quantity;
                        $updateSuccess = $palletModel->update([
                            'id' => $palletId,
                            'stock' => $updatedQuantity
                        ]);

                        if (!$updateSuccess) {
                            $error_message = "Failed to update pallet stock for {$pallet['name']}. Please try again.";
                            break;
                        }
                    }

                    if ($startCoords && $endCoords) {
                        // Get courier details
                        $courierId = $_POST['courier_id'] ?? null;
                        $courier = $userModel->get($courierId);

                        $courierModel = new \App\Models\Courier();
                        $courierInfo_ForBusyStat = $courierModel->get($courierId);

                        if ($courier) {
                            // Calculate estimated arrival time
                            // For simplicity, let's set it to 24 hours from now
                            // $estimatedArrival = date('Y-m-d H:i:s', strtotime('+24 hours'));
                            // Add this code to handle business hours:
                            $currentTime = time();
                            $closingTime = strtotime($this->settings['closing_time']);
                            $openingTime = strtotime($this->settings['opening_time']);
                            $currentHour = date('H:i', $currentTime);

                            $deliveryHours = (int) $_POST['delivery_time_hours'] ?? 2; // Default to 2 hours if not set
                            $deliveryMinutes = (int) $_POST['delivery_time_minutes'] ?? 0; // Default to 0 minutes if not set

                            // Function to calculate adjusted arrival time
                            function calculateArrivalTime(
                                    $currentTime,
                                    $openingTime,
                                    $closingTime,
                                    $deliveryHours,
                                    $deliveryMinutes,
                                    $settings,
                                    $deliveryDate = null // Add parameter for delivery date
                            ) {
                                $deliveryTimestamp = ($deliveryHours * 3600) + ($deliveryMinutes * 60);
                                $arrivalTimestamp = $currentTime + $deliveryTimestamp;
                                $dayOfWeek = date('N', $currentTime); // 1 for Monday, 7 for Sunday
                                $currentHourMinute = date('H:i', $currentTime);
                                $currentDate = date('Y-m-d', $currentTime);

                                $effectiveOpeningTime = strtotime($settings['opening_time']);
                                $effectiveClosingTime = strtotime($settings['closing_time']);

                                // If a specific delivery date is requested
                                if ($deliveryDate && $deliveryDate != $currentDate) {
                                    // Get day of week for the selected delivery date
                                    $deliveryDayOfWeek = date('N', $deliveryDate);

                                    // Check if delivery date is on a weekend
                                    $isWeekend = ($deliveryDayOfWeek >= 6); // 6=Saturday, 7=Sunday

                                    if ($isWeekend && $settings['weekend_operation'] == 0) {
                                        // Weekend delivery requested but weekend operations are disabled
                                        // Find the next Monday after the requested delivery date
                                        $deliveryDateObj = new DateTime(date('Y-m-d', $deliveryDate));
                                        $daysUntilMonday = (8 - $deliveryDayOfWeek) % 7;
                                        $deliveryDateObj->modify("+$daysUntilMonday days");
                                        $adjustedDeliveryDate = $deliveryDateObj->getTimestamp();

                                        // Use opening hour of that Monday + delivery time
                                        return date('Y-m-d H:i:s', strtotime(date('Y-m-d', $adjustedDeliveryDate) . ' ' . date('H:i:s', $effectiveOpeningTime)) + $deliveryTimestamp);
                                    } else {
                                        // Use the appropriate opening/closing times based on whether it's a weekend
                                        if ($isWeekend && $settings['weekend_operation'] == 1) {
                                            $effectiveOpeningTime = strtotime($settings['weekend_opening_time']);
                                            $effectiveClosingTime = strtotime($settings['weekend_closing_time']);
                                        }

                                        // Use the opening hour of the requested day + delivery time
                                        return date('Y-m-d H:i:s', strtotime(date('Y-m-d', $deliveryDate) . ' ' . date('H:i:s', $effectiveOpeningTime)) + $deliveryTimestamp);
                                    }
                                }

                                // For same-day delivery requests, proceed with the original logic:
                                if ($settings['weekend_operation'] == 1) {
                                    // Weekend operation is enabled, use weekend times
                                    if ($dayOfWeek >= 6) { // Saturday or Sunday
                                        $effectiveOpeningTime = strtotime($settings['weekend_opening_time']);
                                        $effectiveClosingTime = strtotime($settings['weekend_closing_time']);
                                    }
                                } else {
                                    // Weekend operation is disabled
                                    if ($dayOfWeek >= 6) { // Saturday or Sunday
                                        // Find the next Monday
                                        $daysUntilMonday = (8 - $dayOfWeek) % 7;
                                        $nextMondayTimestamp = strtotime(date('Y-m-d', strtotime("+$daysUntilMonday days", $currentTime)) . ' ' . date('H:i:s', $effectiveOpeningTime));
                                        return date('Y-m-d H:i:s', $nextMondayTimestamp + $deliveryTimestamp);
                                    }
                                }

                                $arrivalHourMinute = date('H:i', $arrivalTimestamp);
                                $arrivalDay = date('Y-m-d', $arrivalTimestamp);

                                if (strtotime($arrivalHourMinute) > $effectiveClosingTime) {
                                    // Arrival is after closing, set to next day opening
                                    $nextDayTimestamp = strtotime(date('Y-m-d', strtotime('+1 day', $currentTime)) . ' ' . date('H:i:s', $effectiveOpeningTime));
                                    return date('Y-m-d H:i:s', $nextDayTimestamp + $deliveryTimestamp);
                                } elseif (strtotime($currentHourMinute) < $effectiveOpeningTime) {
                                    // Current time is before opening, set to today's opening
                                    $todayOpeningTimestamp = strtotime($arrivalDay . ' ' . date('H:i:s', $effectiveOpeningTime));
                                    return date('Y-m-d H:i:s', $todayOpeningTimestamp + $deliveryTimestamp);
                                } elseif (date('H:i', $currentTime) > date('H:i', strtotime($settings['order_cut_off_time']))) {
                                    // After cut-off, schedule for the next business day
                                    $nextDay = strtotime('+1 day', $currentTime);
                                    $nextDayOfWeek = date('N', $nextDay);
                                    if ($settings['weekend_operation'] == 0 && $nextDayOfWeek >= 6) {
                                        // If weekend operation is off and the next day is a weekend, find the next Monday
                                        $daysUntilMonday = (8 - $nextDayOfWeek) % 7;
                                        $nextBusinessDayTimestamp = strtotime(date('Y-m-d', strtotime("+$daysUntilMonday days", $nextDay)) . ' ' . date('H:i:s', $effectiveOpeningTime));
                                        return date('Y-m-d H:i:s', $nextBusinessDayTimestamp + $deliveryTimestamp);
                                    } else {
                                        // Otherwise, the next day is a weekday or weekend operation is on
                                        $nextBusinessDayTimestamp = strtotime(date('Y-m-d', $nextDay) . ' ' . date('H:i:s', $effectiveOpeningTime));
                                        return date('Y-m-d H:i:s', $nextBusinessDayTimestamp + $deliveryTimestamp);
                                    }
                                } else {
                                    // Arrival is within business hours
                                    return date('Y-m-d H:i:s', $arrivalTimestamp);
                                }
                            }

                            // Get the delivery date from the form
                            $deliveryDateTimestamp = $deliveryDate; // This should be the timestamp from earlier in your code

                            $estimatedArrival = calculateArrivalTime(
                                    $currentTime,
                                    $openingTime,
                                    $closingTime,
                                    $deliveryHours,
                                    $deliveryMinutes,
                                    $this->settings,
                                    $deliveryDateTimestamp // Pass the delivery date timestamp
                            );

                            // Check if current time is after closing hours
                            // if (strtotime($currentHour) > $closingTime) {
                            //     // Set estimated arrival to next day opening time + delivery time
                            //     $estimatedArrival = date('Y-m-d', strtotime('+1 day')) . ' ' . date('H:i:s', $openingTime);
                            // } else if (strtotime($currentHour) < $openingTime) {
                            //     // If before opening hours, set to today's opening time + delivery time
                            //     $estimatedArrival = date('Y-m-d') . ' ' . date('H:i:s', $openingTime);
                            // }
                            // Prepare tracking data
                            $trackingData = [
                                'courier_name' => $courier['name'],
                                'order_id' => $orderId,
                                'user_id' => $courierId,
                                'start_point_lat' => floatval($startCoords[0]),
                                'start_point_lng' => floatval($startCoords[1]),
                                'end_destination_lat' => floatval($endCoords[0]),
                                'end_destination_lng' => floatval($endCoords[1]),
                                'current_location_lat' => floatval($startCoords[0]), // Initially at start point
                                'current_location_lng' => floatval($startCoords[1]), // Initially at start point
                                'last_updated' => date('Y-m-d H:i:s'),
                                'estimated_arrival_time' => $estimatedArrival,
                                'created_at' => date('Y-m-d H:i:s')
                            ];

                            // Save tracking data
                            $trackingId = $courierTrackingModel->save($trackingData);

                            if (!$trackingId) {
                                // Log error but continue with order process
                                error_log("Failed to create courier tracking for order #$orderId");
                            } else {
                                // Successfully saved tracking data, now update the courier's is_busy status
                                $courierToUpdate = $courierModel->getFirstBy(['user_id' => $courierId]);

                                if ($courierToUpdate) {
                                    $updateResult = $courierModel->update(['id' => $courierToUpdate['id'], 'is_busy' => 1]);
                                    if (!$updateResult) {
                                        error_log("Failed to update courier (user_id: $courierId) is_busy status.");
                                    }
                                } else {
                                    error_log("Courier with user_id: $courierId not found.");
                                }
                            }
                        }
                    }

                    if (!isset($error_message)) {
                        // Send notifications
                        $notificationModel->save([
                            'user_id' => $_POST['user_id'],
                            'message' => "Your order #{$orderId} has been created successfully! Total amount: {$totalAmount}",
                            'link' => INSTALL_URL . "?controller=Order&action=details&id=$orderId",
                            'created_at' => time()
                        ]);

                        $notificationModel->save([
                            'user_id' => $_POST['courier_id'],
                            'message' => "New delivery assigned to you. Order #{$orderId}",
                            'link' => INSTALL_URL . "?controller=Order&action=details&id=$orderId",
                            'created_at' => time()
                        ]);

                        // Send email if enabled
                        if ($this->settings['email_sending'] == 'enabled') {
                            $order = $orderModel->get($orderId);
                            $customer = $userModel->get($order['user_id']);
                            $courier = $userModel->get($order['courier_id']);
                            $OrderPallets = $OrderPalletsModel->getAll(['order_id' => $orderId]);

                            foreach ($OrderPallets as &$pallet) {
                                $palletDetails = $palletModel->get($pallet['pallet_id']);
                                $pallet['name'] = $palletDetails['name'] ?? 'Unknown';
                            }

                            $emailContent = $this->generateOrderEmail($order, $customer, $courier, $OrderPallets, "Order Confirmation");
                            $mailer->sendMail($customer['email'], "Order Confirmation #{$orderId}", $emailContent);
                        }

                        header("Location: " . INSTALL_URL, true, 301);
                        exit;
                    }
                } else {
                    $error_message = "Failed to create the order. Please try again.";
                }
            }
        }

        $arr = [
            'users' => $userModel->getAll(),
            'pallets' => $palletModel->getAll(),
            'couriers' => $courierModel->getAll(),
            'currency' => $currency,
            'error_message' => $error_message ?? null
        ];

        $this->view($this->layout, $arr);
    }

    // Add for Status Change stuff later

    function details() {
        $orderModel = new \App\Models\Order();
        $OrderPalletsModel = new \App\Models\OrderPallets();
        $palletModel = new \App\Models\Pallet();
        $userModel = new \App\Models\User();

        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }

        if (empty($_GET['id'])) {
            header("Location: " . INSTALL_URL . "?controller=Order&action=list", true, 301);
            exit;
        }

        if ($_SESSION['user']['role'] == 'user') {
            $userOrders = $orderModel->getAll(['user_id' => $_SESSION['user']['id']]);
            $userOrderIds = array_column($userOrders, 'id');
            if (!in_array($_GET['id'], $userOrderIds)) {
                header("Location: " . INSTALL_URL, true, 301);
                exit;
            }
        }

        $orderId = intval($_GET['id']);
        $orderData = $orderModel->get($orderId);

        if (!$orderData) {
            header("Location: " . INSTALL_URL . "?controller=Order&action=list", true, 301);
            exit;
        }

        $customerData = $userModel->get($orderData['user_id']);
        $courierData = $userModel->get($orderData['courier_id']);

        $opts = array();
        $opts['order_id'] = $orderId;
        $OrderPallets = $OrderPalletsModel->getAll($opts);

        foreach ($OrderPallets as &$pallet) {
            $palletDetails = $palletModel->get($pallet['pallet_id']);
            $pallet['name'] = $palletDetails['name'] ?? 'Unknown';
        }

        $data = [
            'order' => $orderData,
            'customer' => $customerData,
            'courier' => $courierData,
            'pallets' => $OrderPallets,
            'currency' => $this->settings['currency'], // $this->settings['currency_code'], set manually to currency, since local+modded.
        ];

        $this->view($this->layout, $data);
    }

    function delete() {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }
        if ($_SESSION['user']['role'] == 'user') {
            header("Location: " . INSTALL_URL, true, 301);
            exit;
        }

        $palletModel = new \App\Models\Pallet();
        $orderModel = new \App\Models\Order();
        $OrderPalletsModel = new \App\Models\OrderPallets();
        $userModel = new \App\Models\User();

        if (!empty($_POST['id'])) {
            $orderId = $_POST['id'];

            $OrderPallets = $OrderPalletsModel->getAll(['order_id' => $orderId]);
            foreach ($OrderPallets as $orderpallet) {
                $pallet = $palletModel->getFirstBy(['id' => $orderpallet['pallet_id']]);
                $pallet['stock'] += $orderpallet['quantity'];
                $palletModel->update($pallet);
            }
            $OrderPalletsModel->deleteBy(['order_id' => $orderId]);

            $orderModel->delete($orderId);
        }

        // Retrieve all orders from the database
        $orders = $orderModel->getAll();

        // Format orders for display
        foreach ($orders as &$order) {
            $order['customer_name'] = $userModel->get($order['user_id'])['name'] ?? 'Unknown';
            $order['name'] = $userModel->get($order['courier_id'])['name'] ?? 'Unknown';
            $order['delivery_date'] = date($this->settings['date_format'], $order['delivery_date']);
        }

        //$this->view('ajax', ['orders' => $orders, 'currency' => $this->settings['currency_code']]);
        $this->view('ajax', ['orders' => $orders, 'currency' => $this->settings['currency']]); // $this->settings['currency_code'], set manually to currency, since local+modded.
    }

    function pay() {
        if (!empty($_GET['order_id'])) {
            $orderId = $_GET['order_id'];
            $orderModel = new \App\Models\Order();
            $userModel = new \App\Models\User();
            $OrderPalletsModel = new \App\Models\OrderPallets();

            $order = $orderModel->get($orderId);
            $user = $userModel->get($order['user_id']);
            $OrderPallets = $OrderPalletsModel->getAll(['order_id' => $orderId]);

            $this->view($this->layout, [
                'currency' => $this->settings['currency'], // $this->settings['currency_code'], set manually to currency, since local+modded.
                'order' => $order,
                'user' => $user,
                'order_pallets' => $OrderPallets
            ]);
        }
    }

    // Controller method to handle the return from PayPal
    public function pay_success() {
        // Get the order ID from the URL parameter
        $orderId = $_GET['order_id'];

        $orderModel = new \App\Models\Order();
        $userModel = new \App\Models\User();
        // Load the order from the database
        $order = $orderModel->get($orderId);
        $user = $userModel->getFirstBy(['id' => $order['user_id']]);

        // If the order exists and the payment was successful, mark it as paid
        if ($order) {
            // Show a success message or redirect to a success page
            $this->view($this->layout, ['order' => $order, 'user' => $user]);
        }
    }

    // Controller method to handle the cancellation from PayPal
    public function pay_cancel() {
        // Get the order ID from the URL parameter
        $orderId = $_GET['order_id'];

        $orderModel = new \App\Models\Order();
        $userModel = new \App\Models\User();
        // Load the order from the database
        $order = $orderModel->get($orderId);
        $user = $userModel->getFirstBy(['id' => $order['user_id']]);

        if ($order) {
            // Show a cancellation message or redirect to a cancellation page
            $this->view($this->layout, ['order' => $order, 'user' => $user]);
        }
    }

    function paypal_ipn() {
        // PayPal verifies the IPN message
        $orderModel = new \App\Models\Order();
        $notificationModel = new \App\Models\Notification();
        $userModel = new \App\Models\User();
        $orderId = $_POST['custom']; //  Get the order ID from PayPal's "custom" field
        $order = $orderModel->get($orderId);
        $user = $userModel->getFirstBy(['id' => $order['user_id']]);

        // Step 1: Verify IPN message with PayPal (to avoid fraud)
        $url = 'https://www.paypal.com/cgi-bin/webscr';
        $data = array(
            'cmd' => '_notify-validate',
            'tx' => $_POST['txn_id'], // PayPal transaction ID
            'amt' => $_POST['mc_gross'], // Total amount paid
                // 'currency_code' => $_POST['mc_currency'], // Currency code
        );

        // Send the IPN data back to PayPal for validation
        $response = file_get_contents($url . '?' . http_build_query($data));

        // Step 2: If PayPal confirms the payment is valid
        if ($response == "VERIFIED") {
            // Update the order status based on payment confirmation
            if ($_POST['payment_status'] == 'Completed') {
                // Payment is successful, update order status
                $order['status'] = 'paid';
                $orderModel->update($order);
                $notificationModel->save([
                    'user_id' => $user['id'],
                    'message' => "Your order #$orderId has been paid successfully!",
                    'link' => INSTALL_URL . "?controller=Order&action=pay_success&order_id=$orderId",
                    'created_at' => time()
                ]);
            }
        } else {
            // Payment not verified, handle the error (perhaps log it)
            error_log("Invalid IPN message: " . json_encode($_POST));
        }

        // Step 3: Handle canceled or failed payment (if needed)
        if ($_POST['payment_status'] == 'Failed' || $_POST['payment_status'] == 'Canceled') {
            // Update the order status as canceled
            $order['status'] = 'cancelled';
            $orderModel->update($order);
            $notificationModel->save([
                'user_id' => $user['id'],
                'message' => "Your order #$orderId has been cancelled!",
                'link' => INSTALL_URL . "?controller=Order&action=pay_cancel&order_id= $orderId",
                'created_at' => time()
            ]);
        }
    }

    function bulkDelete() {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }
        if ($_SESSION['user']['role'] == 'user') {
            header("Location: " . INSTALL_URL, true, 301);
            exit;
        }

        $orderModel = new \App\Models\Order();
        $OrderPalletsModel = new \App\Models\OrderPallets();
        $userModel = new \App\Models\User();

        if (!empty($_POST['ids']) && is_array($_POST['ids'])) {
            $orderIds = $_POST['ids'];

            $inOrderIds = implode(', ', $orderIds);
            $optsForOrderpallet = ["order_id IN ($inOrderIds) AND 1 " => '1'];
            $OrderPalletsModel->deleteBy($optsForOrderpallet);
            $optsForOrder = ["id IN ($inOrderIds) AND 1 " => '1'];
            $orderModel->deleteBy($optsForOrder);
        }

        // Retrieve all orders from the database
        $orders = $orderModel->getAll();

        // Format orders for display
        foreach ($orders as &$order) {
            $order['customer_name'] = $userModel->get($order['user_id'])['name'] ?? 'Unknown';
            $order['name'] = $userModel->get($order['courier_id'])['name'] ?? 'Unknown';
            $order['delivery_date'] = date($this->settings['date_format'], $order['delivery_date']);
        }

        //$this->view('ajax', ['orders' => $orders, 'currency' => $this->settings['currency_code']]);
        $this->view('ajax', ['orders' => $orders, 'currency' => $this->settings['currency']]); // $this->settings['currency_code'], set manually to currency, since local+modded.
    }

    function print() {
        if (isset($_POST['orderData'])) {
            // Decode the JSON data
            $orders = json_decode($_POST['orderData'], true);

            if (!$orders || empty($orders)) {
                echo "No orders to print";
                exit;
            }
        }

        $this->view('ajax', ['orders' => $orders]);
    }

    function edit() {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }
        if ($_SESSION['user']['role'] == 'user') {
            header("Location: " . INSTALL_URL, true, 301);
            exit;
        }

        $orderModel = new \App\Models\Order();
        $OrderPalletsModel = new \App\Models\OrderPallets();
        $palletModel = new \App\Models\Pallet();
        $userModel = new \App\Models\User();
        $notificationModel = new \App\Models\Notification();
        $mailer = new \App\Helpers\mailer\Mailer();
        $currency = $this->settings['currency'];

        if (!empty($_POST['id'])) {
            $orderId = $_POST['id'];
            $order = $orderModel->get($orderId);
            $currentOrderPallets = $OrderPalletsModel->getAll(['order_id' => $orderId]);

            $currentQuantities = [];
            $palletIds = array_column($currentOrderPallets, 'pallet_id');
            $palletData = $palletModel->getMultiple($palletIds);

            foreach ($currentOrderPallets as $pallet) {
                $currentQuantities[$pallet['pallet_id']] = ($currentQuantities[$pallet['pallet_id']] ?? 0) + $pallet['quantity'];
            }

            $quantityError = false;
            $newQuantities = [];
            $newOrderPallets = [];

            foreach ($_POST['pallet_id'] as $key => $palletId) {
                $quantity = $_POST['quantity'][$key];
                $newQuantities[$palletId] = ($newQuantities[$palletId] ?? 0) + $quantity;
                $newOrderPallets[] = ['pallet_id' => $palletId, 'quantity' => $quantity];
            }

            foreach ($newQuantities as $palletId => $newTotalQuantity) {
                $pallet = $palletData[$palletId] ?? $palletModel->get($palletId);
                $currentTotalQuantity = $currentQuantities[$palletId] ?? 0;
                $stockChange = $newTotalQuantity - $currentTotalQuantity;
                $updatedStock = $pallet['stock'] - $stockChange;

                if ($updatedStock < 0) {
                    $error_message = "Quantity for {$pallet['name']} exceeds available stock.";
                    $quantityError = true;
                    break;
                }
            }

            if (!$quantityError) {
                // Calculate the total amount using the new pricing method
                $total = 0;
                $cashOnDelivery = isset($_POST['cash_on_delivery']) && $_POST['cash_on_delivery'] == '1';
                $codFee = 0;

                foreach ($newQuantities as $palletId => $quantity) {
                    $pallet = $palletData[$palletId] ?? $palletModel->get($palletId);

                    // Get weight and dimensions from pallet
                    $weight = $pallet['weight_kg'] ?? 0;
                    $length = $pallet['size_x_cm'] ?? 0;
                    $width = $pallet['size_y_cm'] ?? 0;
                    $height = $pallet['size_z_cm'] ?? 0;

                    // Calculate price based on weight/dimensions
                    $itemPrice = $this->calculatePalletPrice($weight, $length, $width, $height);

                    // For document category, quantity is always 1
                    if ($pallet['category'] === 'document') {
                        $quantity = 1;
                    }

                    // Calculate total for this item
                    $itemTotal = $itemPrice * $quantity;
                    $total += $itemTotal;
                }

                if ($cashOnDelivery) {
                    $codFee = $total * 0.015; // 1.5% of total amount
                    $total += $codFee;
                }

                $orderData = [
                    'last_processed' => time(),
                    'tracking_number' => \Utility::generateRandomString(),
                    'category' => $pallet['category'] ?? 'Existing',
                    'delivery_date' => $_POST['delivery_date'],
                    'cash_on_delivery' => $cashOnDelivery ? 1 : 0,
                    'start_point' => $order['start_point'],
                    'end_destination' => $order['end_destination'],
                    'status' => $_POST['status'] ?? 'pending',
                    'product_name' => $order['product_name'] ?? 'Whatever, now it is a parcel and it is a product. Therefore, it is a product.',
                    'total_amount' => $order['total_amount'],
                    'quantity' => $order['quantity'],
                    'created_at' => time()
                ];

                if (!$orderModel->update(['id' => $orderId] + $orderData + $_POST)) {
                    $error_message = "Failed to update order with id " . $orderId;
                }

                $OrderPalletsModel->deleteBy(['order_id' => $orderId]);

                // Update stock for pallets based on total difference
                foreach ($newOrderPallets as $orderpallet) {
                    $palletId = $orderpallet['pallet_id'];
                    $quantity = $orderpallet['quantity'];
                    $palletDetails = $palletData[$palletId] ?? $palletModel->get($palletId);

                    // Calculate price based on dimensions and weight
                    $weight = $palletDetails['weight_kg'] ?? 0;
                    $length = $palletDetails['size_x_cm'] ?? 0;
                    $width = $palletDetails['size_y_cm'] ?? 0;
                    $height = $palletDetails['size_z_cm'] ?? 0;
                    $price = $this->calculatePalletPrice($weight, $length, $width, $height);

                    // Check for document category
                    if ($palletDetails['category'] === 'document') {
                        $quantity = 1;
                    }

                    $subtotal = $price * $quantity;

                    $OrderPalletsModel->save([
                        'order_id' => $orderId,
                        'pallet_id' => $palletId,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ]);
                }

                foreach ($newQuantities as $palletId => $newTotalQuantity) {
                    $palletDetails = $palletData[$palletId] ?? $palletModel->get($palletId);
                    $currentTotalQuantity = $currentQuantities[$palletId] ?? 0;
                    $stockChange = $newTotalQuantity - $currentTotalQuantity;
                    $updatedStock = $palletDetails['stock'] - $stockChange;

                    if (!$palletModel->update(['id' => $palletId, 'stock' => $updatedStock])) {
                        $error_message = "Failed to update p allet stock for {$palletDetails['name']}. Please try again.";
                        break;
                    }
                }
            }

            if (!isset($error_message)) {
                $notificationModel->save([
                    'user_id' => $_POST['user_id'],
                    'message' => "Your order #$orderId has been edited successfully!",
                    'link' => INSTALL_URL . "?controller=Order&action=details&id=$orderId",
                    'created_at' => time()
                ]);
                if ($this->settings['email_sending'] == 'en abled') {
                    $order = $orderModel->get($orderId);
                    $customer = $userModel->get($order['user_id']);
                    $courier = $userModel->get($order['courier_id']);

                    $OrderPallets = $OrderPalletsModel->getAll(['order_id' => $orderId]);
                    foreach ($OrderPallets as &$orderpallet) {
                        $orderpalletDetails = $palletModel->get($orderpallet['pallet_id']);
                        $orderpallet['name'] = $orderpalletDetails['name'] ?? 'Unknown';
                    }

                    $emailContent = $this->generateOrderEmail($order, $customer, $courier, $OrderPallets, "Order Update");

                    $mailer->sendMail($customer['email'], "Order Update #{$orderId}", $emailContent);
                }
                header("Location: " . INSTALL_URL . "?controller=Order&action=list ", true, 301);
                exit;
            }
        }

        $orderId = $_GET['order_id'];
        $OrderPallets = $OrderPalletsModel->getAll(['order_id' => $orderId]);

        $palletQuantities = [];
        foreach ($OrderPallets as $orderpallet) {
            $palletId = $orderpallet['pallet_id'];
            if (!isset($palletQuantities[$palletId])) {
                $palletQuantities[$palletId] = 0;
            }
            $palletQuantities[$palletId] += $orderpallet['quantity'];
        }

        $arr = [
            'order' => $orderModel->get($orderId),
            'OrderPallets' => $OrderPallets,
            'users' => $userModel->getAll(),
            'pallets' => $palletModel->getAll(),
            'couriers' => $userModel->getAll(['role' => 'courier']),
            'palletQuantities' => $palletQuantities,
            'currency' => $currency,
            'error_message' => $error_message ?? null
        ];

        $this->view($this->layout, $arr);
    }

    function calculatePrice() {
        // var_dump($_POST); // Add this line to inspect the $_POST array

        $palletModel = new \App\Models\Pallet();
        $total = 0;
        $codFee = 0; // Initialize COD fee to 0
        $items = [];

        // Check if any products are selected
        if (empty($_POST['parcel_id'])) {
            $response = [
                'error' => 'Please select at least one product before calculating the price.'
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
            return;
        }

        foreach ($_POST['parcel_id'] as $key => $palletId) {
            $pallet = $palletModel->get($palletId);
            $quantity = $_POST['quantity'][$key];

            // Get weight and dimensions from pallet
            $weight = $pallet['weight_kg'] ?? 0; // Assuming weight is stored in kg
            $length = $pallet['size_x_cm'] ?? 0; // Assuming dimensions are in cm
            $width = $pallet['size_y_cm'] ?? 0;
            $height = $pallet['size_z_cm'] ?? 0;

            // Calculate price based on weight/dimensions
            $itemPrice = $this->calculatePalletPrice($weight, $length, $width, $height);

            // For document category, quantity is always 1
            if ($pallet['category'] === 'document') {
                $quantity = 1;
            }

            // Calculate total for this item
            $itemTotal = $itemPrice * $quantity;
            $total += $itemTotal;

            $items[] = [
                'name' => $pallet['name'],
                'price' => number_format($itemPrice, 2),
                'quantity' => $quantity,
                'subtotal' => number_format($itemTotal, 2)
            ];
        }

        // Check if cash on delivery is selected
        $cashOnDelivery = (isset($_POST['payment_method'])) && ($_POST['payment_method'] == 'cash');

        if ($cashOnDelivery) {
            $codFee = $total * 0.015; // 1.5% of  total amount
            $total += $codFee;
        }

        $response = [
            // Worth noting that product_price is the total price of all items, not the individual item price. Also, three labels. Product price is what is shown.
            'product_price' => number_format($total, 2),
            // 'cod_fee' => number_format($codFee, 2),
            // 'total' => number_format($total, 2),
            'items' => $items
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Calculate price for a pallet based on weight and dimensions
     */
    private function calculatePalletPrice($weight, $length, $width, $height) {
        // Check if this is a large dimension package
        $maxDimension = max($length, $width, $height);
        $minDimension = min($length, $width, $height);

        $volume = $length * $width * $height; // Volume in cm^3
        $minDimensionforPallets = 80 * 120 * 90; // Minimum volume for large pallets in cm^3

        if ($volume >= $minDimensionforPallets) {
            // Large dimension calculation
            $k = ($maxDimension / 100) * ($minDimension / 100) / 0.96; // Convert cm to meters

            if ($weight <= 15) {
                return $k * 150;
            } else {
                return 2 * $k * 150;
            }
        }
        // Regular pricing based on weight
        // WEIGHT IS NOT REGISTERED... FIX IT ;w; (2 minutes later, I fixed it :3 [It was weight_kg, not weight])
        if ($weight <= 3) {
            return 10;
        } elseif ($weight <= 6) {
            return 15;
        } elseif ($weight <= 10) {
            return 20;
        } elseif ($weight <= 20) {
            return 35;
        } elseif ($weight <= 50) {
            $extraKg = max(0, $weight - 20);
            return 35 + ($extraKg * 1);
        } else {
            // Over 50kg
            $extraKg = max(0, $weight - 50);
            return 30 + ($extraKg * 0.9);
        }
    }

    function export() {
        // Check if orderData is provided
        if (isset($_POST['orderData'])) {
            // Decode the JSON data
            $orders = json_decode($_POST['orderData'], true);

            if (!$orders || empty($orders)) {
                echo "No orders to export";
                exit;
            }
        }

        $format = isset($_POST['format']) ? $_POST['format'] : 'pdf';

        // Export based on format
        switch ($format) {
            case 'pdf':
                $this->exportAsPDF($orders);
                break;
            case 'excel':
                $this->exportAsExcel($orders);
                break;
            case 'csv':
                $this->exportAsCSV($orders);
                break;
            default:
                echo "Invalid export format";
                exit;
        }
    }

    private function exportAsPDF($orders) {
        if (ob_get_level()) {
            ob_end_clean();
        }
        require_once(__DIR__ . '/../Helpers/export/tcpdf/tcpdf.php');

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8');
        $pdf->SetCreator('Your App');
        $pdf->SetTitle('Orders Export');
        $pdf->SetHeaderData('', 0, 'Orders List', '');
        $pdf->setHeaderFont(array('helvetica', '', 12));
        $pdf->setFooterFont(array('helvetica', '', 10));
        $pdf->SetDefaultMonospacedFont('courier');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        $pdf->AddPage();

        // Generate HTML table with dynamic headers
        $html = $this->generateDynamicOrderTable($orders);
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $pdf->Output('orders_export.pdf', 'D');
        exit;
    }

    private function generateDynamicOrderTable($orders) {
        // Start HTML table
        $html = '<table border="1" cellpadding="5">
<thead>
    <tr>';

        // Define preferred header names
        $preferredHeaders = [
            'id' => 'Order ID',
            'tracking_number' => 'Tracking Number',
            'customer_name' => 'Customer',
            'courier_name' => 'Courier',
            'delivery_date' => 'Delivery Date',
            'formatted_total' => 'Total Price',
            'start_point' => 'Start Point',
            'end_destination' => 'End Destination',
            'status_text' => 'Status'
        ];

        if (!empty($orders) && is_array($orders[0])) {
            $orderedKeys = array_keys($orders[0]); // Get the exact order from first item
            // Generate headers in the exact same order as the first item in $orders
            foreach ($orderedKeys as $key) {
                if (!in_array($key, ['user_id', 'courier_id', 'status', 'total_amount'])) {
                    $displayName = $preferredHeaders[$key] ?? ucwords(str_replace('_', ' ', $key));
                    $html .= '<th>' . htmlspecialchars($displayName) . '</th>';
                }
            }

            $html .= '</tr>
    </thead>
    <tbody>';

            // Add order data
            foreach ($orders as $order) {
                $html .= '<tr>';

                foreach ($orderedKeys as $key) {
                    if (!in_array($key, ['user_id', 'courier_id', 'status', 'total_amount'])) {
                        $value = $order[$key] ?? 'N/A';
                        $html .= '<td>' . htmlspecialchars($value) . '</td>';
                    }
                }

                $html .= '</tr>';
            }
        } else {
            // Fallback for no data
            $html .= '<th>No Data Available</th></tr></thead><tbody><tr><td>No orders found</td></tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    private function exportAsExcel($orders) {
        require(__DIR__ . '/../Helpers/export/simplexlsxgen/src/SimpleXLSXGen.php');

        $data = [];

        if (!empty($orders) && is_array($orders[0])) {
            // Вземи оригиналния ред на колоните от първия елемент
            $headerRow = array_keys($orders[0]);
            $data[] = array_map(fn($h) => ucwords(str_replace('_', ' ', $h)), $headerRow);

            // Добави данните в същия ред
            foreach ($orders as $order) {
                $data[] = array_map(fn($key) => $order[$key] ?? 'N/A', $headerRow);
            }
        } else {
            $data[] = ['No Data Available'];
        }

        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs('orders_export.xlsx');
        exit;
    }

    private function exportAsCSV($orders) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="orders_export.csv"');

        $output = fopen('php://output', 'w');

        if (!empty($orders) && is_array($orders[0])) {
            // Вземи оригиналния ред на колоните
            $headerRow = array_keys($orders[0]);
            fputcsv($output, array_map(fn($h) => ucwords(str_replace('_', ' ', $h)), $headerRow));

            foreach ($orders as $order) {
                fputcsv($output, array_map(fn($key) => $order[$key] ?? 'N/A', $headerRow));
            }
        } else {
            fputcsv($output, ['No data available']);
        }

        fclose($output);
        exit;
    }

    private function generateOrderEmail($order, $customer, $courier, $pallets, $title) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Order Confirmation</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }

                    .email-container {
                        max-width: 600px;
                        margin: 20px auto;
                        background: #ffffff;
                        border-radius: 10px;
                        overflow: hidden;
                        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    }

                    .header {
                        background: #0073e6;
                        color: #ffffff;
                        text-align: center;
                        padding: 20px;
                        font-size: 24px;
                    }

                    .content {
                        padding: 20px;
                    }

                    .order-details {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 20px;
                        margin-bottom: 20px;
                    }

                    .detail-column {
                        flex: 1 1 45%;
                    }

                    .detail-column p {
                        margin: 5px 0;
                        font-size: 14px;
                    }

                    .p allets-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }

                    .pallets-table th,
                    .pallets-table td {
                        padding: 12px;
                        border: 1px solid #ddd;
                        text-align: left;
                    }

                    .pallets-table th {
                        background: #0073e6;
                        color: white;
                        font-weight: bold;
                    }

                    .foot er {
                        text-align: center;
                        padding: 15px;
                        background: #f8f8f8;
                        font-size: 12px;
                        color: #666;
                    }

                    @media screen and (max-width: 600px) {
                        .order-details {
                            flex-direction: column;
                        }

                        .detail-column {
                            width: 100%;
                        }
                    }
                </style>
            </head>

            <body>
                <div class="email-container">
                    <div class="header">
                        <?= htmlspecialchars($title) ?>
                    </div>
                    <div class="content">
                        <p style="font-size: 16px; color: #333;">Thank you for your order! Below are the details:</p>
                        <div class="order-details">
                            <div class="detail-column">
                                <p><strong>Order ID:</strong>
                                    <?= htmlspecialchars($order['id']) ?>
                                </p>
                                <p><strong>Customer:</strong>
                                    <?= htmlspecialchars($customer['name']) ?>
                                </p>
                                <p><strong>Address:</strong>
                                    <?= htmlspecialchars($order['address']) ?>
                                </p>
                                <p><strong>Region:</strong>
                                    <?= htmlspecialchars($order['region']) ?>
                                </p>
                            </div>
                            <div class="detail-column">
                                <p><strong>Tracking Number:</strong>
                                    <?php echo htmlspecialchars($order['tracking_number']); ?>
                                </p>
                                <p><strong>Courier:</strong>
                                    <?= htmlspecialchars($courier['name']) ?>
                                </p>
                                <p><strong>Delivery Date:</strong>
                                    <?= date($this->settings['date_format'], $order['delivery_date']) ?>
                                </p>
                                <p><strong>Status:</strong>
                                    <?= \Utility::$order_status[$order['status']] ?? 'Unknown' ?>
                                </p>
                                <p><strong>Total Price:</strong>
                                    <?= \Utility::getDisplayableAmount(htmlspecialchars(number_format($order['total_amount'], 2))) ?>
                                </p>
                            </div>
                        </div>
                        <h3 style="color: #0073e6; margin-top: 20px;">Order Summary</h3>
                        <table class="pallets-table">
                            <thead>
                                <tr>
                                    <th>pallet</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pallets as $pallet) { ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($pallet['name']) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($pallet['quantity']) ?>
                                        </td>
                                        <td>
                                            <?= \Utility::getDisplayableAmount(htmlspecialchars(number_format($pallet['price'], 2))) ?>
                                        </td>
                                        <td>
                                            <?= \Utility::getDisplayableAmount(htmlspecialchars(number_format($pallet['subtotal'], 2))) ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="footer">
                        <p>If you have any questions, please contact our c ustomer service.</p>
                        <p>This is an automated email, please do not reply.</p>
                    </div>
                </div>
            </body>

        </html>
        <?php
        return ob_get_clean();
    }

    public function checkDeliveryTime() {
        // Get closing time from settings
        $closingTime = isset($this->settings['closing_time']) ? $this->settings['closing_time'] : '17:00';
        list($closingHour, $closingMinute) = explode(':', $closingTime);

        // Get the desired date format from template settings
        $dateFormat = isset($this->settings['date_format']) ? $this->settings['date_format'] : 'Y-m-d';

        // Handle both direct page loading and POST requests - use today's date by default
        $today = date('Y-m-d'); // Today's date in Y-m-d format
        $selectedDate = isset($_POST['selected_date']) ? $_POST['selected_date'] : $today;
        $currentTime = isset($_POST['current_time']) ? $_POST['current_time'] : date('H:i');

        // Create DateTime objects for proper time manipulation
        $currentDateTime = new \DateTime("$selectedDate $currentTime");
        $closingDateTime = new \DateTime("$selectedDate $closingTime");

        // Estimated delivery time in minutes
        $deliveryTimeMinutes = isset($_POST['delivery_time']) ? intval($_POST['delivery_time']) : 0; // Default none
        // Calculate estimated delivery completion time
        $estimatedCompletionDateTime = clone $currentDateTime;
        $estimatedCompletionDateTime->modify("+$deliveryTimeMinutes minutes");

        // Format the estimated delivery time for display
        $estimatedDeliveryTime = $estimatedCompletionDateTime->format('H:i');

        // Use today's date as the estimated delivery date for HTML input
        // This will ensure the 27th is shown initially instead of the 28th
        $estimatedDeliveryDateHtml = $today;

        // Format the date according to the template setting
        $estimatedDeliveryDateFormatted = (new \DateTime($today))->format($dateFormat);

        // Check if we can deliver before closing time
        $canDeliver = $estimatedCompletionDateTime <= $closingDateTime;

        // If we can't deliver today, we'll suggest tomorrow
        if (!$canDeliver && $selectedDate === $today) {
            // Calculate tomorrow's date
            $tomorrow = new \DateTime('tomorrow');
            $estimatedDeliveryDateHtml = $tomorrow->format('Y-m-d');
            $estimatedDeliveryDateFormatted = $tomorrow->format($dateFormat);
        }

        // Return result as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'canDeliver' => $canDeliver,
            'closingTime' => $closingTime,
            'estimatedDeliveryTime' => $estimatedDeliveryTime,
            'estimatedDeliveryDateHtml' => $estimatedDeliveryDateHtml,
            'estimatedDeliveryDateFormatted' => $estimatedDeliveryDateFormatted,
            'currentDateTime' => $currentDateTime->format('Y-m-d H:i'),
            'crossesMidnight' => $selectedDate !== $estimatedDeliveryDateHtml,
            'dateFormat' => $dateFormat,
            'today' => $today // Include today's date for reference
        ]);
        exit;
    }
}
