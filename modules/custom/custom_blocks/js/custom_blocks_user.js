(function ($) {
  Drupal.behaviors.custom_blocks_user = {    
    attach: function (context) {      
      $('div.block-custom-blocks', context).each(function(){
        var block = $(this);
        $('div.custom_blocks_user_login a.custom_blocks_user_login_link', block).click(function(){
          $('div.custom_blocks_user_login', block).fadeOut(400, 'linear', function(){$('div.custom_blocks_user_pass', block).fadeIn(100);});
          return false;
        });        
        $('div.custom_blocks_user_pass a.custom_blocks_user_pass_link', block).click(function(){          
          $('div.custom_blocks_user_pass', block).fadeOut(400, 'linear', function(){$('div.custom_blocks_user_login', block).fadeIn(100);});
          return false;
        });
        $('div.custom_blocks_user_title a').click(function(){          
          $('div.custom_blocks_user_login', block).toggle("medium");
        });          
      });      
    }        
  };
})(jQuery);