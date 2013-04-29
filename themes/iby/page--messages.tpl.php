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

    <?php if ($breadcrumb): ?> 
      <div id="breadcrumb_page_node"><?php print $breadcrumb; ?></div>
    <?php endif; ?> 

    <?php if((arg(0) == "messages") && arg(1)): ?> 
      <a href="/messages" class="go_back_button">Back to overview</a>
    <?php endif; ?> 


        <?php print theme('links__system_user_menu' , array('links' => $secondary_menu, 'attributes' => array('id' => 'user-menu' , 'class' => array('links' , 'clearfix' )))); ?> 

      </div>
    <?php endif; ?> 

  </div> <!-- #header --> 

  <div id="page">

<?php if ($action_links): ?>
  <ul class="action-links">
  <?php print render($action_links); ?>
  </ul>
<?php endif; ?>

<?php
echo drupal_render($page['content']);
?>

  </div> <!-- #page -->

</div> <!-- #page-wrapper -->
