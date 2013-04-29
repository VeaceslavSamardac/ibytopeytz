<?php
if(!$tid) {
  $query = db_select('forum_index', 'fi');
  $query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
  $query->fields('fi', array('tid'));
  $query->condition('fi.nid', $nid);
  $tid = $query->execute()->fetchField(0);
}

$query = db_select('forum_index', 'fi');
$query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
$query->fields('fi', array('nid'));
$query->condition('fi.tid', $tid);
$res = $query->execute();
$nids = array(0);
if($res) {
  foreach($res as $row) $nids[] = $row->nid;
}

$query = db_select('iby_forum_flags_cache', 'flags');
//$query->join('node', 'n', "n.status = 1 AND n.nid = flags.entity_id");
$query->fields('flags');
$query->fields('c', array('nid'));
$query->addExpression('SUM(total)', 'likes');
$query->condition('flag_type', 'like');
$query->condition('entity_type', 'comment');
$query->join('comment', 'c', "c.cid = flags.entity_id AND c.nid IN ('".implode("','", $nids)."')");
$query->groupBy('c.nid');
$query->orderBy('likes', 'DESC');
$query->range(0, 5);

$res = $query->execute();

$nids = array();
$nid_followers = array();
if($res) {
  foreach($res as $row) {
    $nids[] = $row->nid;
    $nid_followers[$row->nid] = $row->likes;
  }

  $nodes = entity_load('node', $nids);
  if(count($nodes)):
?>

<div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-right-box ibyforum-right-box">

  <h3>Most useful<br />Tips & Trick</h3>

<?php
    foreach($nodes as $node) {
?>
  <div class="<?php echo $parent_slug;?>-divider divider"></div>

  <div class="thread-follower">
    <h3><a href="/node/<?php echo $node->nid;?>"><?php echo strip_tags($node->title);?></a></h3>
    <div class="followers"><?php echo $nid_followers[$node->nid];?> people found this tip useful</div>

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

