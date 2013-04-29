<?php
$query = db_select('comment', 'c');
$query->condition('c.status', 1);
$query->addExpression('COUNT(c.cid)');
$query->condition('c.uid', $member->uid);
$query->groupBy('c.uid');
$comment_count = $query->execute()->fetchField(0);

// Latest activity

$query = db_select('forum_index', 'fi');
$query->join('node', 'n', 'n.status = 1 AND n.nid = fi.nid');
$query->fields('fi', array('nid'));
$query->fields('n', array('created'));
if(arg(0) == "forum") $query->condition('fi.tid', $forum_tid);
$query->join('node', 'n', 'n.nid = fi.nid AND n.uid='.$member->uid);
$query->orderBy('n.created', 'DESC');
$query->range(0, 1);
$latest_post = $query->execute()->fetchAssoc();

?>
<div class="user-preview-box box-shadow <?php echo $extra_class;?>" id="user-preview-box<?php echo $member->uid;?>">
  <div class="user-preview-top">
<?php
      echo '<div class="user-image">';
      if($member->picture) { echo theme('image', array('path' => file_create_url($member->picture->uri), 'height' => '35px')); }
      echo '</div>';
?>

    <div class="user-info">
      <h3><?php echo $member->name;?></h3>
      <div>Since: <?php echo date("F d, Y", $member->created);?></div>

<?php
if(arg(0) == "forum") {
  $query = db_select('iby_forum_vip_members', 'vm');
  $query->fields('vm', array('timestamp'));
  $query->condition('vm.uid', $member->uid);
  $query->condition('vm.tid', $forum_tid);
  $joined_room_stamp = $query->execute()->fetchField(0);
?>      <div>Joined room: <?php echo date("F d, Y", $joined_room_stamp);?></div> <?php
}
?>

<?php
if(sqtools_is_admin() && (arg(0) == "forum")) {
  echo '<a href="/forum/'.arg(1).'/member/'.$member->uid.'/remove" class="action-links use-ajax">'.t('Remove user from room').'</a>';
}
?>
    </div>
    <div style="clear:both;"></div>
  </div>

  <div style="clear:both;"></div>

  <div class="user-preview-content">
    <div class="flag-buttons">
  
      <?php
      $args = array('flag_type' => "follow", 'uid' => $user->uid, 'flag_group' => "profile", 'entity_type' => "user", 'entity_id' => $member->uid);
      $is_flagged = (sq_flags_api_get($args) ? true : false);
      $flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
      ?>
      <a href="/sq_flags_api/toggle/follow/profile/user/<?php echo $member->uid;?>?sq_flags_api_cb=sq_flags_cb" id="follow_profile_<?php echo $member->uid;?>" class="use-ajax sq-follow-link <?php echo $flag_class;?>"> </a>

      <?php
      $args = array('flag_type' => "like", 'uid' => $user->uid, 'flag_group' => "profile", 'entity_type' => "user", 'entity_id' => $member->uid);
      $is_flagged = (sq_flags_api_get($args) ? true : false);
      $flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
      ?>
      <a href="/sq_flags_api/toggle/like/profile/user/<?php echo $member->uid;?>?sq_flags_api_cb=sq_flags_cb" id="like_profile_<?php echo $member->uid;?>" class="use-ajax sq-like-link <?php echo $flag_class;?>"> </a>
      

      <!--<a href="/messages/new/<?php echo $member->uid;?>" id="contact_profile_<?php echo $member->uid;?>" class="contact-link"> </a>-->
	  <a id="contact_dropdown<?php echo $member->uid; ?>" class="contact-link"></a>
	  <?php //var_dump($member->field_cc_email); ?>
	  <!-- CONTACT DROPDOWN MAGIC START -->
	  <div class="contact_dropdown_container">	
		<div class="contact_dropdown_body">
			<h3>Communicate to me by:</h3>
			<div style="border-bottom: 1px dotted #fff";></div>
				<div style="clear:both;"></div>
				<div class="cc_item pmessage">
				  <div class="label">Private messaging</div>
				  <div class="value"><a href="/messages/new/<?php echo $member->uid;?>">Send a message</a></div>
				  <div style="clear:both;"></div>
				</div>
				
				<?php
				if(isset($member->field_cc_email) && count($member->field_cc_email)) {
				  $langs = array_keys($member->field_cc_email);
				  $cc_email = $member->field_cc_email[$langs[0]][0]['value'];
				  if($cc_email) {

				?>
					<div style="clear:both;">&nbsp;</div>
					<div class="cc_item email">
					  <div class="label">Private e-mail</div>
					  <div class="value"><a href="mailto:<?php echo $cc_email;?>"><?php echo StringTools::shorten($cc_email, 18);?></a></div>
					  <div style="clear:both;"></div>
					</div>
				<?php
				  }
				}
				?>
				
				<?php
				if(isset($member->field_cc_msn) && count($member->field_cc_msn)) {
				  $langs = array_keys($member->field_cc_msn);
				  $cc_msn = $member->field_cc_msn[$langs[0]][0]['value'];
				  if($cc_msn) {

				?>
					<div style="clear:both;">&nbsp;</div>
					<div class="cc_item msn">
					  <div class="label">MSN Messenger</div>
					  <div class="value"><?php echo $cc_msn;?></div>
					  <div style="clear:both;"></div>
					</div>
				<?php
				  }
				}
				?>
				
				<?php
				if(isset($member->field_cc_aim) && count($member->field_cc_aim)) {
				  $langs = array_keys($member->field_cc_aim);
				  $cc_aim = $member->field_cc_aim[$langs[0]][0]['value'];
				  if($cc_aim) {

				?>
					<div style="clear:both;">&nbsp;</div>
					<div class="cc_item aim">
					  <div class="label">AIM / AOL Online</div>
					  <div class="value"><?php echo $cc_aim;?></div>
					  <div style="clear:both;"></div>
					</div>
				<?php
				  }
				}
				?>
				
				<?php
				if(isset($member->field_cc_skype) && count($member->field_cc_skype)) {
				  $langs = array_keys($member->field_cc_skype);
				  $cc_skype = $member->field_cc_skype[$langs[0]][0]['value'];
				  if($cc_skype) {

				?>
					<div style="clear:both;">&nbsp;</div>
					<div class="cc_item skype">
					  <div class="label">SKYPE</div>
					  <div class="value"><?php echo $cc_skype;?></div>
					  <div style="clear:both;"></div>
					</div>
				<?php
				  }
				}
				?>
				<div style="clear:both;"></div>
			
			<?php //echo theme('user_com_rightbar', $variables); ?>
		</div>
	  </div>
  <script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#contact_dropdown<?php echo $member->uid; ?>").tooltip({position: 'bottom center', offset: [0, -55], delay: 500, effect: 'slide'});
		});
	</script>
      
	  <!-- CONTACT DROPDOWN MAGIC END -->
      <div style="clear:both;"></div>

    </div>

    <div style="clear:both;"></div>
 
    <div class="latest-activity">
      <h3 class="blue">Last activity</h3>

      <div class="latest-content">


<?php
//echo "Ago: ".StringTools::timeAgo(mktime(0,0,0,9,9,2011))."<br />";
//echo "Left: ".StringTools::timeLeft(mktime(-10,0,0,10,10,2011))."<br />";
if(is_array($latest_post)) {
  $latest_post = node_load($latest_post['nid']);
  $langs = array_keys($latest_post->body);
  $latest_post->body = StringTools::striptags($latest_post->body[$langs[0]][0]['value']);
 
  echo '<a href="/node/'.$latest_post->nid.'">'.$latest_post->title.'</a><br />';
  echo $latest_post->body;

} else {
  echo '<strong>No activity in here yet</strong><br />';
}

?>
      </div>

      <div class="latest-info">
<?php
if($latest_post) {
  $langs = array_keys($latest_post->taxonomy_forums);
  $forum_tid = $latest_post->taxonomy_forums[$langs[0]][0]['tid'];

  $forum = taxonomy_term_load($forum_tid);

  echo '<strong>'.StringTools::timeAgo($latest_post->created)." ago</strong><br />";
  echo '<strong>Posted in <a href="/forum/'.$forum->tid.'">'.$forum->name.'</a></strong>';
}
?>
      </div>

    </div>

    <div style="clear:both;"></div>

  </div>

  <div class="user-preview-bottom">

    <div class="display-data-grid-list">
      <div class="display-data-grid-stats">
        <div class="row1">

          <div class="comments">
            <span><?php echo intval($comment_count); ?></span> comments
            <div style="clear:both;"></div>
          </div>

          <div class="views">
            <span><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'view' , 'entity_type' => 'user', 'entity_id' => $member->uid))); ?></span> views
            <div style="clear:both;"></div>
          </div>

          <div style="clear:both;"></div>

        </div>
    
        <div style="clear:both;"></div>
    
        <div class="row2">

          <div class="followers follow-user-<?php echo $member->uid;?>">
            <span class="count-number"><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'follow' , 'entity_type' => 'user', 'entity_id' => $member->uid))); ?></span> followers
            <div style="clear:both;"></div>
          </div>

          <div class="likes like-user-<?php echo $member->uid;?>">
            <span class="count-number"><?php echo intval(sq_flags_api_get_count(array('flag_type' => 'like' , 'entity_type' => 'user', 'entity_id' => $member->uid))); ?></span> likes
            <div style="clear:both;"></div>
          </div>

          <div style="clear:both;"></div>
        </div>
    
        <div style="clear:both;">&nbsp;</div>
    
      </div>

    </div>

    <div class="rounded-button"><a href="/user/<?php echo $member->uid;?>">View profile</a></div>

    <div style="clear:both;"></div>
  </div>

  <div style="clear:both;"></div>
</div>

