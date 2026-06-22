<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!is_admin_logged_in()) {
    header("Location: login.php");
    exit();
}

$reviews = $pdo->query("SELECT * FROM reviews ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Management | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="d-flex justify-content-between mb-4">
            <h2>Reviews</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReviewModal">Add Review</button>
        </div>
        <div class="card p-3">
            <table class="table">
                <thead>
                    <tr><th>Client</th><th>Rating</th><th>Comment</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach($reviews as $r): ?>
                    <tr>
                        <td><?php echo $r['client_name']; ?></td>
                        <td><?php echo $r['rating']; ?>/5</td>
                        <td><?php echo substr($r['comment'], 0, 50); ?>...</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete-review" data-id="<?php echo $r['id']; ?>">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Review Modal -->
    <div class="modal fade" id="addReviewModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="addReviewForm" enctype="multipart/form-data">
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
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
                    $.post('../ajax/admin_reviews.php', {action: 'delete', id: $(this).data('id')}, function(response) {
                        alert(response);
                        location.reload();
                    });
                }
            });
        });
    </script>
</body>
</html>
