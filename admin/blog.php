<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Blog Management";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$blogs = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll();
$csrf_token = generate_csrf_token();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Blogs</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBlogModal">Add New Post</button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr><th>Title</th><th>Category</th><th>Date</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($blogs as $blog): ?>
                        <tr>
                            <td><?php echo e($blog['title']); ?></td>
                            <td><?php echo e($blog['category']); ?></td>
                            <td><?php echo date('d M, Y', strtotime($blog['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-sm btn-info edit-blog" data-id="<?php echo $blog['id']; ?>">Edit</button>
                                <button class="btn btn-sm btn-danger delete-blog" data-id="<?php echo $blog['id']; ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Add Blog Modal -->
<div class="modal fade" id="addBlogModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="addBlogForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="modal-content">
                <div class="modal-header"><h5>New Blog Post</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label>Featured Image</label><input type="file" name="image" class="form-control" required></div>
                    <div class="mb-3"><label>Category</label><input type="text" name="category" class="form-control" required></div>
                    <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" rows="10" required></textarea></div>
                    <hr>
                    <h6>SEO Settings</h6>
                    <div class="mb-3"><label>Meta Title</label><input type="text" name="meta_title" class="form-control"></div>
                    <div class="mb-3"><label>Meta Description</label><textarea name="meta_description" class="form-control"></textarea></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Publish</button></div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#addBlogForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('action', 'add');
            $.ajax({
                url: '../ajax/admin_blog.php',
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

        $('.delete-blog').on('click', function() {
            if(confirm('Are you sure?')) {
                $.post('../ajax/admin_blog.php', {
                    action: 'delete',
                    id: $(this).data('id'),
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
