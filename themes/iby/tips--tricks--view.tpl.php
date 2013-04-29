<?php
drupal_add_css(sqtools_default_theme_path()."/css/".$variables['parent_slug'].".css", array('options' => "file"));
exit('UPS! Is still needed...');
?>

<div class="rounded-button comment-button"><a href="/forum/add/forum/forums?parent=<?php echo $parent_tid;?>" class="sq-ajax-link">Add a problem</a></div>
&nbsp;<br />

<div class="<?php echo $parent_slug;?>-large-box box-shadow">
  <h2>Welcome to Tips & Tricks</h2>

  <div class="<?php echo $parent_slug;?>-top-divider">&nbsp;</div>

  <div style="clear:both;"></div>

  <div class="<?php echo $parent_slug;?>-texts">

    <div class="<?php echo $parent_slug;?>-texts-left">
      <div style="clear:both;"></div>
    </div>

    <div class="<?php echo $parent_slug;?>-texts-right">
  &nbsp;
      <div style="clear:both;"></div>
    </div>

    <div style="clear:both;"></div>
  </div>

  <div style="clear:both;"></div>
</div>

<?php
  echo theme($parent_call_name.'__filters', $variables);
?>
<a name="pager-marker"></a>
<div class="rounded-button <?php echo $parent_slug;?>-enter ibyforum-enter"><a href="/node/add<?php echo $_SERVER['REQUEST_URI'];?>" class="sq-ajax-link" rel="#sq_ajax_pop">Create new post</a></div>
<div style="clear:both;">&nbsp;</div>

<div id="<?php echo $parent_slug;?>-content" class="ibyforum-content">

<?php
  $index = 0;
//echo "<pre>";var_dump($topics);exit;
foreach($topics as $child_id => $topic):
  echo theme($parent_call_name.'__topic__grid', $variables + array('topic' => &$topic));
  $index++;
endforeach;
?>
  <div style="clear:both;"></div>


<div class="<?php echo $parent_slug;?>-topbar">
  <div class="<?php echo $parent_slug;?>-topbar-left">Results</div>

  <div class="<?php echo $parent_slug;?>-topbar-right">
   <!-- <div class="<?php echo $parent_slug;?>-area-label">Select area:</div> -->
    <div class="<?php echo $parent_slug;?>-type-all">All</div>
    <div class="<?php echo $parent_slug;?>-type-continence">Continence</div>
    <div class="<?php echo $parent_slug;?>-type-ostomy">Ostomy</div>
  </div>
</div>

  <div style="clear:both;"></div>



</div> <!-- #<?php echo $parent_slug;?>-content -->


&nbsp;<br />
&nbsp;<br />
&nbsp;<br />

<?php
//echo "<pre>";
//var_dump(entity_load('taxonomy_term', array(2)));
//echo "</pre>";