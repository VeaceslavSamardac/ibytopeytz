<?php
// We do not want to show everything, so here we check the path and redirects, if no follow type is specified
if($_GET['q'] == "user/flags/follow") drupal_goto('user/flags/follow/user');
?>

<ul class="flas-menu">
<li><a <?php echo ((arg(3) == "user") ? 'class="flags-active" ':'');?>href="/user/flags/follow/user">Members</a></li>
<li><a <?php echo ((arg(3) == "challenge") ? 'class="flags-active" ':'');?>href="/user/flags/follow/challenge">Challenges</a></li>
<li><a <?php echo ((arg(3) == "solution") ? 'class="flags-active" ':'');?>href="/user/flags/follow/solution">Contributions</a></li>
<li><a <?php echo ((arg(3) == "forum") ? 'class="flags-active" ':'');?>href="/user/flags/follow/forum">Forums</a></li>
<li class="last"><a <?php echo ((arg(3) == "topic") ? 'class="flags-active" ':'');?>href="/user/flags/follow/topic">Topics</a></li>
</ul>
<?php
	//echo theme('user_ifollow_challenges', $variables);
	//echo theme('user_ifollow_members', $variables);
?>

<?php

$follow_type = arg(3);

switch($follow_type) {
case "user":
  $i = 1;
  foreach($flags as $flag) {
    $member = user_load($flag->entity_id);
    if($member) {
      echo theme('user_preview_box', array('extra_class' => (($i%3)?'':'last-in-line'), 'member' => $member));
      $i++;
    }
  }
  break;
case "challenge":
case "forum":
  if($flags) echo theme('forum_follow_list', array('flags' => $flags));
  break;
case "solution":
case "topic":
  if($flags) echo theme('node_follow_list', array('flags' => $flags));
  break;
}

//switch($ifollow_page) {
//	case 'user':
//		echo theme('user_ifollow_members', $variables);
//		break;
//	case 'challenge':
//		echo theme('user_ifollow_challenges', $variables);
//		break;
//	case 'solution':
//		echo theme('user_ifollow_solutions', $variables);
//		break;
//	case 'forum':
//		echo theme('user_ifollow_forums', $variables);
//		break;
//	case 'topic':
//		echo theme('user_ifollow_topics', $variables + array('parent_slug' => 'chat-forums'));
//		break;
//}

//echo "->".arg(3);exit;
//	if($flags) echo theme('flags_list', $variables);

//echo "<pre>"; var_dump($variables);exit;
//echo "<hr>";
//if($flags) {
//  foreach($flags as $flag) {
//	echo "<ul>";
//		echo "<li>Show ".$flag->flag_type." ".$flag->flag_group." ".$flag->entity_type." with id: ".$flag->entity_id."</li>";
//	echo "</ul>";
//	}
//}

?>
<div style="clear:both;"></div>