<?php
if($_SERVER['SERVER_NAME'] == "iby.localhost") echo "<!-- ibyforum--members.tpl.php -->\n";


$variables['parent_tid'] = intval($variables['parents'][0]->tid);
$variables['parent_slug'] = StringTools::slug($variables['parents'][0]->name, "-");
$variables['parent_call_name'] = StringTools::slug($variables['parents'][0]->name, "__");

echo theme($variables['parent_call_name'].'__members', $variables);


