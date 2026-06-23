<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Category Management";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
$csrf_token = generate_csrf_token();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Categories</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $cat): ?>
                        <tr>
                            <td><img src="../uploads/<?php echo e($cat['image']); ?>" width="50" height="50" style="object-fit: cover;"></td>
                            <td><?php echo e($cat['name']); ?></td>
                            <td><?php echo e($cat['slug']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $cat['status'] ? 'success' : 'danger'; ?>">
                                    <?php echo $cat['status'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info edit-cat" data-id="<?php echo $cat['id']; ?>">Edit</button>
                                <button class="btn btn-sm btn-danger delete-cat" data-id="<?php echo $cat['id']; ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="addCategoryForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="modal-content">
                <div class="modal-header"><h5>Add Category</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label>Image</label><input type="file" name="image" class="form-control" required></div>
                    <div class="mb-3"><label>Status</label><select name="status" class="form-control"><option value="1">Active</option><option value="0">Inactive</option></select></div>
                    <hr>
                    <h6>SEO Settings</h6>
                    <div class="mb-3"><label>Meta Title</label><input type="text" name="meta_title" class="form-control"></div>
                    <div class="mb-3"><label>Meta Description</label><textarea name="meta_description" class="form-control"></textarea></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Category</button></div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#addCategoryForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('action', 'add');
            $.ajax({
                url: '../ajax/admin_categories.php',
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

        $('.delete-cat').on('click', function() {
            if(confirm('Are you sure?')) {
                var id = $(this).data('id');
                $.post('../ajax/admin_categories.php', {
                    action: 'delete',
                    id: id,
                    csrf_token: '<?php echo $csrf_token; ?>'
                }, function(response) {
                    alert(response);
                    location.reload();
                });
            }
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
