<?php
$nid = arg(2);
$width = (arg(3)?'470':'490');
?>

<div class="comment-box" style="width:<?php echo ($width);?>px;background-color:#f9f7f1; overflow-y:auto;float:right;" class="box-shadow">
	<div class="tips_trics_ajax_popup_left">
		<h2>Add a comment</h2>
		<div class="content-divider"></div>
    <?php echo drupal_render($page['content']['system_main']['comment_form']); ?>
	</div>
</div>
