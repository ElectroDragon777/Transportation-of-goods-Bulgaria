<?php

namespace App\Controllers;

use Models;
use Core;
use Core\View;
use Core\Controller;

class CourierController extends Controller {

    var $layout = 'admin';
    var $settings;

    public function __construct() {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }
        if ($_SESSION['user']['role'] == 'user') {
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

    public function index() {
        if ($_SESSION['user']['role'] !== 'courier') {
            header("Location: " . INSTALL_URL, true, 301);
            exit;
        }

        $orderModel = new \App\Models\Order();
        $courierLocationModel = new \App\Models\CourierLocationHistory();

        // Get the courier's current tracking status
        $isTracking = false;
        $currentLocation = $courierLocationModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);
        if ($currentLocation && isset($currentLocation['is_tracking'])) {
            $isTracking = (bool) $currentLocation['is_tracking'];
        }

        $this->view('courier/dashboard', [
            'active_orders' => $this->getActiveOrders($_SESSION['user']['id']),
            'completed_orders' => $this->getCompletedOrders($_SESSION['user']['id']),
            'is_tracking' => $isTracking
        ]);
    }

    function list($layout = 'admin') {
        $userModel = new \App\Models\User();

        $opts = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['name'])) {
                $opts["name LIKE '%" . $_POST['name'] . "%' AND 1 "] = "1";
            }
            if (!empty($_POST['phone'])) {
                $opts["phone_number LIKE '%" . $_POST['phone'] . "%' AND 1 "] = "1";
            }
            if (!empty($_POST['email'])) {
                $opts["email LIKE '%" . $_POST['email'] . "%' AND 1 "] = "1";
            }
            if (!empty($_POST['address'])) {
                $opts["address LIKE '%" . $_POST['address'] . "%' AND 1 "] = "1";
            }
            if (!empty($_POST['region'])) {
                $opts["region LIKE '%" . $_POST['region'] . "%' AND 1 "] = "1";
            }
        }

        // Извличане на всички записи на куриери от таблицата users
        $opts['role'] = 'courier';
        $couriers = $userModel->getAll($opts);

        // Прехвърляне на данни към изгледа
        $this->view($layout, ['couriers' => $couriers]);
    }

    function filter() {
        $this->list('ajax');
    }

    function print() {
        // Check if courierData is provided
        if (isset($_POST['courierData'])) {
            // Decode the JSON data
            $couriers = json_decode($_POST['courierData'], true);

            if (!$couriers || empty($couriers)) {
                echo "No couriers to print";
                exit;
            }
        }

        $this->view('ajax', ['couriers' => $couriers]);
    }

    function create() {
        // Create an instance of the User model
        $userModel = new \App\Models\User();
        // Create an instance of the Courier model
        $courierModel = new \App\Models\Courier();

        // Check if the form has been submitted
        if (!empty($_POST['send'])) {
            if ($userModel->existsBy(['email' => $_POST['email']])) {
                $error_message = "User with this email already exists.";
            } else if ($_POST['password'] !== $_POST['repeat_password']) {
                $error_message = "Passwords do not match.";
            } else {
                $_POST['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $_POST['role'] = 'courier';

                // Save the user first
                $userId = $userModel->save($_POST);

                if ($userId) {
                    // If save returned the ID directly
                    $courierData = [
                        'name' => $_POST['name'],
                        'user_id' => $userId,
                        'phone_number' => $_POST['phone_number'],
                        'email' => $_POST['email'],
                        'is_busy' => 0,
                        'allowed_tracking' => 1
                    ];
                } else {
                    // Fallback: Try to find the newly created user by email
                    $newUser = $userModel->getFirstBy(['email' => $_POST['email']]);
                    if ($newUser) {
                        $userId = $newUser['id'];
                        $courierData = [
                            'name' => $_POST['name'],
                            'user_id' => $userId,
                            'phone_number' => $_POST['phone_number'],
                            'email' => $_POST['email'],
                            'is_busy' => 0,
                            'allowed_tracking' => 1
                        ];
                    } else {
                        $error_message = "Failed to retrieve user ID. Please try again.";
                    }
                }

                if (isset($courierData) && $courierModel->save($courierData)) {
                    // Redirect to the list of couriers on successful creation
                    header("Location: " . INSTALL_URL . "?controller=Courier&action=list", true, 301);
                    exit;
                } else if (!isset($error_message)) {
                    $error_message = "Failed to save courier. Please try again.";
                }
            }
        }

        // Pass any error messages to the view
        $arr = array();
        if (isset($error_message)) {
            $arr['error_message'] = $error_message;
        }

        // Load the view and pass the data to it
        $this->view($this->layout, $arr);
    }

    function delete() {
        $userModel = new \App\Models\User();

        if (!empty($_POST['id'])) {
            $userModel->delete($_POST['id']);
            if ($_POST['id'] == $_SESSION['user']['id']) {
                session_destroy();
            }
        }

        $couriers = $userModel->getAll(['role' => 'courier']);
        $this->view('ajax', ['couriers' => $couriers]);
    }

    function bulkDelete() {
        $userModel = new \App\Models\User();

        if (!empty($_POST['ids']) && is_array($_POST['ids'])) {
            $inCourierIds = implode(', ', $_POST['ids']);
            $userModel->deleteBy(["id IN ($inCourierIds) AND 1 " => '1']);
        }

        $couriers = $userModel->getAll(['role' => 'courier']);
        $this->view('ajax', ['couriers' => $couriers]);
    }

    function edit() {
        $userController = new \App\Controllers\UserController();
        $userController->edit();
    }

    // Broken function, help. This is the one you can edit here. I commented it out, so it gets your focus.
    // function edit()
    // {
    //     $userModel = new \App\Models\User();
    //     $courierModel = new \App\Models\Courier();
    //     // Get user data
    //     $userData = $userModel->get($_GET['id']);
    //     // echo $userData['id'];
    //     // echo $userData['name']; /* Works */
    //     // Try to find the corresponding courier by name and/or email (FIX GETTER)
    //     if (isset($userData['name'])) {
    //         // Try to find courier by name
    //         $courierByName = $courierModel->getAll(['name' => $userData['name']])[0];
    //         //echo $courierByName['name'];
    //         if ($courierByName) {
    //             $courierId = $courierByName['id'];
    //         }
    //     }
    //     // If we couldn't find by name, try email as fallback
    //     if (!$courierId && isset($userData['email'])) {
    //         $courierByEmail = $courierModel->get(['email' => $userData['email']]);
    //         if ($courierByEmail) {
    //             $courierId = $courierByEmail['id'];
    //         }
    //     }
    //     // Now get the courier data if we found an ID
    //     $courierData = null;
    //     if ($courierId) {
    //         $courierData = $courierModel->get($courierId);
    //         // Merge courier data into user data for the form
    //         $arr = array_merge($userData, $courierData);
    //     } else {
    //         $arr = $userData;
    //         $arr['error_message'] = "Courier data not found. It will be created when you save.";
    //     }
    //     // Check if the form has been submitted
    //     if (!empty($_POST['id'])) {
    //         // Handle password update
    //         if (!empty($_POST['password'])) {
    //             if ($_POST['password'] !== $_POST['repeat_password']) {
    //                 $arr['error_message'] = "Passwords do not match.";
    //                 $this->view($this->layout, $arr);
    //                 return;
    //             } else {
    //                 $_POST['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    //             }
    //         }
    //         // Remove password fields from POST data before saving
    //         unset($_POST['password']);
    //         unset($_POST['repeat_password']);
    //         // Update user record
    //         $userUpdateSuccess = $userModel->update($_POST);
    //         // Prepare courier data
    //         $courierUpdateData = [
    //             'name' => $_POST['name'],
    //             'phone_number' => $_POST['phone_number'],
    //             'email' => $_POST['email']
    //         ];
    //         // If we found an existing courier, update it with its own ID
    //         $courierUpdateSuccess = false;
    //         if ($courierId) {
    //             $courierUpdateData['id'] = $courierId;
    //             $courierUpdateSuccess = $courierModel->update($courierUpdateData);
    //         } else {
    //             // Otherwise, create a new courier record
    //             $courierUpdateSuccess = $courierModel->save($courierUpdateData);
    //         }
    //         if ($userUpdateSuccess && $courierUpdateSuccess) {
    //             // Redirect to the list of couriers on successful update
    //             header("Location: " . INSTALL_URL . "?controller=Courier&action=list", true, 301);
    //             exit;
    //         } else {
    //             // If saving fails, set an error message
    //             $arr['error_message'] = "Failed to update the courier. Please try again.";
    //         }
    //     }
    //     // Load the view and pass the data to it
    //     $this->view($this->layout, $arr);
    // }

    function export() {
        // Check if courierData is provided
        if (isset($_POST['courierData'])) {
            // Decode the JSON data
            $couriers = json_decode($_POST['courierData'], true);

            if (!$couriers || empty($couriers)) {
                echo "No couriers to export";
                exit;
            }
        }

        $format = isset($_POST['format']) ? $_POST['format'] : 'pdf';

        // Export based on format
        switch ($format) {
            case 'pdf':
                $this->exportAsPDF($couriers);
                break;
            case 'excel':
                $this->exportAsExcel($couriers);
                break;
            case 'csv':
                $this->exportAsCSV($couriers);
                break;
            default:
                echo "Invalid export format";
                exit;
        }
    }

    private function exportAsPDF($couriers) {
        if (ob_get_level()) {
            ob_end_clean();
        }
        require_once(__DIR__ . '/../Helpers/export/tcpdf/tcpdf.php');

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8');
        $pdf->SetCreator('Your App');
        $pdf->SetTitle('Couriers Export');
        $pdf->SetHeaderData('', 0, 'Couriers List', '');
        $pdf->setHeaderFont(array('helvetica', '', 12));
        $pdf->setFooterFont(array('helvetica', '', 10));
        $pdf->SetDefaultMonospacedFont('courier');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        $pdf->AddPage();

        // Generate HTML table with dynamic headers
        $html = $this->generateDynamicCourierTable($couriers);
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $pdf->Output('couriers_export.pdf', 'D');
        exit;
    }

    private function generateDynamicCourierTable($couriers) {
        // Start HTML table
        $html = '<table border="1" cellpadding="5">
<thead>
    <tr>';

        // If we have couriers, use their keys as headers
        if (!empty($couriers) && is_array($couriers[0])) {
            $headers = array_keys($couriers[0]);

            // Add headers to table
            foreach ($headers as $header) {
                $displayHeader = ucwords(str_replace('_', ' ', $header));
                $html .= '<th>' . $displayHeader . '</th>';
            }

            $html .= '</tr>
    </thead>
    <tbody>';

            // Add courier data
            foreach ($couriers as $courier) {
                $html .= '<tr>';
                foreach ($courier as $key => $value) {
                    // Handle empty values
                    if (empty($value) && $value !== 0) {
                        $value = 'N/A';
                    }
                    // Sanitize output
                    $html .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $html .= '</tr>';
            }
        } else {
            // Fallback for no data
            $html .= '<th>No Data Available</th></tr></thead><tbody><tr><td>No couriers found</td></tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    private function exportAsExcel($couriers) {
        // Include SimpleXLSXGen
        require(__DIR__ . '/../Helpers/export/simplexlsxgen/src/SimpleXLSXGen.php');

        // Prepare data
        $data = [];

        // First courier in array determines headers
        if (!empty($couriers) && is_array($couriers[0])) {
            // Use keys from first courier for headers, ensuring proper capitalization
            $headers = array_keys($couriers[0]);
            $headerRow = [];

            foreach ($headers as $header) {
                // Convert courier_id to Courier ID, etc.
                $headerRow[] = ucwords(str_replace('_', ' ', $header));
            }

            $data[] = $headerRow;

            // Add couriers
            foreach ($couriers as $courier) {
                $row = [];
                foreach ($courier as $value) {
                    // Handle empty values
                    $row[] = (empty($value) && $value !== 0) ? 'N/A' : $value;
                }
                $data[] = $row;
            }
        } else {
            // Fallback for no data
            $data[] = ['No Data Available'];
            $data[] = ['No couriers found'];
        }

        // Create and send file
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs('couriers_export.xlsx');
        exit;
    }

    private function exportAsCSV($couriers) {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="couriers_export.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Determine headers dynamically from the first courier
        if (!empty($couriers) && is_array($couriers[0])) {
            $headers = array_keys($couriers[0]);
            // Convert keys to readable headers (e.g., courier_id to Courier ID)
            $readableHeaders = array_map(function ($header) {
                return ucwords(str_replace('_', ' ', $header));
            }, $headers);

            // Add headers
            fputcsv($output, $readableHeaders);

            // Add data using the actual keys from the data
            foreach ($couriers as $courier) {
                $row = [];
                foreach ($courier as $value) {
                    // Handle empty values
                    $row[] = (empty($value) && $value !== 0) ? 'N/A' : $value;
                }
                fputcsv($output, $row);
            }
        } else {
            // Fallback for empty data
            fputcsv($output, ['No data available']);
        }

        fclose($output);
        exit;
    }

    public function updateLocation() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'courier') {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        // Validate inputs
        if (!isset($_POST['latitude']) || !isset($_POST['longitude'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing coordinates']);
            exit;
        }

        $latitude = floatval($_POST['latitude']);
        $longitude = floatval($_POST['longitude']);

        // Get user info for courier name
        $userModel = new \App\Models\User();
        $courier = $userModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);
        $courierName = $courier['name'] ?? 'Unknown Courier';

        // Update current location
        $courierLocationModel = new \App\Models\CourierLocationHistory();
        $locationData = [
            'user_id' => $_SESSION['user']['id'],
            'latitude' => $latitude,
            'longitude' => $longitude,
            'last_updated' => date('Y-m-d H:i:s'),
            'is_tracking' => 1 // Set to actively tracking
        ];

        $courierLocation = $courierLocationModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);
        $status = false;

        if (empty($courierLocation)) {
            $status = $courierLocationModel->save($locationData);
        } else {
            $status = $courierLocationModel->update($courierLocation['id'] + $locationData);
        }

        // Add to location history
        $historyModel = new \App\Models\CourierLocationHistory();
        $historyData = [
            'courier_name' => $courierName,
            'user_id' => $_SESSION['user']['id'],
            'current_lat' => $latitude,
            'current_lng' => $longitude,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Get active order being delivered by this courier
        $orderModel = new \App\Models\Order();
        $activeOrder = $orderModel->getFirstBy([
            'courier_id' => $_SESSION['user']['id'],
            'status' => 'shipped'
        ]);

        if ($activeOrder) {
            $historyData['order_id'] = $activeOrder['id'];

            // Check if courier has reached destination
            if ($this->hasReachedDestination($latitude, $longitude, $activeOrder['end_destination'])) {
                // Update order status to delivered
                $data = [
                    'id' => $activeOrder['id'],
                    'status' => 'delivered',
                    'last_processed' => date('Y-m-d H:i:s'),
                    'delivery_date' => date('Y-m-d')
                ];
                $orderModel->update($data);

                // Return special status to notify frontend
                echo json_encode([
                    'status' => 'success',
                    'destination_reached' => true,
                    'order_id' => $activeOrder['id']
                ]);
                exit;
            }
        }

        $historyModel->save($historyData);

        if ($status) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update location']);
        }
        exit;
    }

    private function hasReachedDestination($currentLat, $currentLng, $destinationString) {
        // Parse destination string to get coordinates
        // Format could be something like "42.6977,23.3219" or a city name
        // For city names, you would need geocoding - this is simplified
        $destCoords = explode(',', $destinationString);

        if (count($destCoords) == 2) {
            $destLat = floatval(trim($destCoords[0]));
            $destLng = floatval(trim($destCoords[1]));

            // Calculate distance using Haversine formula
            $distance = $this->calculateDistance($currentLat, $currentLng, $destLat, $destLng);

            // Consider destination reached if within 100 meters
            return $distance <= 0.1; // 0.1 km = 100 meters
        }

        return false;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        // Haversine formula to calculate distance between two points
        $earthRadius = 6371; // Radius of the earth in km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c; // Distance in km

        return $distance;
    }

    public function getLocation() {
        header('Content-Type: application/json');

        if (!empty($_GET['courier_id'])) {
            $courierLocationModel = new \App\Models\CourierLocationHistory();
            $location = $courierLocationModel->getFirstBy(['user_id' => $_GET['courier_id']]);

            if ($location) {
                echo json_encode([
                    'status' => 'success',
                    'data' => [
                        'latitude' => $location['latitude'],
                        'longitude' => $location['longitude'],
                        'last_updated' => $location['last_updated'],
                        'is_tracking' => (bool) $location['is_tracking']
                    ]
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Location not found'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Courier ID not provided'
            ]);
        }
        exit;
    }

    public function toggleTracking() {
        $settingsModel = new \App\Models\Setting();
        header('Content-Type: application/json');

        if (
                $_SERVER['REQUEST_METHOD'] !== 'POST' ||
                empty($_SESSION['user']) ||
                $_SESSION['user']['role'] !== 'courier'
        ) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        $trackingState = isset($_POST['tracking']) ? (int) $_POST['tracking'] : 0;

        $courierLocationModel = new \App\Models\CourierLocationHistory();
        $currentLocation = $courierLocationModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);

        if ($currentLocation) {
            // Update existing location record with new tracking state
            $data = [
                $currentLocation['id'],
                'latitude' => $currentLocation['latitude'],
                'longitude' => $currentLocation['longitude'],
                'is_tracking' => $trackingState,
                'last_updated' => date($this->settings['date_format'] . 'H:i:s')
            ];
            $result = $courierLocationModel->update($data);
        } else {
            // If no location record exists yet, create one with default values
            $result = $courierLocationModel->save([
                'user_id' => $_SESSION['user']['id'],
                'latitude' => 0,
                'longitude' => 0,
                'is_tracking' => $trackingState,
                'last_updated' => date('Y-m-d H:i:s')
            ]);
        }

        if ($result) {
            echo json_encode([
                'status' => 'success',
                'tracking' => (bool) $trackingState
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update tracking status'
            ]);
        }
        exit;
    }

    public function startTracking() {
        if ($_SESSION['user']['role'] !== 'courier') {
            header("Location: " . INSTALL_URL, true, 301);
            exit;
        }

        $courierLocationModel = new \App\Models\CourierLocationHistory();
        $currentLocation = $courierLocationModel->getFirstBy(['user_id' => $_SESSION['user']['id']]);
        $isTracking = $currentLocation && isset($currentLocation['is_tracking']) ? (bool) $currentLocation['is_tracking'] : false;

        $this->view('courier/tracking', [
            'active_orders' => $this->getActiveOrders($_SESSION['user']['id']),
            'is_tracking' => $isTracking
        ]);
    }

    private function getActiveOrders($courierId) {
        $orderModel = new \App\Models\Order();
        $orders = $orderModel->getAll([
            'courier_id' => $courierId,
            'status' => 'pending'
        ]);

        // Enrich orders with additional data
        // foreach ($orders as &$order) {
        //     $userModel = new \App\Models\User();
        //     $customer = $userModel->getById($order['user_id']);
        //     $order['customer_name'] = $customer ? $customer['name'] : 'Unknown Customer';
        //     // Add courier name
        //     $courier = $userModel->getById($order['courier_id']);
        //     $order['courier_name'] = $courier ? $courier['name'] : 'Unknown Courier';
        // }

        return $orders;
    }

    private function getCompletedOrders($courierId) {
        $orderModel = new \App\Models\Order();
        $orders = $orderModel->getAll([
            'courier_id' => $courierId,
            'status' => 'delivered'
        ]);

        // Enrich orders with additional data
        foreach ($orders as &$order) {
            $userModel = new \App\Models\User();
            $customer = $userModel->getBy($order['user_id']);
            $order['customer_name'] = $customer ? $customer['name'] : 'Unknown Customer';

            // Add courier name
            $courier = $userModel->getById($order['courier_id']);
            $order['courier_name'] = $courier ? $courier['name'] : 'Unknown Courier';
        }

        return $orders;
    }
}
