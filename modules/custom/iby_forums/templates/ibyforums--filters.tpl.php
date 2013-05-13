<?php
$tags_tids = array();
$query_cache = &drupal_static('iby_forum_tagging', array());
if(isset($query_cache['tags_tids'])) $tags_tids = $query_cache['tags_tids'];

$max_font = 21;
$min_font = 12;
$font_diff = ($max_font - $min_font);

if(isset($tags->info['diff']) && $tags->info['diff']) {
  $font_step = ($font_diff / $tags->info['diff']);
}
else $font_step = 0;

?>
<a name="tags"></a>

<div style="clear:both;"></div>
<div class="iby_filters_tag_options">
	<ul class="iby_filters_tag_headline" style="list-style:none;margin:0;padding:0;">
    <li style="float:left;margin:0;padding:0;"><h3 style="font-size:15px;color:#00B0CA;"><?php echo (isset($variables['tagHeadline'])?$variables['tagHeadline']:'')?></h3></li>
	</ul>
	<ul class="iby_filters_tag_option" style="list-style:none;margin:0;padding:0;float:right;">
	 	<li style="float:left;margin:0;padding:0;margin-left:15px; margin-top: 5px;">
			<?php echo '<a href="/forum/'.$forum_id.'#tags" class="iby_filters_tag_option_reset">reset choices</a>';?>
		</li>
    <li style="float:left;margin:0;padding:0;margin-left:12px; margin-top: 5px; padding-left: 10px; border-left:1px solid #666;">
			<a href="#tags" id="iby_filters_tag_option_more" class="iby_filters_tag_option_more">show all tags</a>
		</li>
	</ul>
  <div style="clear:both;"></div>
</div>

<div style="clear:both;"></div>

<?php /* ?>
<div id="tags_search" style="float:right;height:0px;overflow:hidden;">
<?php
$search_form = drupal_get_form('forum_tags_search_form', NULL);
echo render($search_form);
?>
</div>
<?php */ ?>

<div style="clear:both;"></div>

<div class="challenges-divider ibyforum-divider"></div>
<div style="color:#818485;font-size:12px;font-weight:bold;margin-top:5px; padding-top:10px; margin-bottom:0px; border-top:1px dotted #666666;">Filter by tags:</div>


<div class="tag-cloud-container" id="tag-cloud-container">
  <div class="tag-cloud" id="tag-cloud">
<?php

foreach($tags->tags as $tag) {
  $selected = (in_array($tag->tid, $tags_tids) ? true : false );
  if($tag->count > 1) $font_size = intval($min_font + (($tag->count - $tags->info['min-count']) * $font_step));
  else $font_size = $min_font;
  $margin_top = intval(($max_font - $font_size) + 1);

  $url_tids = array_flip($tags_tids);
  if(isset($url_tids[$tag->tid])) unset($url_tids[$tag->tid]);
  else $url_tids[$tag->tid] = true;
  $link = "/forum/".$forum_id;
  //if(count($url_tids)) $link .= "/tags/".$tag->tid;//implode("/", array_keys($url_tids));
  if(count($url_tids)) $link .= "/tags/".implode("/", array_keys($url_tids));

  echo '<div class="tag-cloud-tag'.(($selected)?' selected':'').'" style="margin-top:'.$margin_top.'px;line-height:'.$font_size.'px;font-size:'.$font_size.'px;">';

  // Limit allowed tags to 3, in order for performance to berelatively o.k...
  if((count($tags_tids) > 2) && !$selected){ 
    echo '<div class="linktext">'.preg_replace("/\s+/s", "&nbsp;", trim($tag->name)).'</div>';
  } else {
    echo '<a class="linktext" href="'.$link.'#tags">'.preg_replace("/\s+/s", "&nbsp;", trim($tag->name)).'</a>';
  }

  if($selected) {
    echo '
      <a class="linkimage" id="tag' . $tag->tid . '" href="' . $link . '#tags">
        <img src="/' . drupal_get_path('theme', 'iby') . '/images/close_button.png" />
      </a>
    ';
    echo '<script type="text/javascript">var pos_left = jQuery("#tag'.$tag->tid.'").parent().width(); jQuery("#tag'.$tag->tid.'").css("left", (pos_left-15));</script>';
  }
  echo '</div>';
}
echo '    <div style="clear:both;"></div>'."\n";
echo '  </div>'."\n";
echo '  <div style="clear:both;"></div>'."\n";
echo '</div>'."\n";

echo '<div class="challenges-divider ibyforum-divider"></div>'."\n";

echo '<div style="clear:both;"></div>'."\n";

echo '<div class="challenges-divider ibyforum-divider" style="border-top: 1px dotted #666; padding-top:10px; height: 1px;"></div>'."\n";
