<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$blogs = $pdo->query("SELECT * FROM blogs ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog Management | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-md-block bg-dark sidebar min-vh-100 p-3">
                <a href="index.php" class="text-white text-decoration-none"><h5>Admin Panel</h5></a>
                <ul class="nav flex-column mt-4">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="categories.php">Categories</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="services.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link text-primary" href="blog.php">Blog</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
                </ul>
            </nav>

            <main class="col-md-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Blog Posts</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBlogModal">Add Post</button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($blogs as $blog): ?>
                                <tr>
                                    <td><img src="../uploads/<?php echo $blog['featured_image']; ?>" width="50"></td>
                                    <td><?php echo $blog['title']; ?></td>
                                    <td><?php echo $blog['category']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger delete-blog" data-id="<?php echo $blog['id']; ?>">Delete</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Blog Modal -->
    <div class="modal fade" id="addBlogModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="addBlogForm" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header"><h5>Add Blog Post</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required></div>
                        <div class="mb-3"><label>Featured Image</label><input type="file" name="image" class="form-control" required></div>
                        <div class="mb-3"><label>Category</label><input type="text" name="category" class="form-control"></div>
                        <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" rows="10"></textarea></div>
                        <hr>
                        <h6>SEO Settings</h6>
                        <div class="mb-3"><label>Meta Title</label><input type="text" name="meta_title" class="form-control"></div>
                        <div class="mb-3"><label>Meta Description</label><textarea name="meta_description" class="form-control"></textarea></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Post</button></div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
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
                    var id = $(this).data('id');
                    $.post('../ajax/admin_blog.php', {action: 'delete', id: id}, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });
        });
    </script>
</body>
</html>
