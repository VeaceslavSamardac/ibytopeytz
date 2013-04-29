<?php
$page = $variables['page'];
?>
<?php if(!isset($_GET['inline']) || !$_GET['inline']) { ?>

<div id="node-add-content" class="vip-rooms-add-topic" style="width:490px;height:680px;background-color:#f9f7f1;overflow-x:hidden; overflow-y:auto;padding: 0 20px;">
	<div class="tips_trics_ajax_popup_left popout-content">
		<h2>Your input to <br />this contribution</h2>
    <?php echo drupal_render($page['content']); ?>
	</div>
</div>

<?php } else { ?> 

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
<?php
  if(isset($page['#show_messages']) && $page['#show_messages']) echo $messages;
?>
		<div class="node-add-content" style="width:auto;height:auto;margin: 0 auto;">
		
		  <h2>Create new topic</h2>
		
		<?php
		   echo drupal_render($page['content']);//['content']['system_main']['comment_form']);//['#markup'];   
		?>
      <div style="clear:both;"></div>
		</div>
    <div style="clear:both;"></div>
	</div> <!-- #page -->
  <div style="clear:both;"></div>
</div> <!-- #page-wrapper -->
    <div style="clear:both;"></div>

<?php
}
?>

