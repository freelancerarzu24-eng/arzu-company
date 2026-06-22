<?php
session_start();

/**
 * Sanitize output to prevent XSS
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF Token
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirect with a message
 */
function redirect($url, $message = '', $type = 'success') {
    if (!empty($message)) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header("Location: $url");
    exit();
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if admin is logged in
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

/**
 * Get website setting
 */
function get_setting($pdo, $column) {
    $stmt = $pdo->query("SELECT $column FROM settings LIMIT 1");
    return $stmt->fetchColumn();
}

/**
 * Format currency
 */
function format_currency($amount) {
    return '$' . number_format($amount, 2);
}

/**
 * Slugify a string
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

/**
 * Upload image
 */
function upload_image($file, $target_dir) {
    $target_file = $target_dir . time() . '_' . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) return false;

    // Check file size
    if ($file["size"] > 5000000) return false;

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp") return false;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return basename($target_file);
    } else {
        return false;
    }
}
?>
