<?php
$related_nodes = array();

$nid = 0;

if(arg(0) == "node") {

  $user_roles = array_keys($user->roles);

  $nid = arg(1);

  $tid = _iby_forums_get_parent_tid_by_nid($nid);

  $tids_query = db_select('taxonomy_term_hierarchy', 'th')->fields('th', array('tid'))->condition('th.parent', $tid);

  $query_access = db_select('field_data_field_allowed_roles', 'far');
  $query_access->fields('far', array('entity_id'));
  $query_access->condition('far.field_allowed_roles_value', $user_roles, 'IN');
  $query_access->condition('far.entity_type', 'taxonomy_term');
  $query_access->condition('far.entity_id', $tids_query, 'IN');
  $query_access->distinct();

  $query_closed_rooms = db_select('field_data_field_allowed_roles', 'far');
  $query_closed_rooms->fields('far', array('entity_id'));
  $query_closed_rooms->condition('far.entity_type', 'taxonomy_term');
  $query_closed_rooms->condition('far.entity_id', $tids_query, 'IN');
  $query_closed_rooms->distinct();

  $in_query = db_select('iby_forum_tags_cache', 'tc');
  $in_query->join('node', 'n', 'n.status = 1 AND n.nid = tc.nid');
  $in_query->fields('tc', array('field_tags_tid'));
  $in_query->condition('tc.nid', $nid);

  $query = db_select('iby_forum_tags_cache', 'tc');
  if(!sqtools_is_admin($user)) {
    $query->condition(db_or()->condition('f.tid', $query_access, 'IN')->condition('f.tid', $query_closed_rooms, 'NOT IN'));
  }
  $query->join('node', 'n', 'n.status = 1 AND n.nid = tc.nid');
  $query->join('forum', 'f', 'f.nid = n.nid');
  $query->condition('tc.field_tags_tid', $in_query, 'IN');
  $query->condition('tc.nid', $nid, '<>');
  $query->join('node', 'n', 'n.nid = tc.nid');
  $query->join('field_data_body', 'db', "db.entity_id = n.nid AND entity_type='node' AND bundle='forum'");
  $query->fields('n');
  $query->fields('db', array('body_value'));
  $query->groupBy('n.nid');
  $query->range(0, 3);

  $res = $query->execute();

  if($res) {
    foreach($res as $row) {
      $related_nodes[] = $row;
    }
  }
}

if(count($related_nodes)):
?>

<div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-right-box ibyforum-right-box">

  <h3>Related threads</h3>

  <div class="related-threads">

<?php
  foreach($related_nodes as $related_node) {
    $owner = entity_load('user', array($related_node->uid));
    $account = array_shift($owner);


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
       <div><a href="/node/<?php echo $related_node->nid;?>"><?php echo StringTools::striptags($related_node->title);?></a></div>
        <div class="node_body"><a href="/node/<?php echo $related_node->nid;?>"><?php echo StringTools::shorten(trim(strip_tags($related_node->body_value)), 100);?></a></div>
        <div><?php echo $account->name;?></div>
<?php
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
    <div><?php echo $userrole;?></div>
        <div>Contributions:</div>
    <div><?php echo intval($account->nodes_count);?> posts | <?php echo intval($account->likes_count);?> likes</div>
      </div>

      <div style="clear:both;"></div>

    </div>

<?php
  }
?>

  </div>

  <div class="<?php echo $parent_slug;?>-divider divider"></div>

  <div style="clear:both;"></div>
</div>

  <div style="clear:both;">&nbsp;</div>
  <div style="clear:both;">&nbsp;</div>

<?php
  endif;
?>