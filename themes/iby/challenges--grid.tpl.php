    <div class="<?php echo $parent_slug;?> ibyforum box-shadow <?php echo $parent_slug;?>-grid ibyforum-grid type-<?php echo (($forum->field_type['und'][0]['value'])?$forum->field_type['und'][0]['value']:'all');?>" <?php echo (($first_in_row)?' style="margin-left:0px;"':'');?> id="<?php echo $parent_slug;?><?php echo $forum->tid;?>">
      <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">

        <div class="<?php echo $parent_slug;?>-number ibyforum-number">Challenge #<?php echo $forum->field_challenge_number['und'][0]['value'];?></div>

<?php
$enddate = $forum->field_enddate['und'][0]['value'];
$endtime = strptime($enddate, "%Y-%m-%dT%H:%M:%S");
$endstamp = mktime($endtime['tm_hour'], $endtime['tm_min'], $endtime['tm_sec'], ($endtime['tm_mon']+1), $endtime['tm_mday'], ($endtime['tm_year']+1900));
//echo date("d.m.Y H:i:s", $endstamp);
?>
        <div class="<?php echo $parent_slug;?>-ended ibyforum-ended">Ended <?php echo date("M d, Y h:i a", $endstamp);?></div>

        <div style="clear:both;"></div>

<?php
$image_path = "/".drupal_get_path('module', 'iby_forums')."/images/forum_image_grid.jpg";
if(isset($forum->field_images['und']) && count($forum->field_images['und'])) {
  $image_path = $forum->field_images['und'][0]['uri'];
}
?>
        <div class="<?php echo $parent_slug;?>-image ibyforum-image">
          <?php echo theme('image', array('path' => file_create_url($image_path), 'height' => '150px'));?>
          <div style="clear:both;"></div>
        </div>

        <div style="clear:both;"></div>

        <div class="<?php echo $parent_slug;?>-name"><a href="<?php echo $forum->link;?>"><?php echo trim(strip_tags($forum->name));?></a></div>

        <div style="clear:both;"></div>

        <div class="challenges-short-texts ibyforum-short-texts">
<?php
  if(isset($forum->field_subtitle['und'][0]['value']) && $forum->field_subtitle['und'][0]['value']) {
?>
          <div class="<?php echo $parent_slug;?>-subtitle"><?php echo nl2br(StringTools::shorten(strip_tags($forum->field_subtitle['und'][0]['value']), 150, '...'));?></div>

          <div style="clear:both;"></div>
<?php
  }
?>

          <div class="<?php echo $parent_slug;?>-short"><?php echo nl2br(StringTools::shorten(strip_tags($forum->description), 150, '...'));?></div>

          <div style="clear:both;"></div>

          <div class="<?php echo $parent_slug;?>-tags">
<?php
  $tags = iby_forums_get_forums_filter_tags($forum->tid);

  if(isset($tags->tags) && is_array($tags->tags) && count($tags->tags)) {

    $str = "Tags: ";

    foreach($tags->tags as $tag) {
      //$url_tids = array_flip($tags_tids);
      //if(isset($url_tids[$tag->tid])) unset($url_tids[$tag->tid]);
      //else $url_tids[$tag->tid] = true;
      //$link = "/forum/".$forum_id;
      ////if(count($url_tids)) $link .= "/tags/".$tag->tid;//implode("/", array_keys($url_tids));
      //if(count($url_tids)) $link .= "/tags/".implode("/", array_keys($url_tids));
      //$str .=  '<a class="linktext" href="'.$link.'#tags">'.preg_replace("/\s+/s", "&nbsp;", trim($tag->name)).'</a>, ';

      $str .=  preg_replace("/\s+/s", "&nbsp;", trim($tag->name)).", ";
    }

    echo StringTools::shorten(substr($str, 0, -2), 90, "...");
  }
?>

            <div style="clear:both;"></div>
          </div>

        </div>

          <div style="clear:both;"></div>

          <div class="<?php echo $parent_slug;?>-info ibyforum-info">

		  <div class="<?php echo $parent_slug;?>-stats">
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

        <div style="clear:both;">&nbsp;</div>

	</div>
</div>
		  </div>
          <div class="rounded-button <?php echo $parent_slug;?>-view ibyforum-view"><a href="<?php echo $forum->link;?>">View challenge</a></div>
          <div style="clear:both;"></div>
        </div>

        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
    </div>
