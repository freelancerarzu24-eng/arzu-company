<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT s.*, c.name as category_name FROM services s JOIN categories c ON s.category_id = c.id WHERE s.slug = ? AND s.status = 1");
$stmt->execute([$slug]);
$service = $stmt->fetch();

if (!$service) {
    header("Location: services.php");
    exit();
}

$page_title = $service['title'];
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Fetch gallery images
$gallery_stmt = $pdo->prepare("SELECT image FROM service_images WHERE service_id = ?");
$gallery_stmt->execute([$service['id']]);
$images = $gallery_stmt->fetchAll();
?>

<div class="container my-5">
    <div class="row">
        <!-- Service Gallery & Description -->
        <div class="col-md-8">
            <div id="serviceCarousel" class="carousel slide mb-4 shadow-sm" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach($images as $index => $img): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="uploads/<?php echo $img['image']; ?>" class="d-block w-100" style="height: 450px; object-fit: cover;">
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>

            <div class="service-content bg-white p-4 shadow-sm rounded">
                <h2 class="fw-bold mb-3"><?php echo $service['title']; ?></h2>
                <div class="mb-4">
                    <span class="badge bg-primary px-3 py-2"><?php echo $service['category_name']; ?></span>
                </div>
                <div class="description mt-4">
                    <?php echo nl2br($service['description']); ?>
                </div>
            </div>
        </div>

        <!-- Pricing Packages -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h5 class="mb-0">Select Your Package</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills nav-justified" id="packageTabs">
                        <li class="nav-item"><a class="nav-link active rounded-0 py-3" data-bs-toggle="pill" href="#basic">Basic</a></li>
                        <li class="nav-item"><a class="nav-link rounded-0 py-3" data-bs-toggle="pill" href="#standard">Standard</a></li>
                        <li class="nav-item"><a class="nav-link rounded-0 py-3" data-bs-toggle="pill" href="#premium">Premium</a></li>
                    </ul>
                    <div class="tab-content p-4">
                        <div class="tab-pane fade show active" id="basic">
                            <h3 class="fw-bold"><?php echo format_currency($service['basic_price']); ?></h3>
                            <p class="text-muted">Standard features included in the basic package.</p>
                            <button class="btn btn-primary w-100 buy-now-btn" data-package="Basic" data-price="<?php echo $service['basic_price']; ?>">Buy Now</button>
                        </div>
                        <div class="tab-pane fade" id="standard">
                            <h3 class="fw-bold"><?php echo format_currency($service['standard_price'] ?: 0); ?></h3>
                            <p class="text-muted">Advanced features for growing businesses.</p>
                            <button class="btn btn-primary w-100 buy-now-btn" data-package="Standard" data-price="<?php echo $service['standard_price']; ?>">Buy Now</button>
                        </div>
                        <div class="tab-pane fade" id="premium">
                            <h3 class="fw-bold"><?php echo format_currency($service['premium_price'] ?: 0); ?></h3>
                            <p class="text-muted">Full customization and priority support.</p>
                            <button class="btn btn-primary w-100 buy-now-btn" data-package="Premium" data-price="<?php echo $service['premium_price']; ?>">Buy Now</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Service Details</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Quality Assurance</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> On-time Delivery</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 24/7 Support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Buy Now Modal -->
<div class="modal fade" id="buyNowModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5>Order: <?php echo $service['title']; ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center">
                <p>Please select your country to continue</p>
                <div class="mb-3">
                    <select id="countrySelect" class="form-control">
                        <option value="">Select Country</option>
                        <option value="Bangladesh">Bangladesh</option>
                        <option value="Other">Other Country</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="continueOrder">Continue</button>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/order.js"></script>

<?php require_once 'includes/footer.php'; ?>
