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

    <?php if ($main_menu): ?> 
      <div id="navigation">
        <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu' , 'class' => array('links' , 'clearfix' )))); ?> 
        <?php if(arg(0) == "user") print theme('links__system_user_menu' , array('links' => $secondary_menu, 'attributes' => array('id' => 'user-menu' , 'class' => array('links' , 'clearfix' )))); ?> 
      </div>
    <?php endif; ?> 

  </div> <!-- #header --> 

  <div id="page">
		<div class="page-forum-add-forum" style="width:760px;overflow:hidden; margin: 0 auto;">
		
		<?php
			if($_GET['parent'] == 2) { 
				echo 'You are about to add a challenge';
			} elseif($_GET['parent'] == 92) {
				echo 'You are about to add a forum';
			} elseif($_GET['parent'] == 5) {
				echo 'You are about to add a VIP room';
			}
		?>
		
		<?php
		   echo drupal_render($page['content']);//['content']['system_main']['comment_form']);//['#markup'];   
		?>
		</div>
	</div> <!-- #page -->
</div> <!-- #page-wrapper -->