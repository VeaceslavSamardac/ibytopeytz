<?php $field_group = $element['#field_group']; ?>

<div style="clear:both;"></div>

<?php if ($title) : ?>
  <div class="profile-grouping">
    <h3><?php print $title; ?></h3>
<?php if(sqtools_is_admin($user) || ($user->uid == $element['#uid'])): ?>
    <div class="edit-profile-header">
      <a href="<?php echo "/user/".$element['#uid']."/edit/".$field_group['name'];?>">Edit <?php print $field_group['link_name']; ?> &rsaquo;</a>
      <div><?php print $field_group['visibility']; ?>&nbsp; &nbsp;|&nbsp; &nbsp;</div>
    </div>
<?php endif; ?>
    <div style="clear:both;"></div>
  </div>
<?php endif; ?>

<dl<?php print $attributes; ?>>
  <?php print $profile_items; ?>
</dl>

<div style="clear:both;">&nbsp;</div>