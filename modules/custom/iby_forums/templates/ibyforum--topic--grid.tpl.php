<?php
$is_sticky = max($topic->field_sticky['und'][0]['value'], $topic->field_sticky_global['und'][0]['value']);

$query = db_select('node', 'n');
$query->fields('n', array('created'));
$query->condition('n.nid', $topic->nid);
$node_created = $query->execute()->fetchField(0);
?>

<div class="<?php echo $parent_slug;?> box-shadow <?php echo (($is_sticky) ? 'sticky ':'');?><?php echo $parent_slug;?>-topic-grid ibyforum-topic-grid" <?php echo (($first_in_row)?' style="margin-left:0px;"':'');?> id="<?php echo $parent_slug;?><?php echo $topic->nid;?>">
  <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">

  <div class="<?php echo $parent_slug;?>-name ibyforum-name"><h2><?php echo trim(strip_tags($topic->title));?></h2></div>

    <div class="<?php echo $parent_slug;?>-grid-divider ibyforum-grid-divider"></div>

    <div class="<?php echo $parent_slug;?>-owner ibyforum-owner">

<?php
$topicOwner = user_load($topic->owner_uid);

echo '      <div class="owner-image">';
if($topicOwner->picture) { echo theme('image', array('path' => file_create_url($topicOwner->picture->uri), 'height' => '35px')); }
echo '      </div>';

echo '      <div class="owner-info">';
echo '        <div class="owner-name">Created by <a class="owner-name" href="/user/'.$topicOwner->uid.'">'.StringTools::shorten($topicOwner->name, 20, "...").'</a></div>';
echo '        <div class="owner-created">'.date("d M Y h:i a", $node_created).'</div>';
echo '        <div style="clear:both;"></div>';
echo '      </div>';

?>

      <div style="clear:both;"></div>

    </div>

    <div style="clear:both;"></div>

    <div class="<?php echo $parent_slug;?>-grid-divider ibyforum-grid-divider"></div>

<?php
$base_text = StringTools::shorten(StringTools::striptags($topic->body['und'][0]['value']), 600, "...");

$display_picture = false;
if(preg_match_all('/<img[^>]+src="([^"]+)"[^>]*>/is', $topic->body['und'][0]['value'], $matches, PREG_SET_ORDER)) {
  foreach($matches as $match) {
    if(!preg_match('/(\/emoticon|smiley.*?\.gif)/is', $match[1])) {
      $display_picture = $match[1];
      break;
    }
  }

  if($display_picture) {
    $base_text = StringTools::shorten(StringTools::striptags($topic->body['und'][0]['value']), 300);
?>
    <div class="<?php echo $parent_slug;?>-body-image ibyforum-body-image">
      <img src="<?php echo $display_picture;?>" />
    </div>
<?php
  }
}
?>
    <div class="<?php echo $parent_slug;?>-body ibyforum-body<?php echo (($display_picture)?' ibyforum-with-image':'');?>"><p><?php echo $base_text;?></p></div>

    <div style="clear:both;"></div>

    <div class="<?php echo $parent_slug;?>-tags ibyforum-tags">
<?php
if(isset($topic->field_tags) && is_array($topic->field_tags) && count($topic->field_tags)) {
  $str = "Tags: ";
  foreach($topic->field_tags['und'] as $tag) {
    $term = taxonomy_term_load($tag['tid']);
    $str .= $term->name.', ';
  }
  echo substr($str, 0, -2);
}
?>

      <div style="clear:both;"></div>
    </div>

  </div>

  <div class="<?php echo $parent_slug;?>-stats ibyforum-stats">

<?php echo theme('display_data_grid', $variables); ?>

<?php
  /*
      if(isset($topic->field_tags) && is_array($topic->field_tags) && count($topic->field_tags)) {
        $str = "Tags: ";
        foreach($topic->field_tags['und'] as $tag) {
          $term = taxonomy_term_load($tag['tid']);
          $str .= $term->name.', ';
        }
        echo substr($str, 0, -2);
      }
  */
?>

    <div style="clear:both;"></div>

    <div class="<?php echo $parent_slug;?>-info ibyforum-info">
      <div class="rounded-button <?php echo $parent_slug;?>-view ibyforum-view"><a href="/node/<?php echo $topic->nid;?>">Go to post</a></div>
      <div style="clear:both;"></div>
    </div>

  </div>
</div>
