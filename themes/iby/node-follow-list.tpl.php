<?php
$nids = array();
foreach($flags as $flag) $nids[] = $flag->entity_id;

$query = db_select('comment', 'c');
$query->condition('c.status', 1);
$query->fields('c', array('cid', 'nid'));
$query->fields('f', array('tid'));
$query->condition('c.nid', $nids, 'IN');
$query->join('forum', 'f', 'f.nid = c.nid');
$query->orderBy('c.created', 'DESC');
$query->range(0, 20);
$res = $query->execute();

foreach($res as $row) {
  $comment = comment_load($row->cid);
  $node = node_load($row->nid);
  $account = user_load($node->uid);
  $forum = taxonomy_term_load($row->tid);

  $body_langs = array_keys($comment->comment_body);
  $comment_body = StringTools::shorten(strip_tags(StringTools::striptags($comment->comment_body[$body_langs[0]][0]['value'])), 40);
?>

<div class="follow-list">
  <div class="user-info">
    <div class="user-image">
      <?php if($account->picture) { echo theme('image', array('path' => file_create_url($account->picture->uri), 'height' => '35px')); } else {
	  echo '<div style="width: 40px;">&nbsp;</div>';}?>
    </div>
  </div>
  <?php
	//vdp($comment);

  ?>

  <div class="follow-info">
	<div class="header">
		<a href="/node/<?php echo $node->nid;?>" class="node-link"><?php echo $node->title;?></a> 
    </div>
	<div>
		<?php echo nl2br(StringTools::shorten(strip_tags($comment->comment_body['und'][0]['value']), 350, '...'));?>
	</div>	
    <div style="margin-top: 5px;">
       was created by <a href="/user/<?php echo $account->uid;?>" class="user-link"><?php echo $account->name;?></a>
    </div>
    <div>
      In <a href="/forum/<?php echo $forum->tid;?>" class="forum-link"><?php echo $forum->name;?></a>, <?php echo date("d F Y h:i a", $node->created); ?>
    </div>
  </div>
</div>

<?php
}
?>
