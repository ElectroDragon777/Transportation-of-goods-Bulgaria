<?php

namespace App\Controllers;

use Models;
use Core;
use Core\View;
use Core\Controller;

class CourierTrackingController extends Controller {

    var $layout = 'admin';
    var $settings;

    public function __construct() {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
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

    public function index() {
        // For couriers: show their own tracking page
        if ($_SESSION['user']['role'] === 'courier') {
            return $this->courierDashboard();
        }

        // For admins/dispatchers: show all couriers tracking view
        $courierModel = new \App\Models\Courier();
        $couriers = $courierModel->getAll();

        // Get active orders for tracking
        $orderModel = new \App\Models\Order();
        $activeOrders = $orderModel->getAll(['status' => 'shipped']);

        $this->view($this->layout, [
            'couriers' => $couriers,
            'activeOrders' => $activeOrders
        ]);
    }

    private function courierDashboard() {
        $courierModel = new \App\Models\Courier();
        $courier = $courierModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);

        if (!$courier) {
            // Handle case where courier record doesn't exist
            $this->view($this->layout, ['error' => 'Courier record not found']);
            return;
        }

        $orderModel = new \App\Models\Order();
        $activeOrders = $orderModel->getAll([
            'courier_id' => $courier['id'],
            'status' => 'shipped'
        ]);

        $isTrackingEnabled = (bool) $courier['allowed_tracking'];

        $this->view($this->layout, [
            'courier' => $courier,
            'activeOrders' => $activeOrders,
            'isTrackingEnabled' => $isTrackingEnabled
        ]);
    }

    public function toggleTracking() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
            exit;
        }

        $courierModel = new \App\Models\Courier();

        // Get courier ID - either from POST (admin changing a courier) or from session (courier changing self)
        $courierId = !empty($_POST['courier_id']) ? $_POST['courier_id'] : null;

        // For couriers changing their own status
        if (!$courierId && $_SESSION['user']['role'] === 'courier') {
            $courier = $courierModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);
            if ($courier) {
                $courierId = $courier['id'];
            }
        }

        if (!$courierId) {
            echo json_encode(['status' => 'error', 'message' => 'Courier ID not provided']);
            exit;
        }

        // Get the new tracking state
        $trackingState = isset($_POST['tracking_enabled']) ? (int) $_POST['tracking_enabled'] : null;

        if ($trackingState === null) {
            // If not provided, toggle the current state
            $courier = $courierModel->get($courierId);
            if ($courier) {
                $trackingState = $courier['allowed_tracking'] ? 0 : 1;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Courier not found']);
                exit;
            }
        }

        // Update the tracking permission
        $result = $courierModel->update([
            'id' => $courierId,
            'allowed_tracking' => $trackingState
        ]);

        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Tracking status updated successfully',
                'tracking_enabled' => (bool) $trackingState
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update tracking status']);
        }
        exit;
    }

    public function updateCurrentLocation() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
            exit;
        }

        // Validate required parameters
        if (!isset($_POST['latitude']) || !isset($_POST['longitude'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing coordinates']);
            exit;
        }

        $latitude = floatval($_POST['latitude']);
        $longitude = floatval($_POST['longitude']);

        // Get courier information
        $courierModel = new \App\Models\Courier();
        $courier = $courierModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);

        if (!$courier) {
            echo json_encode(['status' => 'error', 'message' => 'Courier record not found']);
            exit;
        }

        // Check if tracking is allowed for this courier
        if (!$courier['allowed_tracking']) {
            echo json_encode(['status' => 'error', 'message' => 'Tracking is disabled for this courier']);
            exit;
        }

        $currentTime = date('Y-m-d H:i:s');

        // Update the courier_tracking table with current location
        $trackingModel = new \App\Models\CourierTracking();

        // Check if we already have a tracking record for this courier without an order
        $existingTracking = $trackingModel->getFirstBy([
            'courier_id' => $courier['id'],
            'order_id' => 0 // 0 means not associated with an order
        ]);

        if ($existingTracking) {
            // Update existing tracking record
            $trackingData = [
                'id' => $existingTracking['id'],
                'current_location_lat' => $latitude,
                'current_location_lng' => $longitude,
                'last_updated' => $currentTime
            ];
            $result = $trackingModel->update($trackingData);
        } else {
            // Create new tracking record
            $trackingData = [
                'courier_id' => $courier['id'],
                'order_id' => 0, // 0 means not associated with an order
                'user_id' => $_SESSION['user']['id'],
                'start_point_lat' => $latitude, // Using current location as start for now
                'start_point_lng' => $longitude,
                'end_destination_lat' => $latitude, // Same as start since no destination
                'end_destination_lng' => $longitude,
                'current_location_lat' => $latitude,
                'current_location_lng' => $longitude,
                'last_updated' => $currentTime,
                'created_at' => $currentTime
            ];
            $result = $trackingModel->save($trackingData);
        }

        // Also save to the location history table
        $historyModel = new \App\Models\CourierLocationHistory();
        $historyData = [
            'courier_id' => $courier['id'],
            'user_id' => $_SESSION['user']['id'],
            'current_lat' => $latitude,
            'current_lng' => $longitude,
            'order_id' => 0, // 0 means not associated with an order
            'timestamp' => $currentTime
        ];
        $historyModel->save($historyData);

        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Location updated successfully'
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update location']);
        }
        exit;
    }

    public function getCurrentLocation() {
        header('Content-Type: application/json');

        if (empty($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
            exit;
        }

        // Get courier information
        $courierModel = new \App\Models\Courier();
        $courier = $courierModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);

        if (!$courier) {
            echo json_encode(['status' => 'error', 'message' => 'Courier record not found']);
            exit;
        }

        // Get the most recent tracking data
        $trackingModel = new \App\Models\CourierTracking();
        $tracking = $trackingModel->getFirstBy(
                ['user_id' => $courier['user_id']],
                'last_updated DESC'
        );

        if (!$tracking) {
            echo json_encode(['status' => 'error', 'message' => 'No location data available']);
            exit;
        }

        echo json_encode([
            'status' => 'success',
            'location' => [
                'lat' => $tracking['current_location_lat'],
                'lng' => $tracking['current_location_lng'],
                'last_updated' => $tracking['last_updated']
            ],
            'tracking_enabled' => (bool) $courier['allowed_tracking']
        ]);
        exit;
    }

    public function getTrackingData() {
        header('Content-Type: application/json');

        if (empty($_GET['order_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Order ID not provided']);
            exit;
        }

        $orderId = intval($_GET['order_id']);

        $trackingModel = new \App\Models\CourierTracking();
        $tracking = $trackingModel->getFirstBy(['order_id' => $orderId]);

        if (!$tracking) {
            echo json_encode(['status' => 'error', 'message' => 'Tracking data not found for this order']);
            exit;
        }

        // Get courier information
        $courierModel = new \App\Models\Courier();
        $courier = $courierModel->get($tracking['courier_id']);

        // Get order information
        $orderModel = new \App\Models\Order();
        $order = $orderModel->get($orderId);

        // Calculate distance and percentage completion
        $totalDistance = $this->calculateDistance(
                $tracking['start_point_lat'],
                $tracking['start_point_lng'],
                $tracking['end_destination_lat'],
                $tracking['end_destination_lng']
        );

        $distanceRemaining = $this->calculateDistance(
                $tracking['current_location_lat'],
                $tracking['current_location_lng'],
                $tracking['end_destination_lat'],
                $tracking['end_destination_lng']
        );

        $distanceTraveled = $totalDistance - $distanceRemaining;
        $percentComplete = ($distanceTraveled / $totalDistance) * 100;

        echo json_encode([
            'status' => 'success',
            'tracking' => [
                'courier_name' => $courier ? $courier['name'] : 'Unknown',
                'order_tracking_number' => $order ? $order['tracking_number'] : 'Unknown',
                'current_location' => [
                    'lat' => $tracking['current_location_lat'],
                    'lng' => $tracking['current_location_lng'],
                ],
                'start_point' => [
                    'lat' => $tracking['start_point_lat'],
                    'lng' => $tracking['start_point_lng'],
                ],
                'end_destination' => [
                    'lat' => $tracking['end_destination_lat'],
                    'lng' => $tracking['end_destination_lng'],
                ],
                'last_updated' => $tracking['last_updated'],
                'estimated_arrival' => $tracking['estimated_arrival_time'],
                'percent_complete' => round($percentComplete, 1),
                'distance_remaining' => round($distanceRemaining, 2),
                'total_distance' => round($totalDistance, 2)
            ]
        ]);
        exit;
    }

    public function getCourierLocation() {
        header('Content-Type: application/json');

        if (empty($_GET['courier_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Courier ID not provided']);
            exit;
        }

        $courierId = intval($_GET['courier_id']);

        // Get most recent tracking data for this courier (across all orders)
        $trackingModel = new \App\Models\CourierTracking();
        $tracking = $trackingModel->getFirstBy(
                ['courier_id' => $courierId],
                'last_updated DESC'
        );

        if (!$tracking) {
            echo json_encode(['status' => 'error', 'message' => 'No recent location data for this courier']);
            exit;
        }

        echo json_encode([
            'status' => 'success',
            'location' => [
                'lat' => $tracking['current_location_lat'],
                'lng' => $tracking['current_location_lng'],
                'last_updated' => $tracking['last_updated'],
                'order_id' => $tracking['order_id']
            ]
        ]);
        exit;
    }

    public function getLocationHistory() {
        header('Content-Type: application/json');

        if (empty($_GET['order_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Order ID not provided']);
            exit;
        }

        $orderId = intval($_GET['order_id']);

        $historyModel = new \App\Models\CourierLocationHistory();
        $history = $historyModel->getAll(
                ['order_id' => $orderId],
                'timestamp ASC'
        );

        if (empty($history)) {
            echo json_encode(['status' => 'error', 'message' => 'No location history found for this order']);
            exit;
        }

        // Format the history data
        $formattedHistory = [];
        foreach ($history as $record) {
            $formattedHistory[] = [
                'lat' => $record['current_lat'],
                'lng' => $record['current_lng'],
                'timestamp' => $record['timestamp']
            ];
        }

        echo json_encode([
            'status' => 'success',
            'history' => $formattedHistory
        ]);
        exit;
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2) {
        // Haversine formula to calculate distance between two points
        $earthRadius = 6371; // Radius of the earth in km

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c; // Distance in km

        return $distance;
    }
}
