<?php
	drupal_add_css(sqtools_default_theme_path()."/css/ibyforum.css", array('options' => "file"));
?>
	<?php
		$owner = user_load($uid);
		//vdp($owner);
		  if($owner->picture) {
			echo '<img src="'.file_create_url($owner->picture->uri).'" /><br />'."\n";
		  }

		  echo '<div class="owner-name"><a class="owner-name" href="/user/'.$owner->uid.'">'.$owner->name.'</a></div>';
/*		  if(in_array("VIP", $owner->roles))
			echo '<div class="owner-vip">V.I.P</div>';
*/
	
//		vdp($owner);
		
	$role = $owner->roles;
	$userrole = 'Member';

  foreach($role as $k => $v) {
    if(in_array($v, array('administrator', 'content_adm', 'cp_adm'))) {
      $userrole = 'Administrator';

    } elseif(($v == 'VIP') && ($userrole != 'Administrator')) {
      $userrole = 'VIP';

    } elseif(($v == 'moderator') && ($userrole != 'Administrator') && ($userrole != 'VIP')) {
      $userrole = 'Moderator';
    }
	}
			echo '<div class="member-type">'. $userrole.'</div>';
      //echo '<div class="owner-created">Member since '.date("Y", $owner->created).'</div>';
      echo '<div class="owner-created">Joined: '.date("M Y", $owner->created).'</div>';
      echo '<div class="owner-posts">Total posts: '.iby_profiles_pretifier_get_total_posts($owner).'</div>';
		  if($owner->access > (time()-300))
			echo '<div class="owner-online-status"><div class="owner-online-indicator"></div>Online</div>';
		  else echo '<div class="owner-online-status"><div class="owner-offline-indicator"></div>Offline</div>';
		  
		if(isset($owner->field_profile)) {
		$field_profile = sqtools_get_lang_value($owner->field_profile);
		if($field_profile[0]['value'] == 'coloplast') {
        echo '<div class="owner-coloplast-employee">Coloplast Employee</div>';
      }
		}
		
	?>
