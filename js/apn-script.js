jQuery(document).ready(function($){
  if(adPhone.phone_number !== ''){
    $('a[href^="tel:"]').each(function(){
      if(!$(this).hasClass('emergency-phone')){
        $(this).text(adPhone.phone_number).attr('href', 'tel:' + adPhone.phone_number);
      }
    });
  }
});