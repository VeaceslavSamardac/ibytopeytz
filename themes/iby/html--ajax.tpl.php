<?php
/**
 * @file
 * HTML AJAX page.
 */

echo $page;

if(preg_match_all('/.*?(<script[^>]*?src="[^"]+(autocomplete\.js|date|time)[^"]*"[^>]*>[^<]*<\/script>).*?/is', $scripts, $matches, PREG_SET_ORDER)) {
  foreach($matches as $match) echo $match[1]."\n";
}

echo preg_replace('/\s*<script[^>]*?src="[^"]+"[^>]*>[^<]*<\/script>\s*/is', '', $scripts);
