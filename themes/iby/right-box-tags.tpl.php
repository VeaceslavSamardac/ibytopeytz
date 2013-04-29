<?php
$tags_tids = array();
if(isset($_GET['q']) && strpos($_GET['q'], 'tags')) {
  // Now we expand the tag_ids into an array
  $q_parts = explode("tags/", $_GET['q']);
  if(count($q_parts) > 1) {
    $tags_tids = explode("/", $q_parts[1]);
  }
}

$tags = iby_forums_get_comments_filter_tags($nid);

if(count($tags->tags)):
?>
<div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-right-box ibyforum-right-box">

  <a name="tags_box"></a>
  <h3>Tags in this thread</h3>

  <div class="<?php echo $parent_slug;?>-divider divider"></div>

  <div class="thread-tags">

<?php
$max_font = 21;
$min_font = 12;
$font_diff = ($max_font - $min_font);

if($tags->info['diff']) $font_step = ($font_diff / $tags->info['diff']);
else $font_step = 0;

echo '  <ul class="tag-box">'."\n";
foreach($tags->tags as $tag) {
  $selected = (in_array($tag->tid, $tags_tids) ? true : false );
  if($tag->count > 1) $font_size = intval($min_font + (($tag->count-1) * $font_step));
  else $font_size = $min_font;
  $margin_top = intval($max_font - $font_size);

  $url_tids = array_flip($tags_tids);
  if(isset($url_tids[$tag->tid])) unset($url_tids[$tag->tid]);
  else $url_tids[$tag->tid] = true;
  $link = "/node/".$nid;
  if(count($url_tids)) $link .= "/tags/".implode("/", array_keys($url_tids));

  echo '    <li '.(($selected)?'class="selected"':'').' style="margin-top:'.$margin_top.'px;font-size:'.$font_size.'px;">';
  echo '<a class="linktext" href="'.$link.'#tags_box">'.$tag->name.'</a>';
  if($selected) {
    echo '<a class="linkimage" id="tag'.$tag->tid.'" href="'.$link.'#tags_box"><img src="/sites/all/themes/iby/images/close_button.png" /></a>';
    echo '<script type="text/javascript">var pos_left = jQuery("#tag'.$tag->tid.'").parent().width(); jQuery("#tag'.$tag->tid.'").css("left", (pos_left-15));</script>';
  }
  echo '    </li>';
}
echo '  </ul>'."\n";
echo '  <div style="clear:both;"></div>'."\n";
?>

  </div>

  <div class="<?php echo $parent_slug;?>-divider divider"></div>
    <div style="clear:both;"></div>
  <div class="iby_filters_tag_options">
	<ul class="iby_filters_tag_headline" style="list-style:none;margin:0;padding:0;">
  <li style="float:left;margin:0;padding:0;"><h3><?php echo (isset($variables['tagHeadline'])?$variables['tagHeadline']:'')?></h3></li>
	</ul>
</div>
  
  <div style="clear:both;"></div>
</div>

  <div style="clear:both;">&nbsp;</div>
  <div style="clear:both;">&nbsp;</div>

<?php
  endif;
?>