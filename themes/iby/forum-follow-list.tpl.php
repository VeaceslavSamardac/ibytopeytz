<?php
$tids = array();
foreach($flags as $flag) $tids[] = $flag->entity_id;

$query = db_select('forum_index', 'fi');
$query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
$query->fields('fi', array('tid', 'nid'));
$query->condition('fi.tid', $tids, 'IN');
$query->orderBy('fi.created', 'DESC');
$query->range(0, 10);
$res = $query->execute();

foreach($res as $row) {
  $forum = forum_forum_load($row->tid);
  $node = node_load($row->nid);
  $account = user_load($node->uid);
?>
<?php
?>
<div class="follow-list">
  <div class="user-info">
    <div class="user-image">
      <?php if($account->picture) { echo theme('image', array('path' => file_create_url($account->picture->uri), 'height' => '35px')); } else {
			echo '<div style="width: 40px;">&nbsp;</div>';} ?>
    </div>
  </div>

  <div class="follow-info">
    <div class="header">
      <a href="/node/<?php echo $node->nid;?>" class="node-link"><?php echo $node->title;?></a> 
    </div>
	<div>
		<?php echo nl2br(StringTools::shorten(strip_tags($node->body['und'][0]['value']), 350, '...'));?>
	</div>
	<div style="margin-top: 5px;">
		was created by <a href="/user/<?php echo $account->uid;?>" class="user-link"><?php echo (($account->name) ? $account->name : "could not find author");?></a>
	</div>
    <div>
      In <a href="/forum/<?php echo $forum->tid;?>" class="forum-link"><?php echo $forum->name;?></a>, <?php echo date("d F Y h:i a", $node->created); ?>
    </div>
  </div>
</div>

<?php
}
?>
