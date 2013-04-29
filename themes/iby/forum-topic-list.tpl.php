<?php
$variables['forum_id'] = $topic_id;
foreach($parents as $_parent) {
  if($_parent->tid == $variables['forum_id']) {
    $variables['forum'] = $_parent;
    break;
  }
}
$variables['parent_tid'] = intval($parents[0]->tid);
$variables['parent_slug'] = StringTools::slug($parents[0]->name, "-");
$variables['parent_call_name'] = StringTools::slug($parents[0]->name, "__");

drupal_add_css(path_to_theme()."/css/".$variables['parent_slug'].".css", array('options' => "file"));

echo theme($variables['parent_call_name'].'__topic__view', $variables);

