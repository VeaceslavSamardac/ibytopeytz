<?php
echo theme('right_box_followers', array('parent_slug' => $parent_slug, 'nid' => $elements['#node']->nid));

echo theme('right_box_related', array('parent_slug' => $parent_slug, 'nid' => $elements['#node']->nid));

echo theme('right_box_tags', array('parent_slug' => $parent_slug, 'nid' => $elements['#node']->nid));
