<?php
if(isset($_GET['show_ajax']) && $_GET['show_ajax']) {
  echo '<div style="width:550px;height:480px;">';
  //  echo "<pre>"; var_dump($node); echo "</pre>"; exit;
  if ($title && ($node->type == "page")): ?>
    <h1><?php print $title; ?></h1>
  <?php endif;?>
  <?php if ($messages && false): ?>
    <div id="messages"><div class="section clearfix">
    <?php print $messages; ?>
    </div></div> <!-- /.section, /#messages -->
  <?php endif; ?>
  <?php
  echo drupal_render($page['content']);
  echo '</div>';
  return;
}

if($_SERVER['REDIRECT_URL'] == "/user/welcome") {
  $breadcrumb = false;
}
?>
<div id="page-wrapper">
  <div id="header">
  <?php print render($page['header']); ?>
  <?php echo theme('topbar_user'); ?>

    <div class="header-logo-profile">
    
    	<div class="header-logo">
    		<?php if ($logo): ?>
    		  <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
    			<img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
    		  </a>
    		<?php endif; ?>
    	</div>
    	
<?php
if(sqtools_is_admin($user)) {
  if((arg(0) == "admin") && ($messages)) echo '<div id="console" class="clearfix">'.$messages.'</div>';
}
?>

    <div class="action-links-wrapper">
      <ul class="action-links">

<?php
if(sqtools_is_admin($user) && (arg(0) != "node") && $action_links) {
  echo render($action_links);

} elseif((arg(0) == "node") && ($_SERVER['REDIRECT_URL'] != "/user/welcome")) {

   $forum_container = taxonomy_term_load(_iby_forums_get_tid_by_nid(arg(1)));

   $name = ""; $back_name = "";

   $forum_parent = $forum_type = taxonomy_term_load(_iby_forums_get_parent_tid_by_nid(arg(1)));
   if($forum_parent) {
     switch(strtolower($forum_parent->name)) {
     case "challenges": $name = " Contribution"; $back_name = " challenge"; break;
     case "chat forums": $name = " Topic"; $back_name = " forum"; break;
     case "vip rooms": $name = " Post"; $back_name = " VIP room"; break;
     case "tips & tricks": $name = " Problem"; $back_name = " tip"; break;
     }
   }

   if(_iby_forums_check_access(false, false, "node", "edit", false, false, arg(1))) {
?>
        <li><a href="/node/<?php echo arg(1);?>/edit?destination=/node/<?php echo arg(1);?>">Edit<?php echo $name;?></a></li>
<?php
   }
 }
?>
	
      </ul>
    </div>
      <div style="clear:both;"></div>

	<?php $active_link_class = ''; ?>
    <?php if ($main_menu): ?> 
      <div id="navigation">
        <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu' , 'class' => array('links' , 'clearfix' )))); ?> 
        <?php if (isset($link['href']) && ($link['href'] == $_GET['q'])) { $active_link_class = ' active '; } ?>
        <?php if(arg(0) == "user") print theme('links__system_user_menu' , array('links' => $secondary_menu, 'attributes' => array('id' => 'user-menu' , 'class' => array('links' , 'clearfix', $active_link_class )))); ?> 
      </div>
    <?php endif; ?> 

    <?php if ($breadcrumb): ?> 
      <div id="breadcrumb_page_node"><?php print $breadcrumb; ?></div>
    <?php endif; ?> 

<?php
if(isset($page['content']['system_main']['nodes'][20643])) {
?>
  <a href="javascript:void(0);" onclick="history.go(-1);" class="go_back_button">Back</a>
<?php
} elseif ((arg(0) == "node") && isset($forum_parent) && $forum_container) {
?>
  <a href="/forum/<?php echo $forum_container->tid;?>" class="go_back_button">Back to <?php echo $back_name;?></a>
<?php
}
?>
    </div>
  </div> <!-- #header --> 

<?php if($_SERVER['REDIRECT_URL'] == "/user/welcome") { ?>
    <div class="panes">
      <div class="sections" style="display:block;left:47px;">
        <div id="page" class="slide">
<?php } else { ?>
        <div id="page">
<?php } ?>

<?php
if(sqtools_is_admin($user) && $tabs) {
  echo '<ul class="tabs">';
  echo render($tabs);
  echo '</ul>';
  echo '<div style="clear:both;"></div>';

} elseif((arg(0) == "node") && _iby_forums_check_access(false, false, "node", "edit", false, false, arg(1)) && $tabs) {
  echo '<ul class="tabs">';
  echo render($tabs);
  echo '</ul>';
  echo '<div style="clear:both;"></div>';
}


$node_url = drupal_lookup_path('alias', arg(0)."/".arg(1));

if(substr($node_url, 0, 8) == "error/40") {
  $path = substr($_SERVER['REQUEST_URI'],1);
  $type = arg(0, $path);
  $object_id = arg(1, $path);
  $tid = false;

  if(($type == "forum") && $object_id) $tid = _iby_forums_get_parent_tid($object_id);
  elseif(($type == "node") && $object_id) $tid = _iby_forums_get_parent_tid_by_nid($object_id);

  if($tid) {
    $tax = taxonomy_term_load($tid);
    $forum_slug = StringTools::slug($tax->name);
    $alias = 'error/'.$forum_slug;
    $path = drupal_lookup_path('source', $alias);

    if($path) {
      $node = node_load(arg(1, $path));
      $title = $node->title;
      $content = $node->body['und'][0]['value'];
    }
  }
}

?>

<?php
/* New from PP */
// Added page check

if ($title && ($node->type == "page")): ?>
      <h1><?php print $title; ?></h1>
    <?php endif;?>
    <?php if ($messages && false): ?>
      <div id="messages"><div class="section clearfix">
        <?php print $messages; ?>
      </div></div> <!-- /.section, /#messages -->
    <?php endif; ?>
<?php /* END: New from PP */
  echo drupal_render($page['content']);
?>

<?php if($_SERVER['REDIRECT_URL'] == "/user/welcome") { ?>
        </div>
      </div>
<?php } ?>

  </div> <!-- #page -->

</div> <!-- #page-wrapper -->
