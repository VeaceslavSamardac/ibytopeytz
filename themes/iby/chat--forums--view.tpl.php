<?php
drupal_add_css(sqtools_default_theme_path()."/css/".$variables['parent_slug'].".css", array('options' => "file"));
?>

<!-- <div class="rounded-button comment-button"><a href="/forum/add/forum/forums?parent=<?php echo $parent_tid;?>&show_ajax=1" class="sq-ajax-link">Add a new forum</a></div>
&nbsp;<br /> -->

<div class="<?php echo $parent_slug;?>-large-box box-shadow">
<?php
$lang_value = sqtools_get_lang_value($parents[0]->field_subtitle);
$title = strip_tags($lang_value[0]['value']);
?>
  <h2><?php echo $title;?></h2>

  <div style="clear:both;"></div>

  <div class="<?php echo $parent_slug;?>-top-divider ibyforum-top-divider">&nbsp;</div>

  <div style="clear:both;"></div>

  <div class="<?php echo $parent_slug;?>-texts">

    <div class="<?php echo $parent_slug;?>-texts-left">
  <?php echo $parents[0]->description; ?>
      <div style="clear:both;"></div>
    </div>

    <div class="<?php echo $parent_slug;?>-texts-right">
  &nbsp;
      <div style="clear:both;"></div>
    </div>

    <div style="clear:both;"></div>
  </div>

  <div style="clear:both;"></div>
</div>

  <div style="clear:both;"></div>

<div class="<?php echo $parent_slug;?>-topbar">
  <div class="<?php echo $parent_slug;?>-topbar-left">
Forums
  </div>

  <div class="<?php echo $parent_slug;?>-topbar-right">
<?php
$base_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$types = array('continence', 'ostomy', 'all');
$current_type = 'all';
if(isset($_GET['type']) && in_array($_GET['type'], $types)) $current_type = $_GET['type'];
foreach($types as $type) {
  echo '    <a href="'.UrlTools::addParams($base_url, array('type'=>$type)).'" class="ibyforum-type-'.$type.' '.$parent_slug.'-type-'.$type;
  if($current_type == $type) echo ' ibyforum-type-active '.$parent_slug.'-type-active';
  echo '" style="float:right;">'.ucfirst($type).'</a>'."\n";
}
?>
    <div class="<?php echo $parent_slug;?>-area-label" style="float:right;">Select area:</div> 
  </div>
</div>

  <div style="clear:both;"></div>

<div id="<?php echo $parent_slug;?>-forums" class="ibyforum-forums">
  <div id="<?php echo $parent_slug;?>-content" class="ibyforum-content">
<?php
$i = 1;
foreach($chat_forums as $child_id => $forum):
echo theme($variables['parent_call_name'].'__forum', $variables + array('forum' => &$forum, 'last_in_row' => (($i%4)?false:true) ));
$i++;
endforeach;
?>
    <div style="clear:both;"></div>
  </div> <!-- #<?php echo $parent_slug;?>-content -->

  <div style="clear:both;"></div>

</div> <!-- #current-<?php echo $parent_slug;?> -->

<div style="clear:both;"></div>
<?php
if($forums) $forum_tid = $parent_tid;
else {
  $parent_index = count($parents);
  $parent_index--;
  $forum_tid = $parents[$parent_index]->tid;
}

$tags = iby_forums_get_nodes_filter_tags($forum_tid);

echo theme('ibyforums__filters', $variables + array('tags' => $tags, 'tagHeadline' => 'Latest posts'));
echo theme('ibyforums__sorting');
?>

<a name="pager-marker"></a>
<div id="<?php echo $parent_slug;?>-content" class="ibyforum-content">
<?php
  $index = 0;

$index = 0;
foreach($topics as $child_id => $topic):
  if(isset($_GET['view']) && ($_GET['view'] == "list")) echo theme($parent_call_name.'__topic__list', $variables + array('topic' => &$topic));
  else echo theme($parent_call_name.'__topic__grid', $variables + array('first_in_row' => (!($index%3)), 'topic' => &$topic) );
  $index++;
endforeach;
?>
</div> <!-- #<?php echo $parent_slug;?>-content -->

<?php echo theme('pager', array('element'=>0)); ?>

  <div style="clear:both;"></div>
