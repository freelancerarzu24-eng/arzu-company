$(document).ready(function() {
    // Flash message auto hide
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 5000);

    // Smooth scroll
    $('a.nav-link').on('click', function(event) {
        if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 70
            }, 800);
        }
    });
});
