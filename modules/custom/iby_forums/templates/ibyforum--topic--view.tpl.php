<?php
if($_SERVER['SERVER_NAME'] == "iby.localhost") echo "<!-- ibyforum--topic--view.tpl.php -->\n";
?>
<div id="<?php echo $parent_slug;?>-content" class="ibyforum-content">
  <div class="<?php echo $parent_slug;?> ibyforum <?php echo $parent_slug;?>-full ibyforum-full box-shadow odd" id="<?php echo $parent_slug;?><?php echo $forum->tid;?>">
    <div class="<?php echo $parent_slug;?>-bg ibyforum-bg">
      <div class="<?php echo $parent_slug;?>-number ibyforum-number">Challenge #<?php echo $forum->tid;?>
        <div style="float:right;">
<?php
$args = array('flag_type' => "follow", 'uid' => $user->uid, 'flag_group' => "forum", 'entity_type' => "taxonomy_term", 'entity_id' => $forum->tid);
$is_flagged = (sq_flags_api_get($args) ? true : false);
$flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
?>
          <a href="/sq_flags_api/toggle/follow/forum/taxonomy_term/<?php echo $forum->tid;?>?sq_flags_api_cb=sq_flags_cb" id="follow_forum_<?php echo $forum->tid;?>" class="use-ajax sq-follow-link <?php echo $flag_class;?>"> </a>
        </div>
      </div>
      <div class="<?php echo $parent_slug;?>-name ibyforum-name"><?php echo $forum->name;?></div>
      <div class="<?php echo $parent_slug;?>-divider ibyforum-divider"></div>

      <div class="<?php echo $parent_slug;?>-short ibyforum-short"><?php echo nl2br($forum->description);?></div>
      <div class="<?php echo $parent_slug;?>-image ibyforum-image"><img src="/<?php echo drupal_get_path('module', 'iby_forums');?>/images/forum_image_active.jpg" /></div>
      <div style="clear:both;"></div>

      <div class="rounded-button <?php echo $parent_slug;?>-enter ibyforum-enter"><a href="/node/add<?php echo $_SERVER['REQUEST_URI'];?>" class="sq-ajax-link" rel="#sq_ajax_pop">Contribute to this <?php echo $parent_slug;?></a></div>

      <div style="clear:both;">&nbsp;</div>
    </div>
    <div style="clear:both;"></div>
  </div>
  <div style="clear:both;"></div>
</div>

<?php
  echo theme('ibyforums__filters', $variables);
?>

<div id="<?php echo $parent_slug;?>-topics-content ibyforum-topics-content">
<?php
$index = 0;
foreach($topics as $child_id => $topic):
  if(isset($_GET['view']) && ($_GET['view'] == "list")) echo theme($parent_call_name.'__topic__list', $variables + array('topic' => &$topic));
  else echo theme($parent_call_name.'__topic__grid', $variables + array('first_in_row' => (!($index%3)), 'topic' => &$topic) );
  $index++;
endforeach;
?>
  <div style="clear:both;"></div>
</div> <!-- #<?php echo $parent_slug;?>-topics-content -->

&nbsp;<br />
&nbsp;<br />
&nbsp;<br />

