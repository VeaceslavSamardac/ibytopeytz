    <div class="<?php echo $parent_slug;?> ibyforum box-shadow <?php echo $parent_slug;?>-right ibyforum-right type-<?php echo (($forum->field_type['und'][0]['value'])?$forum->field_type['und'][0]['value']:'all');?>" id="<?php echo $parent_slug;?><?php echo $forum->tid;?>">
      <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">

<?php
$image_path = "/".drupal_get_path('module', 'iby_forums')."/images/forum_image_active.jpg";
if(isset($forum->field_images['und']) && count($forum->field_images['und'])) {
  $image_path = $forum->field_images['und'][0]['uri'];
}
?>
        <div class="<?php echo $parent_slug;?>-image ibyforum-image">
          <?php echo theme('image', array('path' => file_create_url($image_path), 'width' => '175px'));?>
        </div>

        <div class="<?php echo $parent_slug;?>-number ibyforum-number"><a href="/forum/<?php echo $forum->tid;?>">Challenge #<?php echo $forum->field_challenge_number['und'][0]['value'];?></a></div>

        <div class="<?php echo $parent_slug;?>-divider ibyforum-divider"></div>

        <div class="<?php echo $parent_slug;?>-name ibyforum-name"><a href="/forum/<?php echo $forum->tid;?>"><?php echo $forum->name;?></a></div>

        <div class="<?php echo $parent_slug;?>-short ibyforum-short"><?php echo nl2br(StringTools::shorten(strip_tags($forum->description), 200, '...'));?>...</div>


        <div class="<?php echo $parent_slug;?>-info ibyforum-info">


<?php
$enddate = $forum->field_enddate['und'][0]['value'];
$endtime = strptime($enddate, "%Y-%m-%dT%H:%M:%S");
$endstamp = mktime($endtime['tm_hour'], $endtime['tm_min'], $endtime['tm_sec'], ($endtime['tm_mon']+1), $endtime['tm_mday'], ($endtime['tm_year']+1900));

?>
<?php	if($endstamp < time()) { ?>
		 <div class="<?php echo $parent_slug;?>-ended ibyforum-ended">Ended <?php echo timeago($endstamp);?> ago</div>
<?php } else { ?>
		<div class="ends-in">Ends in <?php echo iby_time_left($endstamp);?></div>
<?php } ?>

          <div class="rounded-button <?php echo $parent_slug;?>-view ibyforum-view"><a href="/<?php echo (isset($forum->uri['path'])?$forum->uri['path']:"");?>">Read more</a></div>

         <!-- <div class="rounded-button <?php echo $parent_slug;?>-contribute ibyforum-contribute"><a href="/node/add/forum/<?php echo $forum->tid;?>?show_ajax=1" class="sq-ajax-link">Submit contribution</a></div>-->
          <div style="clear:both;"></div>
        </div>

        <div style="clear:both;">&nbsp;</div>
      </div>
      <div style="clear:both;"></div>
    </div>
