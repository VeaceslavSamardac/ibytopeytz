<?php
/*
Available variables:

$user_profile: An array of profile items. Use render() to print them.
Field variables: for each field instance attached to the user a corresponding variable is defined; e.g., $user->field_example has a variable $field_example defined. When needing to access a field's raw values, developers/themers are strongly encouraged to use these variables. Otherwise they will have to explicitly specify the desired field language, e.g. $user->field_example['en'], thus overriding any language negotiation rule that was previously applied.
*/
$account = &$elements['#account'];

sq_flags_api_set(array('flag_type' => "view", 'entity_type' => "user", 'entity_id' => $account->uid));

//$variables['user_profile'] = array_merge($variables['user_profile'], field_info_instances('user', 'user'));//field_attach_view('user', $account, 'full');//$variables['elements'], $variables);
$user_profile += $variables['user_profile'];

unset($user_profile['field_points']);
unset($user_profile['field_halloffame']);

foreach($user_profile as $field_name=>$field_info) {
  if(substr($field_name, 0, 9) == "field_cc_") unset($user_profile[$field_name]);
}

// Member profile
?>
<div style="clear:both;"></div>

<div class="profile-grouping">
  <h3>Member profile</h3>
<?php
 if(user_access("administer permissions", $user) || user_access("administer users", $user) || ($user->uid == $account->uid)): ?>
  <div class="edit-profile-header">
    <a href="/user/<?php echo $account->uid;?>/edit">Edit profile &rsaquo;</a>
    <div>Visible to all members&nbsp; &nbsp;|&nbsp; &nbsp;</div>
  </div>
<?php endif;?>
  <div style="clear:both;"></div>
</div>

<dl class="fields">
<?php
//echo "<pre>"; var_dump($account); exit;
?>
  <div class="field-grouping">
    <div class="field field-type-text field-label-above">
      <div class="field-label">Username:&nbsp;</div>
      <div class="field-items">
        <div class="field-item even"><?php echo $account->name;?></div>
      </div>
      <div style="clear:both;"></div>
    </div>
  
    <div style="clear:both;"></div>

    <div class="field field-type-text field-label-above">
      <div class="field-label">Joined:&nbsp;</div>
      <div class="field-items">
        <div class="field-item even"><?php echo date("m/d/Y", $account->created);?></div>
      </div>
      <div style="clear:both;"></div>
    </div>
  
<?php if(sqtools_is_admin($user)): ?>
    <div style="clear:both;"></div>

    <div class="field field-type-text field-label-above">
      <div class="field-label">Account status:&nbsp;</div>
      <div class="field-items">
        <div class="field-item even"><?php echo (($account->status) ? "Active" : "Inactive");?></div>
      </div>
      <div style="clear:both;"></div>
    </div>
<?php endif;?>  

    <div style="clear:both;"></div>

    <div class="field field-type-text field-label-above">
      <div class="field-label">Last visit:&nbsp;</div>
      <div class="field-items">
        <div class="field-item even"><?php echo date("m/d/Y", $account->access);?></div>
      </div>
      <div style="clear:both;"></div>
    </div>
  
<?php if(sqtools_is_admin($user) || ($user->uid == $account->uid)): ?>
    <div style="clear:both;"></div>

    <div class="field field-type-text field-label-above">
      <div class="field-label">Password:&nbsp;</div>
      <div class="field-items">
        <div class="field-item even">* * * * * *</div>
      </div>
      <div style="clear:both;"></div>
    </div>
<?php endif; ?>
<?php
	$role = $account->roles;
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
?>
    <div class="field field-type-text field-label-above">
      <div class="field-label">Member type:&nbsp;</div>
      <div class="field-items">
        <div class="field-item even"><?php echo $userrole; ?></div>
      </div>
      <div style="clear:both;"></div>
    </div>	
	
    <div style="clear:both;"></div>

  </div>

  <div class="flag-buttons">

    <?php /*if($user->uid != $account->uid) {*/ ?>
    <div class="like-button">
    <?php
    $args = array('flag_type' => "like", 'uid' => $user->uid, 'flag_group' => "profile", 'entity_type' => "user", 'entity_id' => $account->uid);
    $is_flagged = (sq_flags_api_get($args) ? true : false);
    $flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
    ?>
      <a href="/sq_flags_api/toggle/like/profile/user/<?php echo $account->uid;?>?sq_flags_api_cb=sq_flags_cb" id="like_profile_<?php echo $account->uid;?>" class="use-ajax sq-like-link <?php echo $flag_class;?>"> </a>

      <div style="clear:both;"></div>

      <div class="like-text like-user-<?php echo $account->uid;?>">
<?php
      $args = array('flag_type' => "like", 'entity_type' => "user", 'entity_id' => $account->uid);
?>
        <span class="count-number"><?php echo sq_flags_api_get_count($args);?></span> likes
      </div>
    </div>
    
    <div style="clear:both;"></div>

    <div class="follow-button">
    <?php
    $args = array('flag_type' => "follow", 'uid' => $user->uid, 'flag_group' => "profile", 'entity_type' => "user", 'entity_id' => $account->uid);
    $is_flagged = (sq_flags_api_get($args) ? true : false);
    $flag_class = (($is_flagged) ? 'sq-flags-active' : 'sq-flags-inactive');
    ?>
      <a href="/sq_flags_api/toggle/follow/profile/user/<?php echo $account->uid;?>?sq_flags_api_cb=sq_flags_cb" id="follow_profile_<?php echo $account->uid;?>" class="use-ajax sq-follow-link <?php echo $flag_class;?>"> </a>

      <div style="clear:both;"></div>

      <div class="follow-text follow-user-<?php echo $account->uid;?>">
<?php
      $args = array('flag_type' => "follow", 'entity_type' => "user", 'entity_id' => $account->uid);
?>
        <span class="count-number"><?php echo sq_flags_api_get_count($args);?></span> followers
      </div>
    </div>
    
    <div style="clear:both;"></div>
    <?php /*}*/ ?>


  </div>

</dl>


<?php

// Profile picture
?>
<div style="clear:both;"></div>

<div class="profile-grouping">
  <h3>Profile picture</h3>
<?php if(sqtools_is_admin($user) || ($user->uid == $account->uid)): ?>
  <div class="edit-profile-header">
    <a href="/user/<?php echo $account->uid;?>/edit/picture">Edit photo &rsaquo;</a>
<!--    <a href="/user/<?php echo $account->uid;?>/edit/profile_picture">Edit photo &rsaquo;</a>-->
    <div>Visible to all members&nbsp; &nbsp;|&nbsp; &nbsp;</div>
  </div>
<?php endif;?>
  <div style="clear:both;"></div>
</div>

<dl>
<?php
echo $user_profile['user_picture']['#markup'];
unset($user_profile['user_picture']);
?>
</dl>

<?php
// Signature
?>
<div style="clear:both;"></div>

<?php if(sqtools_is_admin($user) || ($user->uid == $account->uid)): ?>

<div class="profile-grouping">
  <h3>Signature</h3>
  <div class="edit-profile-header">
    <a href="/user/<?php echo $account->uid;?>/edit/signature">Edit signature &rsaquo;</a>
<!--    <a href="/user/<?php echo $account->uid;?>/edit/signature">Edit signature &rsa quo;</a>-->
    <div>Visible on all your posts&nbsp; &nbsp;|&nbsp; &nbsp;</div>
  </div>
  <div style="clear:both;"></div>
</div>

<dl>
  <div class="field-items" id="preview-signature">
<?php
  //if(trim($elements['#account']->signature) == "Array") {
  //  $tmpAccount = user_load($account->uid);
  //  $tmpAccount->signature = "";
  //  user_save($tmpAccount);
  //}
  echo nl2br(strip_tags($elements['#account']->signature));
?>
  </div>
</dl>

  <div style="clear:both;"></div>
<?php endif;?>


<div class="profile"<?php print $attributes; ?>>
<?php
print render($user_profile);
?>
</div>

