<?php
header("Content-Type: application/xml; charset=utf-8");
require_once 'includes/db_connect.php';

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$base_url = "https://dmagancy.com/";

// Static Pages
$static_pages = ['index.php', 'services.php', 'portfolio.php', 'blog.php', 'contact.php'];
foreach ($static_pages as $page) {
    echo '<url><loc>' . $base_url . $page . '</loc><priority>0.8</priority></url>';
}

// Services
$services = $pdo->query("SELECT slug FROM services WHERE status = 1");
while($row = $services->fetch()){
    echo '<url><loc>' . $base_url . 'service-details.php?slug=' . $row['slug'] . '</loc><priority>0.7</priority></url>';
}

// Blogs
$blogs = $pdo->query("SELECT slug FROM blogs");
while($row = $blogs->fetch()){
    echo '<url><loc>' . $base_url . 'blog-details.php?slug=' . $row['slug'] . '</loc><priority>0.6</priority></url>';
}

echo '</urlset>';
?>
