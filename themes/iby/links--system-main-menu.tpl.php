<?php
$active_trail = menu_get_active_trail();

$top_forum_tid = false;

if(isset($active_trail[1]['href'])) {
  if(preg_match("/forum\/(\d+)/", $active_trail[1]['href'], $match)) {
    $active_forum_tid = $match[1];
    $top_forum_tid = _iby_forums_get_parent_tid($active_forum_tid);
  } elseif(preg_match("/node\/(\d+)/", $active_trail[1]['href'], $match)) {
    $active_nid = $match[1];
    $top_forum_tid = _iby_forums_get_parent_tid_by_nid($active_nid);
  }
}

//echo "<pre>"; var_dump($variables); echo "</pre>"; exit;
//
//echo "<pre>"; var_dump($active_trail); echo "</pre>"; exit;
//
?>
<ul id="main-menu" class="links clearfix">
  <li class="<?php echo ((isset($active_trail[1]['href']) && ($active_trail[1]['href'] == "dashboard"))?"active ":"");?>first"><a href="/">Home</a></li>
  <li<?php echo (($top_forum_tid == 2)? ' class="active"' : "");?>><a href="/forum/2" title="Challenges">Challenges</a></li>
  <li<?php echo (($top_forum_tid == 92)? ' class="active"' : "");?>><a href="/forum/92" title="Forums">Forums</a></li>
  <li<?php echo (($top_forum_tid == 40)? ' class="active"' : "");?>><a href="/forum/40" title="Tips &amp; Tricks">Tips &amp; Tricks</a></li>
  <li class="<?php echo (($top_forum_tid == 5)?"active ":"");?>last"><a href="/forum/5" title="VIP Rooms">VIP Rooms</a></li>
</ul>
