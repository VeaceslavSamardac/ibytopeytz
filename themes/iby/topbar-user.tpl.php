<?php if ($user && $user->uid): ?>
<?php
  $account = user_load($user->uid);

  if (iby_forums_recent_new()) {
    $style = "background: transparent url('/" . drupal_get_path('theme', 'iby') . "/images/recent_blink.gif') left 7px no-repeat;";
  }
  else {
    $style = "background: transparent url('/" . drupal_get_path('theme', 'iby') . "images/recent_none.gif') left 7px no-repeat;";
  }  
?>
<div id="topbar-user">
	<div class="topbar_left">

		<div id="recent-activity-box" style="<?php echo $style; ?>">
			<a href="/recent?c=1" style="font-size:12px;font-weight:bold;color:#fff;text-decoration:none;"><span class="recent_activity_box">Recent activity<?php echo(iby_forums_recent_new()?' (<span class="recent-counter">'.iby_forums_recent_new()."</span>)":'');?></span></a>
		</div>

		<div id="user-news-box">
			<a href="/user/flags/follow" style="font-size:12px;font-weight:bold;color:#fff;text-decoration:none;"><span class="user_news_box">I follow</span></a>
		</div>

		<div id="user-messages-box">
			<a href="/messages" style="font-size:12px;font-weight:bold;color:#fff;text-decoration:none;"><span class="user_messages_box">
			<?php
			$unreadmessages = db_query("
			SELECT COUNT(is_new) as tum FROM pm_index
			WHERE is_new = 1 AND deleted = 0 AND recipient = {$account->uid}
			")->fetchAll();
			$urm = $unreadmessages[0]->tum;
			echo $urm;
			?>
			unread message<?php echo (($urm == 1) ? '' : 's'); ?></span></a>
		</div>
		
	</div> 

	<div class="topbar_right">

		<div id="top-search-box">
			<form class="search-form" action="/search/node" method="post" id="search-form" accept-charset="UTF-8">
				<div>
					<input type="text" id="edit-keys" name="keys" value="Search" size="40" maxlength="255" class="form-text iby_search">
					<input type="submit" class="searchbutton" value="">
				</div>
			</form>
		</div>
	
		<div id="username-box">
	
		
	
			<div class="user_profileimage">
					<?php if($account->picture) { echo theme('image', array('path' => file_create_url($account->picture->uri), 'height' => '35px')); } ?>
				</div>		
			
			<span class="user_username_box"> <a id="myprofileoptions" href="/user" style="font-size:12px;font-weight:bold;color:#fff;text-decoration:none;width:200px;padding-top:3px;">Hello <?php echo $user->name; ?></a></span>	
		<div id="myProfileDrop">
			<div class="header-profile">
				<span><a href="/user" class="profile-link">My profile &rsaquo;</a></span>				
				<span><a href="/user/logout" class="logout-link">Sign out &rsaquo;</a></span>
			</div>
		</div>
  <script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery("#myprofileoptions").tooltip({position: 'bottom center', offset: [8, 0], delay: 500, effect: 'slide'});
		});
	</script>		
		</div>
		
	</div>
</div>
<div style="clear:both;"></div>
<?php endif; ?>
