<?php
drupal_add_css(sqtools_default_theme_path()."/css/".$variables['parent_slug'].".css", array('options' => "file"));

// Breadcrumb navigation:
$breadcrumb[] = l(t('Home'), NULL);
if ($variables['parents']) {
  $variables['parents'] = array_reverse($variables['parents']);
  foreach ($variables['parents'] as $p) {
    if ($p->tid == $variables['tid']) {
      $title = $p->name;
      $breadcrumb[] = $p->name;
      
    } else {
      $breadcrumb[] = l($p->name, 'forum/' . $p->tid);
    }
  }
}

$breadcrumb[] = l($forum->name, 'forum/' . $forum->tid);

drupal_set_breadcrumb($breadcrumb);
drupal_set_title($title);

echo theme('vip__rooms__menu', array('forum_tid' => $forum->tid));

?>
<div style="width:805px;">

<div style="width:550px;float:left;margin-right:20px;">
<?php

if(is_array($members)) {
  foreach($members as $member) {
    echo theme('user_preview_box', array('forum_tid' => $forum->tid, 'member' => $member));
  }
}

?>

<div style="clear:both;"></div>

</div>

<div id="<?php echo $parent_slug;?>-front-right" style="margin-top:24px;">
<?php
echo theme('right_box_recent_activity', array('parent_slug' => $parent_slug, 'tid' => arg(1)));
?>
  <div style="clear:both;"></div>

</div>
<div style="clear:both;"></div>

</div>

<?php if(_iby_forums_check_access(false, false, 'forum', 'create', false, $forum->tid)): ?>

<h3>Add new member:</h3>
<?php
echo render(drupal_get_form('forum_members_form'));
?>
&nbsp;<br />
<?php

endif;