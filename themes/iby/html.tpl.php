<?php
/**
 * @file
 * Default theme implementation to display the basic html structure of a single
 * Drupal page.
 *
 * Variables:
 * - $css: An array of CSS files for the current page.
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $rdf_namespaces: All the RDF namespace prefixes used in the HTML document.
 * - $grddl_profile: A GRDDL profile allowing agents to extract the RDF data.
 * - $head_title: A modified version of the page title, for use in the TITLE
 *   tag.
 * - $head_title_array: (array) An associative array containing the string parts
 *   that were used to generate the $head_title variable, already prepared to be
 *   output as TITLE tag. The key/value pairs may contain one or more of the
 *   following, depending on conditions:
 *   - title: The title of the current page, if any.
 *   - name: The name of the site.
 *   - slogan: The slogan of the site, if any, and if there is no title.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $page_top: Initial markup from any modules that have altered the
 *   page. This variable should always be output first, before all other dynamic
 *   content.
 * - $page: The rendered page content.
 * - $page_bottom: Final closing markup from any modules that have altered the
 *   page. This variable should always be output last, after all other dynamic
 *   content.
 * - $classes String of classes that can be used to style contextually through
 *   CSS.
 *
 * @see template_preprocess()
 * @see template_preprocess_html()
 * @see template_process()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces; ?>>

<head profile="<?php print $grddl_profile; ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=8" />
  <?php print $head; ?>
  <title><?php
  if((arg(0) == "user") && (arg(1) == "reset")) {
    $tmpAccount = user_load(arg(2));
    if(!$tmpAccount->login) echo "Create password";
    else echo "Reset password";

  } else print $head_title;
  ?></title>
  <?php print $styles; ?>
	<!--[if IE 7]>
			<link rel="stylesheet" type="text/css" href="/<?php echo sqtools_default_theme_path(); ?>/css/ie7.css">
	<![endif]-->
	<!--[if IE 8]>
			<link rel="stylesheet" type="text/css" href="/<?php echo sqtools_default_theme_path(); ?>/css/ie8.css">
	<![endif]-->
	<!--[if IE 9]>
			<link rel="stylesheet" type="text/css" href="/<?php echo sqtools_default_theme_path(); ?>/css/ie9.css">
	<![endif]-->
  <?php print $scripts; ?>

</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>

<div id="skip-link">
  <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
</div>

<?php
print $page_top;
print $page;
print $page_bottom;

if($user->uid && !isset($_GET['pass-reset-token'])):
?> 

<div id="footer">

	<div class="footerContainer">
    <div id="footerPage">
<?php

   $one_month_ago = mktime(0, 0, 0, (date("m")-1), date("d"), date("Y"));
   
   $user_roles = array_keys($user->roles);
   
   $tids_query = db_select('taxonomy_term_hierarchy', 'th')->fields('th', array('tid'))->condition('th.parent', 92);
   
   $query_access = db_select('field_data_field_allowed_roles', 'far');
   $query_access->fields('far', array('entity_id'));
   $query_access->condition('far.field_allowed_roles_value', $user_roles, 'IN');
   $query_access->condition('far.entity_type', 'taxonomy_term');
   $query_access->condition('far.entity_id', $tids_query, 'IN');
   $query_access->distinct();

   $query_closed_forums = db_select('field_data_field_allowed_roles', 'far');
   $query_closed_forums->fields('far', array('entity_id'));
   $query_closed_forums->condition('far.entity_type', 'taxonomy_term');
   $query_closed_forums->condition('far.entity_id', $tids_query, 'IN');
   $query_closed_forums->distinct();
   
   $query = db_select("sq_flags", 'sq');
   $query->join('node', 'n', "n.status = 1 AND n.nid = sq.entity_id AND sq.entity_type = 'node'");
   $query->join('forum', 'f', "f.nid = sq.entity_id");
   $query->condition('f.tid', $tids_query, 'IN');
   $query->fields('sq', array('entity_id'));
   $query->condition('sq.flag_type', 'view');
   $query->condition('sq.entity_type', 'node');
   $query->condition('sq.timestamp', $one_month_ago, '>');
   if(!sqtools_is_admin($user)) {
     $query->condition(db_or()->condition('f.tid', $query_access, 'IN')->condition('f.tid', $query_closed_forums, 'NOT IN'));
   }
   $query->groupBy('sq.entity_id');
   $query->orderBy('COUNT(sq.entity_id)', 'DESC');
   $query->range(0,2);
?>
      <div class="footerLeft"><h3>Most viewed this month</h3>
        <div class="footerContentWrapper">
<?php 
   $res = $query->execute();
   if($res) {
     $i = 0;
     foreach($res as $row) {
       $i++;
?>
          <div class="footerContent<?php echo (($i > 1)?' footerContentLast':'');?>">
<?php     
       $node = node_load($row->entity_id);
       if(!$node) continue;

       $account = user_load($node->uid);
       
       $args = array('flag_type' => 'view', 'entity_type' => 'node', 'entity_id' => $node->nid);
       $node->node_views = sq_flags_api_get_count($args);
?>
            <div class="account_picture">
<?php
if($account->picture) {
  echo theme('image', array('path' => file_create_url($account->picture->uri), 'width' => '25px'));
}
?>
            </div>

            <div class="post_info">
              <h4><a href="/node/<?php echo $node->nid;?>"><?php echo StringTools::shorten($node->title, 35);?></a></h4>
<?php
$node_body_langs = array_keys($node->body);
$node_body = $node->body[$node_body_langs[0]][0]['value'];

$taxID = $node->taxonomy_forums['und'][0]['tid'];
$postedin = db_query("SELECT name FROM taxonomy_term_data AS t WHERE tid = {$taxID}")->fetchField(0);
?>
              <div class="body-text"><?php echo StringTools::shorten(strip_tags($node_body), 90);?> <a href="/node/<?php echo $node->nid;?>" style="font-weight:bold;">Read more</a></div>
              <div class="byuser">By <a href="/user/<?php echo $account->uid; ?>"><?php echo $account->name;?></a> - <?php echo timeago($node->created); ?> ago</div>
              <div class="postedin">Posted in: <a href="/forum/<?php echo $taxID; ?>"><?php echo $postedin; ?></a></div>
              <div class="meta"><?php echo $node->node_views;?> views | <?php echo $node->comment_count;?> replies</div>
              <div style="clear:both;"></div>
            </div>
	        </div>
<?php
    }
  }
?>
        </div> <!-- class=footerContentWrapper -->
      </div> <!-- class=footerLeft -->
<?php
  $query = db_select('comment', 'c');
  $query->fields('c', array('nid'));
  $query->join('node', 'n', 'n.status = 1 AND n.nid = c.nid');
  $query->join('forum', 'f', "f.nid = c.nid");
  $query->condition('f.tid', $tids_query, 'IN');
  $query->condition('c.status', '1');
  $query->condition('c.created', $one_month_ago, '>');
  if(!sqtools_is_admin($user)) {
    $query->condition(db_or()->condition('f.tid', $query_access, 'IN')->condition('f.tid', $query_closed_forums, 'NOT IN'));
  }
  $query->groupBy('c.nid');
  $query->orderBy('COUNT(c.nid)', 'DESC');
  $query->range(0,2);
  $res = $query->execute();
  if($res) {
?>
      <div class="footerCenter"><h3>Most commented this month</h3>
        <div class="footerContentWrapper">
<?php
      $i = 0;
    foreach($res as $row) {
      $i++;
?>
          <div class="footerContent<?php echo (($i > 1)?' footerContentLast':'');?>">
<?php      
      $node = node_load($row->nid);
      if(!$node) continue;
      
      $account = user_load($node->uid);
      
      $args = array('flag_type' => 'view', 'entity_type' => 'node', 'entity_id' => $node->nid);
      $node->node_views = sq_flags_api_get_count($args);
?>
            <div class="account_picture">
<?php
         if($account->picture) {
           echo theme('image', array('path' => file_create_url($account->picture->uri), 'width' => '25px'));
         }
?>
            </div>
<?php
$node_body_langs = array_keys($node->body);
$node_body = $node->body[$node_body_langs[0]][0]['value'];

$taxID = $node->taxonomy_forums['und'][0]['tid'];
$postedin = db_query("SELECT name FROM taxonomy_term_data AS t WHERE tid = {$taxID}")->fetchField(0);
?>
            <div class="post_info">
              <h4><a href="/node/<?php echo $node->nid;?>"><?php echo StringTools::shorten($node->title, 35);?></a></h4>
              <div class="body-text"><?php echo StringTools::shorten(strip_tags($node_body), 90);?> <a href="/node/<?php echo $node->nid;?>" style="font-weight:bold;">Read more</a></div>
              <div class="byuser">By <a href="/user/<?php echo $account->uid; ?>"><?php echo $account->name;?></a> - <?php echo timeago($node->created); ?> ago</div>
              <div class="postedin">Posted in: <a href="/forum/<?php echo $taxID; ?>"><?php echo $postedin; ?></a></div>
              <div class="meta"><?php echo $node->node_views;?> views | <?php echo $node->comment_count;?> replies</div>
              <div style="clear:both;"></div>
            </div>
	        </div>

<?php
    }
?>
        </div> <!-- class=footerCenterWrapper -->
      </div> <!-- class=footerCenter -->
<?php
  }

  $query = db_select("sq_flags", 'sq');
  $query->join('node', 'n', "n.status = 1 AND n.nid = sq.entity_id AND sq.entity_type = 'node'");
  $query->join('forum', 'f', "f.nid = sq.entity_id");
  $query->fields('sq', array('entity_id'));
  $query->condition('f.tid', $tids_query, 'IN');
  $query->condition('sq.flag_type', 'follow');
  $query->condition('sq.entity_type', 'node');
  $query->condition('sq.timestamp', $one_month_ago, '>');
  if(!sqtools_is_admin($user)) {
    $query->condition(db_or()->condition('f.tid', $query_access, 'IN')->condition('f.tid', $query_closed_forums, 'NOT IN'));
  }
  $query->groupBy('sq.entity_id');
  $query->orderBy('COUNT(sq.entity_id)', 'DESC');
  $query->range(0,2);
  $res = $query->execute();
  if($res) {
?>
      <div class="footerRight"><h3>Most followed this month</h3>
<?php
      $i = 0;
    foreach($res as $row) {
      $i++;
?>
        <div class="footerContent<?php echo (($i > 1)?' footerContentLast':'');?>">
<?php
      
      $node = node_load($row->entity_id);
      if(!$node) continue;
      
      $account = user_load($node->uid);
      
      $args = array('flag_type' => 'view', 'entity_type' => 'node', 'entity_id' => $node->nid);
      $node->node_views = sq_flags_api_get_count($args);
?>
          <div class="account_picture">
<?php
      if($account->picture) {
        echo theme('image', array('path' => file_create_url($account->picture->uri), 'width' => '25px'));
      }
?>
          </div>
          <div class="post_info">
            <h4><a href="/node/<?php echo $node->nid;?>"><?php echo StringTools::shorten($node->title, 35);?></a></h4>
<?php
$node_body_langs = array_keys($node->body);
$node_body = $node->body[$node_body_langs[0]][0]['value'];

$taxID = $node->taxonomy_forums['und'][0]['tid'];
$postedin = db_query("SELECT name FROM taxonomy_term_data AS t WHERE tid = {$taxID}")->fetchField(0);
?>
            <div class="body-text"><?php echo StringTools::shorten(strip_tags($node_body), 90);?> <a href="/node/<?php echo $node->nid;?>" style="font-weight:bold;">Read more</a></div>
            <div class="byuser">By <a href="/user/<?php echo $account->uid; ?>"><?php echo $account->name;?></a> - <?php echo timeago($node->created); ?> ago</div>
            <div class="postedin">Posted in: <a href="/forum/<?php echo $taxID; ?>"><?php echo $postedin; ?></a></div>
            <div class="meta"><?php echo $node->node_views;?> views | <?php echo $node->comment_count;?> replies</div>
            <div style="clear:both;"></div>
          </div>
	      </div>
<?php
    }
?>
      </div>
<?php
  }
?>

	  </div>
    <div style="clear:both;"></div>
  </div>
</div>

<?php
endif;
?>


<div style="clear:both;"></div>
<?php
if($user->uid && !$is_front) echo theme('bottom_footer_bar');
else {
  echo theme('bottom_footer_bar', array('narrow' => true));
?>
  <div id="page-bottom-footer"> </div>
<?php
}
?>

</body>
</html>
