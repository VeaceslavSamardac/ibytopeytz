    <div class="<?php echo $parent_slug;?> ibyforum box-shadow <?php echo $parent_slug;?>-forum type-<?php echo $forum->field_type['und'][0]['value'];?>" id="<?php echo $parent_slug.$forum->tid;?>"<?php echo (($last_in_row) ? ' style="margin-right:0;"': '');?>>
      <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">
        <div class="forum-headline"><a href="<?php echo $forum->link;?>"><?php echo mb_substr($forum->name,0,20);?></a></div>
        <div class="<?php echo $parent_slug;?>-description ibyforum-description">
          <a href="<?php echo $forum->link;?>">
<?php echo StringTools::shorten(trim(StringTools::striptags($forum->description)), 140, "...");?>
          </a>
        </div>
		<?php
			  
			$query = db_select('forum', 'f');
			$query->join('node', 'n', 'n.status = 1 AND n.nid = f.nid');
			$query->addExpression('COUNT(*)', 'topics');
			$query->condition('f.tid', $forum->tid);
			//echo $query->__toString();
				
      $totaltopics = intval($query->execute()->fetchField(0));

			$totalposts = db_query("
			SELECT SUM(comment_count)
			FROM forum, node_comment_statistics, node
			WHERE forum.nid = node_comment_statistics.nid AND node.nid = forum.nid AND node.status = 1 AND forum.tid = {$forum->tid}
			")->fetchField();
      $totalposts = intval($totalposts);
		?>

        <div class="<?php echo $parent_slug;?>-divider ibyforum-divider"></div>

        <div class="<?php echo $parent_slug;?>-stats ibyforum-stats">
          <div class="topics-count"><span><?php echo $totaltopics; ?> </span> topics</div>
          <div class="stats-divider">&nbsp;</div>
          <div class="posts-count"><span><?php echo $totalposts; ?> </span> posts</div>
        </div>

        <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
    </div>
