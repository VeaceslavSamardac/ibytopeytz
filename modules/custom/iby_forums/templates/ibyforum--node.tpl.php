<?php
drupal_add_css(path_to_theme()."/css/".$parent_slug.".css", array('options' => "file"));
sq_flags_api_set(array('flag_type' => "view", 'entity_type' => "node", 'entity_id' => $elements['#node']->nid));

if($parent_slug == "vip-rooms") {
  echo theme('vip__rooms__menu', array('forum_tid' => $forum_tid));
  echo '<div style="clear:both;">&nbsp;</div>';
}
?>
<div id="<?php echo $parent_slug;?>-topic-content" class="ibyforum-topic-content">

  <div style="clear:both;"></div>

  <div id="<?php echo $parent_slug;?>-topic-container" class="ibyforum-topic-container">
<?php
// If this is page 1, show the first post
if(!isset($_GET['page']) || !$_GET['page']):
?>

    <div id="<?php echo $parent_slug;?>-topic-owner" class="ibyforum-topic-owner">
		<?php echo theme('user_info_box', array('uid' => $elements['#node']->uid)); ?>
    </div>

    <div class="<?php echo $parent_slug;?>-topic-content ibyforum-topic-content">
      <h2><?php echo $elements['#node']->title; ?></h2>
      
      <div class="action-buttons">
<?php if(!isset($in_preview) || !$in_preview): ?>
        <div class="like-button">
<?php
$args = array('flag_type' => "like", 'uid' => $user->uid, 'flag_group' => "topic", 'entity_type' => "node", 'entity_id' => $elements['#node']->nid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
          <a href="/sq_flags_api/toggle/like/topic/node/<?php echo $elements['#node']->nid;?>?sq_flags_api_cb=sq_flags_cb" id="like_topic_<?php echo $elements['#node']->nid;?>" class="use-ajax sq-like-link <?php echo $flag_class;?>"> </a>
        </div>
        <div class="follow-button">
<?php
$args = array('flag_type' => "follow", 'uid' => $user->uid, 'flag_group' => "topic", 'entity_type' => "node", 'entity_id' => $elements['#node']->nid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
          <a href="/sq_flags_api/toggle/follow/topic/node/<?php echo $elements['#node']->nid;?>?sq_flags_api_cb=sq_flags_cb" id="follow_topic_<?php echo $elements['#node']->nid;?>" class="use-ajax sq-follow-link <?php echo $flag_class;?>"> </a>
        </div>
<?php endif; ?>
        <div style="clear:both;"></div>
      </div>
      
      <div style="clear:both;"></div>
      
      <div class="content-divider"></div>
      
      <div class="created-container">
        <div class="created-info">
          <h3><?php echo 'Created by <a href="/user/'.$elements['#node']->uid.'">'.$elements['#node']->name;?></a></h3>
          <h3><?php echo date("d F Y h:i a", $elements['#node']->created); ?></h3>
        </div>
        <div class="created-number">
          <span class="created-no">No.</span> <span class="created-the-number">000</span>
        </div>
      </div>

      <div class="content-divider"></div>
      
      <div id="<?php echo $parent_slug;?>-topic-body" class="ibyforum-topic-body"><?php
$langs = array_keys($elements['#node']->body);
echo $elements['#node']->body[$langs[0]][0]['value'];
?></div>
      
<?php
//$nodeOwner = user_load($elements['#node']->uid);
//if(!empty($nodeOwner->signature)) {
//  echo '<div class="content-divider"></div>';
//  echo nl2br($nodeOwner->signature);
//}
//
//echo '<div class="content-divider"></div>';
//echo '<div class="signature-info">';
//echo '<strong>Joined</strong>: '.date("d M Y", $nodeOwner->created).' | ';
//
//if($nodeOwner->field_facts_location) {
//  $location_lang = sqtools_get_lang_value($nodeOwner->field_facts_location);
//  if($location_lang[0]['value'])
//    echo '<strong>Location</strong>: '.$location_lang[0]['value'].' | ';
//}
//
//$in_query = db_select('forum_index', 'fi');
//$in_query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
//$in_query->fields('fi', array('nid'));
//$in_query->join('node', 'n', 'n.nid = fi.nid AND n.uid ='.$nodeOwner->uid);
//
//$query = db_select('forum_index', 'fi');
//$query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
//$query->addExpression('COUNT(fi.nid)', 'node_count');
//$query->condition('fi.nid', $in_query, 'IN');
//$total_forum_posts = $query->execute()->fetchField(0);
//
//echo '<strong>Posts</strong>: '.intval($total_forum_posts);
//echo '</div>';
?>
      <div class="content-divider"></div>
<?php
      if(isset($elements['#node']->field_tags['und']) && is_array($elements['#node']->field_tags['und']) && count($elements['#node']->field_tags['und'])) {
        echo "Tags: ";
        $tags = "";
        foreach($elements['#node']->field_tags['und'] as $tag) {
          //$tags .= '<a href="#'.$tag['taxonomy_term']->name.'">'.$tag['taxonomy_term']->name.'</a> ';
          $tags .= $tag['taxonomy_term']->name.', ';
        }
        echo substr($tags, 0, -2);
?>
      <div class="content-divider"></div>
<?php
      }

      if(isset($elements['#node']->field_files['und']) && is_array($elements['#node']->field_files['und']) && count($elements['#node']->field_files['und'])) {
        echo "Files:<br />";
        foreach($elements['#node']->field_files['und'] as $image) {
          if(!empty($image['filename'])) {
            echo '<a href="'.file_create_url($image['uri']).'" target="_blank">'.$image['filename'].'</a>';
            //theme('image_style', array('style_name' => 'thumbnail', 'path' => file_build_uri($image['filename'])));
            //echo theme('image_style', array('style_name' => 'thumbnail', 'path' => file_build_uri($image['filename'])));
            echo "<br />";
          }
        }
      }
?>
      <?php if($user->uid && (!isset($in_preview) || !$in_preview)): ?>
  		<div style="float:left;">
	  	  <a href="/messages/new/1/<?php echo urlencode("Report of node%2F".arg(1))."?destination=node/".arg(1);?>" style="color: #666; font-size: 10px; font-weight: bold; text-decoration:none;" target="_self">Report this <?php
        if($parent_slug == "challenges") echo 'contribution';
        elseif($parent_slug == "chat-forums") echo 'topic';
        elseif($parent_slug == "tips-tricks") echo 'tip or trick';
        elseif($parent_slug == "vip-rooms") echo 'thread';
?></a>
		  </div>
      <div class="rounded-button comment-button"><a href="/comment/reply/<?php echo $elements['#node']->nid;?>?show_ajax=1" class="sq-ajax-comment">Comment</a></div>
      <?php endif;?>

      <div style="clear:both;"></div>

    </div>

    <div style="clear:both;"></div>
<?php
endif; // Show the post?
?>
    <a name="add-comment-anchor"></a>
    <div id="add-comment-content" class="add-comment-content"></div>

    <a name="pager-marker"></a>

    <div style="clear:both;"></div>

    <div id="<?php echo $parent_slug;?>-topic-comments" class="ibyforum-topic-comments">

<?php
print theme('pager');
echo drupal_render($content['comments']);
?>
    </div>


  </div>

  <div id="<?php echo $parent_slug;?>-topic-right" class="ibyforum-topic-right">
<?php
if($user->uid && (!isset($in_preview) || !$in_preview))
  echo theme($parent_call_name.'__right', $variables + array('forum' => (object)$taxonomy_forums[0]['taxonomy_term']));
?>
    <div style="clear:both;"></div>
  </div>


  <div style="clear:both;"></div>


</div>

<div style="clear:both;"></div>
