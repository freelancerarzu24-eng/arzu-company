$(document).ready(function() {
    let selectedPackage = '';
    let selectedPrice = 0;

    $('.buy-now-btn').on('click', function() {
        selectedPackage = $(this).data('package');
        selectedPrice = $(this).data('price');
        $('#buyNowModal').modal('show');
    });

    $('#continueOrder').on('click', function() {
        let country = $('#countrySelect').val();
        if(!country) {
            alert('Please select a country');
            return;
        }

        if(country === 'Bangladesh') {
            // Redirect to checkout page for Bangladesh
            window.location.href = `checkout.php?slug=${getSlugFromUrl()}&package=${selectedPackage}&price=${selectedPrice}`;
        } else {
            // International: WhatsApp Redirect
            let serviceName = $('h2.fw-bold').text();
            let msg = `Hello Arzu,\n\nI want to order:\n\nService: ${serviceName}\nCountry: ${country}\nBudget: $${selectedPrice}\n\nI have my requirements ready. Let's discuss!`;
            let whatsappUrl = `https://wa.me/880123456789?text=${encodeURIComponent(msg)}`;
            window.open(whatsappUrl, '_blank');
        }
    });

    function getSlugFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('slug');
    }
});
