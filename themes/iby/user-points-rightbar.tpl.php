<?php
$points = 0;
if(isset($account->field_points) && !empty($account->field_points) && is_array($account->field_points)) {
  $points = intval($account->field_points['und'][0]['value']);
}
$points = number_format($points, 0, "", ",");
?>
<div class="user-rightbar user-points-rightbar box-shadow">

  <div class="user-rightbar-top">
    <h3>My points</h3>
    <h2><?php echo $points;?></h2>
    <div style="clear:both;"></div>
  </div>

  <div style="clear:both;"></div>

<?php /* This is for later
  <div class="user-rightbar-bottom">
    <a href="javascript:void(0);">Use points in Points Shop &rsaquo;</a><br />
    <a href="javascript:void(0);">Show points history &rsaquo;</a>
    <div style="clear:both;"></div>
  </div>

  <div style="clear:both;"></div>
*/ ?>

<?php if(sqtools_is_admin($user) || user_access("administer users", $user)): ?>
  <div class="user-rightbar-edit" style="clear:both;border-top:1px dotted #666;">
    <a href="/user/<?php echo $account->uid;?>/edit">Edit &rsaquo;</a>
  </div>

  <div style="clear:both;"></div>
<?php endif; ?>

</div>
