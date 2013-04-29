    <div class="<?php echo $parent_slug;?> ibyforum box-shadow <?php echo $parent_slug;?>-active ibyforum-active type-<?php echo (($forum->field_type['und'][0]['value'])?$forum->field_type['und'][0]['value']:'all');?>" id="<?php echo $parent_slug.$forum->tid;?>">
<!--    <div class="<?php echo $parent_slug;?> ibyforum box-shadow <?php echo $parent_slug;?>-active ibyforum-active <?php echo $zebra;?>" id="<?php echo $parent_slug.$forum->tid;?>">-->
      <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">
        <div class="<?php echo $parent_slug;?>-number ibyforum-number">Challenge #<?php echo $forum->field_challenge_number['und'][0]['value'];?>
<?php
$enddate = $forum->field_enddate['und'][0]['value'];
$endtime = strptime($enddate, "%Y-%m-%dT%H:%M:%S");
$endstamp = mktime($endtime['tm_hour'], $endtime['tm_min'], $endtime['tm_sec'], ($endtime['tm_mon']+1), $endtime['tm_mday'], ($endtime['tm_year']+1900));
?>
          <div class="ends-in">Ends in <?php echo iby_time_left($endstamp);?></div>
        </div>
          <div class="<?php echo $parent_slug;?>-name ibyforum-name"><?php echo StringTools::shorten($forum->name, 70);?></div>
        <div class="<?php echo $parent_slug;?>-divider ibyforum-divider"></div>
<?php
$image_path = "/".drupal_get_path('module', 'iby_forums')."/images/forum_image_active.jpg";
if(isset($forum->field_images['und']) && count($forum->field_images['und'])) {
  $image_path = $forum->field_images['und'][0]['uri'];
}
?>
        <div class="<?php echo $parent_slug;?>-image ibyforum-image"><?php echo theme('image', array('path' => file_create_url($image_path), 'height' => '180px'));?></div>
        <div class="<?php echo $parent_slug;?>-short ibyforum-short"><?php echo nl2br(StringTools::shorten(strip_tags($forum->description), 350, '...'));?></div>
        <div class="rounded-button <?php echo $parent_slug;?>-enter ibyforum-enter"><a href="<?php echo $forum->link;?>">Enter this challenge</a></div>

        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
    </div>