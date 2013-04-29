<?php
if(!isset($_GET['inline']) || !$_GET['inline']) {
   echo drupal_render($page['content']);//['content']['system_main']['comment_form']);//['#markup'];

} else {

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
		
		  <h2>Create new post</h2>
		
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

