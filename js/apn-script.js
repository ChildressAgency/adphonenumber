jQuery(function($){
  if(adPhone !== ''){
    $('a[href^="tel:"]').each(function(){
      if(!$(this).hasClass('emergency-phone')){
        $(this).text(adPhone).attr('href', 'tel:' + adPhone);
      }
    });
  }
});