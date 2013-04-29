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

      <div class="action-links-wrapper">
        <ul class="action-links">

<?php
if(sqtools_is_admin($user) && (arg(0) != "forum") && $action_links) {
  echo render($action_links);

} elseif(arg(0) == "forum") {
   if(arg(1) != 40) {

     $forum_container = taxonomy_term_load(arg(1));

     $forum_parent = $forum_type = taxonomy_term_load(_iby_forums_get_parent_tid(arg(1)));
     $name = "";
     switch(strtolower($forum_parent->name)) {
     case "challenges": $name = " Challenge"; break;
     case "chat forums": $name = " Forum"; break;
     case "vip rooms": $name = " VIP Room"; break;
     }

     if(_iby_forums_check_access(false, false, "forum", "create", false, arg(1))) {
?>
          <li><a href="/forum/add/forum/forums?parent=<?php echo $forum_parent->tid;?>">Add new<?php echo $name;?></a></li>
<?php
if(!in_array(arg(1), array(2, 5, 92))):
?>
          <li><a href="/taxonomy/term/<?php echo arg(1);?>/edit?destination=/forum/<?php echo arg(1);?>">Edit<?php echo $name;?></a></li>
<?php
endif;
     }

   }
 }
	?>

        </ul>
      </div>
      <div style="clear:both;"></div>

    </div>

    <?php if ($main_menu): ?>
    <div id="navigation">
      <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu' , 'class' => array('links' , 'clearfix' )))); ?>
      <?php if(arg(0) == "user") print theme('links__system_user_menu' , array('links' => $secondary_menu, 'attributes' => array('id' => 'user-menu' , 'class' => array('links' , 'clearfix' )))); ?>
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
} elseif ((arg(0) == "node") && isset($forum_parent)) {
?>
  <a href="/forum/<?php echo $forum_parent->tid;?>" class="go_back_button">Back to <?php echo str_replace("Chat forums", "Forums", $forum_parent->name);?></a>
<?php
} elseif ((arg(0) == "forum") && isset($forum_parent) && ($forum_parent->tid != arg(1))) {
?>
  <a href="/forum/<?php echo $forum_parent->tid;?>" class="go_back_button">Back to <?php echo str_replace("Chat forums", "Forums", $forum_parent->name);?></a>
<?php
}
?>

  </div> <!-- #header -->

<?php if ($page['sidebar_first']): ?>
  <div id="sidebar">
    <?php print render($page['sidebar_first']); ?>
  </div>
<?php endif; ?>

<?php if ($page['sidebar_first']) { ?>
  <div id="search">
<?php } else { ?>
  <div id="page">
<?php } ?>

<?php
  // If is admin
if(sqtools_is_admin($user)) {
  if((arg(0) == "admin") && ($messages)) echo '<div id="console" class="clearfix">'.$messages.'</div>';
}

if((arg(0) == "comment") && (arg(1) == "reply") && ($messages)) echo '<div id="console" class="clearfix">'.$messages.'</div>';
?>

<?php
if((arg(0) == "search") && ( user_access("administer users", $user) || user_access("administer permissions", $user) )) {
  echo '<ul class="tabs">';
  echo render($tabs);
  echo '</ul>';

} elseif(sqtools_is_admin($user) && $tabs) {
  echo '<ul class="tabs">';
  echo render($tabs);
  echo '</ul>';

} elseif((arg(0) == "forum") && (arg(1) != 40) && _iby_forums_check_access(false, false, "forum", "edit", false, arg(1)) && $tabs) {
  echo '<ul class="tabs">';
  echo render($tabs);
  echo '</ul>';

}

echo drupal_render($page['content']);
?>

    <div style="clear:both;"></div>
  </div> <!-- #page -->
    <div style="clear:both;"></div>
</div> <!-- #page-wrapper -->
