<?php

namespace App\Controllers;

use App\Models\Setting;
use Models;
use Core;
use Core\View;
use Core\Controller;

class PalletController extends Controller
{

    var $layout = 'admin';
    var $settings;

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

    function list($layout = 'admin')
    {
        $palletModel = new \App\Models\Pallet(); // Updated to Pallet model

        $opts = array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['name'])) {
                $opts["name LIKE '%" . $_POST['name'] . "%' AND 1 "] = "1";
            }
            if (!empty($_POST['category'])) {
                $opts["category LIKE '%" . $_POST['category'] . "%' AND 1 "] = "1";
            }
            if (!empty($_POST['description'])) {
                $opts["description LIKE '%" . $_POST['description'] . "%' AND 1 "] = "1";
            }
            if (!empty($_POST['minPrice'])) {
                $opts["price >= " . $_POST['minPrice'] . " AND 1 "] = "1";
            }
            if (!empty($_POST['maxPrice'])) {
                $opts["price <= " . $_POST['maxPrice'] . " AND 1 "] = "1";
            }
            if (!empty($_POST['quantity'])) {
                $opts["quantity = " . $_POST['quantity'] . " AND 1 "] = "1";
            }
            if (!empty($_POST['size_x_cm'])) {
                $opts["size_x_cm = " . $_POST['size_x_cm'] . " AND 1 "] = "1";
            }
            if (!empty($_POST['size_y_cm'])) {
                $opts["size_y_cm = " . $_POST['size_y_cm'] . " AND 1 "] = "1";
            }
            if (!empty($_POST['size_z_cm'])) {
                $opts["size_z_cm = " . $_POST['size_z_cm'] . " AND 1 "] = "1";
            }
            if (!empty($_POST['weight_kg'])) {
                $opts["weight_kg = " . $_POST['weight_kg'] . " AND 1 "] = "1";
            }
        }

        $pallets = $palletModel->getAll($opts); // Updated to Pallet model

        $this->view($layout, ['pallets' => $pallets, 'currency' => $this->settings['currency']]); // Updated to pallets // Updated to currency
        //$this->view($layout, ['pallets' => $pallets, 'currency' => $this->settings['currency_code']]); // Updated to pallets
    }

    function filter()
    {
        $this->list('ajax');
    }

    function create()
    {
        // Create an instance of the Pallet model
        $palletModel = new \App\Models\Pallet(); // Updated to Pallet model

        // Check if the form has been submitted
        if (!empty($_POST['send'])) {
            // Save the data using the Pallet model
            if ($palletModel->save($_POST)) {
                // Redirect to the list of pallets on successful creation
                header("Location: " . INSTALL_URL . "?controller=Pallet&action=list", true, 301); // Updated redirect
                exit;
            } else {
                // If saving fails, set an error message
                $error_message = "Failed to create the packet. Please try again.";
            }
        }

        // Pass any error messages to the view
        $arr = array();
        if (isset($error_message)) {
            $arr['error_message'] = $error_message;
        }
        $arr['currency'] = $this->settings['currency']; //$this->settings['currency_code'], set manually to currency, since local+modded.

        // Load the view and pass the data to it
        //$this->view($this->layout);
        $this->view($this->layout, $arr);
    }

    function delete()
    {
        $palletModel = new \App\Models\Pallet(); // Updated to Pallet model

        if (!empty($_POST['id'])) {
            $palletModel->delete($_POST['id']);
        }

        $pallets = $palletModel->getAll(); // Updated to Pallet model
        $this->view('ajax', ['pallets' => $pallets]); // Updated to pallets
    }

    function bulkDelete()
    {
        $palletModel = new \App\Models\Pallet(); // Updated to Pallet model

        if (!empty($_POST['ids']) && is_array($_POST['ids'])) {
            $inPalletIds = implode(', ', $_POST['ids']);
            $palletModel->deleteBy(["id IN ($inPalletIds) AND 1 " => '1']); // Updated to Pallet model
        }

        $pallets = $palletModel->getAll(); // Updated to Pallet model
        $this->view('ajax', ['pallets' => $pallets]); // Updated to pallets
    }

    function edit()
    {
        $palletModel = new \App\Models\Pallet(); // Updated to Pallet model

        $arr = $palletModel->get($_GET['id']); // Updated to Pallet model

        // Check if the form has been submitted
        if (!empty($_POST['id'])) {

            // Save the data using the Pallet model
            if ($palletModel->update($_POST)) { // Updated to Pallet model
                // Redirect to the list of pallets on successful creation
                header("Location: " . INSTALL_URL . "?controller=Pallet&action=list", true, 301); // Updated redirect
                exit;
            } else {
                // If saving fails, set an error message
                $arr['error_message'] = "Failed to create the pallet. Please try again.";
            }
        }

        $arr['currency'] = $this->settings['currency']; //$this->settings['currency_code'], set manually to currency, since local+modded.
        // Load the view and pass the data to it
        //$this->view($this->layout);
        $this->view($this->layout, $arr);
    }

    // In your Pallet.php controller

    function print()
    {
        if (isset($_POST['palletData'])) { // Updated to palletData
            // Decode the JSON data
            $pallets = json_decode($_POST['palletData'], true); // Updated to palletData

            if (!$pallets || empty($pallets)) { // Updated to pallets
                echo "No pallets to export";
                exit;
            }
        }

        $this->view('ajax', ['pallets' => $pallets]); // Updated to pallets
    }

    function export()
    {
        // Check if palletData is provided
        if (isset($_POST['palletData'])) { // Updated to palletData
            // Decode the JSON data
            $pallets = json_decode($_POST['palletData'], true); // Updated to palletData

            if (!$pallets || empty($pallets)) { // Updated to pallets
                echo "No pallets to export";
                exit;
            }
        }

        $format = isset($_POST['format']) ? $_POST['format'] : 'pdf';

        // Export based on format
        switch ($format) {
            case 'pdf':
                $this->exportAsPDF($pallets); // Updated to pallets
                break;
            case 'excel':
                $this->exportAsExcel($pallets); // Updated to pallets
                break;
            case 'csv':
                $this->exportAsCSV($pallets); // Updated to pallets
                break;
            default:
                echo "Invalid export format";
                exit;
        }
    }

    private function exportAsPDF($pallets) // Updated to pallets
    {
        if (ob_get_level()) {
            ob_end_clean();
        }
        require_once(__DIR__ . '/../Helpers/export/tcpdf/tcpdf.php');

        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8');
        $pdf->SetCreator('Your App');
        $pdf->SetTitle('Pallets Export'); // Updated title
        $pdf->SetHeaderData('', 0, 'Pallets List', ''); // Updated header
        $pdf->setHeaderFont(array('helvetica', '', 12));
        $pdf->setFooterFont(array('helvetica', '', 10));
        $pdf->SetDefaultMonospacedFont('courier');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        $pdf->AddPage();

        // Generate HTML table with dynamic headers
        $html = $this->generateDynamicPalletTable($pallets); // Updated to pallets
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $pdf->Output('pallets_export.pdf', 'D'); // Updated filename
        exit;
    }

    private function generateDynamicPalletTable($pallets) // Updated to pallets
    {
        // Start HTML table
        $html = '<table border="1" cellpadding="5">
    <thead>
        <tr>';

        // If we have pallets, use their keys as headers
        if (!empty($pallets) && is_array($pallets[0])) { // Updated to pallets
            $headers = array_keys($pallets[0]); // Updated to pallets

            // Add headers to table
            foreach ($headers as $header) {
                $displayHeader = ucwords(str_replace('_', ' ', $header));
                $html .= '<th>' . $displayHeader . '</th>';
            }

            $html .= '</tr>
        </thead>
        <tbody>';

            // Add pallet data
            foreach ($pallets as $pallet) { // Updated to pallets
                $html .= '<tr>';
                foreach ($pallet as $value) { // Updated to pallet
                    $html .= '<td>' . htmlspecialchars($value) . '</td>';
                }
                $html .= '</tr>';
            }
        } else {
            // Fallback for no data
            $html .= '<th>No Data Available</th></tr></thead><tbody><tr><td>No packets found</td></tr>'; // Updated to pallets
        }

        $html .= '</tbody></table>';

        return $html;
    }

    private function exportAsExcel($pallets) // Updated to pallets
    {
        // Include SimpleXLSXGen
        require(__DIR__ . '/../Helpers/export/simplexlsxgen/src/SimpleXLSXGen.php');

        // Prepare data
        $data = [];

        // First pallet in array determines headers
        if (!empty($pallets) && is_array($pallets[0])) { // Updated to pallets
            // Use keys from first pallet for headers, ensuring proper capitalization
            $headers = array_keys($pallets[0]); // Updated to pallets
            $headerRow = [];

            foreach ($headers as $header) {
                // Convert product_id to Product ID, etc.
                $headerRow[] = ucwords(str_replace('_', ' ', $header));
            }

            $data[] = $headerRow;

            // Add pallets
            foreach ($pallets as $pallet) { // Updated to pallets
                $data[] = array_values($pallet); // Updated to pallet
            }
        } else {
            // Fallback for no data
            $data[] = ['No Data Available'];
            $data[] = ['No packets found']; // Updated to pallets
        }

        // Create and send file
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs('pallets_export.xlsx'); // Updated filename
        exit;
    }

    private function exportAsCSV($pallets) // Updated to pallets
    {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="pallets_export.csv"'); // Updated filename

        // Open output stream
        $output = fopen('php://output', 'w');

        // Determine headers dynamically from the first pallet
        if (!empty($pallets) && is_array($pallets[0])) { // Updated to pallets
            $headers = array_keys($pallets[0]); // Updated to pallets
            // Convert keys to readable headers (e.g., product_id to Product ID)
            $readableHeaders = array_map(function ($header) {
                return ucwords(str_replace('_', ' ', $header));
            }, $headers);

            // Add headers
            fputcsv($output, $readableHeaders);

            // Add data using the actual keys from the data
            foreach ($pallets as $pallet) { // Updated to pallets
                fputcsv($output, array_values($pallet)); // Updated to pallet
            }
        } else {
            // Fallback for empty data
            fputcsv($output, ['No data available']);
        }

        fclose($output);
        exit;
    }
}