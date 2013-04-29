<?php
if(arg(1) && is_numeric(arg(1)) && (arg(1) != $user->uid)) {
  $account_uid = arg(1);
  //$variables['account'] = user_load(arg(1));//array_shift(entity_load('user', array(arg(1))));
} else {
  $account_uid = $user->uid;
  //$variables['account'] = $user;
}
//$variables['account'] = array_shift(entity_load('user', array($account_uid)));
$variables['account'] = user_load($account_uid);

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

    </div>

  <?php if ($main_menu && (($account_uid == $user->uid) || sqtools_is_admin($user)) && !isset($_GET['pass-reset-token'])): ?> 
      <div id="navigation">
        <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu' , 'class' => array('links' , 'clearfix' )))); ?> 
      <!--  <?php print theme('links__system_user_menu' , array('links' => $secondary_menu, 'attributes' => array('id' => 'user-menu' , 'class' => array('links' , 'clearfix' )))); ?> --> 

    <?php if ($user && $user->uid): ?> 

      <?php if ($breadcrumb): ?> 
        <div id="breadcrumb_page_node"><?php print $breadcrumb; ?></div>
      <?php endif; ?> 

	      <ul id="user-menu" class="links clearfix"><li class="menu-2 first "><a href="/user" title="" <?php if(arg(0) == 'user' && (arg(2) == '')) {echo 'class="active-trail"';} else {echo ''; } ?>>Basic info</a></li>
		      <li class="menu-747"><a href="/user/flags/follow" title="" <?php if(arg(2) == 'follow') {echo 'class="active-trail"';} else {echo ''; } ?>>I follow</a></li>
		      <li class="menu-490"><a href="/messages" title="">Mailbox</a></li>
	      </ul>
    <?php endif; ?> 

	   </div>
    <?php endif; ?> 

  </div> <!-- #header --> 

  <div id="page">

<?php
if(!$user || !$user->uid) {

  if((arg(0) == "user") && !arg(1)) $title = "Login";
  elseif((arg(0) == "user") && (arg(1) == "login")) $title = "Login";
  elseif((arg(0) == "user") && (arg(1) == "register")) $title = "Sign up today and let's innovate";
  elseif((arg(0) == "user") && (arg(1) == "reset")) {
    $tmpAccount = user_load(arg(2));
    if(!$tmpAccount->login) $title = "Create password";
    else $title = "Reset password";
  }
  echo theme('front_page_slider', array('page' => $page, 'title' => $title, 'messages' => $messages));

} else {

  if($messages || isset($_GET['pass-reset-token'])):
  ?>
  <div id="messages"><div class="section clearfix">
<?php
if(isset($_GET['pass-reset-token'])) {
    echo '<div class="messages status"><h2 class="element-invisible">Status message</h2>Dear new member.<br /><p>You have now used your one-time login link.<br />It is no longer necessary to use this link to log in.</p>Please create your password and click save.</div>';
    if($messages) $messages = preg_replace('/<div class="messages status">.*?You have just used your one-time login link[^>]*<\/div>/is', '', $messages);
}
?>

    <?php if($messages) print $messages; ?>
  </div></div>
  <?php endif;?>

      <div class="profile-info"><?php
  echo drupal_render($page['content']);
?>    </div>

<?php
  // check if rightbars is needed
  $show_rightbars = false;
  if(arg(0).arg(1) == "userfiles") $show_rightbars = true;
  elseif((arg(0) == "user") && is_numeric(arg(1))) $show_rightbars = true;
  if($show_rightbars) {
?>
      <div class="profile-rightbar">
<?php

      //if(($user->uid == $variables['account']->uid) || in_array('administrator', array_values($user->roles)))
      echo theme('user_points_rightbar', $variables);

?>      <div style="clear:both;"></div><?php

    //if(($user->uid == $variables['account']->uid) || in_array('administrator', array_values($user->roles)))
      echo theme('user_stats_rightbar', $variables);

?>      <div style="clear:both;"></div><?php

    echo theme('user_com_rightbar', $variables);

?>
        <div style="clear:both;"></div>
      </div>

<?php
  } // check if rightbars is needed
?>      <div style="clear:both;"></div><?php

    // This will also catched by the page template
  if ($messages && false) { ?>
    <div id="messages"><div class="section clearfix">
       <?php print $messages; ?>
    </div></div>
<?php
  }
}
?>
  </div> <!-- #page -->

</div> <!-- #page-wrapper -->
