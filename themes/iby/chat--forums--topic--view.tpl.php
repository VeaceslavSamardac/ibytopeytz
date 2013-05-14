<?php
sq_flags_api_set(array('flag_type' => "view", 'entity_type' => "taxonomy_term", 'entity_id' => $forum->tid));
//$bla = drupal_render(drupal_get_form('node_forum_form'));
?>

<div class="<?php echo $parent_slug;?>-large-box box-shadow">


  <div class="<?php echo $parent_slug;?>-name ibyforum-name">
    <h2><?php echo $forum->name?></h2>
    <div class="flag-buttons" style="float:right;">
<?php
$args = array('flag_type' => "like", 'uid' => $user->uid, 'flag_group' => "forum", 'entity_type' => "taxonomy_term", 'entity_id' => $forum->tid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
      <a href="/sq_flags_api/toggle/like/forum/taxonomy_term/<?php echo $forum->tid;?>?sq_flags_api_cb=sq_flags_cb" id="like_forum_<?php echo $forum->tid;?>" class="use-ajax sq-like-link <?php echo $flag_class;?>"> </a>

<?php
$args = array('flag_type' => "follow", 'uid' => $user->uid, 'flag_group' => "forum", 'entity_type' => "taxonomy_term", 'entity_id' => $forum->tid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
      <a href="/sq_flags_api/toggle/follow/forum/taxonomy_term/<?php echo $forum->tid;?>?sq_flags_api_cb=sq_flags_cb" id="follow_forum_<?php echo $forum->tid;?>" class="use-ajax sq-follow-link <?php echo $flag_class;?>"> </a>

      <div style="clear:both;"></div>
    </div>
    <div style="clear:both;"></div>
  </div>

  <div class="<?php echo $parent_slug;?>-top-divider ibyforum-top-divider">&nbsp;</div>

  <div style="clear:both;"></div>

  <div class="<?php echo $parent_slug;?>-texts">

    <div class="<?php echo $parent_slug;?>-texts-left">
      <?php echo $forum->description;?>
      <div style="clear:both;"></div>
    </div>

    <div class="<?php echo $parent_slug;?>-texts-right">
  &nbsp;
      <div style="clear:both;"></div>
    </div>
	<div class="<?php echo $parent_slug;?>-texts-bottom">
		<div class="challenges_header_stats">
			<?php
				$query = db_select('taxonomy_index', 'ti');
				$query->join('node', 'n', 'n.status = 1 AND n.nid = ti.nid');
				$query->addExpression('COUNT(ti.nid)', 'topics');
				$query->condition('ti.tid', $forum->tid);
				$total_topics = $query->execute()->fetchField(0);
			?>
			
				<span class="stat"><span class="stat_numbers"><?php echo $total_topics; ?></span> topics</span>
				
				<span class="stat view-taxonomy_term-<?php echo $forum->tid;?>"><span class="count-number stat_numbers"><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'view' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?></span> views</span>
				<span class="stat follow-taxonomy_term-<?php echo $forum->tid;?>"><span class="count-number stat_numbers"><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'follow' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?></span> followers</span>
				<span class="stat last"><span class="count-number stat_numbers"><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'like' , 'entity_type' => 'taxonomy_term', 'entity_id' => $forum->tid))); ?></span> likes</span>
				
		</div>
	</div>
  </div>
</div>

<div style="clear:both;"></div>
<?php
if($forums) $forum_tid = $parent_tid;
else {
  $parent_index = count($parents);
  $parent_index--;
  $forum_tid = $parents[$parent_index]->tid;
}

$tags = iby_forums_get_nodes_filter_tags($forum_tid);

echo theme('ibyforums__filters', $variables + array('tags' => $tags, 'tagHeadline' => 'Latest posts'));
echo theme('ibyforums__sorting');


$node_create_url = "/node/add".$_SERVER['REQUEST_URI'];
if(strpos($node_create_url, "?")) $node_create_url .= "&";
else $node_create_url .= "?";
$node_create_url .= "show_ajax=1";
?>

<!--<div class="rounded-button <?php echo $parent_slug;?>-enter ibyforum-enter"><a href="<?php echo $node_create_url;?>" class="sq-ajax-link" rel="#sq_ajax_pop">Create new post</a></div>-->
<a name="pager-marker"></a>
<?php  if($user->uid): ?>
<div class="rounded-button <?php echo $parent_slug;?>-enter ibyforum-enter"><a href="/node/add/forum/<?php echo $forum->tid;?>?show_ajax=1" class="sq-ajax-topic">Create new topic</a></div>
  <?php endif;?>
<div style="clear:both;"></div>

<a name="add-topic-anchor"></a>
<div id="add-topic-content">
<?php
$node_form = node_add('forum');
$vars = array('page' => array('content' => $node_form));
echo theme('chat__forums__node__add', $vars);
?>
<div style="clear:both;"></div>
</div>

<div id="<?php echo $parent_slug;?>-topics-content ibyforum-topics-content">

<?php
$index = 0;
foreach($topics as $child_id => $topic) {
  if(isset($_GET['view']) && ($_GET['view'] == "list")) echo theme($parent_call_name.'__topic__list', $variables + array('topic' => &$topic));
  else echo theme($parent_call_name.'__topic__grid', $variables + array('first_in_row' => (!($index%3)), 'topic' => &$topic) );
  $index++;
}
?>
<div style="clear:both;"></div>
</div>

<?php echo theme('pager', array('element'=>0)); ?>

  <div style="clear:both;"></div>
