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
        $activeOrders = $orderModel->getAll(['status IN (\'paid\', \'pending\') AND 1' => '1']);

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
            'status IN (\'paid\', \'pending\') AND 1' => '1'
        ]);

        $isTrackingEnabled = (bool) $courier['allowed_tracking'];

        $this->view($this->layout, [
            'courier' => $courier,
            'activeOrders' => $activeOrders,
            'isTrackingEnabled' => $isTrackingEnabled
        ]);
    }

    // Assuming this is within your CourierTracking Controller class
    // Make sure you have:
    // use App\Models\Courier; // Or the correct namespace for your Courier model

    public function toggleTracking() {
        // Set header early to prevent HTML output
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(400); // Bad Request for wrong method
                echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
                exit;
            }

            if (empty($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
                http_response_code(401); // Unauthorized
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
                exit;
            }

            // Check if tracking_enabled is provided in the POST request
            if (!isset($_POST['tracking_enabled'])) {
                http_response_code(400); // Bad Request
                echo json_encode(['status' => 'error', 'message' => 'tracking_enabled parameter is missing.']);
                exit;
            }

            // Get the desired new tracking state from the POST request
            // Ensure it's an integer (0 or 1)
            $newTrackingState = filter_var($_POST['tracking_enabled'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);

            if ($newTrackingState === false || $newTrackingState === null) { // filter_var returns false for invalid, null if not set (already checked)
                http_response_code(400); // Bad Request
                echo json_encode(['status' => 'error', 'message' => 'Invalid tracking_enabled value. Must be 0 or 1.']);
                exit;
            }

            $courierModel = new \App\Models\Courier(); // Or use your dependency injection if available
            // Get courier data based on the logged-in user's ID
            $courierData = $courierModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);

            if (!$courierData || !isset($courierData['id'])) {
                // This ensures the logged-in user is a recognized courier
                http_response_code(404); // Not Found (or 403 Forbidden if it's a permission issue)
                echo json_encode(['status' => 'error', 'message' => 'Courier not found for the current user.']);
                exit;
            }
            $courierId = $courierData['id'];

            // Update the tracking permission in the database
            $result = $courierModel->update([
                'id' => $courierId,
                'allowed_tracking' => $newTrackingState // Use the validated state from POST
            ]);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tracking status updated successfully.',
                    'tracking_enabled' => (bool) $newTrackingState // Return the successfully set state as a boolean
                ]);
            } else {
                http_response_code(500); // Internal Server Error if DB update fails
                // It's good practice to log the actual database error here for debugging
                echo json_encode(['status' => 'error', 'message' => 'Failed to update tracking status in the database.']);
            }
            exit;
        } catch (\Exception $e) {
            // Log the exception: error_log($e->getMessage());
            http_response_code(500); // Internal Server Error for unexpected issues
            echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred. Please try again.']);
            exit;
        }
    }

    public function updateCurrentLocation() {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user'])) {
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
                exit;
            }

            // Validate required parameters
            if (!isset($_POST['latitude']) || !isset($_POST['longitude'])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Missing coordinates']);
                exit;
            }

            $latitude = floatval($_POST['latitude']);
            $longitude = floatval($_POST['longitude']);

            // Validate coordinates
            if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid coordinates']);
                exit;
            }

            // Get courier information
            $courierModel = new \App\Models\Courier();
            $courier = $courierModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);

            if (!$courier) {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Courier record not found']);
                exit;
            }

            // Check if tracking is allowed for this courier
            if (!$courier['allowed_tracking']) {
                http_response_code(403);
                echo json_encode(['status' => 'error', 'message' => 'Tracking is disabled for this courier']);
                exit;
            }

            $currentTime = date('Y-m-d H:i:s');

            // Update the courier_tracking table with current location
            $trackingModel = new \App\Models\CourierTracking();

            // Check if we already have a tracking record for this courier without an order
            $existingTracking = $trackingModel->getFirstBy([
                'courier_name' => $courier['name'],
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
                    'courier_name' => $courier['name'],
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
                'courier_name' => $courier['name'],
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
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to update location']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function getCurrentLocation() {
        header('Content-Type: application/json');

        try {
            if (empty($_SESSION['user'])) {
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
                exit;
            }

            // Get courier information
            $courierModel = new \App\Models\Courier();
            $courier = $courierModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);

            if (!$courier) {
                http_response_code(404);
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
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'No location data available']);
                exit;
            }

            echo json_encode([
                'status' => 'success',
                'location' => [
                    'lat' => floatval($tracking['current_location_lat']),
                    'lng' => floatval($tracking['current_location_lng']),
                    'last_updated' => $tracking['last_updated']
                ],
                'tracking_enabled' => (bool) $courier['allowed_tracking']
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function getTrackingData() {
        header('Content-Type: application/json');

        try {
            if (empty($_GET['order_id'])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Order ID not provided']);
                exit;
            }

            $orderId = intval($_GET['order_id']);

            if ($orderId <= 0) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid Order ID']);
                exit;
            }

            $trackingModel = new \App\Models\CourierTracking();
            $tracking = $trackingModel->getFirstBy(['order_id' => $orderId]);

            if (!$tracking) {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Tracking data not found for this order']);
                exit;
            }

            $courierModel = new \App\Models\Courier();
            $courier = $courierModel->getFirstBy(['name' => $tracking['courier_name']]);

            $orderModel = new \App\Models\Order();
            $order = $orderModel->get($orderId);

            // Validate tracking coordinates (current location is vital)
            if (empty($tracking['current_location_lat']) || empty($tracking['current_location_lng'])) {
                // You might still want to send other data, or decide this is an error
                // For now, let's assume other data might be useful even without current location for some views
                // http_response_code(404);
                // echo json_encode(['status' => 'error', 'message' => 'No current location data available for courier']);
                // exit;
            }

            $startLat = !empty($tracking['start_point_lat']) ? floatval($tracking['start_point_lat']) : null;
            $startLng = !empty($tracking['start_point_lng']) ? floatval($tracking['start_point_lng']) : null;
            $endLat = !empty($tracking['end_destination_lat']) ? floatval($tracking['end_destination_lat']) : null;
            $endLng = !empty($tracking['end_destination_lng']) ? floatval($tracking['end_destination_lng']) : null;
            $currentLat = !empty($tracking['current_location_lat']) ? floatval($tracking['current_location_lat']) : null;
            $currentLng = !empty($tracking['current_location_lng']) ? floatval($tracking['current_location_lng']) : null;

            $totalDistance = null;
            $distanceRemaining = null;
            $percentComplete = null;

            if ($startLat && $startLng && $endLat && $endLng) {
                $totalDistance = $this->calculateDistance(
                        $startLat, $startLng, $endLat, $endLng
                );

                if ($currentLat && $currentLng) {
                    $distanceRemaining = $this->calculateDistance(
                            $currentLat, $currentLng, $endLat, $endLng
                    );
                } else {
                    // If no current location, remaining distance is effectively total distance (or undefined)
                    $distanceRemaining = $totalDistance;
                }


                if ($totalDistance > 0 && !is_null($distanceRemaining)) {
                    $distanceTraveled = $totalDistance - $distanceRemaining;
                    $percentComplete = ($distanceTraveled / $totalDistance) * 100;
                    $percentComplete = round(max(0, min(100, $percentComplete)), 1);
                } elseif ($totalDistance == 0 && $distanceRemaining == 0) {
                    // If start and end are the same, and courier is there
                    $percentComplete = 100;
                } else {
                    $percentComplete = 0; // Or null if you prefer
                }
            }

            echo json_encode([
                'status' => 'success',
                'tracking' => [
                    'courier_name' => $courier ? $courier['name'] : 'Unknown',
                    'order_tracking_number' => $order ? $order['tracking_number'] : 'Unknown',
                    'current_location' => ($currentLat && $currentLng) ? [
                        'lat' => $currentLat,
                        'lng' => $currentLng,
                            ] : null,
                    'start_point' => ($startLat && $startLng) ? [
                        'lat' => $startLat,
                        'lng' => $startLng,
                            ] : null,
                    'end_destination' => ($endLat && $endLng) ? [
                        'lat' => $endLat,
                        'lng' => $endLng,
                            ] : null,
                    'last_updated' => $tracking['last_updated'],
                    'estimated_arrival' => !empty($tracking['estimated_arrival_time']) ? $tracking['estimated_arrival_time'] : null,
                    'percent_complete' => $percentComplete,
                    'distance_remaining' => !is_null($distanceRemaining) ? round($distanceRemaining, 2) : null,
                    'total_distance' => !is_null($totalDistance) ? round($totalDistance, 2) : null
                ]
            ]);
        } catch (Exception $e) {
            // Log the actual error for server-side debugging
            error_log('Error in getTrackingData: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Server error processing tracking data.']);
        }
        exit;
    }

    public function getCourierLocation() {
        header('Content-Type: application/json');

        try {
            if (empty($_GET['courier_id'])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Courier ID not provided']);
                exit;
            }
            $courierModel = new \App\Models\Courier();
            $courierId = intval($_GET['courier_id']);

            if ($courierId <= 0) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid Courier ID']);
                exit;
            }

            // Get most recent tracking data for this courier (across all orders)
            $trackingModel = new \App\Models\CourierTracking();
            $tracking = $trackingModel->getFirstBy(
                    ['courier_name' => $courierModel->get($courierId)['name']],
                    'last_updated DESC'
            );

            if (!$tracking) {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'No recent location data for this courier']);
                exit;
            }

            echo json_encode([
                'status' => 'success',
                'location' => [
                    'lat' => floatval($tracking['current_location_lat']),
                    'lng' => floatval($tracking['current_location_lng']),
                    'last_updated' => $tracking['last_updated'],
                    'order_id' => intval($tracking['order_id'])
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function getLocationHistory() {
        header('Content-Type: application/json');

        try {
            if (empty($_GET['order_id'])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Order ID not provided']);
                exit;
            }

            $orderId = intval($_GET['order_id']);

            if ($orderId <= 0) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid Order ID']);
                exit;
            }

            $historyModel = new \App\Models\CourierLocationHistory();
            $history = $historyModel->getAll(
                    ['order_id' => $orderId],
                    'timestamp ASC'
            );

            if (empty($history)) {
                echo json_encode([
                    'status' => 'success',
                    'history' => []
                ]);
                exit;
            }

            // Format the history data
            $formattedHistory = [];
            foreach ($history as $record) {
                $formattedHistory[] = [
                    'lat' => floatval($record['current_lat']),
                    'lng' => floatval($record['current_lng']),
                    'timestamp' => $record['timestamp']
                ];
            }

            echo json_encode([
                'status' => 'success',
                'history' => $formattedHistory
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function updateLocation() { // This is the method your JS calls when orderId is present
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
                exit;
            }
            if (empty($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
                exit;
            }

            if (!isset($_POST['latitude']) || !isset($_POST['longitude']) || !isset($_POST['order_id'])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Missing required parameters (latitude, longitude, order_id).']);
                exit;
            }

            $latitude = floatval($_POST['latitude']);
            $longitude = floatval($_POST['longitude']);
            $orderId = intval($_POST['order_id']);

            if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid coordinates.']);
                exit;
            }
            if ($orderId <= 0) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Invalid Order ID.']);
                exit;
            }

            $courierModel = new \App\Models\Courier();
            $courier = $courierModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);

            if (!$courier) {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Courier record not found.']);
                exit;
            }

            if (!$courier['allowed_tracking']) {
                http_response_code(403);
                echo json_encode(['status' => 'error', 'message' => 'Tracking is disabled for this courier.']);
                exit;
            }

            $currentTime = date('Y-m-d H:i:s');
            $trackingModel = new \App\Models\CourierTracking();
            $orderModel = new \App\Models\Order();

            $existingTracking = $trackingModel->getFirstBy([
                'courier_name' => $courier['name'],
                'order_id' => $orderId
            ]);

            $result = false;
            if ($existingTracking) {
                $trackingData = [
                    'id' => $existingTracking['id'],
                    'current_location_lat' => $latitude,
                    'current_location_lng' => $longitude,
                    'last_updated' => $currentTime
                ];
                $result = $trackingModel->update($trackingData);
            } else {
                $orderData = $orderModel->get($orderId);
                if (!$orderData) {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => "Order ID: $orderId not found to start/update tracking."]);
                    exit;
                }

                // --- Geocoding Logic Start ---
                $startLat = $orderData['start_point_lat'];
                $startLng = $orderData['start_point_lng'];
                $endLat = $orderData['delivery_address_lat']; // Use the new column name
                $endLng = $orderData['delivery_address_lng']; // Use the new column name
                // Fallback: If coordinates are not in orderData, try to geocode (optional, can be slow)
                // Or, better, ensure they are set during order creation.
                if (is_null($startLat) || is_null($startLng)) {
                    $startCoords = $this->geocodeAddress($orderData['start_point']);
                    if ($startCoords) {
                        $startLat = $startCoords['lat'];
                        $startLng = $startCoords['lng'];
                        // Optionally update the order record with these geocoded values
                        // $orderModel->update(['id' => $orderId, 'start_point_lat' => $startLat, 'start_point_lng' => $startLng]);
                    } else {
                        // If start point can't be geocoded, use courier's current location as start
                        $startLat = $latitude;
                        $startLng = $longitude;
                        // Log this event, as it's not ideal
                        error_log("Could not geocode start_point for order {$orderId}. Using current courier location as start.");
                    }
                }

                if (is_null($endLat) || is_null($endLng)) {
                    $endCoords = $this->geocodeAddress($orderData['end_destination']);
                    if ($endCoords) {
                        $endLat = $endCoords['lat'];
                        $endLng = $endCoords['lng'];
                        // Optionally update the order record with these geocoded values
                        // $orderModel->update(['id' => $orderId, 'delivery_address_lat' => $endLat, 'delivery_address_lng' => $endLng]);
                    } else {
                        http_response_code(400); // Or 500 if it's a geocoding service error
                        echo json_encode(['status' => 'error', 'message' => "Could not geocode end_destination for order {$orderId}."]);
                        exit;
                    }
                }
                // --- Geocoding Logic End ---

                $newTrackingData = [
                    'courier_name' => $courier['name'],
                    'order_id' => $orderId,
                    'user_id' => $_SESSION['user']['id'], // Assuming this is the customer's user_id associated with the order
                    'start_point_lat' => $startLat,
                    'start_point_lng' => $startLng,
                    'end_destination_lat' => $endLat,
                    'end_destination_lng' => $endLng,
                    'current_location_lat' => $latitude,
                    'current_location_lng' => $longitude,
                    'last_updated' => $currentTime, // last_updated is often a TIMESTAMP with ON UPDATE CURRENT_TIMESTAMP
                    'created_at' => $currentTime  // created_at is often a TIMESTAMP with DEFAULT CURRENT_TIMESTAMP
                ];
                $result = $trackingModel->save($newTrackingData);
            }


            if ($result) {
                $historyModel = new \App\Models\CourierLocationHistory();
                $historyData = [
                    'courier_name' => $courier['name'],
                    'user_id' => $_SESSION['user']['id'], // Or $courier['user_id'] if this history is for the courier's actions
                    'current_lat' => $latitude,
                    'current_lng' => $longitude,
                    'order_id' => $orderId,
                    'timestamp' => $currentTime // DB schema has DEFAULT CURRENT_TIMESTAMP(), so this might be redundant if not custom
                ];
                $historyModel->save($historyData);

                $destinationReached = false;
                $currentOrder = $orderModel->get($orderId); // Re-fetch or use $orderData if creating new
                // Use the delivery_address_lat/lng from the order for destination check
                // These should ideally be the same as $endLat, $endLng used above.
                if ($currentOrder && !empty($currentOrder['delivery_address_lat']) && !empty($currentOrder['delivery_address_lng'])) {
                    $distanceToDestination = $this->calculateDistance(
                            $latitude,
                            $longitude,
                            floatval($currentOrder['delivery_address_lat']), // Ensure they are float
                            floatval($currentOrder['delivery_address_lng'])  // Ensure they are float
                    );
                    if ($distanceToDestination < 0.1) { // 100 meters threshold
                        $destinationReached = true;
                        // $orderModel->update(['id' => $orderId, 'status' => 'Delivered']); // Example
                    }
                } else {
                    // This case means destination coordinates are not available in the order record
                    error_log("Destination coordinates not found in order record for order ID: $orderId during destination check.");
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Order location updated successfully.',
                    'destination_reached' => $destinationReached
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to update/save order location.']);
            }
        } catch (\Exception $e) {
            error_log("Error in updateLocation: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

// You'll need the calculateDistance method (Haversine formula)
// Add this method to your controller if it's not already there or in a helper trait/class
    protected function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2, string $unit = 'K'): float {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    // In your CourierTrackingController or a helper class
    protected function geocodeAddress(string $address): ?array {
        // Example using Nominatim (simplistic - needs proper error handling, User-Agent)
        // In a real app, use a robust HTTP client and consider caching.
        $baseUrl = "https://nominatim.openstreetmap.org/search";
        $params = [
            'q' => $address,
            'format' => 'json',
            'limit' => 1
        ];
        $url = $baseUrl . '?' . http_build_query($params);

        $opts = [
            'http' => [
                'header' => "User-Agent: MyAppName/1.0 (your-email@example.com)\r\n" // IMPORTANT for Nominatim
            ]
        ];
        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context); // Use @ to suppress warnings, check $response

        if ($response === false) {
            error_log("Geocoding API call failed for address: " . $address);
            return null;
        }

        $data = json_decode($response, true);

        if (!empty($data) && isset($data[0]['lat'], $data[0]['lon'])) {
            return [
                'lat' => floatval($data[0]['lat']),
                'lng' => floatval($data[0]['lon'])
            ];
        }
        error_log("Geocoding failed or returned no results for address: " . $address);
        return null;
    }
}
