<?php
echo theme('right_box_most_useful', array('parent_slug' => $parent_slug, 'nid' => $elements['#node']->nid));

echo theme('right_box_saved', array('parent_slug' => $parent_slug, 'nid' => $elements['#node']->nid));
