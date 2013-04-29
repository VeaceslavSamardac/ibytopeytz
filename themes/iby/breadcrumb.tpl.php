<?php
if((arg(0) == "node") && (arg(1))) {
  $node = node_load(arg(1));
  $breadcrumb[] = $node->title;

} elseif(arg(0) == "user") {
  $breadcrumb = array('<a href="/">Home</a>');

  if(is_numeric(arg(1))) {
    $breadcrumb = array('<a href="/">Home</a>');
    $account = user_load(arg(1));

    $breadcrumb[] = '<a href="/">'.$account->name.'</a>';

    if(!arg(2)) $breadcrumb[] = "Basic info";
    elseif(arg(2) == "edit") $breadcrumb[] = "edit".(arg(3) ? " ".arg(3) : " profile");

  } elseif(arg(1) == "flags") {
    if(arg(2) && arg(3)) {
      $trans_array = array('user'=>"Members", 'challenge'=>"Challenges", 'solution'=>"Contributions", 'forum'=>"Forums", 'topic'=>"Topics");
      $breadcrumb[] = '<a href="/user/flags">I follow</a>';
      $breadcrumb[] = $trans_array[arg(3)];
    }
  }

} elseif(arg(0) == "messages") {
  $breadcrumb = array('<a href="/">Home</a>');

  if(!arg(1)) $breadcrumb[] = "Mailbox";
  else {
    $breadcrumb[] = '<a href="/messages">Mailbox</a>';

    if(arg(1) == "view") $breadcrumb[] = 'Read';
    elseif(arg(1) == "new") $breadcrumb[] = 'New';
  }
  
} elseif(arg(0) == "recent") {
  $breadcrumb = array('<a href="/">Home</a>');

  $breadcrumb[] = "Recent activity";
}

if(!empty($breadcrumb) && is_array($breadcrumb)) {

  if(isset($breadcrumb[1]) && preg_match('/<a[^>]+href="\/forum">/is', $breadcrumb[1]))
    unset($breadcrumb[1]);

  if(isset($breadcrumb[2]))
    $breadcrumb[2] = str_replace("Chat forums", "Forums", $breadcrumb[2]);

  echo '<div class="breadcrumb">' . implode(' › ', $breadcrumb) . '</div>';// &rsaquo;
}
