<?php
$page_title = "Contact Us";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <h2 class="fw-bold mb-4">Let's Talk About Your Project</h2>
            <p class="text-muted mb-5">Fill out the form and I will get back to you as soon as possible.</p>

            <div class="contact-info">
                <div class="d-flex mb-4">
                    <div class="icon-box bg-primary text-white p-3 rounded-circle me-3"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h6 class="mb-1">Email Me</h6>
                        <p class="text-muted mb-0"><?php echo $settings['contact_email']; ?></p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="icon-box bg-primary text-white p-3 rounded-circle me-3"><i class="fas fa-phone"></i></div>
                    <div>
                        <h6 class="mb-1">Call Me</h6>
                        <p class="text-muted mb-0"><?php echo $settings['contact_phone']; ?></p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="icon-box bg-primary text-white p-3 rounded-circle me-3"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h6 class="mb-1">Address</h6>
                        <p class="text-muted mb-0"><?php echo $settings['address']; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 shadow-sm border-0">
                <form id="contactForm">
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-5 py-2">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
