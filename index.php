<?php
$page_title = "Home";
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Fetch SEO for home
$seo_stmt = $pdo->prepare("SELECT * FROM seo_settings WHERE page_name = 'home'");
$seo_stmt->execute();
$home_seo = $seo_stmt->fetch();
?>

<!-- Hero Section -->
<header class="hero-section text-center text-md-start">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h1 class="display-4 fw-bold mb-4 text-gradient">Crafting Next-Gen Digital Experiences</h1>
                <p class="lead mb-4 text-muted">Bespoke web architecture, seamless user interfaces, and high-performance backend systems. Your vision, engineered to perfection.</p>
                <div class="cta-buttons">
                    <a href="services.php" class="btn btn-primary btn-lg me-3">Get Started</a>
                    <a href="portfolio.php" class="btn btn-outline-light btn-lg">View Projects</a>
                </div>
            </div>
            <div class="col-md-5 d-none d-md-block">
                <div class="bg-glass p-5 text-center">
                    <img src="assets/img/hero-ai.png" alt="AI Development" class="img-fluid floating-animation" style="max-height: 300px;">
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Featured Services -->
<section class="services-section">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2 class="text-gradient">Premium Services</h2>
            <p class="text-muted">Explore our professional-grade web solutions designed for the modern era.</p>
        </div>
        <div class="row">
            <?php
            $featured_stmt = $pdo->query("SELECT * FROM services WHERE featured = 1 AND status = 1 LIMIT 3");
            while($service = $featured_stmt->fetch()):
                $img_stmt = $pdo->prepare("SELECT image FROM service_images WHERE service_id = ? LIMIT 1");
                $img_stmt->execute([$service['id']]);
                $main_img = $img_stmt->fetchColumn() ?: 'placeholder.jpg';
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 service-card">
                    <img src="uploads/<?php echo $main_img; ?>" class="card-img-top" alt="<?php echo $service['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $service['title']; ?></h5>
                        <p class="card-text text-muted"><?php echo $service['short_description']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">Starts from <?php echo format_currency($service['basic_price']); ?></span>
                            <a href="service-details.php?slug=<?php echo $service['slug']; ?>" class="btn btn-sm btn-outline-primary">Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-4">
            <a href="services.php" class="btn btn-primary">View All Services</a>
        </div>
    </div>
</section>

<!-- Why Choose Me -->
<section class="why-choose-section bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold mb-4">Why Choose Me?</h2>
                <div class="feature-item d-flex mb-4">
                    <div class="icon-box bg-primary text-white rounded-circle p-3 me-3">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <h5>Quality First</h5>
                        <p class="text-muted">I deliver high-quality, clean, and maintainable code.</p>
                    </div>
                </div>
                <div class="feature-item d-flex mb-4">
                    <div class="icon-box bg-primary text-white rounded-circle p-3 me-3">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div>
                        <h5>On-Time Delivery</h5>
                        <p class="text-muted">I respect deadlines and ensure project delivery within time.</p>
                    </div>
                </div>
                <div class="feature-item d-flex mb-4">
                    <div class="icon-box bg-primary text-white rounded-circle p-3 me-3">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div>
                        <h5>Excellent Support</h5>
                        <p class="text-muted">Ongoing support and communication throughout the project.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="assets/img/why-choose.png" class="img-fluid" alt="Why Choose Me">
            </div>
        </div>
    </div>
</section>

<!-- Statistics Counter -->
<section class="stats-section bg-primary text-white py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3">
                <h2 class="fw-bold">150+</h2>
                <p>Projects Completed</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold">100+</h2>
                <p>Happy Clients</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold">5+</h2>
                <p>Years Experience</p>
            </div>
            <div class="col-md-3">
                <h2 class="fw-bold">24/7</h2>
                <p>Support Available</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="reviews-section">
    <div class="container">
        <div class="section-title text-center">
            <h2 class="text-gradient">Client Success Stories</h2>
        </div>
        <div class="row">
            <?php
            $review_stmt = $pdo->query("SELECT * FROM reviews WHERE status = 1 LIMIT 3");
            while($review = $review_stmt->fetch()):
            ?>
            <div class="col-md-4 mb-4">
                <div class="card p-4 h-100 bg-glass">
                    <div class="mb-3 text-warning">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <i class="fas fa-star<?php echo $i <= $review['rating'] ? '' : '-half-alt'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="mb-4 text-muted">"<?php echo e($review['comment']); ?>"</p>
                    <div class="d-flex align-items-center mt-auto">
                        <img src="uploads/<?php echo $review['client_image'] ?: 'default-user.png'; ?>" class="rounded-circle me-3 border border-primary" width="50" height="50">
                        <div>
                            <h6 class="mb-0"><?php echo e($review['client_name']); ?></h6>
                            <small class="text-muted">Verified Client</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section class="blog-section bg-light">
    <div class="container">
        <div class="section-title text-center">
            <h2>Latest From Blog</h2>
        </div>
        <div class="row">
            <?php
            $blog_stmt = $pdo->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 3");
            while($blog = $blog_stmt->fetch()):
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="uploads/<?php echo $blog['featured_image']; ?>" class="card-img-top" alt="<?php echo $blog['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $blog['title']; ?></h5>
                        <p class="card-text text-muted"><?php echo substr(strip_tags($blog['content']), 0, 100); ?>...</p>
                        <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>" class="text-primary text-decoration-none">Read More <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section bg-white">
    <div class="container">
        <div class="section-title text-center">
            <h2>Frequently Asked Questions</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How long does it take to build a website?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                The timeline depends on the complexity of the project. A simple website might take 1-2 weeks, while a complex web application could take several months.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Do you provide SEO services?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, all my websites are built with SEO best practices in mind, and I also offer dedicated SEO optimization services.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section bg-light" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <h2 class="fw-bold mb-4">Get In Touch</h2>
                <p>Have a project in mind? Let's discuss it!</p>
                <ul class="list-unstyled">
                    <li class="mb-3"><i class="fas fa-map-marker-alt text-primary me-3"></i> <?php echo $settings['address']; ?></li>
                    <li class="mb-3"><i class="fas fa-envelope text-primary me-3"></i> <?php echo $settings['contact_email']; ?></li>
                    <li class="mb-3"><i class="fas fa-phone text-primary me-3"></i> <?php echo $settings['contact_phone']; ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <form action="ajax/contact_process.php" method="POST" id="contactForm">
                    <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Your Name" required></div>
                    <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Your Email" required></div>
                    <div class="mb-3"><textarea name="message" class="form-control" rows="5" placeholder="Your Message" required></textarea></div>
                    <button type="submit" class="btn btn-primary px-5">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
