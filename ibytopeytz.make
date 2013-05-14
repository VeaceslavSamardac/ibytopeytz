; makefile for SamfundsLitteratur

; define core version and drush make compatibility
core = 7.x
api = 2


;hostnames[] = ibytopeytz





;===[ INVESTIGATION DATA bgn ]===============================================================


; === FTP struct ===

;libraries
;└─ tinymce

;modules
;├─ apachesolr
;├─ apachesolr_autocomplete
;├─ chain_menu_access
;├─ claim_user
;├─ ctools
;├─ custom_blocks
;├─ date
;├─ facetapi
;├─ google_analytics
;├─ iby_actions
;├─ iby_forums
;├─ iby_profiles_pretifier
;├─ login_destination
;├─ maxlength
;├─ panels
;├─ privatemsg
;├─ README.txt
;├─ remember_me
;├─ sq_ajax_popup
;├─ sq_flags_api
;├─ sq_tools
;├─ sq_wysiwyg_images
;├─ webform
;└─ wysiwyg

;themes
;└─ iby


; === core ===

;aggregator            0  7.8
;block                 1  7.8
;blog                  1  7.8
;book                  0  7.8
;color                 1  7.8
;comment               1  7.8
;contact               0  7.8
;contextual            1  7.8
;dashboard             1  7.8
;dblog                 1  7.8
;field                 1  7.8
;field_sql_storage     1  7.8
;field_ui              1  7.8
;file                  1  7.8
;filter                1  7.8
;forum                 1  7.8
;help                  1  7.8
;image                 1  7.8
;list                  1  7.8
;locale                0  7.8
;menu                  1  7.8
;node                  1  7.8
;number                1  7.8
;openid                0  7.8
;options               1  7.8
;overlay               0  7.8
;path                  1  7.8
;php                   0  7.8
;poll                  0  7.8
;profile               0  7.8
;rdf                   1  7.8
;search                1  7.8
;search_embedded_form  0  7.8
;search_extra_type     0  7.8
;shortcut              1  7.8
;simpletest            0  7.8
;standard              1  7.8
;statistics            0  7.8
;syslog                0  7.8
;system                1  7.8
;taxonomy              1  7.8
;text                  1  7.8
;toolbar               0  7.8
;tracker               0  7.8
;translation           0  7.8
;trigger               1  7.8
;update                1  7.8
;user                  1  7.8


; === not contrib, not in FTP ===

;ajax_example            0  7.x-1.x-dev
;bulk_export             0  7.x-1.0
;current_search          0  7.x-1.0-rc4
;job_post                0  N
;page_manager            0  7.x-1.0
;stylizer                0  7.x-1.0
;views_content           0  7.x-1.0


; === custom in FTP ===

;claim_user              1  N
;custom_blocks           1  N
;iby_actions             1  N
;iby_forums              1  N
;iby_profiles_pretifier  1  N
;sq_ajax_popup           1  N
;sq_data_conversion      0  N not in FTP
;sq_flags_api            1  N
;sq_forum_extender       0  N not in FTP
;sq_profiles_pretifier   0  N not in FTP
;sq_string_tools         0  N not in FTP
;sq_tools                1  N
;sq_wysiwyg_images       1  N


; === contrib in FTP ===

;acl                      0  7.x-1.0-rc1
;apachesolr               0  7.x-1.0-rc2
;apachesolr_autocomplete  0  7.x-1.2
;chain_menu_access        1  7.x-1.0
;ckeditor                 0  7.x-1.3
;ctools                   1  7.x-1.0
;date                     1  7.x-2.0-alpha4
;devel                    0  7.x-1.0
;facetapi                 0  7.x-1.0-rc4
;field_group              0  7.x-1.0
;forum_access             0  7.x-1.0-beta1
;googleanalytics          1  7.x-1.2        abandoned on drupal.org, must use google_analytics
;login_destination        1  7.x-1.0
;maxlength                1  7.x-3.x-dev
;panels                   0  7.x-3.2
;privatemsg               1  7.x-1.2
;remember_me              1  7.x-1.x-dev
;save_draft               0  7.x-1.4
;votingapi                1  7.x-2.4
;webform                  1  7.x-3.13
;wysiwyg                  1  7.x-2.1


; === contrib, current versions (downloaded) ===
                            new                 old
;acl                      7.x-1.0         0   7.x-1.0-rc1   
;apachesolr               7.x-1.2         0   7.x-1.0-rc2   
;apachesolr_autocomplete  7.x-1.3         0   7.x-1.2       
;chain_menu_access        7.x-2.0         1   7.x-1.0       
;ckeditor                 7.x-1.13        0   7.x-1.3       
;ctools                   7.x-1.3         1   7.x-1.0       
;date                     7.x-2.6         1   7.x-2.0-alpha4
;devel                    7.x-1.3         0   7.x-1.0       
;facetapi                 7.x-1.3         0   7.x-1.0-rc4   
;field_group              7.x-1.1         0   7.x-1.0       
;forum_access             7.x-1.2         0   7.x-1.0-beta1 
;google_analytics         7.x-1.3         1   7.x-1.2   <--- version of abandoned googleanalytics
;login_destination        7.x-1.1         1   7.x-1.0       
;maxlength                7.x-3.0-beta1   1   7.x-3.x-dev   
;panels                   7.x-3.3         0   7.x-3.2       
;privatemsg               7.x-1.3         1   7.x-1.2       
;remember_me              7.x-1.0         1   7.x-1.x-dev   
;save_draft               7.x-1.4         0   7.x-1.4       
;votingapi                7.x-2.11        1   7.x-2.4       
;webform                  7.x-3.18        1   7.x-3.13      
;wysiwyg                  7.x-2.2         1   7.x-2.1       


;===[ INVESTIGATION DATA end ]===============================================================




; modules

projects[acl][subdir] = "contrib"
projects[acl][download][type] = git
projects[acl][download][url] = http://git.drupal.org/project/acl.git
projects[acl][download][tag] = 7.x-1.0

projects[apachesolr][subdir] = "contrib"
projects[apachesolr][download][type] = git
projects[apachesolr][download][url] = http://git.drupal.org/project/apachesolr.git
projects[apachesolr][download][tag] = 7.x-1.2

projects[apachesolr_autocomplete][subdir] = "contrib"
projects[apachesolr_autocomplete][download][type] = git
projects[apachesolr_autocomplete][download][url] = http://git.drupal.org/project/apachesolr_autocomplete.git
projects[apachesolr_autocomplete][download][tag] = 7.x-1.3

projects[chain_menu_access][subdir] = "contrib"
projects[chain_menu_access][download][type] = git
projects[chain_menu_access][download][url] = http://git.drupal.org/project/chain_menu_access.git
projects[chain_menu_access][download][tag] = 7.x-2.0

projects[ckeditor][subdir] = "contrib"
projects[ckeditor][download][type] = git
projects[ckeditor][download][url] = http://git.drupal.org/project/ckeditor.git
projects[ckeditor][download][tag] = 7.x-1.13

projects[config_perms][subdir] = "contrib"
projects[config_perms][download][type] = git
projects[config_perms][download][url] = http://git.drupal.org/project/config_perms.git
projects[config_perms][download][tag] = 7.x-2.0

projects[ctools][subdir] = "contrib"
projects[ctools][download][type] = git
projects[ctools][download][url] = http://git.drupal.org/project/ctools.git
projects[ctools][download][tag] = 7.x-1.3

projects[date][subdir] = "contrib"
projects[date][download][type] = git
projects[date][download][url] = http://git.drupal.org/project/date.git
projects[date][download][tag] = 7.x-2.6

projects[devel][subdir] = "contrib"
projects[devel][download][type] = git
projects[devel][download][url] = http://git.drupal.org/project/devel.git
projects[devel][download][tag] = 7.x-1.3

projects[facetapi][subdir] = "contrib"
projects[facetapi][download][type] = git
projects[facetapi][download][url] = http://git.drupal.org/project/facetapi.git
projects[facetapi][download][tag] = 7.x-1.3

projects[field_group][subdir] = "contrib"
projects[field_group][download][type] = git
projects[field_group][download][url] = http://git.drupal.org/project/field_group.git
projects[field_group][download][tag] = 7.x-1.1

projects[forum_access][subdir] = "contrib"
projects[forum_access][download][type] = git
projects[forum_access][download][url] = http://git.drupal.org/project/forum_access.git
projects[forum_access][download][tag] = 7.x-1.2

projects[google_analytics][subdir] = "contrib"
projects[google_analytics][download][type] = git
projects[google_analytics][download][url] = http://git.drupal.org/project/google_analytics.git
projects[google_analytics][download][tag] = 7.x-1.3

;projects[libraries][subdir] = "contrib"
;projects[libraries][download][type] = git
;projects[libraries][download][url] = http://git.drupal.org/project/libraries.git
;projects[libraries][download][tag] = 7.x-2.1   

projects[login_destination][subdir] = "contrib"
projects[login_destination][download][type] = git
projects[login_destination][download][url] = http://git.drupal.org/project/login_destination.git
projects[login_destination][download][tag] = 7.x-1.1

projects[maxlength][subdir] = "contrib"
projects[maxlength][download][type] = git
projects[maxlength][download][url] = http://git.drupal.org/project/maxlength.git
projects[maxlength][download][tag] = 7.x-3.0-beta1

projects[panels][subdir] = "contrib"
projects[panels][download][type] = git
projects[panels][download][url] = http://git.drupal.org/project/panels.git
projects[panels][download][tag] = 7.x-3.3

projects[privatemsg][subdir] = "contrib"
projects[privatemsg][download][type] = git
projects[privatemsg][download][url] = http://git.drupal.org/project/privatemsg.git
projects[privatemsg][download][tag] = 7.x-1.3

projects[remember_me][subdir] = "contrib"
projects[remember_me][download][type] = git
projects[remember_me][download][url] = http://git.drupal.org/project/remember_me.git
projects[remember_me][download][tag] = 7.x-1.0

projects[save_draft][subdir] = "contrib"
projects[save_draft][download][type] = git
projects[save_draft][download][url] = http://git.drupal.org/project/save_draft.git
projects[save_draft][download][tag] = 7.x-1.4

projects[votingapi][subdir] = "contrib"
projects[votingapi][download][type] = git
projects[votingapi][download][url] = http://git.drupal.org/project/votingapi.git
projects[votingapi][download][tag] = 7.x-2.11

projects[webform][subdir] = "contrib"
projects[webform][download][type] = git
projects[webform][download][url] = http://git.drupal.org/project/webform.git
projects[webform][download][tag] = 7.x-3.18

projects[wysiwyg][subdir] = "contrib"
projects[wysiwyg][download][type] = git
projects[wysiwyg][download][url] = http://git.drupal.org/project/wysiwyg.git
projects[wysiwyg][download][tag] = 7.x-2.2


; libraries

libraries[tinymce][download][type] = "file"
;libraries[tinymce][download][url] = "http://download.moxiecode.com/tinymce/3.5.8/tinymce_3.5.8.zip"
;libraries[tinymce][download][url] = "http://www.tinymce.com/4.0b2/tinymce_4.0b2.zip"
libraries[tinymce][download][url] = "https://github.com/downloads/tinymce/tinymce/tinymce_3.4.3.1.zip"
libraries[tinymce][overwrite] = TRUE

