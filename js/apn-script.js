jQuery(document).ready(function($){
  if(adPhone != ''){
    $('a[href^="tel:"]').each(function(){
      $(this).text(adPhone).attr('href', 'tel:' + adPhone);
    });
  }
});