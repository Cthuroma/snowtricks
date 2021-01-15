jQuery(document).ready(function () {
  $('.pages-ul').each(function(){
    if($(this).data('page') !== 0){
      $(this).css('display', 'none');
    }
  });

  $('.page-item').each(function(){
    $(this).click(function(){
      page = $(this).data('page');
      $('.pages-ul').each(function(){
        if($(this).data('page') === page){
          $(this).css('display', 'block');
        }else{
          $(this).css('display', 'none');
        }
      });
      $('.page-item').each(function() {
        $(this).removeClass('active');
      });
      $(this).addClass('active');
    });
  });

});
