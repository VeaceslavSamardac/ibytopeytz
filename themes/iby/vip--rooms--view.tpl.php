<?php
drupal_add_css(sqtools_default_theme_path()."/css/".$variables['parent_slug'].".css", array('options' => "file"));
?>

<!-- <div class="rounded-button comment-button"><a href="/forum/add/forum/forums?parent=<?php echo $parent_tid;?>&show_ajax=1" class="sq-ajax-link">Add a new forum</a></div>
&nbsp;<br />-->

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
  <?php echo $parents[0]->description; ?>
      <div style="clear:both;"></div>
    </div>

    <div style="clear:both;"></div>
  </div>

  <div style="clear:both;"></div>
</div>

<div style="clear:both;">&nbsp;</div>

<div id="<?php echo $parent_slug;?>-content">
  <div id="<?php echo $parent_slug;?>-rooms-content">

    <div class="<?php echo $parent_slug;?>-topbar">
      <div class="<?php echo $parent_slug;?>-topbar-left">
V.I.P. Rooms - Quick access
      </div>

      <div class="<?php echo $parent_slug;?>-topbar-right">
       <div class="<?php echo $parent_slug;?>-area-label"></div>
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
    </div>

    <div style="clear:both;"></div>

    <div id="<?php echo $parent_slug;?>-forums" class="ibyforum-forums">
      <div id="<?php echo $parent_slug;?>-forums-content" class="ibyforum-content">
<?php
foreach($active_rooms as $child_id => $forum):
  echo theme($variables['parent_call_name'].'__active', $variables + array('forum' => &$forum));
endforeach;
?>
        <div style="clear:both;"></div>
      </div> <!-- #<?php echo $parent_slug;?>-content -->

      <div style="clear:both;"></div>

    </div> <!-- #<?php echo $parent_slug;?>-forums -->

    <div style="clear:both;"></div>

    <a name="pager-marker"></a>
    <div class="<?php echo $parent_slug;?>-topbar">
      <div class="<?php echo $parent_slug;?>-topbar-left">Archived V.I.P. Rooms</div>
    </div>

    <div style="clear:both;"></div>

    <div id="<?php echo $parent_slug;?>-forums" class="ibyforum-forums">
      <div id="<?php echo $parent_slug;?>-archived-content" class="ibyforum-content">
<?php
foreach($ended_rooms as $child_id => $forum):
  echo theme($variables['parent_call_name'].'__archived', $variables + array('forum' => &$forum));
endforeach;
?>
        <div style="clear:both;"></div>
      </div>

      <div style="clear:both;"></div>

    </div>

<div style="clear:both;">&nbsp;</div>

<?php echo theme('pager', array('element'=>0)); ?>

    <div style="clear:both;"></div>

  </div> <!-- #<?php echo $parent_slug;?>-rooms-content -->

  <div id="<?php echo $parent_slug;?>-front-right">
<?php
  //echo theme($parent_call_name.'__front__right', $variables + array('forum' => (object)$taxonomy_forums[0]['taxonomy_term']));
  echo theme($parent_call_name.'__front__right', $variables);
?>
    <div style="clear:both;"></div>

  </div>
  <div style="clear:both;"></div>

</div>  <!-- #<?php echo $parent_slug;?>-content -->


&nbsp;<br />
&nbsp;<br />
&nbsp;<br />

<?php
//echo "<pre>";
//var_dump(entity_load('taxonomy_term', array(2)));
//echo "</pre>";
