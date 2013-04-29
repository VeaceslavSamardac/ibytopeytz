<?php

if(arg(1)) {
	$userID = arg(1);
} else {
	$userID = $user->uid;
}

// 1 month back timestamp
$cur_stamp = time();
$min_stamp = mktime(0, 0, 0, (date("m", $cur_stamp)-1), date("d", $cur_stamp), date("Y", $cur_stamp));

// Forums
$forums_ptid = array_shift(_iby_forums_get_tids_from_names(array('chat forums'), 2));

$query = db_select('taxonomy_term_hierarchy', 'th');
$query->fields('th', array('tid'));
$query->condition('th.parent', $forums_ptid);
$tids_res = $query->execute()->fetchAll();
$forum_tids = array();
foreach($tids_res as $tid_res) $forum_tids[] = $tid_res->tid;

$in_query = db_select('forum_index', 'fi');
$in_query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
$in_query->fields('fi', array('nid'));
$in_query->join('node', 'n', 'n.nid = fi.nid AND n.uid ='.$userID);
//$in_query->condition('fi.tid', $forum_tids, 'IN');
//$in_query->condition('fi.tid', array('2', '92', '5'), 'NOT IN');
$in_query->condition('fi.created', $min_stamp, '>');
$in_query->distinct();

$query = db_select('forum_index', 'fi');
$query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
$query->join('comment', 'c', 'c.status = 1 AND c.nid = n.nid AND c.created >= '.$min_stamp.' AND c.uid = '.$userID);
$query->addExpression('COUNT(c.cid)', 'comment_count');
//$query->condition('fi.nid', $in_query, 'IN');
$total_forum_posts = intval($query->execute()->fetchField(0));

$query = db_select('forum_index', 'fi');
$query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid AND n.created >= '.$min_stamp.' AND n.uid = '.$userID);
$query->addExpression('COUNT(fi.nid)', 'node_count');
//$query->condition('fi.nid', $in_query, 'IN');
$total_forum_posts += intval($query->execute()->fetchField(0));

$total_forums = array();

$query = db_select('node', 'n');
$query->condition('n.status', 1);
$query->fields('n', array('nid', 'title', 'created'));
$query->condition('n.uid', $userID);
$query->condition('n.type', 'forum');
$query->condition('n.created', $min_stamp, '>');
//$query->condition('n.nid', $in_query, 'IN');
$query->orderBy('n.created', 'DESC');
$query->range(0,10);
$posts_res = $query->execute();

$latest_posts = array();

if($posts_res) {
  foreach($posts_res as $post_res) {
    if(!isset($total_forums[$post_res->nid])) $total_forums[$post_res->nid] = true;
    $latest_posts[$post_res->created] = $post_res;
  }
}

$query = db_select('comment', 'c');
$query->condition('c.status', 1);
$query->fields('c', array('cid', 'created'));
$query->fields('n', array('nid', 'title'));
$query->join('node', 'n', 'n.nid = c.nid');
$query->condition('c.uid', $userID);
$query->condition('n.type', 'forum');
$query->condition('c.created', $min_stamp, '>');
//$query->condition('n.nid', $in_query, 'IN');
$query->orderBy('c.created', 'DESC');
$query->range(0,10);
$posts_res = $query->execute();

if($posts_res) {
  foreach($posts_res as $post_res) {
    if(!isset($total_forums[$post_res->nid])) $total_forums[$post_res->nid] = true;
    $latest_posts[$post_res->created] = $post_res;
  }
}
krsort($latest_posts);
$latest_posts = array_slice($latest_posts, 0, 10);

$in_query->groupBy('fi.tid');

$total_forums = count($total_forums);

// Challenges
$challenges_ptid = array_shift(_iby_forums_get_tids_from_names(array('challenges'), 2));

$query = db_select('taxonomy_term_hierarchy', 'th');
$query->fields('th', array('tid'));
$query->condition('th.parent', $challenges_ptid);
$tids_res = $query->execute()->fetchAll();
$challenges_tids = array();
foreach($tids_res as $tid_res) $challenges_tids[] = $tid_res->tid;

$in_query = db_select('forum_index', 'fi');
$query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid AND n.uid ='.$userID);
$in_query->fields('fi', array('nid'));
$in_query->condition('fi.tid', $challenges_tids, 'IN');
$in_query->condition('fi.created', $min_stamp, '>');

$query = db_select('forum_index', 'fi');
$query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
$query->addExpression('COUNT(fi.nid)', 'node_count');
$query->condition('fi.nid', $in_query, 'IN');
$total_challenge_posts = $query->execute()->fetchField(0);

$in_query->groupBy('fi.tid');

$total_challenges = count($in_query->execute()->fetchAll());


// "Buddy" aka follow user
$query = db_select('sq_flags', 'sq');
$query->join('node', 'n', "n.status = 1 AND n.nid = sq.entity_id AND sq.entity_type = 'node'");
$query->fields('sq', array('entity_id'));
$query->condition('sq.flag_type', 'follow');
$query->condition('sq.entity_type', 'user');
$query->condition('sq.entity_id', $userID, '<>');
$query->orderBy('sq.timestamp', 'DESC');
$query->range(0, 1);

$buddy_uid = $query->execute()->fetchField(0);

//echo $total_forum_posts." - ".$total_forums;
//echo "<pre>"; var_dump($latest_post); exit;
//exit;

?>
<div class="user-rightbar user-stats-rightbar box-shadow">

  <div class="user-rightbar-top">
    <h3>My stats this month</h3>
    <div style="clear:both;"></div>
  </div>

  <div style="clear:both;"></div>

  <div class="user-rightbar-bottom">
    <div class="label">Total posts:</div>
    <div class="value"><?php echo $total_forum_posts;?> posts in <?php echo $total_forums;?> Forum(s)</div>

    <div style="clear:both;">&nbsp;</div>

    <?php if($latest_posts && is_array($latest_posts) && count($latest_posts)) : ?>	
		<div class="label">Latest activity:</div>
  <?php foreach($latest_posts as $latest_post) { ?>
		<div class="value"><?php echo date("M d, H:i", $latest_post->created); ?>:<br />
<?php
if($latest_post->cid) {
  $topic = node_load($latest_post->nid);
  echo $latest_post->title.'<br />';
  echo 'Comment to <a href="/node/'.$topic->nid.'" style="font-weight:bold;">'.$topic->title."</a><br />";

} else {
  $post_tid = _iby_forums_get_tid_by_nid($latest_post->nid);
  $forum = taxonomy_term_load($post_tid);
  echo '<a href="/node/'.$latest_post->nid.'">'.$latest_post->title.'</a><br />';
  echo 'Posted in <a href="/forum/'.$post_tid.'" style="font-weight:bold;">'.$forum->name."</a><br />";
}
?>
    </div>
		<div style="clear:both;">&nbsp;</div>
  <?php } ?>
		<div style="clear:both;">&nbsp;</div>
	<?php endif; ?>

<?php if($buddy_uid) {
    $buddy = user_load($buddy_uid);
?>

    <div class="label">Most recent added buddy:</div>
    <div class="value"><a href="/user/<?php echo $buddy->uid;?>"><?php echo $buddy->name;?></a></div>
    <div style="clear:both;">&nbsp;</div>
<?php } ?>

  </div>

  <div style="clear:both;"></div>

</div>

<div style="clear:both;"></div>

