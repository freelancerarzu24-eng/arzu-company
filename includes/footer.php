<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container text-center text-md-start">
        <div class="row">
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary"><?php echo $site_name; ?></h5>
                <p><?php echo $settings['footer_content'] ?? 'Professional Web Development Services for your business growth.'; ?></p>
            </div>

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Quick Links</h5>
                <p><a href="services.php" class="text-white text-decoration-none">Services</a></p>
                <p><a href="portfolio.php" class="text-white text-decoration-none">Portfolio</a></p>
                <p><a href="blog.php" class="text-white text-decoration-none">Blog</a></p>
                <p><a href="contact.php" class="text-white text-decoration-none">Contact Us</a></p>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Contact</h5>
                <p><i class="fas fa-home me-3"></i> <?php echo $settings['address'] ?? 'Remote, Worldwide'; ?></p>
                <p><i class="fas fa-envelope me-3"></i> <?php echo $settings['contact_email'] ?? 'info@dmagancy.com'; ?></p>
                <p><i class="fas fa-phone me-3"></i> <?php echo $settings['contact_phone'] ?? '+880123456789'; ?></p>
            </div>

            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-primary">Follow Us</h5>
                <div class="social-links">
                    <?php
                    $social_stmt = $pdo->query("SELECT * FROM social_links WHERE url != '#' AND url != ''");
                    while($social = $social_stmt->fetch()): ?>
                        <a href="<?php echo $social['url']; ?>" class="text-white me-4 text-decoration-none" target="_blank">
                            <i class="fab fa-<?php echo $social['platform']; ?>"></i>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <hr class="mb-4">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8">
                <p>Copyright © <?php echo date('Y'); ?> All rights reserved by:
                    <a href="index.php" style="text-decoration: none;">
                        <strong class="text-primary"><?php echo $site_name; ?></strong>
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<?php if(!empty($settings['whatsapp_number'])): ?>
<a href="https://wa.me/<?php echo $settings['whatsapp_number']; ?>" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/main.js"></script>

</body>
</html>
