<?php
$forum_tid = arg(3);

$parent_tid = _iby_forums_get_parent_tid($forum_tid);

$parent_forum = taxonomy_term_load($parent_tid);

$parent_slug = StringTools::slug($parent_forum->name, "__");

echo theme($parent_slug.'__node__add', $variables);
