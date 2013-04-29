<?php
if($_SERVER['SERVER_NAME'] == "iby.localhost") echo "<!-- ibyforum--view.tpl.php -->\n";

drupal_add_css(sqtools_default_theme_path()."/css/".$variables['parent_slug'].".css", array('options' => "file"));

//$forums_active = array_slice($forums, 0, 2);
//$forums_ended = array_slice($forums, 2);

?>
<div class="rounded-button comment-button"><a href="/forum/add/forum/forums?parent=<?php echo $parent_tid;?>" class="sq-ajax-link">Add a new <?php echo $parent_slug;?></a></div>

<div id="<?php echo $parent_slug;?>-active" class="ibyforum-active">
  <div id="<?php echo $parent_slug;?>-content" class="ibyforum-content">
<?php
foreach($forums_active as $child_id => $forum):
  echo theme('ibyforum__active', $variables + array('forum' => &$forum));
endforeach;
?>
    <div style="clear:both;"></div>
  </div> <!-- #<?php echo $parent_slug;?>-content -->

  <div style="clear:both;"></div>

</div> <!-- #current-<?php echo $parent_slug;?> -->

<div style="clear:both;"></div>

<?php
  echo theme('ibyforums__filters', $variables);
?>

<div id="<?php echo $parent_slug;?>-ended" class="ibyforum-ended">
  <div id="<?php echo $parent_slug;?>-content" class="ibyforum-content">
<?php
  $index = 0;
foreach($forums_ended as $child_id => $forum):
  if(isset($_GET['view']) && ($_GET['view'] == "list")) echo theme('ibyforum__list', $variables + array('forum' => &$forum));
  else echo theme('ibyforum__grid', $variables + array('forum' => &$forum, 'first_in_row'=>(!($index%3))) );
  $index++;
endforeach;
?>
    <div style="clear:both;"></div>
  </div> <!-- #<?php echo $parent_slug;?>-content -->

</div> <!-- #current-<?php echo $parent_slug;?> -->

&nbsp;<br />
&nbsp;<br />
&nbsp;<br />
