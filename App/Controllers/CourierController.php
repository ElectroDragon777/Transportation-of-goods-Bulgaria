<?php

namespace App\Controllers;

use Models;
use Core;
use Core\View;
use Core\Controller;

class CourierController extends Controller
{

    var $layout = 'admin';

    public function __construct()
    {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }
        if ($_SESSION['user']['role'] == 'user') {
            header("Location: " . INSTALL_URL, true, 301);
            exit;
        }
    }

    function list($layout = 'admin')
    {
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

    function filter()
    {
        $this->list('ajax');
    }

    function print()
    {
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

    function create()
    {
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

                // Prepare courier data
                $courierData = [
                    'name' => $_POST['name'],
                    'phone_number' => $_POST['phone_number'],
                    'email' => $_POST['email'],
                    'is_busy' => 0,
                    'allowed_tracking' => 1
                ];

                if ($userModel->save($_POST) && $courierModel->save($courierData)) {
                    // Redirect to the list of couriers on successful creation
                    header("Location: " . INSTALL_URL . "?controller=Courier&action=list", true, 301);
                    exit;
                } else {
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

    function delete()
    {
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

    function bulkDelete()
    {
        $userModel = new \App\Models\User();

        if (!empty($_POST['ids']) && is_array($_POST['ids'])) {
            $inCourierIds = implode(', ', $_POST['ids']);
            $userModel->deleteBy(["id IN ($inCourierIds) AND 1 " => '1']);
        }

        $couriers = $userModel->getAll(['role' => 'courier']);
        $this->view('ajax', ['couriers' => $couriers]);
    }


    function edit()
    {
        $userModel = new \App\Models\User();

        $arr = $userModel->get($_GET['id']);

        // Check if the form has been submitted
        if (!empty($_POST['id'])) {

            // Save the data using the Courier model
            if ($userModel->update($_POST)) {
                // Redirect to the list of couriers on successful creation
                header("Location: " . INSTALL_URL . "?controller=Courier&action=list", true, 301);
                exit;
            } else {
                // If saving fails, set an error message
                $arr['error_message'] = "Failed to create the courier. Please try again.";
            }
        }

        // Load the view and pass the data to it
        $this->view($this->layout, $arr);
    }

    // Broken function, help.
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

    function export()
    {
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

    private function exportAsPDF($couriers)
    {
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

    private function generateDynamicCourierTable($couriers)
    {
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

    private function exportAsExcel($couriers)
    {
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

    private function exportAsCSV($couriers)
    {
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
}
