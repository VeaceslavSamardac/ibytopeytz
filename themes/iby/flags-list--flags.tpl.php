<?php
drupal_add_css(sqtools_default_theme_path()."/css/challenges.css", array('options' => "file"));
drupal_add_css(sqtools_default_theme_path()."/css/chat-forums.css", array('options' => "file"));
drupal_add_css(sqtools_default_theme_path()."/css/tips-tricks.css", array('options' => "file"));
$i = "";

if($flags && false) {
  foreach($flags as $flag) {
	$iFollowNode	=	entity_load('node', array($flag->entity_id));
	
	$topicNode		= 	entity_load('node', array($flag->entity_id));
	if($flag->flag_group == 'challenge') {
		$iFollowTax		=	entity_load('taxonomy_term', array($flag->entity_id));
			
			echo "CHALLENGES FULL START"; 
			echo theme('challenges__full', $variables);
			echo "CHALLENGES FULL END";
			echo "<hr>";
			
			var_dump($topicNode);
			foreach ($topicNode as $topic) {
				echo "CHALLENGES LIST START";						
				echo theme($parent_call_name.'__topic__list', $variables + array('topic' => &$topic));
				echo "CHALLENGES LIST END";
			}

			$i++;
		
		if($i == 1) {
			echo "<h2>Challenges you follow</h2>";
		}
		foreach($iFollowTax as $ift => $forum) {
			//include(drupal_get_path('theme', 'iby')."/ifollow-challenges.tpl.php");

		}

	}elseif($flag->flag_group == 'solution') {
		$i++;
		$iFollowNode	=	entity_load('node', array($flag->entity_id));
		
		if($i == 1) {
			echo "<h2>Solutions you follow</h2>";
		}
		foreach($iFollowNode as $ifn => $topic) {
			include(drupal_get_path('theme', 'iby')."/ifollow-solutions.tpl.php");
		}
	}elseif($flag->flag_group == 'topic') {
		$i++;
		$iFollowNode	=	entity_load('node', array($flag->entity_id));
		
		if($i == 1) {
			echo "<h2>Topics you follow</h2>";
		}
		foreach($iFollowNode as $ifn => $elements) {
//			echo $forum->description;
			//var_dump($forum);

			include(drupal_get_path('theme', 'iby')."/ifollow-topics.tpl.php");
		}	
	}elseif($flag->flag_group == 'profile') {
		$i++;
		$iFollowUser	=	entity_load('user', array($flag->entity_id));
		
		if($i == 1) {
			echo "<h2>Topics you follow</h2>";
		}
		foreach($iFollowUser as $ifu => $user) {
//			echo $forum->description;
			//var_dump($user);

			include(drupal_get_path('theme', 'iby')."/ifollow-users.tpl.php");
		}
	}
?>


<div style="clear:both;"></div>
	
<?php	
	//echo "<li>Show ".$flag->flag_type." ".$flag->flag_group." ".$flag->entity_type." with id: ".$flag->entity_id."</li>";

  }
}

if($flags) {
  foreach($flags as $flag) {
	echo "<ul>";
		echo "<li>Show ".$flag->flag_type." ".$flag->flag_group." ".$flag->entity_type." with id: ".$flag->entity_id."</li>";
	echo "</ul>";
	}
}	
?>
