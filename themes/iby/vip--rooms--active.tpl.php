    <div class="<?php echo $parent_slug;?> ibyforum box-shadow <?php echo $parent_slug;?>-forum type-<?php echo $forum->field_type['und'][0]['value'];?>" id="<?php echo $parent_slug.$forum->tid;?>">
      <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">
        <div class="forum-headline"><a href="<?php echo $forum->link;?>"><?php echo StringTools::shorten($forum->name, 40, '...');?></a></div>
        <div class="<?php echo $parent_slug;?>-description ibyforum-description">
          <a href="<?php echo $forum->link;?>">
			<?php echo StringTools::shorten(trim(StringTools::striptags($forum->description)), 140, "...");?>
          </a>
        </div>

        <div class="<?php echo $parent_slug;?>-divider ibyforum-divider"></div>
        <div class="<?php echo $parent_slug;?>-assigned">Assigned members: <?php echo count($forum->field_vip_members['und']); ?></div>


<?php
$has_access = false;

/*if(in_array('administrator', array_values($user->roles))) {
  $has_access = true;

} else*/
if(isset($forum->field_vip_members) && count($forum->field_vip_members)) {
  $langs = array_keys($forum->field_vip_members);
  $members = $forum->field_vip_members[$langs[0]];
  if($members && count($members)) {
    foreach($members as $member) {
      if($member['value'] == $user->uid) $has_access = true;
    }
  }
}

if($has_access) {
?>
        <div class="rounded-button comment-button"><a href="/forum/<?php echo $forum->tid;?>">Enter room</a></div>
<?php
} else {
?>
        <div class="rounded-button red comment-button"></div>
<?php
}
?>

        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
    </div>
