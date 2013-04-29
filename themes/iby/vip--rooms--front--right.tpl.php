<?php
echo theme('right_box_newest_members', array('parent_slug' => $parent_slug, 'tid' => arg(1)));

echo theme('right_box_recent_activity', array('parent_slug' => $parent_slug, 'tid' => arg(1)));
