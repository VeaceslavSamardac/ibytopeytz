<?php
if(isset($_COOKIE['view_option']) && !isset($_GET['view'])) {
  $_GET['view'] = $_COOKIE['view_option'];
} elseif(isset($_GET['view'])) {
  setcookie('view_option', $_GET['view'], time()+(60*60*24*365));
}

$base_link = $_SERVER['REQUEST_URI'];
if(strpos($base_link, "?")) {
  $link_parts = explode("?", $base_link);
  $base_link = $link_parts[0];
}
echo '<div class="iby_filters_view_box">';
echo '<ul class="iby_filters_show_list" style="list-style:none;margin:0;padding:0;">'."\n ";
echo '<li style="float:left;margin:0;padding:0;">Show: </li>'."\n";

echo '<li style="float:left;margin:0;padding:0;margin-left:15px;"><a href="'.$base_link.'#tags" '.((!isset($_GET['o'])) ? 'class="current_filter_option"' : "").'>Most recent</a></li>'."\n";
echo '<li style="float:left;margin:0;padding:0;margin-left:15px;"><a href="'.$base_link.'?o=contributions#tags" '.((isset($_GET['o']) && ($_GET['o'] == 'contributions')) ? 'class="current_filter_option"' : "").'>Most contributions</a></li>'."\n";
echo '<li style="float:left;margin:0;padding:0;margin-left:15px;"><a href="'.$base_link.'?o=view#tags" '.((isset($_GET['o']) && ($_GET['o'] == 'view')) ? 'class="current_filter_option"' : "").'>Most viewed</a></li>'."\n";
echo '<li style="float:left;margin:0;padding:0;margin-left:15px;"><a href="'.$base_link.'?o=follow#tags" '.((isset($_GET['o']) && ($_GET['o'] == 'follow')) ? 'class="current_filter_option"' : "").'>Most followed</a></li>'."\n";
//echo '<li style="float:left;margin:0;padding:0;margin-left:15px;"><a href="'.$base_link.'?o=like#tags" '.((isset($_GET['o']) && ($_GET['o'] == 'like')) ? 'class="current_filter_option"' : "").'>Most liked</a></li>'."\n";
echo '</ul>'."\n"; ?>
<ul class="iby_filters_view_options" style="list-style:none;margin:0;padding:0;float:right;">
	<li style="float:left;margin:0;padding:0;margin-left:15px;margin-top:">View As: </li>
	<li style="float:left;margin:0;padding:0;margin-left:15px;margin-top:">
		<a href="<?php $_SERVER['SCRIPT_NAME'];?>?view=grid" class="<?php echo ((isset($_GET['view']) && ($_GET['view'] == 'grid') || (!isset($_GET['view']))) ? 'iby_filters_view_grid_current' : "iby_filters_view_grid") ?>">Grid</a> 
	</li>
	<li style="float:left;margin:0;padding:0;margin-left:15px;margin-top:">
		<a href="<?php $_SERVER['SCRIPT_NAME'];?>?view=list" class="<?php echo ((isset($_GET['view']) && ($_GET['view'] == 'list')) ? 'iby_filters_view_list_current' : "iby_filters_view_list") ?>">List</a>
	</li>
</ul>
<?php
echo '</div>';
echo '<div style="clear:both;"></div>'."\n";
?>
<div class="challenges-divider ibyforum-divider" style="border-top: 1px dotted #666; margin: 10px 0px; height: 1px;"></div>
<div style="clear:both;"></div>
