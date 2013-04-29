<?php
/*******************************************************************/
/* Original version is found in includes/form.inc theme_fieldset() */
/*******************************************************************/

/* this works nicely
<fieldset style="border:none;">
<div style="border-bottom:2px dotted #000;">
<legend style="padding-left:0;width:50px;float:left;">Personalia</legend>
<div style="float:right;width:30px;">bla</div>
<div style="clear:both;"></div>
</div>
*/



$element = $variables['element'];
element_set_attributes($element, array('id'));
_form_set_class($element, array('form-wrapper'));

$output = '<fieldset' . drupal_attributes($element['#attributes']) . '>';
if (!empty($element['#title'])) {
  // Always wrap fieldset legends in a SPAN for CSS positioning.
  //  $output .= '<div class="legend-container">'."\n"
  $output .= '<legend><span class="fieldset-legend">' . $element['#title'] . '</span></legend>';


//<div style="border-bottom:2px dotted #000;">
//<legend style="padding-left:0;width:50px;float:left;">Personalia</legend>
//<div style="float:right;width:30px;">bla</div>
//<div style="clear:both;"></div>
//</div>




}
$output .= '<div class="fieldset-wrapper">';
if (!empty($element['#description'])) {
  $output .= '<div class="fieldset-description">' . $element['#description'] . '</div>';
}
$output .= $element['#children'];
if (isset($element['#value'])) {
  $output .= $element['#value'];
}
$output .= '</div>';
$output .= "</fieldset>\n";
echo $output;
