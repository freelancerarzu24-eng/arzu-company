<?php
$page_title = "Services";
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$search = $_GET['search'] ?? '';
$category_id = $_GET['category'] ?? '';

$query = "SELECT s.*, c.name as category_name FROM services s JOIN categories c ON s.category_id = c.id WHERE s.status = 1";
$params = [];

if ($search) {
    $query .= " AND (s.title LIKE ? OR s.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category_id) {
    $query .= " AND s.category_id = ?";
    $params[] = $category_id;
}

$query .= " ORDER BY s.id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$services = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories WHERE status = 1")->fetchAll();
?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-md-3 mb-4">
            <div class="card p-3 shadow-sm">
                <h5>Filter Services</h5>
                <form action="services.php" method="GET">
                    <div class="mb-3">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" value="<?php echo e($search); ?>" placeholder="Keywords...">
                    </div>
                    <div class="mb-3">
                        <label>Category</label>
                        <select name="category" class="form-control">
                            <option value="">All Categories</option>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $category_id == $cat['id'] ? 'selected' : ''; ?>><?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </form>
            </div>
        </div>

        <!-- Service List -->
        <div class="col-md-9">
            <div class="row">
                <?php if(empty($services)): ?>
                    <div class="col-12 text-center">
                        <h4>No services found.</h4>
                    </div>
                <?php endif; ?>
                <?php foreach($services as $service):
                    $img_stmt = $pdo->prepare("SELECT image FROM service_images WHERE service_id = ? LIMIT 1");
                    $img_stmt->execute([$service['id']]);
                    $main_img = $img_stmt->fetchColumn() ?: 'placeholder.jpg';
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 service-card">
                        <img src="uploads/<?php echo $main_img; ?>" class="card-img-top" alt="<?php echo $service['title']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $service['title']; ?></h5>
                            <p class="card-text text-muted"><?php echo substr($service['short_description'], 0, 80); ?>...</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fw-bold text-primary"><?php echo format_currency($service['basic_price']); ?></span>
                                <a href="service-details.php?slug=<?php echo $service['slug']; ?>" class="btn btn-sm btn-outline-primary">View More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
