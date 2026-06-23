<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Dashboard'; ?> | Dmagancy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .sidebar { min-height: 100vh; background: #212529; color: #fff; }
        .sidebar .nav-link { color: #adb5bd; padding: 12px 20px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #343a40; }
        .stat-card { border-radius: 10px; border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        main { background: #f8f9fa; min-height: 100vh; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
