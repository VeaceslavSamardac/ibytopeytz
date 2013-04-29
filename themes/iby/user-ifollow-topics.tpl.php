<!-- user-ifollow-challenges.tpl.php -->
<!--
<?php
	//var_dump($variables);
?>
-->
<?php
	drupal_add_css(sqtools_default_theme_path()."/css/ibyforum.css", array('options' => "file"));
	drupal_add_css(sqtools_default_theme_path()."/css/challenges.css", array('options' => "file"));
	drupal_add_css(sqtools_default_theme_path()."/css/chat-forums.css", array('options' => "file"));
?>

<?php
	foreach($flags as $flag) {
		$loadTopics = entity_load('node', array($flag->entity_id));
		
		echo "<pre>";
//		var_dump($loadTopics);
		echo "</pre>";
		foreach($loadTopics as $child_id => $elements) {
			//echo theme('chat__forums__node', $variables + array('elements["#node"]' => &$elements, 'parent_slug' => 'chat-forums'));
			
			// START EXPERIMENTS ?>
  <div id="<?php echo $parent_slug;?>-topic-container" class="ibyforum-topic-container">
    <div id="<?php echo $parent_slug;?>-topic-owner" class="ibyforum-topic-owner">
<?php
    echo theme('user_info_box', array('uid' => $elements->uid));
?>
    </div>

    <div class="<?php echo $parent_slug;?>-topic-content ibyforum-topic-content">
      <h2><?php echo $elements->title; ?></h2>
      
      <div class="action-buttons">
        <div class="like-button">
<?php
$args = array('flag_type' => "like", 'uid' => $user->uid, 'flag_group' => "topic", 'entity_type' => "node", 'entity_id' => $elements->nid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
          <a href="/sq_flags_api/toggle/like/topic/node/<?php echo $elements->nid;?>?sq_flags_api_cb=sq_flags_cb" id="like_topic_<?php echo $elements->nid;?>" class="use-ajax sq-like-link <?php echo $flag_class;?>"> </a>
        </div>
        <div class="follow-button">
<?php
$args = array('flag_type' => "follow", 'uid' => $user->uid, 'flag_group' => "topic", 'entity_type' => "node", 'entity_id' => $elements->nid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
          <a href="/sq_flags_api/toggle/follow/topic/node/<?php echo $elements->nid;?>?sq_flags_api_cb=sq_flags_cb" id="follow_topic_<?php echo $elements->nid;?>" class="use-ajax sq-follow-link <?php echo $flag_class;?>"> </a>
        </div>
        <div style="clear:both;"></div>
      </div>
      
      <div style="clear:both;"></div>
      
      <div class="content-divider"></div>
      
      <h3><?php echo "Created by ".$elements->name;?></h3>
      <h3><?php echo date("d F Y h:i a", $elements->created);?></h3>

      <div class="content-divider"></div>
      
      <div id="<?php echo $parent_slug;?>-topic-body" class="ibyforum-topic-body"><?php
$langs = array_keys($elements->body);
echo $elements->body[$langs[0]][0]['value'];
?></div>
      
      <div class="content-divider"></div>
      
<?php
      if(isset($elements->field_tags['und']) && is_array($elements->field_tags['und']) && count($elements->field_tags['und'])) {
        echo "Tags: ";
        $tags = "";
        foreach($elements->field_tags['und'] as $tag) {
          //$tags .= '<a href="#'.$tag['taxonomy_term']->name.'">'.$tag['taxonomy_term']->name.'</a> ';
          $tags .= $tag['taxonomy_term']->name.', ';
        }
        echo substr($tags, 0, -2);
?>
      <div class="content-divider"></div>
<?php
      }

      if(isset($elements->field_files['und']) && is_array($elements->field_files['und']) && count($elements->field_files['und'])) {
        echo "Files:<br />";
        foreach($elements->field_files['und'] as $image) {
          if(!empty($image['filename'])) {
            echo '<a href="'.image_style_url('large', $image['uri']).'" target="_blank">'.$image['filename'].'</a>';
            //theme('image_style', array('style_name' => 'thumbnail', 'path' => file_build_uri($image['filename'])));
            //echo theme('image_style', array('style_name' => 'thumbnail', 'path' => file_build_uri($image['filename'])));
            echo "<br />";
          }
        }
      }

//      foreach($elements->field_images['und'] as $image) {
//
//      if(!empty($node->field_image['und'][0]['filename']) {
//          echo theme('image_style', array('style_name' => 'banner', 'path' => file_build_uri($node->field_image['und'][0]['filename'])));
//        }
//
//      $image['path'] = image_style_url($image['style_name'], $image);
//      return theme('image', $image);
//    }
//echo "<pre>"; var_dump($elements->field_images);exit;
?>
      <div class="<?php echo $parent_slug;?>-topic-navigation ibyforum-topic-navigation">
        <div class="rounded-button comment-button"><a href="#new_comment">Post reply</a></div>
<!--        <div class="rounded-button comment-button"><a href="/comment/reply/<?php echo $elements->nid;?>?show_ajax=1" class="sq-ajax-link">Post reply</a></div>-->
      </div>

      <div style="clear:both;"></div>

    </div>

    <div style="clear:both;"></div>

    <div id="<?php echo $parent_slug;?>-topic-comments" class="ibyforum-topic-comments">

<?php
			$getComments = db_query("
			SELECT * 
			FROM comment, node
			WHERE node.nid = {$elements->nid}
			AND node.nid = comment.nid
			ORDER BY comment.created DESC
			LIMIT 3
			")->fetchAll();
			//var_dump($getTopics);
			echo "<h3 style='margin-bottom:10px; font-size: 20px;'>Recent comments</h3>";
			foreach($getComments as $child_id => $comment):
				echo theme('chat__forums__comment', $variables + array('comment' => &$comment));
			endforeach;

//	echo drupal_render($content['comments']);
?>
    </div>


  </div>			
  <?php
			// END EXPERIMENTS
		}
	}
?>