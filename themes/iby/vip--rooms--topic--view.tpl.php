<?php
sq_flags_api_set(array('flag_type' => "view", 'entity_type' => "taxonomy_term", 'entity_id' => $forum->tid));

echo theme('vip__rooms__menu', array('forum_tid' => $forum->tid));

if($forums) $forum_tid = $parent_tid;
else {
  $parent_index = count($parents);
  $parent_index--;
  $forum_tid = $parents[$parent_index]->tid;
}

$tags = iby_forums_get_nodes_filter_tags($forum_tid);

echo theme('ibyforums__filters', $variables + array('tags' => $tags, 'tagHeadline' => 'VIP Room' . ' - ' . $forum->name));
echo theme('ibyforums__sorting');
?>

<div class="rounded-button <?php echo $parent_slug;?>-enter ibyforum-enter"><a href="/node/add/forum/<?php echo $forum->tid;?>?show_ajax=1" class="sq-ajax-topic">Create new topic</a></div>

<!--<div class="rounded-button <?php echo $parent_slug;?>-enter ibyforum-enter"><a href="/node/add<?php echo $_SERVER['REQUEST_URI'];?>?show_ajax=1" class="sq-ajax-link" rel="#sq_ajax_pop">Create new post</a></div>-->
<div style="clear:both;"></div>

<a name="add-topic-anchor"></a>
<div id="add-topic-content">
<?php
$node_form = node_add('forum');
$vars = array('page' => array('content' => $node_form));
echo theme('vip__rooms__node__add', $vars);
?>
</div>

<div id="ibyforum-topics-content">

<?php
$index = 0;
foreach($topics as $child_id => $topic) {
  if(isset($_GET['view']) && ($_GET['view'] == "list")) echo theme($parent_call_name.'__topic__list', $variables + array('topic' => &$topic));
  else echo theme($parent_call_name.'__topic__grid', $variables + array('first_in_row' => (!($index%3)), 'topic' => &$topic) );
  $index++;
}
?>

<?php echo theme('pager', array('element'=>1)); ?>

  <div style="clear:both;"></div>

</div> <!-- #<?php echo $parent_slug;?>-topics-content -->
