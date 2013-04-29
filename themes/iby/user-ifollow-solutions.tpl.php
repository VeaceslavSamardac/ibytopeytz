<!-- user-ifollow-challenges.tpl.php -->
<!--
<?php
	var_dump($variables);
?>
-->
<?php
	drupal_add_css(sqtools_default_theme_path()."/css/ibyforum.css", array('options' => "file"));
	drupal_add_css(sqtools_default_theme_path()."/css/challenges.css", array('options' => "file"));
	drupal_add_css(sqtools_default_theme_path()."/css/tips-tricks.css", array('options' => "file"));
	
?>
<?php
foreach($flags as $flag) {
	if($flag->flag_group == 'solution') {

		$loadSolutions = entity_load('node', array($flag->entity_id));
			foreach($loadSolutions as $child_id => $topic) {
				echo theme('challenges__topic__list', $variables + array('topic' => &$topic, 'parent_slug' => 'challenges'));
			}
	}
}
?>

