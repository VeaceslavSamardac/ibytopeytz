jQuery(document).ready(function() {
       
        jQuery.fn.close_overlays = function(data) {
            jQuery('.sq-ajax-link').overlay().close();
        };

        jQuery.fn.form_success = function(data) {
            //window.location.href = data.goto_url;
            // We could go a long way here, to actually hit the goto-after-ajax...
            // "All" that is needed is: create a timestamp to use for dummy, to make the url unique
            // Check to see if we should add the dummy with ? or &
            // Remove any existing hasmark
            // Add the hasmark
            var timestamp = Math.round(new Date().getTime() / 1000);
            var current_url = "" + window.location.href;

            current_url = current_url.replace(/#.*/i, "");
            current_url = current_url.replace(/(\?|&)sq_ajax_dummy=\d+/i, "");

            if(current_url.indexOf('?') != -1) current_url = current_url + '&';
            else current_url = current_url + '?';
            current_url = current_url + 'sq_ajax_dummy=' + timestamp;
            current_url = current_url + "#goto-after-ajax";
            window.location.href = current_url;
            //location.reload();
        };

        jQuery.fn.console_log = function(data) {
            console.log(data);
        };
    });


Drupal.behaviors.sq_ajax_popup = {
    attach: function(context) {
        jQuery('.sq-ajax-link').attr('rel', "#sq_ajax_pop");
        jQuery('.sq-ajax-textpage-link').attr('rel', "#sq_ajax_pop");
        
        if(jQuery('#sq_ajax_pop').length) {
            jQuery('#sq_ajax_pop').remove();
        }
        jQuery('body').append('<div class="sq_lightboxpop sq_ajax_pop" style="display:none;position:absolute;overflow:hidden;border:1px solid #666;z-index:1000;width:200px;height:80px;" id="sq_ajax_pop"><a href="javascript:void(0);" class="close"> </a><div class="content"></div><div class="contentbottom"></div></div>');

        
        jQuery('.sq-ajax-link').overlay({ mask: { color: '#fff', opacity: 0.4 }, closeOnClick: false, effect: 'default', fixed: false, onBeforeLoad: function() {

                    var wrap = this.getOverlay().find('.content');
                    jQuery('#sq_ajax_pop .content').html(' ');
                    
                    var close_height = this.getOverlay().find('.close').height();
                    var popup_elm = jQuery('#sq_ajax_pop');
                    var popup_width = popup_elm.width();

                    wrap.load(this.getTrigger().attr("href"), function() {

                            var content_width = jQuery(this).children(':first').width();
                            var content_height = jQuery(this).children(':first').height();

                            var animate_options = popup_elm.position();
                            
                            var width_diff = (content_width - popup_width);
                            var move_value = (width_diff / 2);

                            animate_options.left = (animate_options.left - move_value);
                            animate_options.width = content_width;
//                          animate_options.height = (content_height + close_height);
                            animate_options.height = (content_height);

                            jQuery('#sq_ajax_pop').animate( animate_options, 50, function() { /* animation done */ } );
                            
                            Drupal.attachBehaviors(document, Drupal.settings);
                            
                            jQuery('form').bind('form-pre-serialize', function(e) {
                                    tinyMCE.triggerSave();
                                });
                        });
                }
            });

        jQuery('.sq-ajax-textpage-link').overlay({ mask: { color: '#fff', opacity: 0.4 }, closeOnClick: false, effect: 'default', fixed: false, onBeforeLoad: function() {

                    var wrap = this.getOverlay().find('.content');
                    jQuery('#sq_ajax_pop .content').html(' ');
                    
                    var close_height = this.getOverlay().find('.close').height();
                    var popup_elm = jQuery('#sq_ajax_pop');
                    var popup_width = popup_elm.width();

                    wrap.load(this.getTrigger().attr("href"), function() {

                            var content_width = jQuery(this).children(':first').width();
                            var content_height = jQuery(this).children(':first').height();
                            jQuery(this).children(':first').css('height', ''); // Unsetting height of the child element, so IE will let i scale
                            jQuery(this).height(content_height); // Setting the contents height
                            jQuery(this).css({ padding: '20px 0px 0px 20px', 'overflow-x': 'hidden', 'overflow-y': 'auto', 'background-color': '#fff' });

                            // Compensating for paddings
                            content_width += 40;
                            content_height += 20;

                            var animate_options = popup_elm.position();
                            
                            var width_diff = (content_width - popup_width);
                            var move_value = (width_diff / 2);

                            animate_options.left = (animate_options.left - move_value);
                            animate_options.width = content_width;
//                          animate_options.height = (content_height + close_height);
                            animate_options.height = (content_height);

                            jQuery('#sq_ajax_pop').animate( animate_options, 50, function() { /* animation done */ } );

                        });
                }
            });

    },
    detach: function (context, settings, trigger) {
    }
}


Drupal.behaviors.sq_topic_form = {
    attach: function(context) {
        jQuery('.sq-ajax-topic').click(function(e) {

                if(jQuery('#add-topic-content').height() > 0) {
                    jQuery('#add-topic-content').animate( { height: 0 }, function() {
                            jQuery('#add-topic-content').removeClass('box-shadow');
                        });
                    return false;
                
                } else {

                    jQuery('#add-topic-content').css('height', '0');
                    jQuery('#add-topic-content').addClass('box-shadow');

                    var new_height = jQuery('#node-add-content').height();
                    var new_width = (jQuery('#node-add-content').width()+35);

                    jQuery('#add-topic-content').css('width', new_width + 'px' );

                    jQuery('#add-topic-content').animate( { height: new_height });
                    
                    return false;
                }
            
            })
    },

    detach: function (context, settings, trigger) {
    }
}

Drupal.behaviors.sq_comment_form = {
    attach: function(context) {
        jQuery('.sq-ajax-comment').click(function(e) {
                var elm_id = '';
                if(jQuery(this).attr('rel')) elm_id = jQuery(this).attr('rel');

                var content_elm_id = 'add-comment-content'+elm_id;
                var marker_elm_id = 'add-comment-marker'+elm_id;

                if(jQuery('#'+content_elm_id).html().length > 1) {
                    jQuery('#'+content_elm_id).animate( { height: 0 }, function() {
                            jQuery('#'+content_elm_id).removeClass('box-shadow');
                            jQuery('#'+content_elm_id).html(' ');
                        });
                    return false;

                } else {

                    jQuery('.add-comment-content').removeClass('box-shadow');
                    jQuery('.add-comment-content').removeClass('box-shadow').html(' ');
                    jQuery('.add-comment-content').css('height', '0');

                    jQuery('#'+content_elm_id).css('height', '0');

                    var caller_elm = jQuery(e.target);
                    var caller_href = caller_elm.attr("href");
                    
                    jQuery('#'+content_elm_id).load(caller_href, function() {
                            jQuery('#'+content_elm_id).addClass('box-shadow');

                            var new_height = jQuery('div:first', this).height();
                            var new_width = (jQuery('div:first', this).width()+40);

                            jQuery(this).css('width', new_width + 'px' );

                            Drupal.attachBehaviors(document, Drupal.settings);

                            //jQuery('form').bind('form-pre-serialize', function(e) {
                            //        tinyMCE.triggerSave();
                            //    });
                            jQuery(this).animate( { height: new_height }, function() { window.location.href = window.location.href + '#add-comment-marker'+elm_id; } );
                        });
                    
                    return false;
                }
            
            })
    },

    detach: function (context, settings, trigger) {
    }
}
