<?php
if($_SERVER['SERVER_NAME'] == "iby.localhost") echo "<!-- html--ajax.tpl.php (sq_ajax_popup) -->\n";
//html--ajax.tpl.php
/**
 * @file
 * HTML AJAX page.
 */
//var_dump($variables);exit;
echo $page;
//
////$all_scripts = drupal_get_js('header', NULL);
////echo "<pre>"; var_dump($all_scripts);
//echo $scripts;
//

if(preg_match_all('/.*?(<script[^>]*?src="[^"]+(autocomplete\.js|date|time)[^"]*"[^>]*>[^<]*<\/script>).*?/is', $scripts, $matches, PREG_SET_ORDER)) {
  foreach($matches as $match) echo $match[1]."\n";
}

//echo preg_replace('/.*?(<script[^>]*?src="[^"]+autocomplete.js[^"]*"[^>]*>[^<]*<\/script>).*/is', '$1', $scripts);
//echo preg_replace('/.*?(<script[^>]*?src="[^"]+date[^"]*"[^>]*>[^<]*<\/script>).*/is', '$1', $scripts);
//echo preg_replace('/.*?(<script[^>]*?src="[^"]+time[^"]*"[^>]*>[^<]*<\/script>).*/is', '$1', $scripts);

echo preg_replace('/\s*<script[^>]*?src="[^"]+"[^>]*>[^<]*<\/script>\s*/is', '', $scripts);
?>
