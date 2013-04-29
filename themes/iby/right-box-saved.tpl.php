<?php
if(!isset($tid) || !$tid) {
  $query = db_select('forum_index', 'fi');
  $query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
  $query->fields('fi', array('tid'));
  $query->condition('fi.nid', $nid);
  $tid = $query->execute()->fetchField(0);
}

$query = db_select('forum_index', 'fi');
$query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
$query->fields('fi', array('nid'));
$query->addExpression('MAX(s.timestamp)', 'max_timestamp');
$query->condition('fi.tid', $tid);
$query->join('sq_flags', 's', "s.entity_id = fi.nid AND s.entity_type='node' AND s.flag_type='follow' AND s.uid=".$user->uid);
$query->groupBy('fi.nid');
$query->orderBy('max_timestamp', 'DESC');

$res = $query->execute();
if($res) {
  $nids = array();
  foreach($res as $row) $nids[] = $row->nid;

  $nodes = entity_load('node', $nids);
  if(count($nodes)):
?>

<div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-right-box ibyforum-right-box">

  <h3>My saved<br />Tips & Tricks</h3>

<?php
   foreach($nodes as $node) {
      $query = db_select('comment', 'comment');
      $query->condition('comment.status', 1);
      $query->fields('comment', array('cid', 'created'));
      $query->addExpression('SUM(fc.total)', 'likes');
      $query->condition('comment.nid', $node->nid);
      $query->leftJoin('iby_forum_flags_cache', 'fc', "fc.entity_type='comment' AND fc.flag_type='like' AND fc.entity_id=comment.cid");
      $query->groupBy('comment.cid');
      $query->orderBy('likes', 'DESC');
      $query->range(0,1);
      $res = $query->execute()->fetchAssoc();

      if($res) {
        $top_comment = entity_load('comment', array($res['cid']));
        $top_comment = $top_comment[$res['cid']];
        $top_comment->created = $res['created'];
      }

?>
  <div class="<?php echo $parent_slug;?>-divider divider"></div>

  <div class="thread-follower">
    <h3>Problem:</h3>
    <div class="saved-problem"><a href="/node/<?php echo $node->nid;?>"><?php echo strip_tags($node->title);?></a></div>

    <h3>Solution:</h3>
      <div class="saved-solution"><?php echo (($top_comment)?StringTools::striptags($top_comment->comment_body[$top_comment->language][0]['value']):"");?></div>

    <div class="<?php echo $parent_slug;?>-divider divider"></div>

    <span class="submitted">Submitted: <?php echo date("d F Y h:i a", $top_comment->created);?></span>

    <span class="removefromlist"><a href="/sq_flags_api/toggle/follow/topic/node/<?php echo $node->nid;?>?sq_flags_api_cb=refresh_page" id="follow_topic_<?php echo $node->nid;?>" class="use-ajax">Remove this tip from my list</a></span>

    <div style="clear:both;"></div>

  </div>
<?php
    }
?>
  <div class="<?php echo $parent_slug;?>-divider divider"></div>

  <div style="clear:both;"></div>
</div>

  <div style="clear:both;">&nbsp;</div>
  <div style="clear:both;">&nbsp;</div>

<?php
  endif;
}
?>

