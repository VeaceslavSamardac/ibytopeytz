<?php
$is_sticky = max($topic->field_sticky['und'][0]['value'], $topic->field_sticky_global['und'][0]['value']);

$query = db_select('node', 'n');
$query->fields('n', array('created'));
$query->condition('n.nid', $topic->nid);
$node_created = $query->execute()->fetchField(0);
?>
<div class="<?php echo $parent_slug;?> box-shadow <?php echo (($is_sticky) ? 'sticky ':'');?><?php echo $parent_slug;?>-topic-list ibyforum-topic-list" id="<?php echo $parent_slug;?><?php echo $topic->nid;?>">
  <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">

  <div class="<?php echo $parent_slug;?>-number ibyforum-number"><h2><?php echo StringTools::shorten(strip_tags($topic->title), 60, "...");?></h2></div>

    <div class="<?php echo $parent_slug;?>-topic-list-divider ibyforum-topic-list-divider"></div>

<?php
$topicOwner = user_load($topic->owner_uid);
if($topicOwner) {
  echo '<div class="'.$parent_slug.'-owner ibyforum-owner">';
  
  echo '  <div class="owner-info">';
  echo '    <div class="owner-name">Created by <a class="owner-name" href="/user/'.$topicOwner->uid.'">'.$topicOwner->name.'</a> | '.date("d M Y h:i a", $node_created).'</div>';
  echo '    <div style="clear:both;"></div>';
  echo '  </div>';
  
  echo '  <div style="clear:both;"></div>';
  
  echo '</div>';
  
  echo '<div style="clear:both;"></div>';
  
  echo '<div class="owner-image">';
  if($topicOwner->picture) { echo theme('image', array('path' => file_create_url($topicOwner->picture->uri), 'height' => '26px')); }
  echo '</div>';
}
?>
    <div class="<?php echo $parent_slug;?>-body ibyforum-body"><p style="margin-top:0px;"><?php echo StringTools::shorten(strip_tags($topic->body['und'][0]['value']), 250);?></p></div>

    <div style="clear:both;"></div>

    <div class="<?php echo $parent_slug;?>-tags ibyforum-tags">

      <div style="clear:both;"></div>
    </div>

  </div>

  <div class="<?php echo $parent_slug;?>-stats ibyforum-stats">

<?php echo theme('display_data_grid', $variables); ?>

    <div class="<?php echo $parent_slug;?>-info ibyforum-info">
      <div class="rounded-button <?php echo $parent_slug;?>-view ibyforum-view"><a href="/node/<?php echo $topic->nid;?>">Go to post</a></div>
      <div style="clear:both;"></div>
    </div>

    <div style="clear:both;"></div>

  </div>

  <div style="clear:both;"></div>

</div>
