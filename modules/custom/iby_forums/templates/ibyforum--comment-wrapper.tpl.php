<div id="comments" class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php if ($content['comments'] && $node->type != 'forum'): ?>
    <?php print render($title_prefix); ?>
    <h2 class="title"><?php print t('Comments'); ?></h2>
    <?php print render($title_suffix); ?>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

  <?php if ($content['comment_form'] && false):?>
  <div class="comment_form_wrapper">
    <h2 class="title comment-form"><?php print t('Add new comment'); ?></h2>
    <a name="new_comment"></a>
  	<div style="clear:both;"></div>
    <?php print render($content['comment_form']); ?>
  </div>
  <?php endif; ?>

</div>
