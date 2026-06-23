<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$page_title = "Review Management";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$reviews = $pdo->query("SELECT * FROM reviews ORDER BY id DESC")->fetchAll();
$csrf_token = generate_csrf_token();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
    <div class="d-flex justify-content-between mb-4">
        <h2>Reviews</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReviewModal">Add Review</button>
    </div>
    <div class="card p-3 shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr><th>Client</th><th>Rating</th><th>Comment</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach($reviews as $r): ?>
                    <tr>
                        <td><?php echo e($r['client_name']); ?></td>
                        <td><?php echo $r['rating']; ?>/5</td>
                        <td><?php echo e(substr($r['comment'], 0, 50)); ?>...</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete-review" data-id="<?php echo $r['id']; ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Add Review Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="addReviewForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <div class="modal-content">
                <div class="modal-header"><h5>Add Client Review</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Client Name</label><input type="text" name="client_name" class="form-control" required></div>
                    <div class="mb-3"><label>Client Image</label><input type="file" name="image" class="form-control"></div>
                    <div class="mb-3"><label>Rating</label><select name="rating" class="form-control"><option value="5">5 Stars</option><option value="4">4 Stars</option><option value="3">3 Stars</option><option value="2">2 Stars</option><option value="1">1 Star</option></select></div>
                    <div class="mb-3"><label>Comment</label><textarea name="comment" class="form-control" required></textarea></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Save Review</button></div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#addReviewForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('action', 'add');
            $.ajax({
                url: '../ajax/admin_reviews.php',
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
        $('.delete-review').on('click', function() {
            if(confirm('Delete this review?')) {
                $.post('../ajax/admin_reviews.php', {
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
