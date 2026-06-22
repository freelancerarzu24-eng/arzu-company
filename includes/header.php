<?php
require_once 'db_connect.php';
require_once 'functions.php';

// Fetch settings
$stmt = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $stmt->fetch();

$site_name = $settings['website_name'] ?? 'Dmagancy';
$site_logo = $settings['website_logo'] ?? 'assets/img/logo.png';
$favicon = $settings['favicon'] ?? 'assets/img/favicon.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . " | " . $site_name : $site_name; ?></title>

    <!-- Meta SEO -->
    <meta name="description" content="<?php echo $meta_description ?? ''; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords ?? ''; ?>">

    <!-- Favicon -->
    <link rel="icon" href="<?php echo $favicon; ?>" type="image/x-icon">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
