jQuery(document).ready(function() {

        jQuery.fn.sq_flags_cb = function(data) {
            var link_elm = jQuery('#'+data.flag_type+'_'+data.flag_group+'_'+data.entity_id);
            var cur_class = (data.action == 'add') ? 'sq-flags-inactive' : 'sq-flags-active' ;
            var new_class = (data.action == 'add') ? 'sq-flags-active' : 'sq-flags-inactive' ;

            link_elm.removeClass(cur_class);
            link_elm.addClass(new_class);

            var counter_class = data.flag_type + '-' + data.entity_type + '-' + data.entity_id;
            jQuery('.' + counter_class + ' .count-number').html(data.new_count);
        };

        jQuery.fn.recent_all_read = function(data) {
            jQuery('.new-content').removeClass('new-content');
            jQuery('.recent-counter').text(0);
            jQuery('.check_taxonomy_terms').attr('disabled', 'disabled');
            jQuery('.check_taxonomy_terms').removeAttr('checked');
            jQuery('.check_nodes').attr('disabled', 'disabled');
            jQuery('.check_nodes').removeAttr('checked');
        };

        jQuery("#edit-field-bg-iam-und").change(function(event) {
                jQuery("#user-profile-form").submit();
            });

        jQuery("#important-notice .notice-show-hide").click(function(event) {
                var forum_tid = this.id.substr(11);
                if(jQuery(this).hasClass('button-show')) {
                    // Open
                    jQuery('#important-notice').animate( { height: 228 }, 250, function() {
                            jQuery('#important-notice .notice-show-hide').toggleClass('button-show');
                            jQuery('#important-notice').toggleClass('notice-closed');
                        } );
                    cookie_del('closed_notice_'+forum_tid);

                } else {
                    jQuery('#important-notice').animate( { height: 57 }, 250, function() {
                            jQuery('#important-notice .notice-show-hide').toggleClass('button-show');
                            jQuery('#important-notice').toggleClass('notice-closed');
                        } );
                    cookie_set('closed_notice_'+forum_tid, 1, 700);
                }
            });

        jQuery("#iby_filters_tag_option_more").click(function(event) {
                if(jQuery('#tag-cloud')) {
                    var tag_cloud_height = jQuery('#tag-cloud').height();

                    if(jQuery(this).hasClass('show-tags')) {
                        // Close
                        jQuery('#tag-cloud-container').animate( { height: 100 }, 500, function() {
                                jQuery('#iby_filters_tag_option_more').toggleClass('show-tags');
                                jQuery('#iby_filters_tag_option_more').html('show all tags');
                            } );

                    } else {
                        jQuery('#tag-cloud-container').animate( { height: (tag_cloud_height + 20)  }, 500, function() {
                                jQuery('#iby_filters_tag_option_more').toggleClass('show-tags');
                                jQuery('#iby_filters_tag_option_more').html('show less tags');
                            } );
                    }
                }
            });

        if((window.location.hash == "#tags") && jQuery('#tag-cloud')) {
            var tag_cloud_height = jQuery('#tag-cloud').height();
            jQuery('#tag-cloud-container').animate( { height: (tag_cloud_height + 20)  }, 500, function() {
                    jQuery('#iby_filters_tag_option_more').toggleClass('show-tags');
                    jQuery('#iby_filters_tag_option_more').html('show less tags');
                } );
        }

        if(jQuery(".show-more-link").length) {

            jQuery(".show-more-link").each(function(index) {

                    var cur_tid = jQuery(this).attr('id').substr(14, jQuery(this).attr('id').length);
                    var full_text_height = jQuery('#ibyforum-fulltext'+cur_tid).height();

                    if(full_text_height >= 230) {

                        jQuery(this).click(function(event) {

                                if(jQuery(this).hasClass('show-less')) {
                                    // Close
                                    jQuery('#ibyforum-short'+cur_tid).animate( { height: 280 }, 250, function() {
                                            jQuery('#show-more-link'+cur_tid).toggleClass('show-less');
                                        } );
                                } else {
                                    // Open up
                                    var full_height = jQuery('#ibyforum-fulltext'+cur_tid).height();
                                    jQuery('#ibyforum-short'+cur_tid).animate( { height: (full_height + 70) }, 250, function() {
                                            jQuery('#show-more-link'+cur_tid).toggleClass('show-less');
                                        } );
                                }

                            });
                    } else jQuery(".show-more-link").css('display', 'none');
                });
        }


        jQuery('#mark_as_read').click(function() {
                var taxonomy_terms = [];
                var nodes = [];

                if(jQuery('.check_taxonomy_terms:checked').length > 0) {
                    jQuery('.check_taxonomy_terms:checked').each(function(index) {
                            taxonomy_terms.push(jQuery(this).val());
                        });
                }
                if(jQuery('.check_nodes:checked').length > 0) {
                    jQuery('.check_nodes:checked').each(function(index) {
                            nodes.push(jQuery(this).val());
                        });
                }

                jQuery.post('/recent/mark/some', { nodes: nodes, taxonomy_terms: taxonomy_terms }, function(data) {
                        if(typeof data != 'undefined') {

                            var decrement = 0;

                            if(typeof data.taxonomy_terms != 'undefined') {
                                if(data.taxonomy_terms.length > 0) {
                                    decrement = decrement + data.taxonomy_terms.length;

                                    for(i in data.taxonomy_terms) {
                                        jQuery('#taxonomy_terms_'+data.taxonomy_terms[i]).removeAttr('checked');
                                        jQuery('#taxonomy_terms_'+data.taxonomy_terms[i]).attr('disabled', 'disabled');
                                        jQuery('#item_taxonomy_term_'+data.taxonomy_terms[i]).removeClass('new-content');
                                    }
                                }
                            }

                            if(typeof data.nodes != 'undefined') {
                                if(data.nodes.length > 0) {
                                    decrement = decrement + data.nodes.length;

                                    for(i in data.nodes) {
                                        jQuery('#nodes_'+data.nodes[i]).removeAttr("checked");
                                        jQuery('#nodes_'+data.nodes[i]).attr('disabled', 'disabled');
                                        jQuery('#item_node_'+data.nodes[i]).removeClass('new-content');
                                    }
                                }
                            }

                            var new_count = (parseInt(jQuery('.recent-counter:eq(0)').text()) - decrement);
                            jQuery('.recent-counter').text(new_count);

                        }
                    });

            });

    });



function toggle_input_value(event, elm) {
    if(elm.attr('name') != "pass") {
        if(event.type == "focus") {
            if(elm.attr('value') == elm.attr('defaultValue')) {
                elm.attr('value', "");
            }

        } else if(event.type == "blur") {
            if(elm.attr('value') == "") {
                elm.attr('value', elm.attr('defaultValue'));
            }
        }
    }
}

function cookie_set(name, value, days) {
    if(days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function cookie_read(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0 ; i < ca.length ; i++) {
        var c = ca[i];
        while(c.charAt(0)==' ') c = c.substring(1, c.length);
        if(c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function cookie_del(name) {
    cookie_set(name, "", -1);
}

function reinit_tinymce() {
    jQuery(tinyMCE.editors).each(function(){
            tinyMCE.remove(this);
        });

    Drupal.attachBehaviors(document, Drupal.settings);

    jQuery('form').bind('form-pre-serialize', function(e) {
            tinyMCE.triggerSave();
        });
};

jQuery(document).ready(function() {
        jQuery.fn.goto_node = function(node_nid, page) {
            var timestamp = Math.round(new Date().getTime() / 1000);
            window.location = '/node/' + node_nid;
        };

        jQuery.fn.goto_forum = function(tax_tid) {
            window.location = '/forum/' + tax_tid;
        };

        jQuery.fn.refresh_page = function(tax_tid) {
            location.reload();
        };

        jQuery.fn.reinit_tinymce = function() {
            jQuery(tinyMCE.editors).each(function(){
                    tinyMCE.remove(this);
                });

            Drupal.attachBehaviors(document, Drupal.settings);

            jQuery('form').bind('form-pre-serialize', function(e) {
                    tinyMCE.triggerSave();
                });

        };
    });


// setup ul.tabs to work as tabs for each div directly under div.panes and slideshow with cycle script
jQuery(function() {

        // setup ul.tabs to work as tabs for each div directly under div.panes
        if(jQuery(".front ul.tabs").length) {
            jQuery(".front ul.tabs").tabs(".front div.panes > div");
            var scrollable = new Array();

            scrollable[1] = jQuery(".fp-scrollable2").scrollable({circular: true}).navigator();//.autoscroll();

            var apiTabs = jQuery(".front ul.tabs").data("tabs");
            if(apiTabs.length) {
                apiTabs.onClick(function(e, index) {
                    });

                jQuery(".signin-message .close").click(function() {
                        jQuery(".signin-message").hide();

                        return false;
                    });
            }
        } else if(jQuery(".page-dashboard ul.tabs").length) {
            jQuery(".page-dashboard ul.tabs").tabs(".page-dashboard div.panes > div");

            var apiTabs = jQuery(".page-dashboard ul.tabs").data("tabs");
            if(apiTabs.length) {
                apiTabs.onClick(function(e, index) {

                        api.begin();

                    });
            }
        } else if(jQuery(".page-forum ul.tabs").length) {
            jQuery(".page-forum ul.tabs").tabs(".page-forum div.panes > div");

            var scrollable = new Array();
            scrollable[0] = jQuery(".fp-scrollable1").scrollable({circular: true}).navigator();//.autoscroll();
            scrollable[1] = jQuery(".fp-scrollable2").scrollable({circular: true}).navigator();//.autoscroll();

            var apiTabs = jQuery(".page-forum ul.tabs").data("tabs");
            if(apiTabs.length) {
                apiTabs.onClick(function(e, index) {

                        window.api = scrollable[index].data("scrollable");
                        api.begin();

                    });
            }
        }

    });

jQuery(document).ready(function(){
        var Input = jQuery('input[name=keys]');
        var default_value = Input.val();

        jQuery(Input).focus(function() {
                if(jQuery(this).val() == default_value) {
                    jQuery(this).val("");
                }
            }).blur(function(){
                    if(jQuery(this).val().length == 0) {
                        jQuery(this).val(default_value);
                    }
                });
    });


jQuery(document).ready(function() {
        jQuery('#user-register-form #edit-name').keydown(function() {
                if(jQuery(this).val().length >= 15) {
                    jQuery('#max15chars').clearQueue();
                    jQuery('#max15chars').animate({'color': '#e0684b'}, 0, function() {
                            jQuery('#max15chars').animate({color: jQuery('#max15chars').parent().css('color') }, 2000 );
                        });
                }
            });

        jQuery('#edit-field-new-profile-und-user-continence').click(function() {
                jQuery('#edit-field-new-profile-und-coloplast').removeAttr("checked");
                jQuery('#edit-field-new-profile-und-other').removeAttr("checked");
                jQuery('#edit-field-profile-und input').removeAttr("checked");
            });

        jQuery('#edit-field-new-profile-und-user-ostomy').click(function() {
                jQuery('#edit-field-new-profile-und-coloplast').removeAttr("checked");
                jQuery('#edit-field-new-profile-und-other').removeAttr("checked");
                jQuery('#edit-field-profile-und input').removeAttr("checked");
            });

        jQuery('#edit-field-new-profile-und-coloplast').click(function() {
                jQuery('#edit-field-new-profile-und-other').removeAttr("checked");
                jQuery('#edit-field-new-profile-und-user-continence').removeAttr("checked");
                jQuery('#edit-field-new-profile-und-user-ostomy').removeAttr("checked");
                jQuery('#edit-field-profile-und input').removeAttr("checked");
            });

        jQuery('#edit-field-new-profile-und-other').click(function() {
                jQuery('#edit-field-new-profile-und-coloplast').removeAttr("checked");
                jQuery('#edit-field-new-profile-und-user-continence').removeAttr("checked");
                jQuery('#edit-field-new-profile-und-user-ostomy').removeAttr("checked");
                jQuery('#edit-field-profile-und input').removeAttr("checked");
            });


    });

