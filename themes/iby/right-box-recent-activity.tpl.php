<?php
$tid_query = db_select('taxonomy_term_hierarchy', 'th');
$tid_query->fields('th', array('tid'));
$tid_query->condition('th.parent', $tid);
$tid_res = $tid_query->execute();
$tids = array();
if($tid_res) {
  foreach($tid_res as $tid_row) {
    $tids[] = $tid_row->tid;
  }
}
if(!count($tids)) $tids[] = $tid;

$nid_query = db_select('forum_index', 'fi');
$nid_query->fields('fi', array('nid'));
$nid_query->condition('fi.tid', $tids, 'IN');

$cid_query = db_select('comment', 'c');
$cid_query->fields('c', array('cid'));
$cid_query->condition('c.nid', $nid_query, 'IN');
$cid_query->orderBy('c.created', 'DESC');
$cid_query->range(0, 10);

$sorted_entities = array();

$cids = array();
$cid_infos = array();
$cid_res = $cid_query->fields('c', array('created'))->range(0,10)->execute();
if($cid_res) {
  foreach($cid_res as $cid_row) {
    $sorted_entities[$cid_row->created] = $cid_row;
    $cids[] = $cid_row->cid;
    $cid_infos[] = $cid_row;
  }
}

$nids = array();
$nid_infos = array();
$nid_res = $nid_query->fields('fi', array('created'))->orderBy('fi.created', 'DESC')->range(0,10)->execute();
if($nid_res) {
  foreach($nid_res as $nid_row) {
    $sorted_entities[$nid_row->created] = $nid_row;
    $nids[] = $nid_row->nid;
    $nid_infos[] = $nid_row;
  }
}



if(count($sorted_entities)):

  krsort($sorted_entities);
  $sorted_entities = array_slice($sorted_entities, 0, 10);

?>

<div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-right-box ibyforum-right-box">

  <a name="tags_box"></a>
  <h3>Recent Activity</h3>

  <div class="related-threads">

<?php
  foreach($sorted_entities as $entity) {

    if(isset($entity->cid) && $entity->cid) $info = entity_load('comment', array($entity->cid));
    elseif(isset($entity->nid) && $entity->nid) $info = entity_load('node', array($entity->nid));

    $entity = array_shift($info);

    $owner = entity_load('user', array($entity->uid));
    $account = array_shift($owner);
?>

    <div class="<?php echo $parent_slug;?>-divider divider"></div>

    <div class="thread-follower">
      <div class="account-picture">
<?php
      if($account->picture) {
        echo theme('image', array('path' => file_create_url($account->picture->uri), 'width' => '25px'));
      }
?>
      </div>

      <div class="account-info">
        <div><a href="/user/<?php echo $account->uid;?>"><?php echo $account->name;?></a></div>
        <div><?php echo date("d M - h:i a", $entity->created);?></div>
        <div class="node_body"><a href="/node/<?php echo $related_node->nid;?>"><?php echo nl2br(StringTools::shorten(StringTools::striptags($related_node->body_value), 100));?></a></div>
      </div>

      <div class="entity-info" style="font-size:11px;color:#666;text-decoration:none;">
<?php
if(isset($entity->comment_body)) {
  $type = "comment";
} elseif(isset($entity->body)) {
  $type = "topic";
}
?>
        <div><?php echo 'Added a <a href="/node/'.$entity->nid.'">'.$type.'</a>';?></div>
      </div>

      <div style="clear:both;"></div>

    </div>

<?php
  }
?>

  </div>

  <div class="<?php echo $parent_slug;?>-divider divider"></div>

<!--  <a href="/node/<?php echo $nid;?>#tags_box" style="font-weight:bold;">Reset choices</a>-->

  <div style="clear:both;"></div>
</div>

  <div style="clear:both;">&nbsp;</div>
  <div style="clear:both;">&nbsp;</div>

<?php
  endif;
?>