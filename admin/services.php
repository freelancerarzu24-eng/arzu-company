<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Service Management";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$services = $pdo->query("SELECT s.*, c.name as category_name FROM services s JOIN categories c ON s.category_id = c.id ORDER BY s.id DESC")->fetchAll();
$categories = $pdo->query("SELECT id, name FROM categories WHERE status = 1")->fetchAll();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Services</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">Add Service</button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Basic Price</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($services as $service): ?>
                        <tr>
                            <td><?php echo e($service['title']); ?></td>
                            <td><?php echo e($service['category_name']); ?></td>
                            <td><?php echo format_currency($service['basic_price']); ?></td>
                            <td><span class="badge bg-<?php echo $service['status'] ? 'success' : 'danger'; ?>"><?php echo $service['status'] ? 'Active' : 'Inactive'; ?></span></td>
                            <td><span class="badge bg-<?php echo $service['featured'] ? 'warning' : 'secondary'; ?>"><?php echo $service['featured'] ? 'Yes' : 'No'; ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-info edit-service" data-id="<?php echo $service['id']; ?>">Edit</button>
                                <button class="btn btn-sm btn-danger delete-service" data-id="<?php echo $service['id']; ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="addServiceForm" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header"><h5>Add New Service</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Category</label>
                            <select name="category_id" class="form-control" required>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo e($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Short Description</label>
                        <textarea name="short_description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Full Description</label>
                        <textarea name="description" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label>Basic Price ($)</label><input type="number" name="basic_price" class="form-control" required></div>
                        <div class="col-md-4 mb-3"><label>Standard Price ($)</label><input type="number" name="standard_price" class="form-control"></div>
                        <div class="col-md-4 mb-3"><label>Premium Price ($)</label><input type="number" name="premium_price" class="form-control"></div>
                    </div>
                    <div class="mb-3">
                        <label>Gallery Images (Multiple)</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Status</label><select name="status" class="form-control"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                        <div class="col-md-6 mb-3"><label>Featured</label><select name="featured" class="form-control"><option value="0">No</option><option value="1">Yes</option></select></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Service</button></div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#addServiceForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('action', 'add');
            $.ajax({
                url: '../ajax/admin_services.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        });

        $('.delete-service').on('click', function() {
            if(confirm('Are you sure?')) {
                var id = $(this).data('id');
                $.post('../ajax/admin_services.php', {action: 'delete', id: id}, function(response) {
                    alert(response);
                    location.reload();
                });
            }
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
