<?php
$forum_id = $variables["taxonomy_forums"][0]["tid"];

$variables['parent_tid'] = 0;
$variables['parent_slug'] = 'ibyforum';
$variables['parent_call_name'] = 'ibyforum';

$parents = taxonomy_get_parents($forum_id);
if(!count($parents)) $parents = array(taxonomy_term_load($forum_id));

if(isset($parents) && is_array($parents) && count($parents)) {
  reset($parents);
  $parent = current($parents);
  $variables['parent_tid'] = intval($parent->tid);
  $variables['parent_slug'] = StringTools::slug($parent->name, "-");
  $variables['parent_call_name'] = StringTools::slug($parent->name, "__");
}

if(arg(0) != "comment") echo theme($variables['parent_call_name'].'__node', $variables);
