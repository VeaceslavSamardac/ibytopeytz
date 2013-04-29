<?php
drupal_add_css(sqtools_default_theme_path()."/css/".$variables['parent_slug'].".css", array('options' => "file"));

if(_iby_forums_check_access(false, false, "forum", "edit", arg(1))):
//if(user_access('administer')) :
?>
<!--<a href="/forum/add/forum/forums?parent=<?php echo $parent_tid;?>&show_ajax=1" class="sq-ajax-link">Add a new <?php echo $parent_slug;?></a>-->
<?php endif; ?>

<div class="<?php echo $parent_slug;?>-topbar">
  <div class="<?php echo $parent_slug;?>-topbar-left">Active challenges</div>


  <div class="<?php echo $parent_slug;?>-topbar-right">
    <div class="<?php echo $parent_slug;?>-area-label">Select area:</div> 
<?php
$base_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$types = array('all', 'continence', 'ostomy');
$current_type = 'all';
if(isset($_GET['type']) && in_array($_GET['type'], $types)) $current_type = $_GET['type'];
foreach($types as $type) {
  echo '    <a href="'.UrlTools::addParams($base_url, array('type'=>$type)).'" class="ibyforum-type-'.$type.' '.$parent_slug.'-type-'.$type;
  if($current_type == $type) echo ' ibyforum-type-active '.$parent_slug.'-type-active';
  echo '">'.ucfirst($type).'</a>'."\n";
}
?>
  </div>
</div>

<div style="clear:both;"></div>

<div id="<?php echo $parent_slug;?>-active" class="ibyforum-active">
  <div id="<?php echo $parent_slug;?>-content" class="ibyforum-content">
	<div class="fp-scrollable">
		<div class="items">
<?php
$index = 2;
foreach($forums_active as $child_id => $forum):
  echo (($index%2) ? "" : "<div class=\"challengesList\">");
  echo theme($variables['parent_call_name'].'__active', $variables + array('forum' => &$forum));
  echo (($index%2) ? "</div>" : "");
	$index++;
endforeach;
echo (($index%2) ? "</div>" : "");

?>
	
		</div>
	</div>
	<div style="clear:both;"></div>
		<div class="nav-slides">
			<!-- "previous page" action -->
			<a class="prev browse left"></a>
			<!-- wrapper for navigator elements -->
			<span class="navi1 navi clearfix"></span>
			<!-- "next page" action -->
			<a class="next browse right"></a>
		</div>
      <div style="clear:both;"></div>

<script type="text/javascript">
            var scrollable = new Array();
            scrollable[0] = jQuery(".fp-scrollable").scrollable({circular: true}).navigator();//.autoscroll();
//jQuery(".fp-scrollable").scrollable({ circular: true }).click(function() {
//	jQuery(this).data("scrollable").next();		
//});
</script>

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

$tags = iby_forums_get_forums_filter_tags($forum_tid);

echo theme('challenges__filters', $variables + array('tags' => $tags, 'tagHeadline' => 'Ended challenges'));
echo theme('challenges__sorting');
?>

<a name="pager-marker"></a>
<div id="<?php echo $parent_slug;?>-ended" class="ibyforum-ended">
  <div id="<?php echo $parent_slug;?>-content" class="ibyforum-content">
  
<?php
$index = 0;
foreach($forums_ended as $child_id => $forum):
  if(isset($_GET['view']) && ($_GET['view'] == "list")) echo theme($variables['parent_call_name'].'__list', $variables + array('forum' => &$forum));
  else echo theme($variables['parent_call_name'].'__grid', $variables + array('forum' => &$forum));
  $index++;
endforeach;

?>

<div style="clear:both;">&nbsp;</div>

<?php echo theme('pager', array('element'=>0)); ?>

    <div style="clear:both;"></div>
  </div> <!-- #<?php echo $parent_slug;?>-content -->

</div> <!-- #current-<?php echo $parent_slug;?> -->