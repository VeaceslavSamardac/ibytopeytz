<?php
$breadcrumb = drupal_set_breadcrumb();
if(!empty($breadcrumb) && is_array($breadcrumb)) {
  echo '<div class="breadcrumb">' . implode(' › ', $breadcrumb) . '</div>';// &rsaquo;

//  $count = count($breadcrumb);
//  if($count > 2) {
//    $count -= 2;
//    echo '<div class="breadcrumb-back">' . $breadcrumb[$count] . '</div>';
//  }
}
