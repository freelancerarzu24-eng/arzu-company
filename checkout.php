<?php
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    redirect('login.php', 'Please login to place an order.', 'warning');
}

$slug = $_GET['slug'] ?? '';
$package = $_GET['package'] ?? 'Basic';
$price = $_GET['price'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM services WHERE slug = ?");
$stmt->execute([$slug]);
$service = $stmt->fetch();

if (!$service) {
    header("Location: services.php");
    exit();
}

$page_title = "Checkout";
require_once 'includes/header.php';
require_once 'includes/navbar.php';

$settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-7">
            <div class="card p-4 shadow-sm">
                <h4 class="mb-4">Complete Your Order</h4>
                <form id="checkoutForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Project Requirements</label>
                        <textarea name="requirements" class="form-control" rows="5" placeholder="Explain what you need..." required></textarea>
                    </div>

                    <div class="payment-methods mt-4">
                        <h5>Payment Method (Manual)</h5>
                        <p class="text-muted small">Send payment to one of the numbers below and upload the screenshot.</p>

                        <div class="row mb-3">
                            <?php if($settings['bkash_number']): ?>
                                <div class="col-md-4">
                                    <div class="border p-2 text-center rounded">
                                        <strong>bKash</strong><br><?php echo $settings['bkash_number']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($settings['nagad_number']): ?>
                                <div class="col-md-4">
                                    <div class="border p-2 text-center rounded">
                                        <strong>Nagad</strong><br><?php echo $settings['nagad_number']; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label>Select Payment Method Used</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="bKash">bKash</option>
                                <option value="Nagad">Nagad</option>
                                <option value="Rocket">Rocket</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Transaction ID</label>
                            <input type="text" name="transaction_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Upload Payment Screenshot</label>
                            <input type="file" name="payment_screenshot" class="form-control" required>
                        </div>
                    </div>

                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                    <input type="hidden" name="package_type" value="<?php echo $package; ?>">
                    <input type="hidden" name="amount" value="<?php echo $price; ?>">
                    <input type="hidden" name="country" value="Bangladesh">

                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-4">Submit Order</button>
                </form>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card p-4 shadow-sm bg-light">
                <h5>Order Summary</h5>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Service:</span>
                    <span class="fw-bold"><?php echo $service['title']; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Package:</span>
                    <span class="badge bg-info"><?php echo $package; ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="h5">Total Amount:</span>
                    <span class="h5 fw-bold text-primary"><?php echo format_currency($price); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#checkoutForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'ajax/order_process.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var res = JSON.parse(response);
                if(res.status === 'success') {
                    alert(res.message);
                    window.location.href = 'customer/orders.php';
                } else {
                    alert(res.message);
                }
            }
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
