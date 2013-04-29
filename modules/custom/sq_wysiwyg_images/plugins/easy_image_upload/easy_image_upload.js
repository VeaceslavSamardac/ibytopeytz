jQuery(document).ready(function() {
        jQuery.fn.easy_image_upload_cb = function(image_str) {
            jQuery('#easy_image_pop').overlay().close();
            tinyMCE.activeEditor.execCommand('mceInsertContent', 'false', image_str);
        };
    });

Drupal.wysiwyg.plugins['easy_image_upload'] = {
 
  /**
   * Invoke is called when the toolbar button is clicked.
   */
  invoke: function(data, settings, instanceId) {
     if(jQuery('#easy_image_pop').length) {
         jQuery('#easy_image_pop').remove();
     }
     jQuery('body').append('<div class="easy_image_pop" style="display:none;background-color:#fff;position:absolute;overflow:hidden;border:1px solid #666;z-index:1000;width:400px;height:100px;" id="easy_image_pop"><a href="javascript:void(0);" class="close"> </a><div class="content"></div><div class="contentbottom"></div></div>');
        
     jQuery('#easy_image_pop').overlay({ top: 200, mask: { color: '#fff', loadSpeed: 200, opacity: 0.5 }, closeOnClick: false, load: true, onBeforeLoad: function() {
                 var wrap = this.getOverlay().find('.content');
                 jQuery('#easy_image_pop .content').html(' ');
                 wrap.load('/upload/wysiwyg_file?show_ajax=1', function() { Drupal.attachBehaviors(document, Drupal.settings); } );
             }
         });

    }

};
