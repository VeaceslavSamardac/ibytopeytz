<?php /*print drupal_render($form['field_profile']);*/ ?>
  <?php print drupal_render($form['field_new_profile']); ?>
<div class="left-col">
  <?php print drupal_render($form['account']['name']); ?>
  <?php print drupal_render($form['account']['mail']); ?>
</div>
<div class="right-col">
  <?php print drupal_render($form['account']['pass']); ?>

  <p class="" style="height:16px;padding-left:20px;font-size:11px;line-height:16px;font-weight:bold;color:#00B0CA;">We will send you a confirmation link to your e-mail.</p>
  <p class="" style="height:44px;padding-left:20px;font-size:11px;line-height:16px;color:#666;">Follow the link in the e-mail to set your password and start using your profile.</p>

  <?php print drupal_render($form['confirm']); ?>
  <?php print drupal_render($form['field_newsletters']); ?>
  <div class="btns-sbmt">
    <a href="/?cancel=1" class="cancel">Cancel</a> 
    <?php print drupal_render($form['actions']); ?>
    <?php print drupal_render_children($form); ?>
  </div>
</div>
