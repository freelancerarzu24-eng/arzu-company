<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <?php if(!empty($settings['website_logo'])): ?>
                <img src="uploads/<?php echo $settings['website_logo']; ?>" alt="<?php echo $site_name; ?>" height="40">
            <?php else: ?>
                <?php echo $site_name; ?>
            <?php endif; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="portfolio.php">Portfolio</a></li>
                <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <?php if(is_logged_in()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle btn btn-primary text-white ms-lg-2 px-3" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            Dashboard
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="customer/index.php">My Account</a></li>
                            <li><a class="dropdown-item" href="customer/orders.php">My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-outline-primary ms-lg-2 px-3" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-2 px-3" href="register.php">Join</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
