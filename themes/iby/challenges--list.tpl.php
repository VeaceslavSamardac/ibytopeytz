    <div class="<?php echo $parent_slug;?> ibyforum box-shadow <?php echo $parent_slug;?>-list ibyforum-list type-<?php echo (($forum->field_type['und'][0]['value'])?$forum->field_type['und'][0]['value']:'all');?>" id="<?php echo $parent_slug;?><?php echo $forum->tid;?>">
      <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">

<?php
$image_path = "/".drupal_get_path('module', 'iby_forums')."/images/forum_image_list.jpg";
if(isset($forum->field_images['und']) && count($forum->field_images['und'])) {
  $image_path = $forum->field_images['und'][0]['uri'];
}
?>
        <div class="<?php echo $parent_slug;?>-image ibyforum-image">
          <?php echo theme('image', array('path' => file_create_url($image_path), 'height' => '120px'));?>
          <div style="clear:both;"></div>
        </div>

        <div class="<?php echo $parent_slug;?>-texts ibyforum-texts">
          <div class="<?php echo $parent_slug;?>-title ibyforum-title">
            <span class="<?php echo $parent_slug;?>-number ibyforum-number">#<?php echo $forum->field_challenge_number['und'][0]['value'];?></span>
            <?php echo StringTools::shorten($forum->name, 40, "...");?>
          </div>
          <div class="<?php echo $parent_slug;?>-divider ibyforum-divider"></div>
          <div class="<?php echo $parent_slug;?>-short ibyforum-short"><?php echo nl2br(StringTools::shorten(strip_tags($forum->description), 200, '...'));?></div>
          <div style="clear:both;"></div>
        </div>

<?php
$enddate = $forum->field_enddate['und'][0]['value'];
$endtime = strptime($enddate, "%Y-%m-%dT%H:%M:%S");
$endstamp = mktime($endtime['tm_hour'], $endtime['tm_min'], $endtime['tm_sec'], ($endtime['tm_mon']+1), $endtime['tm_mday'], ($endtime['tm_year']+1900));
?>
        <div class="<?php echo $parent_slug;?>-info ibyforum-info">
		
<div class="display-data-grid-list">
	<div class="display-data-grid-stats">
        <div class="row1">
			<div class="comments">
<?php
	$query = db_select('taxonomy_index', 'ti');
	$query->join('node', 'n', 'n.status = 1 AND n.nid = ti.nid');
	$query->addExpression('COUNT(ti.nid)', 'solutions');
	$query->condition('ti.tid', $forum->tid);
	$totalcomments = $query->execute()->fetchField(0);
?>
				<span><?php echo $totalcomments; ?></span> contributions
				
				<div style="clear:both;"></div>
			</div>
			<div class="views">
				<span><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'view' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?></span> views
				<div style="clear:both;"></div>
			</div>
			<div style="clear:both;"></div>
		</div>

        <div style="clear:both;"></div>

		<div class="row2">
			<div class="followers">
				<span><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'follow' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?></span> followers
				<div style="clear:both;"></div>
			</div>
			<div class="likes">
				<div style="clear:both;"></div>
			</div>
			<div style="clear:both;"></div>
        </div>
	</div>
</div>		
		
		
		
		
		
          <div class="<?php echo $parent_slug;?>-ended ibyforum-ended">Ended <?php echo date("M d, Y h:i a", $endstamp);?></div>
          <div style="clear:both;"></div>
          <div class="rounded-button <?php echo $parent_slug;?>-view ibyforum-view"><a href="<?php echo $forum->link;?>">View challenge</a></div>
          <div style="clear:both;"></div>
        </div>

        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
    </div>
