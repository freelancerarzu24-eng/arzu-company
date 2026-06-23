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
    $title = $_POST['title'];
    $live = $_POST['live_url'];
    $github = $_POST['github_url'];
    $tech = $_POST['tech_stack'];
    $desc = $_POST['description'];

    $uploaded_images = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file = [
                'name' => $_FILES['images']['name'][$key],
                'tmp_name' => $_FILES['images']['tmp_name'][$key],
                'size' => $_FILES['images']['size'][$key],
                'error' => $_FILES['images']['error'][$key]
            ];
            $img = upload_image($file, '../uploads/');
            if ($img) $uploaded_images[] = $img;
        }
    }
    $images_json = json_encode($uploaded_images);

    $stmt = $pdo->prepare("INSERT INTO portfolios (title, images, live_url, github_url, tech_stack, description) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$title, $images_json, $live, $github, $tech, $desc])) {
        echo "Portfolio project added successfully!";
    } else {
        echo "Error adding project.";
    }
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM portfolios WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo "Project deleted successfully!";
    } else {
        echo "Error deleting project.";
    }
}
?>
