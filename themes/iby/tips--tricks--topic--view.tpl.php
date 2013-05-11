<?php
drupal_add_css(sqtools_default_theme_path()."/css/".$variables['parent_slug'].".css", array('options' => "file"));
?>

<div class="<?php echo $parent_slug;?>-large-box box-shadow">
<?php
$lang_value = sqtools_get_lang_value($parents[0]->field_subtitle);
$title = strip_tags($lang_value[0]['value']);
?>
  <h2><?php echo $title;?></h2>

  <div class="<?php echo $parent_slug;?>-top-divider ibyforum-top-divider">&nbsp;</div>

  <div style="clear:both;"></div>

  <div class="<?php echo $parent_slug;?>-texts">

    <div class="<?php echo $parent_slug;?>-texts-left">
  <?php echo $forum->description;?>
      <div style="clear:both;"></div>
    </div>

    <div style="clear:both;"></div>
  </div>

  <div style="clear:both;"></div>
</div>

<?php
if($forums) $forum_tid = $parent_tid;
else {
  $parent_index = count($parents);
  $parent_index--;
  $forum_tid = $parents[$parent_index]->tid;
}

$tags = iby_forums_get_nodes_filter_tags($forum_tid);
echo theme($parent_call_name.'__filters', $variables + array('tags' => $tags, 'tagHeadline' => 'Find useful Tips & Trick'));
?>

<!-- <div class="rounded-button <?php echo $parent_slug;?>-enter ibyforum-enter"><a href="/node/add<?php echo $_SERVER['REQUEST_URI'];?>" class="sq-ajax-link" rel="#sq_ajax_pop">Create new post</a></div> -->
<!-- <div style="clear:both;">&nbsp;</div> -->

<div id="<?php echo $parent_slug;?>-content">


  <div id="<?php echo $parent_slug;?>-topics-content" class="<?php echo $parent_slug;?>-topics-content ibyforum-topics-content">

<?php

  if(arg(2) == "tags") {
?>
<div class="<?php echo $parent_slug;?>-topbar">
  <div class="<?php echo $parent_slug;?>-topbar-left">Results</div>

  <div class="<?php echo $parent_slug;?>-topbar-right">
    <div class="<?php echo $parent_slug;?>-area-label">Select area:&nbsp;</div>
<?php
$base_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$types = array('all', 'continence', 'ostomy');
$current_type = 'all';
if(isset($_GET['type']) && in_array($_GET['type'], $types)) $current_type = $_GET['type'];
foreach($types as $type) {
  echo '    <a href="'.UrlTools::addParams($base_url, array('type'=>$type)).'" class="ibyforum-type-'.$type.' '.$parent_slug.'-type-'.$type;
  if($current_type == $type) echo ' ibyforum-type-active '.$parent_slug.'-type-active';
  echo '">'.ucfirst($type).'</a>'."\n";
}
?>
    <div style="clear:both;"></div>
  </div>
  <div style="clear:both;"></div>
</div>

<?php

    $index = 0;
    foreach($topics_solved as $child_id => $topic_solved) {
      echo theme($parent_call_name.'__topic__list', $variables + array('topic' => &$topic_solved));
      $index++;
    }    
?>
<div style="clear:both;"></div>
<div class="<?php echo $parent_slug;?>-divider" style="width:555px;">&nbsp;</div>
<div style="clear:both;"></div>
<?php
  }
?>

   
    <div class="<?php echo $parent_slug;?>-topbar">
      <div class="<?php echo $parent_slug;?>-topbar-left">Unsolved problems</div>

    <div class="<?php echo $parent_slug;?>-topbar-right">
      <div class="<?php echo $parent_slug;?>-area-label">Select area:&nbsp;</div>
<?php
$base_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$types = array('all', 'continence', 'ostomy');
$current_type = 'all';
if(isset($_GET['type']) && in_array($_GET['type'], $types)) $current_type = $_GET['type'];
foreach($types as $type) {
  echo '      <a href="'.UrlTools::addParams($base_url, array('type'=>$type)).'" class="ibyforum-type-'.$type.' '.$parent_slug.'-type-'.$type;
  if($current_type == $type) echo ' ibyforum-type-active '.$parent_slug.'-type-active';
  echo '">'.ucfirst($type).'</a>'."\n";
}
?>
      </div>
    </div>

    <div style="clear:both;"></div>

<?php
$index = 0;
foreach($topics_unsolved as $child_id => $topic_unsolved) {
  echo theme($parent_call_name.'__topic__grid', $variables + array('first_in_row' => (!($index%3)), 'topic' => &$topic_unsolved) );
  $index++;
}
?>
    <div style="clear:both;"></div>
<?php if($user->uid): ?>
<a name="tips_tricks_form"></a>
	<div class="comment_form_wrapper tips_tricks_form_wrapper box-shadow">
	<h2 class="title comment-form"><?php print t('Got an unsolved problem?'); ?></h2>
	<div class="content-divider"></div>
<?php
  module_load_include('inc', 'node', 'node.pages'); 
echo drupal_render(node_add('forum'));
?>
	</div>
<div style="clear:both;"></div>
<?php endif;?>

  </div> <!-- #end <?php echo $parent_slug;?>-topics-content -->

  <div id="<?php echo $parent_slug;?>-front-right">
<?php
  //echo theme($parent_call_name.'__front__right', $variables + array('forum' => (object)$taxonomy_forums[0]['taxonomy_term']));
  echo theme($parent_call_name.'__front__right', $variables);
?>
    <div style="clear:both;"></div>

  </div>

  <div style="clear:both;"></div>

</div> <!-- #<?php echo $parent_slug;?>-content -->


