<?php
if(arg(0) == "forum") {
  $query = db_select('node', 'n');
  $query->condition('n.status', 1);
  $query->fields('n', array('nid'));
  $query->fields('ct', array('field_challenge_tid_value'));
  $query->join('field_data_field_challenge_tid', 'ct', "ct.entity_type = 'node' AND bundle='important_notice' AND ct.entity_id = n.nid");
  $query->condition('ct.field_challenge_tid_value', $forum->tid);
  $query->range(0, 1);
  $res = $query->execute()->fetchObject();

  if(!$res) {

    if(sqtools_is_admin($user) || _iby_forums_check_access(false, false, "forum", "edit", false, $forum->tid))
      echo '<ul class="action-links"><li><a href="/node/add/important-notice?destination=forum/'.arg(1).'">add important notice</a></li></ul>';

  } else {
    $notice = node_load($res->nid);

    if($notice) {

      if(sqtools_is_admin($user) || _iby_forums_check_access(false, false, "forum", "edit", false, $forum->tid))
        echo '<ul class="action-links"><li><a href="/node/'.$notice->nid.'/edit?destination=forum/'.arg(1).'">edit important notice</a></li></ul>';

      $closed = false;
      if(isset($_COOKIE['closed_notice_'.$forum->tid]) && $_COOKIE['closed_notice_'.$forum->tid]) $closed = true;
?>

  <div style="clear:both; height:10px;">&nbsp;</div>
  <div id="important-notice" class="box-shadow<?php echo (($closed)?" notice-closed":"");?>">

    <div class="notice-texts">
      <h1 class="notice-title">Important notice!</h1>

      <h2 class="notice-header"><?php echo $notice->title; ?></h2>

      <h3 class="notice-sub-header">
<?php
$langs = array_keys($notice->field_subheading);
echo $notice->field_subheading[$langs[0]][0]['value'];
?>
      </h3>

      <div class="notice-body">
<?php
$langs = array_keys($notice->body);
echo StringTools::striptags($notice->body[$langs[0]][0]['value']);
?>
      </div>
    </div>

    <a href="javascript:void(0);" id="toggle_tid_<?php echo $forum->tid;?>" class="notice-show-hide<?php echo (($closed)?" button-show":"");?>"> </a>

  </div>
<?php
    }
  }
}
?>

  <div class="<?php echo $parent_slug;?> ibyforum <?php echo $parent_slug;?>-full ibyforum-full box-shadow type-<?php echo (($forum->field_type['und'][0]['value'])?$forum->field_type['und'][0]['value']:'all');?>" id="<?php echo $parent_slug;?><?php echo $forum->tid;?>">
    <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">
      <div class="<?php echo $parent_slug;?>-number ibyforum-number">
        <span>Challenge #<?php echo $forum->field_challenge_number['und'][0]['value'];?></span>
        <span style="margin: 0px 5px; border-right: 1px solid #666;width:1px;overflow:hidden;">&nbsp;</span><span class="challenge_header_type the_type_is_<?php echo (($forum->field_type['und'][0]['value'])?$forum->field_type['und'][0]['value']:'all');?>">
		<?php echo (($forum->field_type['und'][0]['value'])?$forum->field_type['und'][0]['value']:'all');?></span>
<?php
$enddate = $forum->field_enddate['und'][0]['value'];
$endtime = strptime($enddate, "%Y-%m-%dT%H:%M:%S");
$endstamp = mktime($endtime['tm_hour'], $endtime['tm_min'], $endtime['tm_sec'], ($endtime['tm_mon']+1), $endtime['tm_mday'], ($endtime['tm_year']+1900));

?>
		<span style="float:right;">
<?php	if($endstamp < time()) { ?>
		 <div class="<?php echo $parent_slug;?>-ended ibyforum-ended">Ended <?php echo date("M d, Y h:i a", $endstamp);?></div>
<?php } else { ?>
		<div class="ends-in">Ends in <?php echo iby_time_left($endstamp);?></div>
<?php } ?>
		
		</span>
	  
      </div>
		<div class="challenges_header_wrapper">
        <div style="float:right;">
<?php
$args = array('flag_type' => "follow", 'uid' => $user->uid, 'flag_group' => "challenge", 'entity_type' => "taxonomy_term", 'entity_id' => $forum->tid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
          <a href="/sq_flags_api/toggle/follow/challenge/taxonomy_term/<?php echo $forum->tid;?>?sq_flags_api_cb=sq_flags_cb" id="follow_challenge_<?php echo $forum->tid;?>" class="use-ajax sq-follow-link <?php echo $flag_class;?>"> </a>

        </div>
			<a href="/forum/<?php echo $forum->tid; ?>" class="challenges_title_link" style="text-decoration:none; color:#666;">
			<h3 class="challenges_title_link">
			<?php if(arg(0) == 'dashboard') {
				echo StringTools::shorten($forum->name, 80, '');
			} else { 
				echo $forum->name;
			} ?>
			</h3></a>
			<div class="challenges_header_stats">
			<?php
				$query = db_select('taxonomy_index', 'ti');
				$query->join('node', 'n', 'n.status = 1 AND n.nid = ti.nid');
				$query->addExpression('COUNT(ti.nid)', 'solutions');
				$query->condition('ti.tid', $forum->tid);
				$totalsolutions = $query->execute()->fetchField(0);
			?>
			
				<span class="stat"><span class="stat_numbers"><?php echo $totalsolutions; ?></span> Solutions</span>
				<span class="stat view-taxonomy_term-<?php echo $forum->tid;?>"><span class="count-number stat_numbers"><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'view' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?></span> views</span>
				<span class="stat last follow-taxonomy_term-<?php echo $forum->tid;?>"><span class="count-number stat_numbers"><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'follow' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?></span> followers</span>
				
		   <!-- <span class="stat last"><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'like' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?> likes</span> -->
				
			</div>
	  
	  </div>
	  
    <div class="<?php echo $parent_slug;?>-divider ibyforum-divider"></div>

		<div class="<?php echo $parent_slug;?>-short-container ibyforum-short-container" id="ibyforum-short-container<?php echo $forum->tid;?>">
      <div class="<?php echo $parent_slug;?>-short ibyforum-short" id="ibyforum-short<?php echo $forum->tid;?>">
<?php if(isset($forum->field_subtitle['und'][0]['value']) && $forum->field_subtitle['und'][0]['value']):?>
			<span style="display:block;font-size:15px;font-weight: bold;color:#666; margin-bottom:20px;">
			<?php if(arg(0) == 'dashboard') {
				echo StringTools::shorten($forum->field_subtitle['und'][0]['value'], 100, '');
			} else { 
				echo $forum->field_subtitle['und'][0]['value'];
			} ?>
			</span>
<?php endif; ?>

<?php if(arg(0) == 'dashboard') {
      echo nl2br(StringTools::shorten(strip_tags($forum->description), 600, '...'));
} else {
      echo '<div class="ibyforum-fulltext" id="ibyforum-fulltext'.$forum->tid.'">';
      echo $forum->description;
//echo nl2br(StringTools::shorten(strip_tags($forum->description), 2000, ''));
      echo '<div style="clear:both;"></div>';
      echo '</div>';
}
?>
        <div style="clear:both;"></div>
      </div>

      <div style="clear:both;"></div>
      <a href="javascript:void(0);" class="show-more-link" id="show-more-link<?php echo $forum->tid;?>"> </a>
      <!--<a href="javascript:void(0);" id="toggle_tid_<?php echo $forum->tid;?>" class="notice-show-hide<?php echo (($closed)?" button-show":"");?>"> </a>-->

      <div style="clear:both;"></div>
    </div>


    <div class="<?php echo $parent_slug;?>-image ibyforum-image">
 
	    <div class="fp-scrollable">
        <div class="items">

<?php
$show_slider_nav = false;
if(isset($forum->field_images['und'])) {

  if(count($forum->field_images['und']) > 1) {
    $show_slider_nav = true;
    
    $i = 1;
    foreach($forum->field_images['und'] as $image) {
      //['uri'];
      echo '<div class="slide item'.$i.'" style="width:330px;height:230px;">'.theme('image', array('path' => file_create_url($image['uri']), 'width' => '330px')).'<div style="clear:both;"></div></div>';
      $i++;
    }

  } else {
    $image_path = $forum->field_images['und'][0]['uri'];
    echo '<div style="width:330px;height:230px;overflow:hidden;">'.theme('image', array('path' => file_create_url($image_path), 'width' => '330px')).'</div>';

  }

} else {
  $image_path = "/".drupal_get_path('module', 'iby_forums')."/images/forum_image_active.jpg";
  echo '<div style="width:330px;height:230px;overflow:hidden;">'.theme('image', array('path' => file_create_url($image_path), 'width' => '330px')).'</div>';

}
?>

        </div>

	    </div>

      <div style="clear:both;"></div>
<?php if($show_slider_nav): ?>

      <div class="nav-slides">
        <!-- "previous page" action -->
        <a class="prev browse left"></a>
        <!-- wrapper for navigator elements -->
        <span class="navi1 navi clearfix"></span>
        <!-- "next page" action -->
        <a class="next browse right"></a>
      </div>


      <div style="clear:both;"></div>

      <script type="text/javascript">
            // =================
            // scrollable() and navigator() should be from jquery tools
            // also once() was giving error at some point
            // NEXT 2 LINES ARE NOT WORKING ON LIVE TOO, COMMENTED FOR NOW,
            //var scrollable = new Array();
            //scrollable[0] = jQuery(".fp-scrollable").scrollable({circular: true}).navigator().autoscroll();
            // =================
            //jQuery(".fp-scrollable").scrollable({ circular: true }).click(function() {
            //  jQuery(this).data("scrollable").next();
            //});
      </script>

<?php endif; ?>

    </div>

    <div style="clear:both;"></div>

	  <?php
$enddate = $forum->field_enddate['und'][0]['value'];
$endtime = strptime($enddate, "%Y-%m-%dT%H:%M:%S");
$endstamp = mktime($endtime['tm_hour'], $endtime['tm_min'], $endtime['tm_sec'], ($endtime['tm_mon']+1), $endtime['tm_mday'], ($endtime['tm_year']+1900));
$curTime = time();
	if($endstamp > $curTime) {
?>
      <div class="rounded-button <?php echo $parent_slug;?>-enter ibyforum-enter">
	  <?php
	  
		if(arg(0) == 'dashboard') { ?>
			<a href="/forum/<?php echo $forum->tid; ?>/">Enter this challenge</a>
<?php
		} else { ?>
			<a href="/node/add/forum/<?php echo $forum->tid;?>?show_ajax=1" class="sq-ajax-topic">Contribute to this challenge</a>
<?php
		}
	}
?>
       <div style="clear:both;"></div>

      </div>

      <div style="clear:both;"></div>
    </div>

    <div style="clear:both;"></div>
  </div>
