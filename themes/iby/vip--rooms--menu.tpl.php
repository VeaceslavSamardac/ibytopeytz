<?php
//echo $forum_tid;
?>
<?php
//echo "-->" . arg(3);
?>
<ul id="user-menu" class="links clearfix">
  <li><a href="/forum/<?php echo $forum_tid;?>" <?php echo ((arg(2) == '') ? 'id="current"' : ''); ?>>Team dialogue</a></li>
  <li><a href="/forum/<?php echo $forum_tid;?>/files" <?php echo ((arg(2) == 'files') ? 'id="current"' : ''); ?>>Shared Files</a></li>
  <li><a href="/forum/<?php echo $forum_tid;?>/members" <?php echo ((arg(2) == 'members') ? 'id="current"' : ''); ?>>Team members</a></li>
</ul>
