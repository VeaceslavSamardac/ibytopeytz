<div class="vip-rooms-archived">
  <h3><a href="<?php echo $forum->link;?>"><?php echo $forum->name;?></a></h3>
  <div class="<?php echo $parent_slug;?>-description ibyforum-description">
<?php echo StringTools::shorten(trim(StringTools::striptags($forum->description)), 140, "...");?>
  </div>
  <div class="<?php echo $parent_slug;?>-enter ibyforum-description">
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
    <a href="/forum/<?php echo $forum->tid;?>">Enter this room &rsaquo;</a>
<?php
} else {
?>
    <a href="/forum/<?php echo $forum->tid;?>">LOCKED</a>
<?php
}
?>
  </div>
</div>

<div style="clear:both;"></div>
