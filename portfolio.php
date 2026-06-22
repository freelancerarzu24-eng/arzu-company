<?php
$page_title = "Portfolio";
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$portfolios = $pdo->query("SELECT * FROM portfolios ORDER BY id DESC")->fetchAll();
?>

<div class="container my-5">
    <div class="section-title text-center mb-5">
        <h2>My Recent Works</h2>
        <p>A showcase of my recent web development projects.</p>
    </div>
    <div class="row">
        <?php foreach($portfolios as $p):
            $images = json_decode($p['images'], true);
            $main_img = !empty($images) ? $images[0] : 'placeholder.jpg';
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="uploads/<?php echo $main_img; ?>" class="card-img-top" alt="<?php echo $p['title']; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $p['title']; ?></h5>
                    <p class="text-muted small"><?php echo $p['tech_stack']; ?></p>
                    <p class="card-text"><?php echo substr($p['description'], 0, 100); ?>...</p>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="<?php echo $p['live_url']; ?>" target="_blank" class="btn btn-sm btn-primary">Live Demo</a>
                        <a href="<?php echo $p['github_url']; ?>" target="_blank" class="btn btn-sm btn-outline-dark"><i class="fab fa-github"></i> GitHub</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
