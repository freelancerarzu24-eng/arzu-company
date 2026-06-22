<?php
$page_title = "Blog";
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$blogs = $pdo->query("SELECT * FROM blogs ORDER BY id DESC")->fetchAll();
?>

<div class="container my-5">
    <div class="section-title text-center mb-5">
        <h2>Latest Articles</h2>
        <p>Stay updated with the latest web development tips and trends.</p>
    </div>
    <div class="row">
        <?php foreach($blogs as $blog): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="uploads/<?php echo $blog['featured_image']; ?>" class="card-img-top" alt="<?php echo $blog['title']; ?>">
                <div class="card-body">
                    <span class="badge bg-light text-primary mb-2"><?php echo $blog['category']; ?></span>
                    <h5 class="card-title fw-bold"><?php echo $blog['title']; ?></h5>
                    <p class="card-text text-muted"><?php echo substr(strip_tags($blog['content']), 0, 120); ?>...</p>
                    <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>" class="btn btn-link text-primary p-0 text-decoration-none">Read More <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
