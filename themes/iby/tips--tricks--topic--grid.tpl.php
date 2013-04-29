<?php
$query = db_select('node', 'n');
$query->fields('n', array('created'));
$query->condition('n.nid', $topic->nid);
$node_created = $query->execute()->fetchField(0);
?>
    <div class="<?php echo $parent_slug;?> <?php echo $parent_slug;?>-topic <?php echo $parent_slug;?>-grid ibyforum box-shadow type-<?php echo (isset($topic->field_type[$topic->language][0]['value'])?$topic->field_type[$topic->language][0]['value']:'all');?>" id="<?php echo $parent_slug.$forum->tid;?>">
      <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">
        <div class="forum-headline">
  <a href="/node/<?php echo $topic->nid;?>"><?php echo StringTools::shorten(strip_tags($topic->title), 38, "...");?></a>
          <p><?php echo date("F d, Y | h:i a", $node_created);?></p>
        </div>
          <div class="<?php echo $parent_slug;?>-description ibyforum-description"><?php echo StringTools::shorten(StringTools::striptags($topic->body[$topic->language][0]['value']), 100, "...");?></div>

        <div class="<?php echo $parent_slug;?>-divider ibyforum-divider"></div>

        <div class="<?php echo $parent_slug;?>-stats ibyforum-stats">
<?php
      if(isset($topic->field_tags) && is_array($topic->field_tags) && count($topic->field_tags)) {
        $str = "Tags: ";
        foreach($topic->field_tags[$topic->language] as $tag) {
          $term = taxonomy_term_load($tag['tid']);
          $str .= $term->name.', ';
        }
        echo substr($str, 0, -2);
      }
?>
          <div style="clear:both;"></div>
        </div>

        <div class="rounded-button comment-button"><a href="/node/<?php echo $topic->nid;?>/new_comment">Submit solution</a></div>

       <div style="clear:both;"></div>
      </div>
      <div style="clear:both;"></div>
    </div>

