<?php

namespace App\Controllers;

use Models;
use Core;
use Core\View;
use Core\Controller;

class UserController extends Controller {

    var $layout = 'admin';

    public function __construct() {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }
// if ($_SESSION['user']['role'] == 'user') {
//     header("Location: " . INSTALL_URL, true, 301);
//     exit;
// }
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
            if (!empty($_POST['roles']) && is_array($_POST['roles'])) {
                $roles = "'" . implode("','", $_POST['roles']) . "'";
                $opts["role IN (" . $roles . ") AND 1 "] = "1";
            }
        }

// Извличане на всички записи от таблицата gallery
        $users = $userModel->getAll($opts);

// Прехвърляне на данни към изгледа
        $this->view($layout, ['users' => $users]);
    }

    function filter() {
        $this->list('ajax');
    }

    function print() {
        if (isset($_POST['userData'])) {
// Decode the JSON data
            $users = json_decode($_POST['userData'], true);

            if (!$users || empty($users)) {
                echo "No users to print";
                exit;
            }
        }

        $this->view('ajax', ['users' => $users]);
    }

    public function changeRole() {
        $userModel = new \App\Models\User();

        if (!empty($_POST['id']) && !empty($_POST['role'])) {
            $role = $_POST['role'];

            if (in_array($role, ['user', 'admin'])) {
                $userModel->update($_POST);
            }
        }

// Return refreshed user list
        $users = $userModel->getAll();
        $this->view('ajax', ['users' => $users]);
    }

    function create() {
// Create an instance of the User model
        $userModel = new \App\Models\User();

// Check if the form has been submitted
        if (!empty($_POST['send'])) {
            if ($userModel->existsBy(['email' => $_POST['email']])) {
                $error_message = "User with this email already exists.";
            } else if ($_POST['password'] !== $_POST['repeat_password']) {
                $error_message = "Passwords do not match.";
            } else {
                $_POST['password_hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $_POST['role'] = 'user';

                if ($userModel->save($_POST)) {
                    header("Location: " . INSTALL_URL . "?controller=User&action=list", true, 301);
                    exit;
                } else {
                    $error_message = "Failed to register. Please try again.";
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

        $users = $userModel->getAll();
        $this->view('ajax', ['users' => $users]);
    }

    function bulkDelete() {
        $userModel = new \App\Models\User();

        if (!empty($_POST['ids']) && is_array($_POST['ids'])) {
            $userIds = $_POST['ids'];

            $inUserIds = implode(', ', $userIds);
            $userModel->deleteBy(["id IN ($inUserIds) AND 1 " => '1']);
            if (in_array($_SESSION['user']['id'], $userIds)) {
                session_destroy();
            }
        }

        $users = $userModel->getAll();
        $this->view('ajax', ['users' => $users]);
    }

    public function edit() {
        $userModel = new \App\Models\User(); // Assuming this class exists and is correctly namespaced
// Get the user ID from POST or GET
        $id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

        if ($id === null) {
// Handle error: No ID provided
// Maybe redirect or show an error message
            echo "Error: No user ID provided.";
            return;
        }

// Fetch existing user data for the view (e.g., to populate the form initially)
        $arr = $userModel->get($id);
        if (!$arr) {
            echo "Error: User not found.";
            return;
        }

// Store the referer URL (though not used in the provided logic snippet, it's good practice)
// $referer = $_SERVER["HTTP_REFERER"] ?? null; // PHP 7+ null coalesce operator
// Check if the form has been submitted
        if (!empty($_POST['id']) && $_POST['id'] == $id) { // Ensure POST ID matches the user being edited
            if ($userModel->update($_POST)) {
                $userUpdateSuccess = true;
                $courierUpdateAttempted = false;
                $courierUpdateSuccess = false;

// After successful user update, check if the user is a courier and update their details
// We rely on $_POST['role'] to determine the user's role after the update.
// Ensure your form submits the 'role' and UserModel's update method handles it.
                $userRole = isset($_POST['role']) ? $_POST['role'] : null;

// If the role wasn't part of the POST, you might need to re-fetch the user to get the updated role.
// Example: $updatedUser = $userModel->get($id); $userRole = $updatedUser['role'];
// For this example, we'll assume $_POST['role'] is the definitive role after update.

                if ($userRole === 'courier') {
                    $courierUpdateAttempted = true;
                    $courierModel = new \App\Models\Courier(); // Assuming this class exists

                    $courierData = [];
// Populate courierData only with fields present in $_POST
                    if (isset($_POST['name'])) {
                        $courierData['name'] = $_POST['name'];
                    }
                    if (isset($_POST['email'])) {
                        $courierData['email'] = $_POST['email'];
                    }
                    if (isset($_POST['phone_number'])) {
                        $courierData['phone_number'] = $_POST['phone_number'];
                    }
// Add other fields from the 'couriers' table if they are part of the user edit form
// For example, if 'is_busy' or 'allowed_tracking' were editable on this form:
// if (isset($_POST['is_busy'])) $courierData['is_busy'] = $_POST['is_busy'];
// if (isset($_POST['allowed_tracking'])) $courierData['allowed_tracking'] = $_POST['allowed_tracking'];


                    if (!empty($courierData)) {
// The CourierModel needs a method to update based on user_id.
// This method should update fields like name, email, phone_number in the 'couriers' table.
                        if ($courierModel->updateDetailsByUserId($id, $courierData)) {
                            $courierUpdateSuccess = true;
                        } else {
// Courier update failed, $courierUpdateSuccess remains false
                        }
                    } else {
// No specific courier data (name, email, phone) was submitted in the form to update.
// This can be treated as a non-failure, or a partial success if user update worked.
// If there were no courier-specific fields to update, we can consider it not an error.
// For simplicity, if $courierData is empty, we can assume no courier update was needed for these fields.
// If other courier fields were *always* expected, this logic might need adjustment.
                    }
                }

// Start session if not already started (ideally done globally at script start)
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

// Set notification message based on update outcomes
                if ($userUpdateSuccess) {
                    if ($courierUpdateAttempted) {
                        if ($courierUpdateSuccess) {
                            $_SESSION['profile_update_message'] = "Profile and courier details updated successfully!";
                        } else {
                            $_SESSION['profile_update_message'] = "Profile updated, but failed to update associated courier details.";
// You might want to log the courier update failure or store more detailed error info
// $_SESSION['courier_update_error'] = "Specific error for courier update...";
                        }
                    } else {
                        $_SESSION['profile_update_message'] = "Profile updated successfully!";
                    }
                }
// Note: The original code only sets $arr['error_message'] on userModel->update failure.
// If user update is successful, it always redirects.
// Construct the profile page URL.
// $profileUrl = "/your_project_directory/profile?id=" . $id; //Replace /your_project_directory
                $profileUrl = "/Transportation-of-goods-Bulgaria/index.php?controller=User&action=profile&id=" . $id;
                header("Location: " . $profileUrl, true, 301); // Using 303 See Other might be more appropriate for POST-redirect-GET
                exit;
            } else {
// If user model update fails, set an error message
                $arr['error_message'] = "Failed to update the user. Please try again.";
            }
        }

// Load the view and pass the data to it (either initial data or data with error message)
        $this->view($this->layout, $arr);
    }

    function profile() {
        $userModel = new \App\Models\User();
        $user = $userModel->get($_GET['id']);

// Pass the message to the view
        $message = isset($_SESSION['profile_update_message']) ? $_SESSION['profile_update_message'] : null;
        unset($_SESSION['profile_update_message']);

        $this->view($this->layout, ['user' => $user, 'message' => $message]);
    }

    function uploadProfilePicture() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
            $user_id = $_POST['user_id']; // Get user ID
            $userModel = new \App\Models\User();

// Get current user data to check for existing photo
            $currentUser = $userModel->get($user_id);
            $oldPhotoPath = isset($currentUser['photo_path']) ? $currentUser['photo_path'] : null;

            $fileName = basename($_FILES["profile_picture"]["name"]);
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowedTypes = ["jpg", "jpeg", "png", "gif"];
            if (!in_array(strtolower($fileExt), $allowedTypes)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file format!']);
                exit;
            }

            require 'App\Helpers\uploader\src\class.upload.php';
            $handle = new \Verot\Upload\Upload($_FILES['profile_picture']);
            if ($handle->uploaded) {
                $handle->file_new_name_body = 'profile_' . $user_id . '_' . uniqid();
                $handle->image_resize = true;
                $handle->image_x = 300;
                $handle->image_ratio_y = true;
                $upload_path = 'web/upload/';
                $handle->process($upload_path);
                if ($handle->processed) {
                    $photoPath = $upload_path . $handle->file_dst_name;
                    $handle->clean();

// Update user photo in database
                    $userModel->update(['id' => $user_id, 'photo_path' => $photoPath]);
                    $_SESSION['user']['photo_path'] = $photoPath;

// Delete old photo if it exists and isn't a default photo
                    if ($oldPhotoPath && file_exists($oldPhotoPath) && strpos($oldPhotoPath, 'profile_') !== false) {
                        unlink($oldPhotoPath);
                    }

// Return success message - we'll handle notification in JavaScript
                    echo json_encode([
                        'status' => 'success',
                        'photo_path' => $photoPath,
                    ]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => $handle->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'File upload failed.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
        }
    }

    function editPassword() {
        $userModel = new \App\Models\User();
        $id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

        if (!empty($_POST['id'])) {
            $newPassword = $_POST['password'];
            $repeatNewPassword = $_POST['repeat_password'];

            if ($newPassword != $repeatNewPassword) {
                $errorMessage = 'Passwords do NOT match';
            }

            if (!isset($errorMessage)) {
                if ($userModel->update(['id' => $id, 'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)])) {
                    header("Location: " . INSTALL_URL . "?controller=User&action=profile&id=$id", true, 301);
                    exit;
                }
                $errorMessage = 'Error updating password';
            }
        }

        $this->view($this->layout, ['id' => $id, 'error_message' => $errorMessage ?? null]);
    }

    function export() {
// Check if userData is provided
        if (isset($_POST['userData'])) {
// Decode the JSON data
            $users = json_decode($_POST['userData'], true);

            if (!$users || empty($users)) {
                echo "No users to export";
                exit;
            }
        }

        $format = isset($_POST['format']) ? $_POST['format'] : 'pdf';

// Export based on format
        switch ($format) {
            case 'pdf':
                $this->exportAsPDF($users);
                break;
            case 'excel':
                $this->exportAsExcel($users);
                break;
            case 'csv':
                $this->exportAsCSV($users);
                break;
            default:
                echo "Invalid export format";
                exit;
        }
    }

    private function exportAsPDF($users) {
        if (ob_get_level()) {
            ob_end_clean();
        }
        require_once(__DIR__ . '/../Helpers/export/tcpdf/tcpdf.php');

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8');
        $pdf->SetCreator('Your App');
        $pdf->SetTitle('Users Export');
        $pdf->SetHeaderData('', 0, 'Users List', '');
        $pdf->setHeaderFont(array('helvetica', '', 12));
        $pdf->setFooterFont(array('helvetica', '', 10));
        $pdf->SetDefaultMonospacedFont('courier');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        $pdf->AddPage();

// Generate HTML table with dynamic headers
        $html = $this->generateDynamicUserTable($users);
        $pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
        $pdf->Output('users_export.pdf', 'D');
        exit;
    }

    private function generateDynamicUserTable($users) {
// Start HTML table
        $html = '<table border="1" cellpadding="5">
<thead>
    <tr>';

// If we have users, use their keys as headers
        if (!empty($users) && is_array($users[0])) {
            $headers = array_keys($users[0]);

// Add headers to table
            foreach ($headers as $header) {
                $displayHeader = ucwords(str_replace('_', ' ', $header));
                $html .= '<th>' . $displayHeader . '</th>';
            }

            $html .= '</tr>
    </thead>
    <tbody>';

// Add user data
            foreach ($users as $user) {
                $html .= '<tr>';
                foreach ($user as $key => $value) {
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
            $html .= '<th>No Data Available</th></tr></thead><tbody><tr><td>No users found</td></tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }

    private function exportAsExcel($users) {
// Include SimpleXLSXGen
        require(__DIR__ . '/../Helpers/export/simplexlsxgen/src/SimpleXLSXGen.php');

// Prepare data
        $data = [];

// First user in array determines headers
        if (!empty($users) && is_array($users[0])) {
// Use keys from first user for headers, ensuring proper capitalization
            $headers = array_keys($users[0]);
            $headerRow = [];

            foreach ($headers as $header) {
// Convert user_id to User ID, etc.
                $headerRow[] = ucwords(str_replace('_', ' ', $header));
            }

            $data[] = $headerRow;

// Add users
            foreach ($users as $user) {
                $row = [];
                foreach ($user as $value) {
// Handle empty values
                    $row[] = (empty($value) && $value !== 0) ? 'N/A' : $value;
                }
                $data[] = $row;
            }
        } else {
// Fallback for no data
            $data[] = ['No Data Available'];
            $data[] = ['No users found'];
        }

// Create and send file
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs('users_export.xlsx');
        exit;
    }

    private function exportAsCSV($users) {
// Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="users_export.csv"');

// Open output stream
        $output = fopen('php://output', 'w');

// Determine headers dynamically from the first user
        if (!empty($users) && is_array($users[0])) {
            $headers = array_keys($users[0]);
// Convert keys to readable headers (e.g., user_id to User ID)
            $readableHeaders = array_map(function ($header) {
                return ucwords(str_replace('_', ' ', $header));
            }, $headers);

// Add headers
            fputcsv($output, $readableHeaders);

// Add data using the actual keys from the data
            foreach ($users as $user) {
                $row = [];
                foreach ($user as $value) {
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
}
