<?php
  drupal_add_css(sqtools_default_theme_path()."/css/challenges.css", array('options' => "file"));
  drupal_add_css(sqtools_default_theme_path()."/css/ibyforum.css", array('options' => "file"));
?>
<div class="panes"><!-- Front page Sections start -->

<!-- FIRST TAB PRESENTATION START-->

<!-- root element for scrollable -->

<!-- root element for the items -->

<?php
  if(
    isset($variables['dashboard_slider_id'])
    &&
    is_array($variables['dashboard_slider_id'])
    &&
    count($variables['dashboard_slider_id'])
  ) {
    $i = 1;
    $dashboard_slide = entity_load("node", $dashboard_slider_id);
    foreach($dashboard_slide as $ds) {
      $sliderTitle = $ds->field_top_header['und'][0]['value'];
      $sliderSubtitle = $ds->title;
      $i = 1;
      if(
        isset($variables['challenges_tids'])
        &&
        is_array($variables['challenges_tids'])
        &&
        count($variables['challenges_tids'])
      ) {
        foreach($variables['challenges_tids'] as $challenges_tid) {
          $forum_term = forum_forum_load($challenges_tid);
          $type = $forum_term->field_type['und'][0]['value'];
          if($i == 1) {
            $cNumber1 = $forum_term->field_challenge_number['und'][0]['value'];
            $cName1 = 	StringTools::shorten($forum_term->name, 40, '');
          }
          if($i == 2) {
            $cNumber2 = $forum_term->field_challenge_number['und'][0]['value'];
            $cName2 = StringTools::shorten($forum_term->name, 40, '');
          }
          $i++;
        }
      }
?>

<!--
      <ul class="tabs fp-tabs">
        <li>
          <a href="#first" class="tab1">
            <span class="dashboard upper"><?php echo $sliderTitle; ?></span>
            <span class="dashboard lower"><?php echo $sliderSubtitle; ?></span>
          </a>
        </li>
        <li>
          <a href="#second" class="tab2">
            <span class="dashboard upper">Challenge #<?php echo $cNumber1; ?></span>
            <span class="dashboard lower"><?php echo ucwords($cName1); ?></span>
          </a>
        </li>
        <li>
          <a href="#third" class="tab3">
            <span class="dashboard upper">Challenge #<?php echo $cNumber2;?></span>
            <span class="dashboard lower"><?php echo ucwords($cName2); ?></span>
          </a>
        </li>
      </ul>
-->

      <div class="items">
      <div class="slide dashboard_slide first">
      <div class="challenges-content type-message slider1">
      <div class="challenges ibyforum challenges-full ibyforum-full box-shadow type-message">
      <div class="challenges-bg ibyforum-bg dashboard_slide_bg">

      <div class="challenges-number ibyforum-number">
        <?php echo $ds->field_top_header['und'][0]['value']; ?>
      </div>

      <div class="challenges_header_wrapper">
        <h3 class="challenges_title_link"><?php echo $ds->title; ?></h3>
      </div>

      <div class="challenges-divider ibyforum-divider">
      </div>

      <span style="display:block;font-size:15px;font-weight:bold;padding:20px 20px 0 20px;color:#666;margin-bottom:20px;">
        <?php echo nl2br(strip_tags($ds->field_dashboard_subheader['und'][0]['value'])); ?>
      </span>

      <div class="challenges-short-container ibyforum-short-container-slider" style="height: 313px;" id="<?php echo $ds->nid; ?>">
        <div class="challenges-short ibyforum-short">
          <?php echo nl2br(strip_tags($ds->body['und'][0]['value'])); ?>
        </div>
        <div style="clear:both;"></div>
      </div>

      <div class="challenges-image ibyforum-image-slider">
        <?php echo nl2br($ds->field_dashboard_right_text['und'][0]['value']);?>
        <div style="clear:both;"></div>
      </div>

      <div style="clear:both;"></div>

      </div>
      </div>
      </div>
      </div>
      </div>
<?php
    }
  }
?>

<?php
  $i = 1;
  if(
    isset($variables['challenges_tids'])
    &&
    is_array($variables['challenges_tids'])
    &&
    count($variables['challenges_tids'])
  ) {
    foreach($variables['challenges_tids'] as $challenges_tid) {
      echo '<div class="items">';
      echo '<div class="slide dashboard_slide">';
        $forum_term = forum_forum_load($challenges_tid);
        $type = $forum_term->field_type['und'][0]['value'];
        echo '<div class="challenges-content type-'.$type.' slider'.$i.'">'."\n";
        echo theme('challenges__full', array('forum' => $forum_term, 'parent_slug' => 'challenges'));
        echo '<div style="clear:both;"></div>'."\n";
        echo '</div> <!-- .slider'.$i.' -->'."\n";
      echo '</div>';
      echo '</div>';
      $i++;
    }
  }
?>
</div><!-- Front page presentation End -->

<ul class="tabs fp-tabs">
  <li>
    <a href="#first" class="tab1-bottom">
      <span class="dashboard upper"><?php echo $sliderTitle; ?></span>
      <span class="dashboard lower"><?php echo $sliderSubtitle; ?></span>
    </a>
  </li>
  <li>
    <a href="#second" class="tab2-bottom">
      <span class="dashboard upper">Challenge #<?php echo $cNumber1; ?></span>
      <span class="dashboard lower"><?php echo ucwords($cName1); ?></span>
    </a>
  </li>
  <li>
    <a href="#third" class="tab3-bottom">
      <span class="dashboard upper">Challenge #<?php echo $cNumber2;?></span>
      <span class="dashboard lower"><?php echo ucwords($cName2); ?></span>
    </a>
  </li>
</ul>

</div><!-- #Front page presentation end -->

<div style="clear:both;"></div>

<div class="dashboard">
<div class="moderator-area">
  <div class="moderator-posts">
    <div class="forum-updates-header-short">
      <h3>Important messages</h3>
      <div class="right">
      <a href="/forum/17679">See all important messages &rsaquo;</a>
    </div>
  </div>

  <div class="important_messages">
  <div class="items">

<?php
  if(
    isset($variables['moderator_nids'])
    &&
    is_array($variables['moderator_nids'])
    &&
    count($variables['moderator_nids'])
  ) {
    $i = 1;
    $moderator_topics = entity_load("node", $moderator_nids);
    $index = 2;
    foreach($moderator_topics as $moderator_topic) {
      echo (($index%2) ? "" : "<div class=\"moderatorList\">");
      $account = entity_load('user', array($moderator_topic->uid));
      $account = array_shift($account);
?>
      <div class="moderator-content slider slider<?php echo $i;?>">
      <div class="moderator-image">
        <?php
          if($account->picture) {
            echo theme('image', array('path' => file_create_url($account->picture->uri), 'width' => '35px'));
          }
        ?>
        <div style="clear:both;"></div>
      </div>

      <div class="moderator-post">
        <h3 style="font-size:12px; line-height:17px;"><?php echo $moderator_topic->title;?></h3>

      <div class="moderator-body">
        <div class="moderator-body-text">
          <?php
            echo StringTools::shorten(
              StringTools::striptags($moderator_topic->body[$moderator_topic->language][0]['value']), 180
            );
          ?>
        </div>
        <div style="clear:both;"></div>
        <span class="readmore">
          <a href="/node/<?php echo $moderator_topic->nid;?>">Read more &rsaquo;</a>
        </span>
      </div>
      <?php
        if($moderator_topic->field_tags['und'][0]['tid'] != 0) {
          $tid = $moderator_topic->field_tags['und'][0]['tid'];
        } else {
          $tid = 0;
        }
      ?>

      <div class="tips-tricks-meta" style="margin-top: 15px";>
        <span class="oneline">By: <?php echo $account->name; ?></span>
        <span class="oneline seperater" style="display:inline;">
          <?php
            echo intval(
              sq_flags_api_get_count(array('flag_type' => 'view' , 'entity_type' => 'node', 'entity_id' => $moderator_topic->nid))
            );
          ?> views
        </span>
        <span class="oneline" style="display:inline;"><?php echo $moderator_topic->comment_count; ?> replies</span>
        <div style="clear:both;"></div>
      </div>

      <div style="clear:both;"></div>
      </div>

      <div style="clear:both;"></div>
      </div> <!-- .slider<?php echo $i;?> -->
<?php
      echo (($index%2) ? "</div>" : "");
      $index++;
      $i++;
    }
  }

  echo (($index%2) ? "</div>" : "");
?>
</div>
</div>
<div style="clear:both;"></div>
<div class="nav-slides">
<!-- "previous page" action -->
<a class="prev browse left"></a>
<!-- wrapper for navigator elements -->
<span class="navi1 navi clearfix"></span>
<!-- "next page" action -->
<a class="next browse right"></a>
</div>
<div style="clear:both;"></div>

<script type="text/javascript">
var scrollable = new Array();
jQuery(".important_messages").scrollable({circular: true}).navigator();//.autoscroll();
</script>

<div style="clear:both;"></div>
</div> <!-- .moderator-posts -->

<div class="banner-area">
  <?php global $base_url; ?>
  <a href="<?php print $base_url; ?>/forum/19629">
    <img src="/<?php echo sqtools_default_theme_path(); ?>/images/banner-ad-front-page.png" width="180px" height="285px">
  </a>
  <div style="clear:both;"></div>
</div> <!-- .banner-area -->
</div> <!-- .moderator-area -->

<div style="clear:both;">&nbsp;</div>

<div class="forum-updates">
<div class="forum-updates-header">
<h3>Forum updates</h3>
<div class="right">
<a href="/forum/92">Visit forums &rsaquo;</a>
</div>
</div>

<?php
// We need to make sure, the css is loaded
drupal_add_css(sqtools_default_theme_path()."/css/ibyforum.css", array('options' => "file"));
drupal_add_css(sqtools_default_theme_path()."/css/tips-tricks.css", array('options' => "file"));
drupal_add_css(sqtools_default_theme_path()."/css/chat-forums.css", array('options' => "file"));
$forum_topics = entity_load('node', $forums_nids);
if(is_array($forum_topics) && count($forum_topics)) {
  foreach($forum_topics as $forum_topic) {
    $forum_topic->owner_uid = $forum_topic->uid;
    echo theme('dashboard__chat__forums__recent', array('parent_slug' => 'chat-forums', 'topic' => &$forum_topic));
  }
}
?>
<div style="clear:both;"></div>
</div> <!-- .forum-updates -->

<div class="tips-tricks">
<div class="forum-updates-header">
<h3>Featured tips and tricks</h3>
<div class="right">
<a href="/forum/40">Visit Tips & Tricks &rsaquo;</a>
</div>
</div>
<?php
if(isset($variables['tips_tricks_nids']) && is_array($variables['tips_tricks_nids']) && count($variables['tips_tricks_nids'])) {

  $tips_tricks_topics = entity_load("node", $tips_tricks_nids);
  $ttc = 0;
  //var_dump($tips_tricks_topics);
  foreach($tips_tricks_topics as $tips_tricks_topic) {
    $tips_tricks_topic->owner_uid = $tips_tricks_topic->uid;
    //var_dump($tips_tricks_topic);
    $ttc++;
    $account = user_load($tips_tricks_topic->owner_uid);

    ?>
      <div class="tips-tricks-content <?php if($ttc == 3) { echo "last"; } ?>">
      <h3><?php echo StringTools::shorten(strip_tags("Problem: ".$tips_tricks_topic->title), 65, "...");?></h3>
      <div class="readmore">
      <a href="/node/<?php echo $tips_tricks_topic->nid;?>">Read more &rsaquo;</a>
      <div style="clear:both;"></div>
      </div>


      <div class="tips-tricks-meta">
      <span class="oneline">Submitted: <?php echo date("d M Y h:i a", $tips_tricks_topic->created) ?></span>
      <span class="oneline">Usertags:
      <?php


      if(isset($tips_tricks_topic->field_tags['und']) && is_array($tips_tricks_topic->field_tags['und']) && count($tips_tricks_topic->field_tags['und'])) {
        $str = "";
        foreach($tips_tricks_topic->field_tags['und'] as $tag) {
          //$tags .= '<a href="#'.$tag['taxonomy_term']->name.'">'.$tag['taxonomy_term']->name.'</a> ';
          $term = taxonomy_term_load($tag['tid']);
          $str .= $term->name.', ';
        }
        echo substr($str, 0, -2);
      } ?>
    </span>
      <div style="clear:both;"></div>
      </div>

      <div style="clear:both;"></div>
      </div> <!-- .slider<?php echo $i;?> -->
      <?php
  }
}
?>

</div> <!-- .featured-tips-tricks -->

<div style="clear:both;"></div>

<div class="bottom-info">

<div class="community-info">
<div class="dashboard-bottom-header">
<h3>Community info</h3>
</div>
<div class="users-online"><?php echo $online_users;?> users are online right now</div>
<div class="community-members">We are now<br /><?php echo $active_users;?> members<br /><?php echo $vip_members;?> VIP members</div>
<div class="total-posts">We have a total of <?php echo $total_posts;?> posts</div>

<div style="clear:both;"></div>

<div class="newest-member">
<?php
vds($newest_member_uid);
$newest_member = entity_load('user', array($newest_member_uid));
$newest_member = array_shift($newest_member);


if($newest_member->picture) { echo theme('image', array('path' => file_create_url($newest_member->picture->uri), 'width' => '60px')); }
?>
<div class="member-welcome">
<h3>Welcome to our newest member</h3>
<div class="member-name"><a href="/user/<?php echo $newest_member->uid;?>"><?php echo $newest_member->name;?></a></div>
</div>

</div>

<div style="clear:both;"></div>
</div> <!-- .community-info -->

<div class="great-idea">
  <div class="dashboard-bottom-header">
    <h3>Got a great idea?</h3>
  </div>
  <img src="/<?php echo sqtools_default_theme_path(); ?>/images/balloons_multi.png" style="float:left;">
    <p>Submit your idea about new activities or improvements of our community.</p>
    <p>Product ideas are welcome as well, but they can also be posted in the Challenge section.</p>
    <div class="rounded-button send-button">
      <a href="/messages/new/role_9/<?php echo urlencode("I have an idea");?>">Submit idea</a>
    </div>

<?php /*
<div class="rounded-button send-button">
  <a href="/messages/new/role_3,role_8,role_9/<?php echo urlencode("I have an idea");?>?destination=node/<?php echo $nid;?>">
    Submit idea
  </a>
</div>
*/ ?>

  <div style="clear:both;"></div>
</div> <!-- .great-idea -->

<div style="clear:both;"></div>

</div> <!-- .bottom-info -->

<div style="clear:both;"></div>
</div> <!-- .dashboard -->

<?php
//echo "<pre>"; var_dump($variables); exit;
