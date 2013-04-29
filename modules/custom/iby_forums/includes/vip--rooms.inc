<?php
/**
 * Process variables for vip--rooms--forums.tpl.php
 */

function iby_forums_preprocess_vip__rooms__forums(&$variables) {
  $query_cache = &drupal_static('iby_forum_tagging', array());

  global $user;

  $vid = variable_get('forum_nav_vocabulary', 0);
  $vocabulary = taxonomy_vocabulary_load($vid);
  $title = !empty($vocabulary->name) ? $vocabulary->name : '';

  // These are also used for inactive rooms...
  $user_roles = array_keys($user->roles);

  $tids_query = db_select('taxonomy_term_hierarchy', 'th')->fields('th', array('tid'))->condition('th.parent', $variables['tid']);

  $query_access = db_select('field_data_field_allowed_roles', 'far');
  $query_access->fields('far', array('entity_id'));
  $query_access->condition('far.field_allowed_roles_value', $user_roles, 'IN');
  $query_access->condition('far.entity_type', 'taxonomy_term');
  $query_access->condition('far.entity_id', $tids_query, 'IN');
  $query_access->distinct();

  $query_member_rooms = db_select('field_data_field_vip_members', 'vm');
  $query_member_rooms->fields('vm', array('entity_id'));
  $query_member_rooms->condition('vm.entity_type', 'taxonomy_term');
  $query_member_rooms->condition('vm.entity_id', $tids_query, 'IN');
  $query_member_rooms->condition('vm.field_vip_members_value', $user->uid);
  $query_member_rooms->distinct();

  
  // Get the active rooms
  $active_rooms = array();
  
  $query = db_select('taxonomy_term_data', 'ttd');

  if(!sqtools_is_admin($user)) {
    $query->condition('ttd.tid', $query_access, 'IN');
    //$query->condition(db_or()
    //                  ->condition('ttd.tid', $query_access, 'IN')
    //                  ->condition('ttd.tid', $query_member_rooms, 'IN')
    //                  ->condition(
    //                              db_and()
    //                              ->condition('ttd.tid', $query_closed_rooms, 'NOT IN')
    //                              ->condition('fdfvp.field_vip_private_value', 1, '<>')
    //                              )
    //                  );
  }

  $query->join('taxonomy_term_hierarchy', 'h', 'h.tid = ttd.tid');
  $query->join('field_data_field_vip_archived', 'fdfva', 'fdfva.entity_id = ttd.tid');
  $query->leftJoin('field_data_field_vip_private', 'fdfvp', 'fdfvp.entity_id = ttd.tid');

  $query
    ->fields('ttd')
    ->fields('h', array('parent'))
    ->fields('fdfva', array('field_vip_archived_value'));

  if(isset($_GET['type']) && in_array($_GET['type'], array('continence', 'ostomy')))
    $query->join('field_data_field_type', 'ft', "ft.entity_id = ttd.tid AND ft.entity_type = 'taxonomy_term' AND ft.bundle = 'forums' AND (ft.field_type_value = 'all' OR ft.field_type_value = '".addslashes($_GET['type'])."')");

  $result = $query->condition('ttd.vid', $vid)
    ->condition('h.parent', $variables['tid'])
    ->where('fdfva.field_vip_archived_value = 0')
    ->orderBy('ttd.weight')
    ->orderBy('ttd.name')
    ->execute();
  foreach($result as $active_room) {
    $active_rooms[$active_room->tid] = $active_room;
  }


  // archived rooms
  $ended_rooms = array();
  
  $query = db_select('taxonomy_term_data', 'ttd')->extend('PagerDefault');
  $query->element(0);

  if(!sqtools_is_admin($user)) {
    $query->condition('ttd.tid', $query_access, 'IN');
    //$query->condition(db_or()
    //                  ->condition('ttd.tid', $query_access, 'IN')
    //                  ->condition('ttd.tid', $query_member_rooms, 'IN')
    //                  ->condition(
    //                              db_and()
    //                              ->condition('ttd.tid', $query_closed_rooms, 'NOT IN')
    //                              ->condition('fdfvp.field_vip_private_value', 1, '<>')
    //                              )
    //                  );
  }

  $query->join('taxonomy_term_hierarchy', 'h', 'h.tid = ttd.tid');
  $query->join('field_data_field_vip_archived', 'fdfva', 'fdfva.entity_id = ttd.tid');
  $query->leftJoin('field_data_field_vip_private', 'fdfvp', 'fdfvp.entity_id = ttd.tid');

  $query->fields('ttd')
    ->fields('h', array('parent'))
    ->fields('fdfva', array('field_vip_archived_value'));

  if(isset($_GET['type']) && in_array($_GET['type'], array('continence', 'ostomy')))
    $query->join('field_data_field_type', 'ft', "ft.entity_id = ttd.tid AND ft.entity_type = 'taxonomy_term' AND ft.bundle = 'forums' AND ft.field_type_value = '".addslashes($_GET['type'])."'");
  
  $query->condition('ttd.vid', $vid)
    ->condition('h.parent', $variables['tid'])
    ->where('fdfva.field_vip_archived_value = 1')
    ->groupBy('ttd.tid');

  //$query->addExpression('(SELECT COUNT(*) FROM users)', 'user_count');
  //$query->join('sq_flags', 'flag', 'flag.object_id');

  if(isset($query_cache['o'])) {
    switch($query_cache['o']) {
    case "comment":
      $query->addExpression('SUM(fi.comment_count)', 'comment_counts');
      $query->leftJoin('forum_index', 'fi', 'fi.tid = ttd.tid');
      $query->orderBy('comment_counts', 'DESC');
      break;
    case "view":
    case "follow":
    case "like":
      $query->leftJoin('iby_forum_flags_cache', 'fc', "fc.flag_type='".$query_cache['o']."' AND fc.entity_type='taxonomy_term' AND fc.entity_id=ttd.tid");
      $query->orderBy('fc.total', 'DESC');
      break;
    }
  }

  if(isset($query_cache['tids']) && is_array($query_cache['tids']) && count($query_cache['tids']))
    $query->condition('ttd.tid', $query_cache['tids'], 'IN');

  $query->limit(5);

  $result = $query->execute();

  foreach($result as $ended_room) {
    //echo "<pre>";var_dump($ended_challenge);exit;
    $ended_rooms[$ended_room->tid] = $ended_room;
  }


//  $variables['forums_active'] = taxonomy_term_load_multiple(array_keys($active_rooms));
//  $variables['forums_ended'] = taxonomy_term_load_multiple(array_keys($ended_rooms));

  $variables['active_rooms'] = entity_load('taxonomy_term', array_keys($active_rooms));
  $variables['ended_rooms'] = entity_load('taxonomy_term', array_keys($ended_rooms));


  if(is_array($variables['active_rooms']) && count($variables['active_rooms'])) {
    foreach($variables['active_rooms'] as $index=>$forum)
      $variables['active_rooms'][$index]->link = url("forum/".$forum->tid);
  }

  if(is_array($variables['ended_rooms']) && count($variables['ended_rooms'])) {
    foreach($variables['ended_rooms'] as $index=>$forum)
      $variables['ended_rooms'][$index]->link = url("forum/".$forum->tid);
  }


  // Now for the topics
  if(isset($variables['tid']) && $variables['tid'] && empty($variables['container'])) {
    $query = db_select('forum_index', 'f')->extend('PagerDefault');
	$query->join('node', 'n', 'n.status = 1 AND n.nid = f.nid');
    $query->element(1);
    $query->fields('f');
    
    //if(isset($query_cache['tids'])) $tids = array_intersect($tids, $query_cache['tids']);
    
    //if(count($tids)) {
    $query->condition('f.tid', $variables['tid']);
      
    if(isset($query_cache['nids']) && count($query_cache['nids']))
      $query->condition('f.nid', $query_cache['nids'], 'IN');

    //$query->orderBy('f.created', 'DESC');
    //$query->orderBy('f.sticky', 'DESC');

    if(isset($query_cache['o'])) {
      switch($query_cache['o']) {
      case "comment":
        $query->orderBy('f.comment_count', 'DESC');
        break;
      case "view":
      case "follow":
      case "like":
        $query->leftJoin('iby_forum_flags_cache', 'fc', "fc.flag_type='".$query_cache['o']."' AND fc.entity_type='node' AND fc.entity_id=f.nid");
        $query->orderBy('fc.total', 'DESC');
        break;
      default:
        $query->orderBy('f.created', 'DESC');
        $query->orderBy('f.nid', 'DESC');
        break;
      }
    } else {
      $query->orderBy('f.created', 'DESC');
      $query->orderBy('f.nid', 'DESC');
    }

    //$query->range(0, 6);
    if(isset($_GET['view']) && ($_GET['view'] == 'list')) {
      $query->limit(10);
    } elseif(isset($_GET['view']) && ($_GET['view'] == 'grid')) {
      $query->limit(6);
    } elseif(isset($_COOKIE['view']) && ($_COOKIE['view_option'] == 'list')) {
      $query->limit(10);
    } else {
      $query->limit(6);
    }

    //$query->element(3);
    $query->setCountQuery($query->getCountQuery());

    $query->execute();      
    //$query->join('users', 'u', 'f.uid = u.uid');
    //$query->fields('u', array('uid'))

    //echo $query->__toString();

    $result = $query->execute();
    $nids = array();
    foreach ($result as $record) {
      $nids[] = $record->nid;
    }
    
    //echo "<pre>"; var_dump($nids); exit;

    $variables['topics'] = entity_load('node', $nids);
    foreach($variables['topics'] as $index=>$topic) $variables['topics'][$index]->owner_uid = $topic->uid;

    //echo "<pre>";var_dump($variables['topics']);exit;
    //}
  }

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

  drupal_set_breadcrumb($breadcrumb);
  drupal_set_title($title);

  if ($variables['forums_defined'] = count($variables['forums']) || count($variables['parents'])) {
    if (!empty($variables['forums'])) {
      $variables['forums'] = theme('forum_list', $variables);
    }
    else {
      $variables['forums'] = '';
    }

    if ($variables['tid'] && !in_array($variables['tid'], variable_get('forum_containers', array()))) {
      $variables['topics'] = theme('forum_topic_list', $variables);
    }
    else {
      $variables['topics'] = '';
    }

    // Provide separate template suggestions based on what's being output. Topic id is also accounted for.
    // Check both variables to be safe then the inverse. Forums with topic ID's take precedence.
    if ($variables['forums'] && !$variables['topics']) {
      $variables['theme_hook_suggestions'][] = 'forums__containers';
      $variables['theme_hook_suggestions'][] = 'forums__' . $variables['tid'];
      $variables['theme_hook_suggestions'][] = 'forums__containers__' . $variables['tid'];
    }
    elseif (!$variables['forums'] && $variables['topics']) {
      $variables['theme_hook_suggestions'][] = 'forums__topics';
      $variables['theme_hook_suggestions'][] = 'forums__' . $variables['tid'];
      $variables['theme_hook_suggestions'][] = 'forums__topics__' . $variables['tid'];
    }
    else {
      $variables['theme_hook_suggestions'][] = 'forums__' . $variables['tid'];
    }

  }
  else {
    drupal_set_title(t('No forums defined'));
    $variables['forums'] = '';
    $variables['topics'] = '';
  }
}

