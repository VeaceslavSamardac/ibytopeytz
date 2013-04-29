<?php
$variables['parent_tid'] = 0;
$variables['parent_slug'] = 'ibyforum';
$variables['parent_call_name'] = 'ibyforum';

if(isset($parents) && is_array($parents) && count($parents)) {
  $variables['parent_tid'] = intval($variables['parents'][0]->tid);
  $variables['parent_slug'] = StringTools::slug($variables['parents'][0]->name, "-");
  $variables['parent_call_name'] = StringTools::slug($variables['parents'][0]->name, "__");
}

echo theme($variables['parent_call_name'].'__view', $variables);
