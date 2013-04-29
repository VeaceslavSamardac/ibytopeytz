<?php
drupal_add_css(sqtools_default_theme_path()."/css/".$variables['parent_slug'].".css", array('options' => "file"));

// Breadcrumb navigation:
$breadcrumb[] = l(t('Home'), NULL);
if ($variables['parents']) {
  $variables['parents'] = array_reverse($variables['parents']);
  foreach ($variables['parents'] as $p) {
    if ($p->tid == $variables['tid']) {
      $title = $p->name;
      $breadcrumb[] = $p->name;
      
    } else {
      $breadcrumb[] = l($p->name, 'forum/' . $p->tid);
    }
  }
}

$breadcrumb[] = l($forum->name, 'forum/' . $forum->tid);

drupal_set_breadcrumb($breadcrumb);
drupal_set_title($title);


//echo "<pre>";
//var_dump($files);
//exit;
echo theme('vip__rooms__menu', array('forum_tid' => $forum->tid));

$base_url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$headers = array('filename' => array('d' => "asc", 'label' => "Name"),
                 'filetype' => array('d' => "asc", 'label' => "File type"),
                 'filesize' => array('d' => "asc", 'label' => "File size"),
                 'name' => array('d' => "asc", 'label' => "Uploaded by"),
                 'timestamp' => array('d' => "asc", 'label' => "Date")
                 );

$orderby_names = array('filename', 'filetype', 'filesize', 'name', 'timestamp');
$direction = ((isset($_GET['d']) && (strtolower($_GET['d']) == "desc")) ? "desc" : "asc");
if(isset($_GET['o']) && in_array($_GET['o'], $orderby_names)) $orderby = $_GET['o'];
else $orderby = "filename";

if($direction == "asc") $headers[$orderby]['d'] = "desc";
?>

<div style="width:805px;">

<div style="width:550px;float:left;margin-right:20px;">

<table class="sticky-enabled">

  <thead>
    <tr>
<?php
foreach($headers as $order=>$header) {
  echo '<th>';
  echo '<a href="'.UrlTools::addParams($base_url, array('o' => $order, 'd' => $header['d'])).'" class="sortable'.(($order == $orderby)?' active '.$direction : ' '.$header['d']).'">';
  echo $header['label'];
  echo '</a>';
  echo '</th>';
}
?>
    </tr>
  </thead>

  <tbody>
<?php
foreach($files as $file) {
  $file_owner = entity_load('user', array($file->uid));
?>
    <tr>
      <td style="text-align:left;color:#0fa9c1;font-size:bold;">
        <strong><?php echo $file->filename; ?></strong>
        <div class="content-divider" style="border-top:1px dotted #666;padding:0;margin:5px;margin-left:0px;"></div>
        <a class="download-link" style="text-decoration:none;color:#666;font-weight:bold;" <?php echo ((substr($file->filemime, 0, 5) == "image") ? 'target="_blank" ' : "");?>href="<?php echo file_create_url($file->uri);?>">Download file</a></td>
      <td style="background-color:#f9f9f9;"><?php echo substr($file->filename, (strrpos($file->filename, "."))); ?></td>
      <td><?php echo SizeConverter::bytesToHuman($file->filesize); ?>b</td>
      <td style="background-color:#f9f9f9;"><?php echo $file->name; ?></td>
      <td><?php echo date("M d, Y", $file->timestamp)."<br />".date("h:i a", $file->timestamp); ?></td>
    </tr>
<?php
}
?>
  </tbody>

</table>

</div>

<div id="<?php echo $parent_slug;?>-front-right" style="margin-top:24px;">
<?php
echo theme('right_box_recent_activity', array('parent_slug' => $parent_slug, 'tid' => arg(1)));
?>
  <div style="clear:both;"></div>

</div>
<div style="clear:both;"></div>

</div>