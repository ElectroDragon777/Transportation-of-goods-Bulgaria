<?php

namespace App\Controllers;

use Models;
use Core\Controller;

class ActivityController extends Controller
{
    var $layout = 'admin';

    var $settings;

    public function __construct()
    {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }

        $this->settings = $this->loadSettings();
    }

    function loadSettings()
    {
        $settingModel = new \App\Models\Setting();
        $settings = $settingModel->getAll();
        $app_settings = [];
        foreach ($settings as $setting) {
            $app_settings[$setting['key']] = $setting['value'];
        }
        return $app_settings;
    }

    function index()
    {
        // Get messages (existing code)
        $messageModel = new \App\Models\Message();
        $messages = $messageModel->getAll(['recipient_id' => $_SESSION['user']['id']]);
        $userModel = new \App\Models\User();

        // Use sender_name from the database
        foreach ($messages as &$message) {
            $sender = $userModel->getFirstBy(['id' => $message['sender_id']]);
            $message['sender_name'] = $sender ? htmlspecialchars($sender['name']) : 'Unknown User';
        }

        // Initialize order model
        $orderModel = new \App\Models\Order();

        // Set up filter options
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

        // Filter orders based on user role
        if ($_SESSION['user']['role'] == 'user') {
            // Regular users can only see their own orders
            $opts['user_id'] = $_SESSION['user']['id'];
        } elseif ($_SESSION['user']['role'] == 'courier') {
            // Couriers can only see orders assigned to them
            $opts['courier_id'] = $_SESSION['user']['id'];
        }
        // Admin and root can see all orders (no additional filters)

        // Check for specific GET parameters
        if (!empty($_GET['user_id']) && $_GET['user_id'] == $_SESSION['user']['id']) {
            $opts['user_id'] = $_GET['user_id'];
        }

        if (!empty($_GET['courier_id']) && $_GET['courier_id'] == $_SESSION['user']['id']) {
            $opts['courier_id'] = $_GET['courier_id'];
        }

        // Get all orders matching the criteria
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
        }

        // Set up the template variables
        $tpl = [
            'orders' => $orders,
            'messages' => $messages,
            'currency' => $this->settings['currency'] ?? 'BGN'
        ];

        // Pass the data to the view
        $this->view($this->layout, $tpl);
    }
}