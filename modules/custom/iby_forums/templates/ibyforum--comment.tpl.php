<?php
$comment_created = false;
if(isset($comment->first_new) && $comment->first_new && ($comment->uid == $user->uid) && ($comment->created > (time() - 90)) )
  $comment_created = true;

$comment_editable = false;
if(($comment->uid == $user->uid) && ($comment->created > (time() - 1800)) )
  $comment_editable = 1;
elseif(_iby_forums_check_access(false, false, "comment", "edit", false, false, false, $comment->cid))
  $comment_editable = 2;

$commentOwner = user_load($comment->uid);

$comment_number = (comment_get_display_ordinal($comment->cid, $comment->node_type)+1);
?>

  <div class="hard-divider">
    <a href="#" id="comment-number<?php echo $comment_number;?>">To top <img src="/<?php echo drupal_get_path('module', 'iby_forums');?>/images/to_top_icon.png" /></a>
    <div style="clear:both;"></div>
  </div>

  <?php if($comment_created) echo '<a name="goto-after-ajax"></a>'."\n";?>
  <?php if(isset($_POST['op']) && (strtolower($_POST['op']) == 'preview')) {
    echo '<div class="comment-preview">';
    echo '<h1>This is a preview of your comment</h1>';
    echo '<div class="content-divider"></div>';
  }
  ?>
  <div class="comment-container <?php echo (($comment_created) ? "comment-created ": ""); ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

    <div class="comment-owner">
<?php
  echo theme('user_info_box', array('uid' => $comment->uid));
?>
    </div>

    <div class="comment-content">

      <div class="created-container">
        <div class="created-info">
          <h2>Comment by <a href="/user/<?php echo $commentOwner->uid;?>"><?php echo $commentOwner->name; ?></a></h2>
          <div style="clear:both;"></div>
          <h3>Created <?php echo date("d F Y h:i a", $comment->created);?></h3>
        </div>

        <div class="action-buttons">
          <div class="like-button">
<?php
$args = array('flag_type' => "like", 'uid' => $user->uid, 'flag_group' => "comment", 'entity_type' => "comment", 'entity_id' => $comment->cid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
            <a href="/sq_flags_api/toggle/like/comment/comment/<?php echo $comment->cid;?>?sq_flags_api_cb=sq_flags_cb" id="like_comment_<?php echo $comment->cid;?>" class="use-ajax sq-like-link <?php echo $flag_class;?>"> </a>
          </div>

          <div style="clear:both;"></div>

          <div class="created-number">
            <span class="created-no">No.</span> <span class="created-the-number"><?php echo sprintf("%03d", $comment_number);?></span>
          </div>
        </div>
      
        <div style="clear:both;"></div>
      
        <div class="content-divider"></div>
      </div>

      <div class="comment-body">
<?php
echo $comment->comment_body[$comment->language][0]['value'];

if(!empty($comment->signature)) {
  echo '<div class="content-divider"></div>';
  echo nl2br($comment->signature);
}

//echo '<div class="content-divider"></div>';
//echo '<div class="signature-info">';
//echo '<strong>Joined</strong>: '.date("d M Y", $commentOwner->created).' | ';
//
//if($commentOwner->field_facts_location) {
//  $location_lang = sqtools_get_lang_value($commentOwner->field_facts_location);
//  if($location_lang[0]['value'])
//    echo '<strong>Location</strong>: '.$location_lang[0]['value'].' | ';
//}
//
//$in_query = db_select('forum_index', 'fi');
//$in_query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
//$in_query->fields('fi', array('nid'));
//$in_query->join('node', 'n', 'n.nid = fi.nid AND n.uid ='.$commentOwner->uid);
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
      </div>

      <div class="content-divider"></div>

	  <?php
		if($comment->pid <> 0) { ?>
			<div style="float:left;">
				<a href="/messages/new/1/<?php echo urlencode("Report of comment ".$comment->cid." on node%2F".arg(1))."?destination=node/".arg(1);?>?destination=node/" style="color: #666; font-size: 10px; font-weight: bold; text-decoration:none;" target="_self">Report this comment</a>
			</div>
<?php	}
	  ?>
      
      
<?php
      if(isset($comment->field_tags['und']) && is_array($comment->field_tags['und']) && count($comment->field_tags['und'])) {
        echo "Tags: ";
        $tags = "";
        foreach($comment->field_tags['und'] as $tag) {
          $term = taxonomy_term_load($tag['tid']);
          //$tags .= '<a href="#'.$term->name.'">'.$term->name.'</a> ';
          $tags .= $term->name.', ';
        }
        echo substr($tags, 0, -2);
        echo '<div class="content-divider"></div>'."\n";
      }

      if(isset($comment->field_files['und']) && is_array($comment->field_files['und']) && count($comment->field_files['und'])) {
        echo "Files:<br />";
        foreach($comment->field_files['und'] as $file) {
          if(!empty($file['filename'])) {
            echo '<a href="'.file_create_url($file['uri']).'" target="_blank">'.$file['filename'].'</a>';
            echo "<br />";
          }
        }
      }

if(!$comment->pid) {
?>
    <div class="solution-navigation">
		  <div style="float:left;">
				<a href="/messages/new/1/<?php echo urlencode("Report of comment ".$comment->cid." on node%2F".arg(1))."?destination=node/".arg(1);?>?destination=node/" style="color: #666; font-size: 10px; font-weight: bold; text-decoration:none;" target="_self">Report this comment</a>
		  </div>

    <?php if($user->uid):?>
	  	<div class="rounded-button comment-button" style="float:right;"><a href="/comment/reply/<?php echo $elements['#node']->nid;?>?show_ajax=1" class="sq-ajax-comment" rel="iby<?php echo $comment->cid;?>">Comment</a></div>
    <?php endif;?>

      <div style="clear:both;"></div>
    </div>
    <div style="clear:both;"></div>
<?php
}
      if($comment_editable) {
        unset($content['links']['comment']['#links']['comment-reply']);
        if($comment_editable > 1) echo "Admin: ";
        print render($content['links']);
      }
?>

      <div style="clear:both;"></div>

    </div>   

    <div style="clear:both;"></div>

    <a name="add-comment-anchoriby<?php echo $comment->cid;?>"></a>
    <div id="add-comment-contentiby<?php echo $comment->cid;?>" class="add-comment-content" style="float:right;"></div>

    <div style="clear:both;"></div>

  </div>
  <?php if(isset($_POST['op']) && (strtolower($_POST['op']) == 'preview')) {
		echo '</div>';
  }
  ?>
