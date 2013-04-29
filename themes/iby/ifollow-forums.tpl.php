    <div class="tips-tricks tips-tricks-topic tips-tricks-grid ibyforum box-shadow type-<?php echo (isset($topic->field_type[$topic->language][0]['value'])?$topic->field_type[$topic->language][0]['value']:'all');?>" id="<?php echo $parent_slug.$forum->tid;?>">
      <div class="tips-tricks-bg ibyforum-bg">
        <div class="forum-headline">
          <a href="/node/<?php echo $topic->nid;?>">Problem <?php echo strtolower($topic->title);?></a>
          <br /><?php echo date("F d, Y | h:i a", $topic->created);?>
        </div>
        <div class="tips-tricks-description ibyforum-description"><?php echo substr(strip_tags($topic->body[$topic->language][0]['value']), 0, 100);?></div>

        <div class="tips-tricks-divider ibyforum-divider"></div>

        <div class="tips-tricks-stats ibyforum-stats">
<?php
      if(isset($topic->field_tags) && is_array($topic->field_tags) && count($topic->field_tags)) {
        $str = "";
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
