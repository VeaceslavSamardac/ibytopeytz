<!-- user-ifollow-challenges.tpl.php -->
<!--
<?php
	//var_dump($variables);
?>
-->
<?php
	drupal_add_css(sqtools_default_theme_path()."/css/ibyforum.css", array('options' => "file"));
	drupal_add_css(sqtools_default_theme_path()."/css/challenges.css", array('options' => "file"));
?>

<?php
  foreach($flags as $flag) {
		//echo "<li>Show ".$flag->flag_type." ".$flag->flag_group." ".$flag->entity_type." with id: ".$flag->entity_id."</li>";
		if($flag->flag_group == 'profile') {
			$loadUsers = entity_load('user', array($flag->entity_id));
			
			foreach($loadUsers as $user) { ?>
<div class="ifollow-challenges-wrapper">
	<div class="challenges ibyforum box-shadow challenges-list ifollow ibyforum-list type-all" id="challenges<?php echo $forum->tid;?>">
		<div class="challenges-bg ibyforum-bg">
		<pre>
		<?php var_dump($user);?>
		</pre>
		<?php
		$image_path = "/".drupal_get_path('module', 'iby_forums')."/images/forum_image_list.jpg";
		if(isset($forum->field_images['und']) && count($forum->field_images['und'])) {
		  $image_path = $forum->field_images['und'][0]['uri'];
		}
		?>

        <div class="challenges-image ibyforum-image">
          <?php echo theme('image', array('path' => file_create_url($image_path), 'height' => '120px'));?>
        </div>

        <div class="challenges-texts ibyforum-texts ifollow">
          <div class="challenges-title ibyforum-title">
            <span class="challenges-number ibyforum-number">#666</span>
            Testing 2          </div>
         
          <div class="challenges-short ibyforum-short"><?php echo nl2br(StringTools::shorten($forum->description, 200, '...'));?></div>
          <div style="clear:both;"></div>
        </div>

        <div class="challenges-info ibyforum-info ifollow">
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
		
		
		
		
		
          <div class="challenges-ended ibyforum-ended ifollow">Ended <?php echo date("M d, Y h:i a", $endstamp);?></div>
		  
          <div style="clear:both;"></div>
          <div class="rounded-button challenges-view ibyforum-view"><a href="<?php echo $forum->link;?>">View challenge</a></div>
          <div style="clear:both;"></div>
        </div>

        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
    </div>
	<?php
	/*
			$getTopics = db_query("
			SELECT * 
			FROM node, taxonomy_index as ti
			WHERE ti.tid = {$forum->tid}
			AND ti.nid = node.nid
			")->fetchAll();
			foreach($getTopics as $child_id => $topic):
				echo theme('challenges__topic__list', $variables + array('topic' => &$topic, 'parent_slug' => 'challenges'));
			endforeach;
			
	*/		
	?>
</div>			
<?php		}	//This end the inner Foreach loop
		}
	}
?>

