<?php
if($_SERVER['SERVER_NAME'] == "iby.localhost") {
  cache_clear_all();
  cache_clear_all();
  menu_rebuild();
}

drupal_add_library('system', 'effects');

//function iby_pager($variables) {
//   // Blat the pager out of existence
//echo "<pre>"; var_dump($variables); exit;
//  return "";//$variables;
//}

function iby_menu_link_alter(&$item) {
  //echo "<pre>"; var_dump($item); echo "</pre>";

  static $currentPosition;
  if($currentPosition) {
  } else {
  }
	//  echo "menu_link_alter: <pre>"; var_dump($item); exit;
}

function iby_menu_alter(&$items) {
  //echo "<pre>"; var_dump($items); echo "</pre>"; exit;
  //echo "menu_alter: <pre>"; var_dump($items); exit;
}

function iby_pager_link($variables) {
  $text = $variables['text'];
  $page_new = $variables['page_new'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $attributes = $variables['attributes'];

  $page = isset($_GET['page']) ? $_GET['page'] : '';
  if ($new_page = implode(',', pager_load_array($page_new[$element], $element, explode(',', $page)))) {
    $parameters['page'] = $new_page;
  }

  $query = array();
  if (count($parameters)) {
    $query = drupal_get_query_parameters($parameters, array());
  }
  if ($query_pager = pager_get_query_parameters()) {
    $query = array_merge($query, $query_pager);
  }

  if(!isset($query['keys']) || !$query['keys']) {
    if(isset($_POST['keys']) && $_POST['keys']) $query['keys'] = $_POST['keys'];
    elseif(isset($_GET['keys']) && $_GET['keys']) $query['keys'] = $_GET['keys'];
  }

  // Set each pager link title
  if (!isset($attributes['title'])) {
    static $titles = NULL;
    if (!isset($titles)) {
      $titles = array(
        t('« first') => t('Go to first page'),
        t('‹ previous') => t('Go to previous page'),
        t('next ›') => t('Go to next page'),
        t('last »') => t('Go to last page'),
      );
    }
    if (isset($titles[$text])) {
      $attributes['title'] = $titles[$text];
    }
    elseif (is_numeric($text)) {
      $attributes['title'] = t('Go to page @number', array('@number' => $text));
    }
  }

//$bla = l($text, $_GET['q'], array('attributes' => $attributes, 'query' => $query, 'fragment' => 'pager-marker'));
//echo "<pre>"; var_dump($bla); echo "</pre>"; exit;

  return l($text, $_GET['q'], array('attributes' => $attributes, 'query' => $query, 'fragment' => 'pager-marker'));
}


function iby_pager_first($variables) {
  $text = $variables['text'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  global $pager_page_array;
  $output = theme('pager_link', array('text' => $text, 'page_new' => pager_load_array(0, $element, $pager_page_array), 'element' => $element, 'parameters' => $parameters));
  return $output;
}

function iby_pager_last($variables) {
  $text = $variables['text'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  global $pager_page_array, $pager_total;
  $output = theme('pager_link', array('text' => $text, 'page_new' => pager_load_array($pager_total[$element] - 1, $element, $pager_page_array), 'element' => $element, 'parameters' => $parameters));
  return $output;
}

function iby_pager_previous($variables) {
  $text = $variables['text'];
  $element = $variables['element'];
  $interval = $variables['interval'];
  $parameters = $variables['parameters'];

  global $pager_page_array;
  $output = '';

  $page_new = pager_load_array($pager_page_array[$element] - $interval, $element, $pager_page_array);

  if ($page_new[$element] < 1) {
    $output = theme('pager_first', array('text' => $text, 'element' => $element, 'parameters' => $parameters));

  } else {
    $output = theme('pager_link', array('text' => $text, 'page_new' => $page_new, 'element' => $element, 'parameters' => $parameters));
  }

  return $output;
}

function iby_pager_next($variables) {
  $text = $variables['text'];
  $element = $variables['element'];
  $interval = $variables['interval'];
  $parameters = $variables['parameters'];
  global $pager_page_array, $pager_total;
  $output = '';

  $page_new = pager_load_array($pager_page_array[$element] + $interval, $element, $pager_page_array);

  if ($page_new[$element] == ($pager_total[$element] - 1)) {
    $output = theme('pager_last', array('text' => $text, 'element' => $element, 'parameters' => $parameters));

  } else {
    $output = theme('pager_link', array('text' => $text, 'page_new' => $page_new, 'element' => $element, 'parameters' => $parameters));
  }

  return $output;
}

function iby_time_left($endstamp) {
  // 60 = minute
  // 3600 = hour
  // 86400 = day
  // 604800 = week
  // 2592000 = month (30 days)
  // 31536000 = year (365 days)

  $parts = array();
  $timediff = ($endstamp - time());
  if($timediff < 1) return;

  if($timediff >= 31536000) {
    $years = floor($timediff / 31536000);
    return $years." year".(($years > 1)?"s":"");

  } elseif($timediff >= 2592000) {
    $months = floor($timediff / 2592000);
    return $months." month".(($months > 1)?"s":"");

  } elseif($timediff >= 604800) {
    $weeks = floor($timediff / 604800);
    return $weeks." week".(($weeks > 1)?"s":"");

  } elseif($timediff >= 86400) {
    $days = floor($timediff / 86400);
    return $days." day".(($days > 1)?"s":"");

  } elseif($timediff >= 3600) {
    $hours = floor($timediff / 3600);
    return $hours." hour".(($hours > 1)?"s":"");

  } elseif($timediff >= 60) {
    $minutes = floor($timediff / 60);
    return $minutes." minute".(($minutes > 1)?"s":"");

  } else {
    return $timediff." second".(($seconds > 1)?"s":"");
  }
}

function timeago($created) {
  return StringTools::timeAgo($created);
}

function vdp($array, $kill_after=false) {
	echo "<pre>";
	var_dump($array);
	echo "</pre>";
  if($kill_after) exit();
}

function vds($array, $kill_after=false) {
	echo "<!--";
	var_dump($array);
	echo "-->";
	if($kill_after) exit();
}	

function iby_theme($existing, $type, $theme, $path) {
  $templates = array();
  $templates['bottom_footer_bar'] = array('template' => "bottom-footer-bar");
  $templates['user_preview_box'] = array('template' => "user-preview-box");
  $templates['node_follow_list'] = array('template' => "node-follow-list");
  $templates['forum_follow_list'] = array('template' => "forum-follow-list");
  $templates['front_page_slider'] = array('template' => "front-page-slider");
  $templates['user_dashboard'] = array('template' => "user-dashboard");
  $templates['user_stats_rightbar'] = array('template' => "user-stats-rightbar");
  $templates['user_points_rightbar'] = array('template' => "user-points-rightbar");
  $templates['user_com_rightbar'] = array('template' => "user-com-rightbar");
  $templates['topbar_user'] = array('template' => "topbar-user");
  $templates['page__comment__ajax'] = array('template' => "page--comment--ajax");
  $templates['user_ifollow_challenges'] = array('template' => "user-ifollow-challenges");
  $templates['user_ifollow_members'] = array('template' => "user-ifollow-members");
  $templates['user_ifollow_solutions'] = array('template' => "user-ifollow-solutions");
  $templates['user_ifollow_forums'] = array('template' => "user-ifollow-forums");
  $templates['user_ifollow_topics'] = array('template' => "user-ifollow-topics");
  $templates['user_info_box'] = array('template' => "user-info-box");
  $templates['mydrafts'] = array('template' => "mydrafts");
  $templates['recent_activity'] = array('template' => "recent-activity");

  
  $templates['user_register_form'] = array(
    'render element' => 'form',
    'template' => 'user-register-form',
  );
  
  $templates['textfield_custom'] = array(
    'render element' => 'element',
  );
  
  $templates['pass_custom'] = array(
    'render element' => 'element',
  );
  
  return $templates;
}

// This function is useful, if we implement something like a url param called ajax, that can be checked against, to see if should remove all js, but the settings...
function iby_js_alter(&$javascript) {
  date_popup_add();

  // If the ajax get param is set, remove all other js, than the settings object
  //if(isset($_GET['ajax']) && $_GET['ajax']) {
  //  //$javascript = array('settings' => $javascript['settings']);
  //}
}

// Just a reminder, that it is here...
//function iby_user_profile($account) {
//	return 'Hi '.$account->name.'<br />';
//}




//function iby_theme($existing, $type, $theme, $path) {
//}


//function iby_form_alter(&$form, &$form_state, $form_id) {
//}

// Maybe this should be moved to a module?
function iby_form_user_login_alter(&$form, &$form_state, $form_id) {
  $form['actions']['lost_password'] = array('#markup' => l(t('Lost password'), 'user/password', array('attributes' => array('class' => array("forgot-pass"), 'title' => t('Request new password via e-mail.')))) );  

  $form['name']['#default_value'] = "Your username";
  // Not working... security blabla...
  //$form['pass']['#default_value'] = "Your password";

  //$form['name']['#ajax'] = array('callback' => 'bla_bla_test_ajax');//, 'wrapper' => 'presenter-rightbox2');

  $form['name']['#size'] = 34;
  $form['pass']['#size'] = 34;

  unset($form['name']['#title']);
  unset($form['name']['#description']);
  unset($form['pass']['#title']);
  unset($form['pass']['#description']);

  $form['form_build_id'] = array(
    '#type' => 'hidden',
    '#value' => $form['#build_id'],
    '#id' => $form['#build_id'],
    '#name' => 'form_build_id',
  );

  if (isset($form_id)) {
    $form['form_id'] = array(
      '#type' => 'hidden',
      '#value' => $form_id,
      '#id' => drupal_html_id("edit-$form_id"),
    );
  }
  if (!isset($form['#id'])) {
    $form['#id'] = drupal_html_id($form_id);
  }

  form_state_values_clean($form_state);

}

function iby_form_user_register_form_alter(&$form, &$form_state, $form_id) {
  $terms_url = variable_get('claim_user_terms_url', 'legal');
  $options['attributes'] = array('class' => "sq-ajax-textpage-link");
  $options['query'] = array('show_ajax' => 1);

  $form['confirm'] = array(
    '#type' => 'checkbox',
    '#title' => t('I have read the !link', array('!link' => l(t('terms and conditions'), $terms_url, $options))),
    //'#title' => 'I have read the <a href="/'.$terms_url.'" target="_blank">terms and conditions</a>',
    '#required' => TRUE,
  );
  $form['account']['name']['#title'] = t('Choose a username <span id="max15chars">(max. 15 characters)</span>');
  $form['account']['name']['#attributes']['maxlength'] = 15;
  $form['account']['name']['#description'] = t('This is the name displayed when you use the forum. We recommend you choose a username that does not identify with your real name');
  $form['account']['mail']['#title'] = t('Your email address');
  $form['account']['mail']['#description'] = t('You use this email for logging in. We do not mis-use your e-mail or use it for commercial purposes without your approval');
  $form['account']['pass']['#description'] = '';
  $form['actions']['submit']['#value'] = t('Sign up');
}

function iby_element_info_alter(&$type) {  
  unset($type['password_confirm']['#process'][1]);
  $type['password_confirm']['#process'][] = 'iby_form_process_password_confirm';  
}

function iby_form_process_password_confirm($element) {
  $element['pass1']['#title'] = t('Choose a password');
  $element['pass1']['#description'] = t('Your password must have at least 6 characters.<br />You can use upper and lower case characters, numbers and symbols like !"$£&€ in your password');
  $element['pass2']['#title'] = t('Retype password');
  $element['pass2']['#description'] = ''; 
   return $element; 
}

function iby_preprocess_user_register_form(&$variables) {
  if (!empty($variables['form']['account']['name']['#needs_validation'])){
    $variables['form']['account']['name']['#theme'] = 'textfield_custom';
  }
  if (!empty($variables['form']['account']['mail']['#needs_validation'])){
    $variables['form']['account']['mail']['#theme'] = 'textfield_custom';
  }
  if (!empty($variables['form']['account']['pass']['pass1']['#needs_validation'])){
    $variables['form']['account']['pass']['pass1']['#theme'] = 'pass_custom';
    $variables['form']['account']['pass']['pass2']['#theme'] = 'pass_custom';
  }
  
  //var_dump($form);
}

function iby_preprocess_webform_form(&$variables) {    
  if (!empty($variables['form']['submitted']['fieldset_1']['full_name']['#needs_validation'])){
    $variables['form']['submitted']['fieldset_1']['full_name']['#theme'] = 'textfield_custom';
  }

  if (!empty($variables['form']['submitted']['fieldset_1']['email_address']['#needs_validation'])){
    $variables['form']['submitted']['fieldset_1']['email_address']['#theme'] = 'textfield_custom';
  }
  if (!empty($variables['form']['submitted']['previous_username']['#needs_validation'])){
    $variables['form']['submitted']['previous_username']['#theme'] = 'textfield_custom';
  }
}

function iby_preprocess_claim_user_form(&$variables) {    
  if (!empty($variables['form']['mail']['#needs_validation'])){
    $variables['form']['mail']['#theme'] = 'textfield_custom';
  }
}

function iby_textfield_custom($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'text';
  element_set_attributes($element, array('id', 'name', 'value', 'size', 'maxlength'));
  _form_set_class($element, array('form-text'));

  $extra = '';
  if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
    drupal_add_library('system', 'drupal.autocomplete');
    $element['#attributes']['class'][] = 'form-autocomplete';

    $attributes = array();
    $attributes['type'] = 'hidden';
    $attributes['id'] = $element['#attributes']['id'] . '-autocomplete';
    $attributes['value'] = url($element['#autocomplete_path'], array('absolute' => TRUE));
    $attributes['disabled'] = 'disabled';
    $attributes['class'][] = 'autocomplete';
    $extra = '<input' . drupal_attributes($attributes) . ' />';
  }
  $suffix = '<span class="required-status ok">OK</span>';
  if (isset($element['#parents']) && form_get_error($element)) {
    $suffix = '<span class="required-status err">Error</span>';
  }
  $output = '<input' . drupal_attributes($element['#attributes']) . ' />' . $suffix;

  return $output . $extra;
}

function iby_pass_custom($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'password';
  element_set_attributes($element, array('id', 'name', 'size', 'maxlength'));
  _form_set_class($element, array('form-text'));

  $suffix = '<span class="required-status ok">OK</span>';
  if (isset($element['#parents']) && form_get_error($element)) {
    $suffix = '<span class="required-status err">Error</span>';
  }
  
  return '<input' . drupal_attributes($element['#attributes']) . ' />' . $suffix;
}

function iby_form_alter(&$form, $form_state, $form_id) {
//vds($form);
	if($form_id == 'important_notice_node_form') {
		$form['title']['#title'] = t('Header');
	}
}
