<?php
$query = db_select('comment', 'c');
$query->condition('c.status', 1);
$query->fields('c', array('cid'));
$query->addExpression('SUM(fc.total)', 'likes');
$query->condition('c.nid', $topic->nid);
$query->leftJoin('iby_forum_flags_cache', 'fc', "fc.entity_type='comment' AND fc.flag_type='like' AND fc.entity_id=c.cid");
$query->groupBy('c.cid');
$query->orderBy('likes', 'DESC');
$query->range(0,1);
$top_comment = $query->execute()->fetchAssoc();

$cid = $top_comment['cid'];
$likes = intval($top_comment['likes']);

$top_comment = entity_load('comment', array($cid));
$top_comment = $top_comment[$cid];

$query = db_select('node', 'n');
$query->fields('n', array('created'));
$query->condition('n.nid', $topic->nid);
$node_created = $query->execute()->fetchField(0);
?>
    <div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-topic <?php echo $parent_slug;?>-list ibyforum box-shadow type-<?php echo (($topic->field_type[$topic->language][0]['value'])?$topic->field_type[$topic->language][0]['value']:'all');?>" id="<?php echo $parent_slug.$forum->tid;?>">
      <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">
        <div class="forum-headline">
          <a class="<?php echo $parent_slug;?>-add" href="/node/<?php echo $topic->nid;?>">Add to this tip</a>
          <a href="/node/<?php echo $topic->nid;?>" style="overflow:hidden;height:18px;display:block;">Problem: <?php echo StringTools::shorten(strip_tags($topic->title), 38, "...");?></a>
          <?php echo date("F d, Y | h:i a", $node_created);?>
        </div>
        <div class="<?php echo $parent_slug;?>-description ibyforum-description">
          <div class="<?php echo $parent_slug;?>-action-box ibyforum-action-box like-comment-<?php echo $cid;?>">

          <span class="count-number"><?php echo $likes; ?></span> people found this tip useful<br />
<?php
$args = array('flag_type' => "like", 'uid' => $user->uid, 'entity_group' => "topic", 'entity_type' => "comment", 'entity_id' => $cid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>

            <a href="/sq_flags_api/toggle/like/topic/comment/<?php echo $cid;?>?sq_flags_api_cb=sq_flags_cb" id="like_topic_<?php echo $cid;?>" class="use-ajax sq-like-link <?php echo $parent_slug;?>-useful <?php echo $flag_class;?>">I found this tip useful</a>
          </div>

          <h2 class="solution-label">Solution: </h2>
<?php
  echo StringTools::striptags($top_comment->comment_body[$top_comment->language][0]['value']);
?>
        </div>

        <div style="clear:both;"></div>

        <div class="<?php echo $parent_slug;?>-tags">
<?php
      if(isset($topic->field_tags) && is_array($topic->field_tags) && count($topic->field_tags)) {
        $str = "Tags:<br />";
        foreach($topic->field_tags['und'] as $tag) {
          $term = taxonomy_term_load($tag['tid']);
          $str .= $term->name.', ';
        }
        echo substr($str, 0, -2);
      }
?>
          <div style="clear:both;"></div>
        </div>

        <div style="clear:both;"></div>

        <div class="<?php echo $parent_slug;?>-stats ibyforum-stats">
<?php
$args = array('flag_type' => "follow", 'uid' => $user->uid, 'flag_group' => "topic", 'entity_type' => "node", 'entity_id' => $topic->nid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
          <div class="rounded-button comment-button">
            <a href="/sq_flags_api/toggle/follow/topic/node/<?php echo $topic->nid;?>?sq_flags_api_cb=refresh_page" class="use-ajax <?php echo $flag_class;?>">
  <?php echo (($is_flagged) ? "Remove this tip from my list" : "Save to my Tips & Tricks");?></a>
          </div>
          <div style="clear:both;"></div>
        </div>

      </div>

      <div style="clear:both;"></div>
    </div>
