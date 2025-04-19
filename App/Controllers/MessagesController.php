<?php

namespace App\Controllers;

use Models;
use Core\Controller;

class MessagesController extends Controller
{

    var $layout = 'admin';

    public function __construct()
    {
        if (empty($_SESSION['user'])) {
            header("Location: " . INSTALL_URL . "?controller=Auth&action=login", true, 301);
            exit;
        }
    }

    function index()
    {
        // Fetch all messages for the current user (recipient)
        $messageModel = new \App\Models\Message();
        $messages = $messageModel->getAll(['recipient_id' => $_SESSION['user']['id']]);

        $this->view($this->layout, ['messages' => $messages]);
    }

    function sendMessage()
    {
        // This will handle sending a message from the contact form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageModel = new \App\Models\Message();

            //Get sender ID
            $sender_id = $_SESSION['user']['id'] ?? null; // Get sender ID from session or null if not logged in

            // Get recipient ID (always 1 for root)
            $recipient_id = 1;

            $data = [
                'sender_id' => $sender_id,
                'recipient_id' => $recipient_id,
                'message' => $_POST['message']
            ];

            if ($messageModel->save($data)) {
                // Optionally return a success message (e.g., for AJAX)
                echo json_encode(['status' => 'success', 'message' => 'Message sent!']);
            } else {
                // Optionally return an error message
                echo json_encode(['status' => 'error', 'message' => 'Failed to send message.']);
            }
        } else {
            // If it's not a POST request, redirect or show an error
            header("Location: " . INSTALL_URL, true, 302); // Redirect back
            exit;
        }
    }

    function markAsRead()
    {
        // Corrected method name to "markAsRead" to align with "is_read"
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $messageModel = new \App\Models\Message();
            $messageModel->update(['id' => $_POST['id'], 'is_read' => 1]); // Updated to is_read

            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }

    function markAllRead()
    {
        // Corrected method name to "markAllRead" to align with "is_read"
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageModel = new \App\Models\Message();
            $messageModel->updateBy(['is_read' => 1], ['recipient_id' => $_SESSION['user']['id']]); // Updated to recipient_id and is_read

            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }
}