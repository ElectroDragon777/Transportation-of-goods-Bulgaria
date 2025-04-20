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
        $messageModel = new \App\Models\Message();
        $messages = $messageModel->getAll(['recipient_id' => $_SESSION['user']['id']]);
        $userModel = new \App\Models\User();
        // Use sender_name from the database
        foreach ($messages as &$message) {
            $sender = $userModel->getFirstBy(['id' => $message['sender_id']]);
            $message['sender_name'] = $sender ? htmlspecialchars($sender['name']) : 'Unknown User';
        }

        $this->view($this->layout, ['messages' => $messages]);
    }

    function sendMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageModel = new \App\Models\Message();
            $userModel = new \App\Models\User();

            $sender_id = $_SESSION['user']['id'] ?? null;

            $sender = $userModel->getFirstBy(['id' => $sender_id]);
            $sender_name = $sender ? htmlspecialchars($sender['name']) : 'Unknown User';

            $data['sender_name'] = $sender_name;

            $recipient_id = 1;

            $data = [
                'sender_id' => $sender_id,
                'recipient_id' => $recipient_id,
                'message' => $_POST['message'],
                'created_at' => time(), // Get the current Unix timestamp
                'sender_name' => $sender_name,
            ];

            if ($messageModel->save($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Message sent!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send message.']);
            }
        } else {
            header("Location: " . INSTALL_URL, true, 302);
            exit;
        }
    }

    function markAsRead()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $messageModel = new \App\Models\Message();
            $messageModel->update(['id' => $_POST['id'], 'is_read' => 1]); // DO NOT update created_at

            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }

    function markAllRead()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageModel = new \App\Models\Message();
            $messageModel->updateBy(['is_read' => 1], ['recipient_id' => $_SESSION['user']['id']]); // DO NOT update created_at

            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error"]);
        }
    }
}