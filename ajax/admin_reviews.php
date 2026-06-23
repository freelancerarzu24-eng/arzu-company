<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    die("Unauthorized");
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    die("CSRF token validation failed");
}

$action = $_POST['action'] ?? '';

if ($action == 'add') {
    $name = $_POST['client_name'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $image = '';
    if (!empty($_FILES['image']['name'])) {
        $image = upload_image($_FILES['image'], '../uploads/');
    }

    $stmt = $pdo->prepare("INSERT INTO reviews (client_name, client_image, rating, comment, status) VALUES (?, ?, ?, ?, 1)");
    if ($stmt->execute([$name, $image, $rating, $comment])) {
        echo "Review added successfully!";
    } else {
        echo "Error adding review.";
    }
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo "Review deleted successfully!";
    } else {
        echo "Error deleting review.";
    }
}
?>
