<?php
$variables['parent_tid'] = 0;
$variables['parent_slug'] = 'ibyforum';
$variables['parent_call_name'] = 'ibyforum';

$parents = taxonomy_get_parents($node->forum_tid);

if(!count($parents) && isset($node->taxonomy_forums[$node->language][0]['taxonomy_term'])) {
  $parent = $node->taxonomy_forums[$node->language][0]['taxonomy_term'];

  $variables['parent_tid'] = intval($parent->tid);
  $variables['parent_slug'] = StringTools::slug($parent->name, "-");
  $variables['parent_call_name'] = StringTools::slug($parent->name, "__");

} elseif(isset($parents) && is_array($parents) && count($parents)) {
  $parent = array_pop($parents);
  
  $variables['parent_tid'] = intval($parent->tid);
  $variables['parent_slug'] = StringTools::slug($parent->name, "-");
  $variables['parent_call_name'] = StringTools::slug($parent->name, "__");
}

echo theme($variables['parent_call_name'].'__comment', $variables);
