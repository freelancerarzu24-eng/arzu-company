<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$portfolios = $pdo->query("SELECT * FROM portfolios ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Portfolio Management | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
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
                    <li class="nav-item"><a class="nav-link text-primary" href="portfolio.php">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
                </ul>
            </nav>

            <main class="col-md-10 px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Portfolio</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPortfolioModal">Add Project</button>
                </div>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Tech Stack</th>
                                    <th>Live URL</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($portfolios as $p): ?>
                                <tr>
                                    <td><?php echo $p['title']; ?></td>
                                    <td><?php echo $p['tech_stack']; ?></td>
                                    <td><a href="<?php echo $p['live_url']; ?>" target="_blank"><?php echo $p['live_url']; ?></a></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger delete-portfolio" data-id="<?php echo $p['id']; ?>">Delete</button>
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

    <!-- Add Portfolio Modal -->
    <div class="modal fade" id="addPortfolioModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="addPortfolioForm" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header"><h5>Add Portfolio Project</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="mb-3"><label>Project Title</label><input type="text" name="title" class="form-control" required></div>
                        <div class="row">
                            <div class="col-md-6 mb-3"><label>Live URL</label><input type="url" name="live_url" class="form-control"></div>
                            <div class="col-md-6 mb-3"><label>GitHub URL</label><input type="url" name="github_url" class="form-control"></div>
                        </div>
                        <div class="mb-3"><label>Technology Stack</label><input type="text" name="tech_stack" class="form-control" placeholder="e.g. PHP, MySQL, Bootstrap"></div>
                        <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="4"></textarea></div>
                        <div class="mb-3"><label>Project Images (Multiple)</label><input type="file" name="images[]" class="form-control" multiple></div>
                    </div>
                    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Project</button></div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addPortfolioForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('action', 'add');
                $.ajax({
                    url: '../ajax/admin_portfolio.php',
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

            $('.delete-portfolio').on('click', function() {
                if(confirm('Are you sure?')) {
                    var id = $(this).data('id');
                    $.post('../ajax/admin_portfolio.php', {action: 'delete', id: id}, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });
        });
    </script>
</body>
</html>
