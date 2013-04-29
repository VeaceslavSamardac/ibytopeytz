<div class="user-rightbar user-com-rightbar box-shadow">
  <div class="user-rightbar-top">
    <h3>Communication channels</h3>
  </div>

  <div class="user-rightbar-bottom">
<?php if($user->uid == $account->uid): ?>
    <div class="notice">(Visible to all members)</div>

    <div style="clear:both;"></div>
<?php endif;?>
    <div class="cc_item pmessage">
      <div class="label">Private messaging</div>
      <div class="value"><a href="/messages/new/<?php echo $account->uid;?>">Send a message</a></div>
      <div style="clear:both;"></div>
    </div>

<?php
if(isset($account->field_cc_email) && count($account->field_cc_email)) {
  $langs = array_keys($account->field_cc_email);
  $cc_email = $account->field_cc_email[$langs[0]][0]['value'];
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
if(isset($account->field_cc_msn) && count($account->field_cc_msn)) {
  $langs = array_keys($account->field_cc_msn);
  $cc_msn = $account->field_cc_msn[$langs[0]][0]['value'];
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
if(isset($account->field_cc_aim) && count($account->field_cc_aim)) {
  $langs = array_keys($account->field_cc_aim);
  $cc_aim = $account->field_cc_aim[$langs[0]][0]['value'];
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
if(isset($account->field_cc_skype) && count($account->field_cc_skype)) {
  $langs = array_keys($account->field_cc_skype);
  $cc_skype = $account->field_cc_skype[$langs[0]][0]['value'];
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

  </div>

  <div style="clear:both;"></div>

<?php
  if(sqtools_is_admin($user) || ($account->uid == $user->uid)) {
?>
  <div class="user-rightbar-edit" style="clear:both;border-top:1px dotted #666;">
    <a href="/user/<?php echo $account->uid;?>/edit/cc">Edit &rsaquo;</a>
  </div>

  <div style="clear:both;"></div>
<?php
}
?>

</div>
