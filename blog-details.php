<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE slug = ?");
$stmt->execute([$slug]);
$blog = $stmt->fetch();

if (!$blog) {
    header("Location: blog.php");
    exit();
}

$page_title = $blog['title'];
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="blog.php">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo e($blog['title']); ?></li>
              </ol>
            </nav>

            <h1 class="fw-bold mb-3"><?php echo e($blog['title']); ?></h1>
            <div class="mb-4 text-muted small">
                <span><i class="fas fa-calendar-alt me-1"></i> <?php echo date('d M, Y', strtotime($blog['created_at'])); ?></span>
                <span class="ms-3"><i class="fas fa-tag me-1"></i> <?php echo e($blog['category']); ?></span>
            </div>

            <img src="uploads/<?php echo $blog['featured_image']; ?>" class="img-fluid rounded mb-5 w-100 shadow-sm" alt="<?php echo e($blog['title']); ?>">

            <div class="blog-content">
                <?php echo nl2br($blog['content']); ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
