<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-0">
    <div class="position-sticky pt-3">
        <h5 class="text-center py-3">Dmagancy Admin</h5>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>" href="categories.php"><i class="fas fa-list me-2"></i> Categories</a></li>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>" href="services.php"><i class="fas fa-concierge-bell me-2"></i> Services</a></li>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'portfolio.php' ? 'active' : ''; ?>" href="portfolio.php"><i class="fas fa-briefcase me-2"></i> Portfolio</a></li>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>" href="blog.php"><i class="fas fa-blog me-2"></i> Blog</a></li>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>" href="orders.php"><i class="fas fa-shopping-cart me-2"></i> Orders</a></li>
            <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'meetings.php' || basename($_SERVER['PHP_SELF']) == 'available_slots.php') ? 'active' : ''; ?>" href="meetings.php"><i class="fas fa-video me-2"></i> Meetings</a></li>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'tickets.php' ? 'active' : ''; ?>" href="tickets.php"><i class="fas fa-headset me-2"></i> Support Tickets</a></li>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reviews.php' ? 'active' : ''; ?>" href="reviews.php"><i class="fas fa-star me-2"></i> Reviews</a></li>
            <hr>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>" href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a></li>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'payment_settings.php' ? 'active' : ''; ?>" href="payment_settings.php"><i class="fas fa-credit-card me-2"></i> Payments</a></li>
            <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'seo.php' ? 'active' : ''; ?>" href="seo.php"><i class="fas fa-search me-2"></i> SEO</a></li>
            <li class="nav-item mt-5"><a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
        </ul>
    </div>
</nav>
