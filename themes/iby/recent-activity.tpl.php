<?php
drupal_add_css(sqtools_default_theme_path()."/css/recent-activity.css", array('options' => "file"));

if(!$user->uid) {
  drupal_goto('no_access');
}

$filters = iby_forums_recent_get_filters();
?>

<div class="recent-topbar">

  <div class="recent-topbar-left">Welcome to activity overview<?php echo(iby_forums_recent_new()?' (<span class="recent-counter">'.iby_forums_recent_new()."</span> new!)":'');?></div>

  <div class="recent-topbar-right">
    <div class="recent-area-label">Select area:</div> 
<?php
$base_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$types = array('all', 'continence', 'ostomy');
$current_type = 'all';
if($filters->type && in_array($filters->type, $types)) $current_type = $filters->type;
foreach($types as $type) {
  echo '    <a href="'.UrlTools::addParams($base_url, array('type'=>$type)).'" class="ibyforum-type-'.$type.' '.$parent_slug.'-type-'.$type;
  if($current_type == $type) echo ' ibyforum-type-active '.$parent_slug.'-type-active';
  echo '">'.ucfirst($type).'</a>'."\n";
}
?>
  </div>
</div>

<div style="clear:both;"></div>

<div id="ibyforum-recent" class="ibyforum-recent">
  <div id="ibyforum-content" class="ibyforum-content">

    <ul id="time-filter">
      <li class="label">Show since last:</li>
<?php
$curUrl = UrlTools::parse();
$params = UrlTools::parseParams($curUrl['query']);

$params['tl'] = mktime((date("H")-24),0,0,date("m"),date("d"),date("Y"));
$link = l('day', substr($curUrl['path'],1), array('query' => $params));
echo '      <li class="link'.(($filters->time_limit && ($filters->time_limit == $params['tl']))?" active":"").'">'.$link.'</li><li class="spacer">|</li>';

$params['tl'] = mktime(0,0,0,date("m"),(date("d")-7),date("Y"));
$link = l('week', substr($curUrl['path'],1), array('query' => $params));
echo '      <li class="link'.(($filters->time_limit && ($filters->time_limit == $params['tl']))?" active":"").'">'.$link.'</li><li class="spacer">|</li>';

$params['tl'] = mktime(0,0,0,(date("m")-1),date("d"),date("Y"));
$link = l('month', substr($curUrl['path'],1), array('query' => $params));
echo '      <li class="link'.(($filters->time_limit && ($filters->time_limit == $params['tl']))?" active":"").'">'.$link.'</li><li class="spacer">|</li>';

$params['tl'] = mktime(0,0,0,date("m"),date("d"),(date("Y")-1));
$link = l('year', substr($curUrl['path'],1), array('query' => $params));
echo '      <li class="link'.(($filters->time_limit && ($filters->time_limit == $params['tl']))?" active":"").'">'.$link.'</li><li class="spacer">|</li>';

$params['tl'] = 0;
$link = l('all', substr($curUrl['path'],1), array('query' => $params));
echo '      <li class="link'.(($filters->time_limit === 0)?" active":"").'">'.$link.'</li>';
?>
    </ul>

    <div style="clear:both;">&nbsp;</div>

<?php
$curstamp = time();

if($activities) {
  if(!$filters->order_by) $filters->order_by = "last_activity";
  if(!$filters->direction) $filters->direction = "ASC";

  $orderDirections = array('ASC'=>"DESC",'DESC'=>"ASC");

  $orderByClass = "orderby";
  if($filters->direction && in_array($filters->direction, $orderDirections)) $orderByClass .= ' '.strtolower($filters->direction);

  //echo theme('pager');
  //echo '<div style="clear:both;"></div>';

  echo '<a href="recent/mark/all?cb=recent_all_read" id="mark_all_read" class="use-ajax">Mark all as read</a><br />';

  echo '<table id="recent-list">';
  echo '<tr>';

  $curUrl = UrlTools::parse();
  $params = UrlTools::parseParams($curUrl['query']);

  echo '<th class="mark">';
  echo '<a href="javascript:void(0);" id="mark_as_read">Mark selected<br />as read</a><br />';
  //echo '<select name="">';
  //echo '<option value="0">Mark as</option>';
  //echo '<option value="read">Read</option>';
  //echo '<option value="unread">Unread</option>';
  //echo '</select>';
  echo '</th>';

  $params['o'] = "marked";
  if($filters->direction && $filters->order_by && ($filters->order_by == "marked")) {
    unset($params['o']);
    unset($params['d']);
  }
  else $params['d'] = "ASC";
  $link = l('My favorites', substr($curUrl['path'],1), array('query' => $params));
  echo '<th class="sortable marked'.(($filters->order_by && ($filters->order_by == "marked"))?" ".$orderByClass:'').'">'.$link.'</th>';

  $params['o'] = "section";
  if($filters->direction && in_array($filters->direction, $orderDirections) && $filters->order_by && ($filters->order_by == "section"))
    $params['d'] = $orderDirections[$filters->direction];
  else $params['d'] = "ASC";
  $link = l('Section', substr($curUrl['path'],1), array('query' => $params));
  echo '<th class="sortable section'.(($filters->order_by && ($filters->order_by == "section"))?" ".$orderByClass:'').'">'.$link.'</th>';

  $params['o'] = "forum";
  if($filters->direction && in_array($filters->direction, $orderDirections) && $filters->order_by && ($filters->order_by == "forum"))
    $params['d'] = $orderDirections[$filters->direction];
  else $params['d'] = "ASC";
  $link = l("Challenge / Forum /<br />VIP-room", substr($curUrl['path'],1), array('query' => $params, 'html'=>true));
  echo '<th class="sortable forum'.(($filters->order_by && ($filters->order_by == "forum"))?" ".$orderByClass:'').'">'.$link.'</th>';

  $params['o'] = "node";
  if($filters->direction && in_array($filters->direction, $orderDirections) && $filters->order_by && ($filters->order_by == "node"))
    $params['d'] = $orderDirections[$filters->direction];
  else $params['d'] = "ASC";
  $link = l('Topic / Contribution / Problem', substr($curUrl['path'],1), array('query' => $params));
  echo '<th class="sortable node'.(($filters->order_by && ($filters->order_by == "node"))?" ".$orderByClass:'').'">'.$link.'</th>';
 
  $params['o'] = "last_activity";
  if($filters->direction && in_array($filters->direction, $orderDirections) && $filters->order_by && ($filters->order_by == "last_activity"))
    $params['d'] = $orderDirections[$filters->direction];
  else $params['d'] = "ASC";
  $link = l('When', substr($curUrl['path'],1), array('query' => $params));
  echo '<th class="sortable last-activity'.(($filters->order_by && ($filters->order_by == "last_activity"))?" ".$orderByClass:'').'">'.$link.'</th>';

  //$params['o'] = "user";
  //if($filters->direction && in_array($filters->direction, $orderDirections) && $filters->order_by && ($filters->order_by == "user"))
  //  $params['d'] = $orderDirections[$filters->direction];
  //else $params['d'] = "ASC";
  //$link = l('By', substr($curUrl['path'],1), array('query' => $params));
  //echo '<th class="sortable user'.(($filters->order_by && ($filters->order_by == "user"))?" ".$orderByClass:'').'">'.$link.'</th>';

  echo '<th class="user">By</th>';

  echo '<th class="unread-comments">'.t('Unread comments').'</th>';

  $params['o'] = "comments";
  if($filters->direction && in_array($filters->direction, $orderDirections) && $filters->order_by && ($filters->order_by == "comments"))
    $params['d'] = $orderDirections[$filters->direction];
  else $params['d'] = "ASC";
  $link = l('Total comments', substr($curUrl['path'],1), array('query' => $params));
  echo '<th class="sortable comments'.(($filters->order_by && ($filters->order_by == "comments"))?" ".$orderByClass:'').'">'.$link.'</th>';

  echo '</tr>';
  
  foreach($activities as $row) {
    $classes = array();
    if($row->is_sticky) $classes[] = "sticky";
    if($row->new_content) $classes[] = "new-content";

    echo '<tr';
    if(count($classes)) echo ' class="'.implode(" ", $classes).'"';
    echo ' id="item_'.(($row->nid)?'node_'.$row->nid:'taxonomy_term_'.$row->tid).'">';


    if($row->nid) {
      echo '<td class="mark"><input type="checkbox" class="check_nodes" name="nodes['.$row->nid.']" id="nodes_'.$row->nid.'" value="'.$row->nid.'"'.((!$row->new_content)?' disabled="disabled"':'').' /></td>';

      $link_url = "/sq_flags_api/toggle/starmark/starmarks/node/".$row->nid."?sq_flags_api_cb=sq_flags_cb";
      $link_id = "starmark_starmarks_".$row->nid;
      $link_class = (($row->starmarked) ? 'sq-flags-active' : 'sq-flags-inactive');

    } else {
      echo '<td class="mark"><input type="checkbox" class="check_taxonomy_terms" name="taxonomy_terms['.$row->tid.']" id="taxonomy_terms_'.$row->tid.'" value="'.$row->tid.'"'.((!$row->new_content)?' disabled="disabled"':'').' /></td>';

      $link_url = "/sq_flags_api/toggle/starmark/starmarks/taxonomy_term/".$row->tid."?sq_flags_api_cb=sq_flags_cb";
      $link_id = "starmark_starmarks_".$row->tid;
      $link_class = (($row->starmarked) ? 'sq-flags-active' : 'sq-flags-inactive');
    }

    echo '<td class="marked"><a href="'.$link_url.'" id="'.$link_id.'" class="use-ajax '.$link_class.'">&nbsp;</a></td>';

    echo '<td class="section">'.l($row->section_name, 'forum/'.$row->forum_parent).'</td>';

    if($row->tid != 40) {
      $params = array();
      if(isset($row->nid) && $row->nid) $params = array('fragment'=>StringTools::slug($row->section_name, "-").$row->nid);
      echo '<td class="forum">'.l(StringTools::shorten($row->forum_name, 35, "..."), 'forum/'.$row->tid, $params).'</td>';
    } else echo '<td class="forum">-</td>';

    $row->type = 'forum';

    $first_unread_page = array();
    $first_unread_number = 1;
    if($row->nid) {
      if($row->new_comments) {
        $first_unread_page = comment_new_page_count($row->comments, 1, $row);
        $first_unread_number = $row->comments;
        echo '<td class="node">'.l(StringTools::shorten($row->node_title, 70, "..."), 'node/'.$row->nid, array('query'=>$first_unread_page, 'fragment'=>'comment-number'.$first_unread_number)).'</td>';
      } else {
        echo '<td class="node">'.l(StringTools::shorten($row->node_title, 70, "..."), 'node/'.$row->nid).'</td>';
      }
      
    } else echo '<td class="node">-</td>';

    $params = comment_new_page_count($row->comments, 1, $row);
    if(($curstamp - $row->last_activity) < 86400) $link_title = StringTools::timeAgo($row->last_activity, false, true);
    $link_title = date("d M Y", $row->last_activity);
    if($row->new_comments) {
      $link = l($link_title, 'node/'.$row->nid, array('query'=>$first_unread_page, 'fragment'=>'comment-number'.$first_unread_number));
    } else {
      $link = l($link_title, 'node/'.$row->nid);
    }
    echo '<td class="last-activity">'.$link.'</td>';

    echo '<td class="user">'.l($row->last_contributor->name, 'user/'.$row->last_contributor_uid).'</td>';

    if($row->new_comments) {
      echo '<td class="unread-comments">'.l($row->new_comments, 'node/'.$row->nid, array('query'=>$first_unread_page, 'fragment'=>'comment-number'.$first_unread_number)).'</td>';
    } else echo '<td class="unread-comments">-</td>';

    echo '<td class="comments">'.$row->comments.'</td>';

    //echo '<td>'.$row->new_comments.'</td>';
    echo '</tr>';
  }
  echo '</table>';
}
?>
  	<div style="clear:both;"></div>

<?php
  echo theme('pager');
?>
  	<div style="clear:both;"></div>

    <ul id="pages-filter">
<?php
$curUrl = UrlTools::parse();
$params = UrlTools::parseParams($curUrl['query']);

unset($params['page']);

$params['l'] = 10;
$link = l($params['l'], substr($curUrl['path'],1), array('query' => $params));
echo '      <li class="link'.(($filters->limit && ($filters->limit == $params['l']))?" active":"").'">'.$link.'</li><li class="spacer">/</li>';

$params['l'] = 25;
$link = l($params['l'], substr($curUrl['path'],1), array('query' => $params));
echo '      <li class="link'.((!$filters->limit || ($filters->limit && ($filters->limit == $params['l'])))?" active":"").'">'.$link.'</li><li class="spacer">/</li>';

$params['l'] = 50;
$link = l($params['l'], substr($curUrl['path'],1), array('query' => $params));
echo '      <li class="link'.(($filters->limit && ($filters->limit == $params['l']))?" active":"").'">'.$link.'</li><li class="spacer">/</li>';

$params['l'] = 100;
$link = l($params['l'], substr($curUrl['path'],1), array('query' => $params));
echo '      <li class="link'.(($filters->limit && ($filters->limit == $params['l']))?" active":"").'">'.$link.'</li><li class="spacer"></li>';

echo '      <li class="link">&nbsp;per page</li><!--<li class="spacer">/</li>-->';

//unset($params['l']);
//$link = l('All', substr($curUrl['path'],1), array('query' => $params));
//echo '      <li class="link'.((!$filters->limit || !$filters->limit)?" active":"").'">'.$link.'</li>';
?>
    </ul>

    <div style="clear:both;"></div>

  </div> <!-- #recent-content -->

  <div style="clear:both;"></div>

</div> <!-- #current-recent -->

<div style="clear:both;"></div>

<?php /*echo theme('pager', array('element'=>0));*/ ?>

