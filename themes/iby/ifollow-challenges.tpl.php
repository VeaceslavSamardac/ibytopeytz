<?php
		$image_path = "/".drupal_get_path('module', 'iby_forums')."/images/forum_image_list.jpg";
		if(isset($forum->field_images['und']) && count($forum->field_images['und'])) {
		  $image_path = $forum->field_images['und'][0]['uri'];
		}
?>	

<div id="challenges-ended" class="ibyforum-ended">
	<div id="challenges-content" class="ibyforum-content">
	
<!-- START LIST -->	

    <div class="challenges ibyforum box-shadow challenges-list ibyforum-list type-<?php echo (($forum->field_type['und'][0]['value'])?$forum->field_type['und'][0]['value']:'all');?>" id="challenges<?php echo $forum->tid;?>">
      <div class="challenges-bg ibyforum-bg">

<?php
$image_path = "/".drupal_get_path('module', 'iby_forums')."/images/forum_image_list.jpg";
if(isset($forum->field_images['und']) && count($forum->field_images['und'])) {
  $image_path = $forum->field_images['und'][0]['uri'];
}
?>
        <div class="challenges-image ibyforum-image">
          <?php echo theme('image', array('path' => file_create_url($image_path), 'height' => '120px'));?>
          <div style="clear:both;"></div>
        </div>

        <div class="challenges-texts ibyforum-texts">
          <div class="challenges-title ibyforum-title">
            <span class="challenges-number ibyforum-number">#<?php echo $forum->field_challenge_number['und'][0]['value'];?></span>
            <?php echo $forum->name;?>
          </div>
          <div class="challenges-divider ibyforum-divider"></div>
          <div class="challenges-short ibyforum-short"><?php echo nl2br(StringTools::shorten($forum->description, 200, '...'));?></div>
          <div style="clear:both;"></div>
        </div>

<?php
$enddate = $forum->field_enddate['und'][0]['value'];
$endtime = strptime($enddate, "%Y-%m-%dT%H:%M:%S");
$endstamp = mktime($endtime['tm_hour'], $endtime['tm_min'], $endtime['tm_sec'], ($endtime['tm_mon']+1), $endtime['tm_mday'], ($endtime['tm_year']+1900));
?>
        <div class="challenges-info ibyforum-info">
		
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
				<span><?php echo $totalcomments; ?></span> comments
				
				<div style="clear:both;"></div>
			</div>
			<div class="views">
				<span><?php echo intval(sq_flags_api_get(array('flag_type' => 'view' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?></span> views
				<div style="clear:both;"></div>
			</div>
			<div style="clear:both;"></div>
		</div>

        <div style="clear:both;"></div>

		<div class="row2">
			<div class="followers">
				<span><?php echo intval(sq_flags_api_get(array('flag_type' => 'follow' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?></span> followers
				<div style="clear:both;"></div>
			</div>
			<div class="likes">
        <span><?php echo intval(sq_flags_api_get(array('flag_type' => 'like' , 'entity_type' => 'node', 'taxonomy_term' => $forum->tid))); ?></span> likes
				<div style="clear:both;"></div>
			</div>
			<div style="clear:both;"></div>
        </div>
	</div>
</div>		
		
		
		
		
		
          <div class="challenges-ended ibyforum-ended">Ended <?php echo date("M d, Y h:i a", $endstamp);?></div>
          <div style="clear:both;"></div>
          <div class="rounded-button challenges-view ibyforum-view"><a href="/forum/<?php echo $forum->tid;?>">View challenge</a></div>
          <div style="clear:both;"></div>
        </div>

        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
    </div>
	
<!-- END LIST -->	