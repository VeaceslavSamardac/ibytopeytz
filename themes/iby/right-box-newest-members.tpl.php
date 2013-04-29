<?php
$query = db_select('field_data_field_vip_members', 'vm');
$query->fields('ivm', array('uid', 'timestamp'));
$query->join('iby_forum_vip_members', 'ivm', 'ivm.tid = vm.entity_id AND ivm.uid = vm.field_vip_members_value');
$query->groupBy('ivm.uid');
$query->orderBy('ivm.timestamp', 'DESC');
$query->range(0, 3);

$res = $query->execute();
if($res) {
  $uids = array();
  foreach($res as $row) $uids[] = $row->uid;

  $accounts = entity_load('user', $uids);
  if(count($accounts)):
?>

<div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-right-box ibyforum-right-box">

  <h3>Newest members<br />in VIP Rooms</h3>

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
	<?php
		//vdp($account);
		
	$role = $account->roles;
	$userrole = '';
	foreach($role as $k => $v) {
		if(($v == 'administrator') || ($v == 'VIP') || ($v == 'moderator')) {
			if($v == 'administrator') {
				$userrole = 'Administrator';
			}
			if($v == 'VIP') {
				if($userrole != 'Administrator') {
					$userrole = 'VIP';
				}
			}
			if($v == 'moderator') {
				if(($userrole != 'Administrator') && ($userrole != 'VIP')) {
					$userrole = 'Moderator';
				}
			}
		}
	}
		
	?>
    <div class="account-info">
      <div><a href="/user/<?php echo $account->uid; ?>/"><?php echo $account->name;?></a></div>
      <div>Member type: <?php echo $userrole; ?></div>
      <div><?php echo $account->nodes_count;?> posts | <?php echo intval($account->likes_count);?> likes</div>
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

