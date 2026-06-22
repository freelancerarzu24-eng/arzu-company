<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject'] ?? 'Contact Inquiry');
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        echo "Please fill all required fields.";
    } else {
        // In a real production app, you'd send an email here.
        // For this task, we will just return success.
        echo "Thank you for your message. I will get back to you soon!";
    }
}
?>
