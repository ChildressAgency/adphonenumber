jQuery(document).ready(function($){
  $('a[href^="tel:"]').each(function(){
    $(this).text(adPhone).attr('href', 'tel:' + adPhone);
  });
});