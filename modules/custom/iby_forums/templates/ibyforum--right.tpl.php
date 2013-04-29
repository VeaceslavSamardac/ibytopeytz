<?php
if($_SERVER['SERVER_NAME'] == "iby.localhost") echo "<!-- ibyforum--right.tpl.php -->\n";
?>
    <div class="<?php echo $parent_slug;?> ibyforum box-shadow <?php echo $parent_slug;?>-right ibyforum-right <?php echo $zebra;?>" id="<?php echo $parent_slug;?><?php echo $forum->tid;?>">
      <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">

        <div class="<?php echo $parent_slug;?>-number ibyforum-number"><?php echo $forum->name;?></div>

        <div class="<?php echo $parent_slug;?>-image ibyforum-image">
          <img src="/<?php echo drupal_get_path('module', 'iby_forums')."/images/forum_image_grid.jpg";?>" />
        </div>

        <div class="<?php echo $parent_slug;?>-name ibyforum-name"><?php echo $forum->name;?></div>

        <div class="<?php echo $parent_slug;?>-short ibyforum-short"><?php echo nl2br(substr($forum->description, 0, 200));?>...</div>

        <div class="<?php echo $parent_slug;?>-info ibyforum-info">
          <div class="rounded-button <?php echo $parent_slug;?>-view ibyforum-view"><a href="/<?php echo (isset($forum->uri['path'])?$forum->uri['path']:"");?>">Read more</a></div>
          <div class="rounded-button <?php echo $parent_slug;?>-contribute ibyforum-contribute"><a href="/node/add/<?php echo (isset($forum->uri['path'])?$forum->uri['path']:"");?>" class="iby-ajax-link">Submit contribution</a></div>
          <div style="clear:both;"></div>
        </div>

        <div style="clear:both;">&nbsp;</div>
      </div>
      <div style="clear:both;"></div>
    </div>
