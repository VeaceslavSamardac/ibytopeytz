<?php
//echo $page['content']['system_main']['main']['#markup'];
echo drupal_render($page['content']);//['content']['system_main']['comment_form']);//['#markup'];

// The use of exit; is usefull, when you want to stop the template hierachy chain
// Meaning: html.tpl.php, html--node.tpl.php, etc. won't get loaded! ;)
// Otherwise you'll have to make sure, that you have something like html--node.php with only "echo $page;"
// The only problem is, that some hooks will not be called either...
?>