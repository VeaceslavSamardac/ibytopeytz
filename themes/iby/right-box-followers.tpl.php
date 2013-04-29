<?php
$query = db_select('sq_flags', 's');
$query->join('node', 'n', "n.status = 1 AND n.nid = s.entity_id AND s.entity_type = 'node'");
$query->fields('s', array('uid'));
$query->condition('flag_type', 'follow');
$query->condition('entity_type', 'node');
$query->condition('entity_id', $nid);
$query->orderBy('timestamp', 'DESC');
$query->range(0, 5);

$res = $query->execute();
if($res) {
  $uids = array();
  foreach($res as $row) $uids[] = $row->uid;

  $accounts = entity_load('user', $uids);
  if(count($accounts)):
?>

<div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-right-box ibyforum-right-box">

  <h3>Members following this thread</h3>

<?php
    foreach($accounts as $account) {

      $query = db_select('node', 'n');
	  $query->condition('n.status', 1);
      $query->addExpression('COUNT(*)', 'total_nodes');
      $query->condition('uid', $account->uid);
      $query->groupBy('uid');
      $account->nodes_count = $query->execute()->fetchField(0);

      $query = db_select('sq_flags', 's');
	  $query->join('node', 'n', "n.status = 1 AND n.nid = s.entity_id AND s.entity_type = 'node'");
      $query->addExpression('COUNT(s.entity_id)', 'total_likes');
      $query->condition('s.flag_type', 'like');
      $query->condition('s.uid', $account->uid);
      $query->groupBy('s.uid');
      $account->likes_count = $query->execute()->fetchField(0);

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
      <div><?php echo $account->name;?></div>
      <div>Member type</div>
      <div>Contributions:</div>
      <div><?php echo $account->nodes_count;?> posts | <?php echo $account->likes_count;?> likes</div>
    </div>

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

