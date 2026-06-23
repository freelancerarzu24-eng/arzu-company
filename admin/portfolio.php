<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Portfolio Management";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$portfolios = $pdo->query("SELECT * FROM portfolios ORDER BY id DESC")->fetchAll();
$csrf_token = generate_csrf_token();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Portfolio</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPortfolioModal">Add Project</button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr><th>Title</th><th>Tech Stack</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($portfolios as $p): ?>
                        <tr>
                            <td><?php echo e($p['title']); ?></td>
                            <td><?php echo e($p['tech_stack']); ?></td>
                            <td>
                                <button class="btn btn-sm btn-info edit-portfolio" data-id="<?php echo $p['id']; ?>">Edit</button>
                                <button class="btn btn-sm btn-danger delete-portfolio" data-id="<?php echo $p['id']; ?>">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="addPortfolioModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="addPortfolioForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="modal-content">
                <div class="modal-header"><h5>Add New Project</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Project Title</label><input type="text" name="title" class="form-control" required></div>
                    <div class="mb-3"><label>Images (Multiple)</label><input type="file" name="images[]" class="form-control" multiple required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Live URL</label><input type="url" name="live_url" class="form-control"></div>
                        <div class="col-md-6 mb-3"><label>GitHub URL</label><input type="url" name="github_url" class="form-control"></div>
                    </div>
                    <div class="mb-3"><label>Tech Stack</label><input type="text" name="tech_stack" class="form-control" placeholder="e.g. PHP, MySQL, Bootstrap"></div>
                    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" rows="5"></textarea></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Project</button></div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                $.post('../ajax/admin_portfolio.php', {
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
