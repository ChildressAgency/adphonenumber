document.addEventListener('DOMContentLoaded', function() {
  if (adPhone.phone_number !== '') {
    document.querySelectorAll('a[href^="tel:"]').forEach(function(anchor) {
      if (!anchor.classList.contains('emergency-phone')) {
        anchor.textContent = adPhone.phone_number;
        anchor.setAttribute('href', 'tel:' + adPhone.phone_number);
      }
    });
  }
});