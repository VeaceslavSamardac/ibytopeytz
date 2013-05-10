<?php
/**
 * Process variables for chat--forums--fourms.tpl.php
 */
function iby_forums_preprocess_chat__forums__forums(&$variables) {
  $query_cache = &drupal_static('iby_forum_tagging', array());

  $vars = $variables;

  global $user;

  // O.k... So this is where i need to do the magic....

  // Wanted breadcrumb = Home > Challenges > Challenge #1

  $vid = variable_get('forum_nav_vocabulary', 0);
  $vocabulary = taxonomy_vocabulary_load($vid);
  $title = !empty($vocabulary->name) ? $vocabulary->name : '';


  // These are checks for "closed" forums
  $user_roles = array_keys($user->roles);

  $tids_query = db_select('taxonomy_term_hierarchy', 'th')->fields('th', array('tid'))->condition('th.parent', $variables['tid']);

  $query_access = db_select('field_data_field_allowed_roles', 'far');
  $query_access->fields('far', array('entity_id'));
  $query_access->condition('far.field_allowed_roles_value', $user_roles, 'IN');
  $query_access->condition('far.entity_type', 'taxonomy_term');
  $query_access->condition('far.entity_id', $tids_query, 'IN');
  $query_access->distinct();

  $chat_forums = array();

  $tids = array();

  if(isset($variables['tid']) && $variables['tid'])
    $tids[] = $variables['tid'];

  $query = db_select('taxonomy_term_data', 'ttd');

  if(!sqtools_is_admin($user)) {
    $query->condition('ttd.tid', $query_access, 'IN');
  }

  $query->join('taxonomy_term_hierarchy', 'h', 'h.tid = ttd.tid');
  $query
    ->fields('ttd')
    ->fields('h', array('parent'));

  if(isset($_GET['type']) && in_array($_GET['type'], array('continence', 'ostomy')))
    $query->join('field_data_field_type', 'ft', "ft.entity_id = ttd.tid AND ft.entity_type = 'taxonomy_term' AND ft.bundle = 'forums' AND (ft.field_type_value = 'all' OR ft.field_type_value = '".addslashes($_GET['type'])."')");

  $query->condition('ttd.vid', $vid)
    ->condition('h.parent', $variables['tid'])
    ->orderBy('ttd.weight')
    ->orderBy('ttd.name');

  $result = $query->execute();
  foreach($result as $chat_forum) {
    $chat_forums[$chat_forum->tid] = $chat_forum;
    $tids[] = $chat_forum->tid;
  }

  $variables['chat_forums'] = entity_load('taxonomy_term', array_keys($chat_forums));

  foreach($variables['chat_forums'] as $index=>$forum)
    $variables['chat_forums'][$index]->link = url("forum/".$forum->tid);

  // Now for the topics
  $query = db_select('forum_index', 'f')->extend('PagerDefault');//->extend('TableSort');
  $query->join('node', 'n', 'n.status = 1 AND n.nid = f.nid');
  $query->element(0);

  $query->fields('f');

  if(isset($variables['forums']) && count($variables['forums'])) {
    $query->join('field_data_field_sticky_global', 'fdfsg', 'fdfsg.entity_id = f.nid');
    $query->addField('fdfsg', 'field_sticky_global_value', 'sticky_global');
    $query->orderBy('fdfsg.field_sticky_global_value', 'DESC');
  } else {
    $query->join('field_data_field_sticky', 'fdfs', 'fdfs.entity_id = f.nid');
    $query->addFfield('fdfs', 'field_sticky_value', 'sticky');
    $query->orderBy('fdfs.field_sticky_value', 'DESC');
  }

  if(isset($query_cache['tids'])) {
    $tids = array_intersect($tids, $query_cache['tids']);
  }

  if(count($tids)) {
    $query->condition('f.tid', $tids, "IN");

    if(isset($query_cache['nids']) && count($query_cache['nids'])) {
      $query->condition('f.nid', $query_cache['nids'], 'IN');
    }

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
        $query->orderBy('fc.created', 'DESC');
        $query->orderBy('fc.nid', 'DESC');
        break;
      }

    } else {
      $query->orderBy('f.created', 'DESC');
      $query->orderBy('f.nid', 'DESC');
    }

    if(isset($_GET['view']) && ($_GET['view'] == 'list')) {
      $query->limit(10);
    } elseif(isset($_GET['view']) && ($_GET['view'] == 'grid')) {
      $query->limit(6);
    } elseif(isset($_COOKIE['view_option']) && ($_COOKIE['view_option'] == 'list')) {
      $query->limit(10);
    } else {
      $query->limit(6);
    }

    //$query->join('users', 'u', 'f.uid = u.uid');
    //$query->fields('u', array('uid'))

    $result = $query->execute();

    $nids = array();
    foreach ($result as $record) {
      $nids[] = $record->nid;
    }

    $variables['topics'] = entity_load('node', $nids);

    foreach($variables['topics'] as $index=>$topic) {
      $variables['topics'][$index]->owner_uid = $topic->uid;
    }

    foreach($vars['topics'] as $topic) {
      if (isset($variables['topics'][$topic->nid])) {
        $merged = (object)array_merge((array)$variables['topics'][$topic->nid], (array)$topic);
        $variables['topics'][$topic->nid] = $merged;
      }
    }

  }

//  if($nids) {
//    $query = db_select('node', 'n');//->extend('TableSort');
//    $query->fields('n', array('title', 'nid', 'type', 'sticky', 'created', 'uid'));
//    //$query->addField('n', 'comment', 'comment_mode');
//    
//    $query->join('node_comment_statistics', 'ncs', 'n.nid = ncs.nid');
//    $query->fields('ncs', array('cid', 'last_comment_uid', 'last_comment_timestamp', 'comment_count'));
//    
//    $query->join('forum_index', 'f', 'f.nid = ncs.nid');
//    $query->addField('f', 'tid', 'forum_tid');
//    
//    $query->join('users', 'u', 'n.uid = u.uid');
//    $query->addField('u', 'name');
//  
//    $query->join('users', 'u2', 'ncs.last_comment_uid = u2.uid');
//    
//    $query->condition('n.nid', $nids);
//
//    //$query
//    //  ->orderBy('f.sticky', 'DESC')
//    //  ->orderByHeader($forum_topic_list_header)
//    //  ->condition('n.nid', $nids);
//  
//    $result = $query->execute();
//
//    $topics = array();
//    $first_new_found = FALSE;
//    foreach ($result as $topic) {
//      if ($user->uid) {
//        // folder is new if topic is new or there are new comments since last visit
//        if ($topic->forum_tid != $tid) {
//          $topic->new = 0;
//        }
//        else {
//          $history = _forum_user_last_visit($topic->nid);
//          $topic->new_replies = comment_num_new($topic->nid, $history);
//          $topic->new = $topic->new_replies || ($topic->last_comment_timestamp > $history);
//        }
//      }
//      else {
//        // Do not track "new replies" status for topics if the user is anonymous.
//        $topic->new_replies = 0;
//        $topic->new = 0;
//      }
//
//      // Make sure only one topic is indicated as the first new topic.
//      $topic->first_new = FALSE;
//      if ($topic->comment_count > 0) {
//        $last_reply = new stdClass();
//        $last_reply->created = $topic->last_comment_timestamp;
//        $last_reply->name = $topic->last_comment_name;
//        $last_reply->uid = $topic->last_comment_uid;
//        $topic->last_reply = $last_reply;
//      }
//      $topics[] = $topic;
//    }
//  }    


  // Breadcrumb navigation:
  $breadcrumb[] = l(t('Home'), NULL);
  if ($variables['parents']) {
    $variables['parents'] = array_reverse($variables['parents']);
    foreach ($variables['parents'] as $p) {
      $name = ((strtolower($p->name) == "chat forums") ? "Forums" : $p->name );

      if ($p->tid == $variables['tid']) {
        $title = $name;
        $breadcrumb[] = $name;

      } else {
        $breadcrumb[] = l($name, 'forum/' . $p->tid);
      }
    }
  }

  drupal_set_breadcrumb($breadcrumb);
  drupal_set_title($title);

  //$tid = $variables['tid'];
  //echo "<pre>"; forum_get_topics($tid, 'title', 20);exit;


  if ($variables['forums_defined'] = count($variables['forums']) || count($variables['parents'])) {

    if (!empty($variables['forums'])) {
      $variables['forums'] = theme('forum_list', $variables);
    }
    else {
      $variables['forums'] = '';
    }

    //if (!empty($variables['topics'])) {
    //  $variables['topics'] = theme('forum_topic_list', $variables);
    //}
    if ($variables['tid'] && !in_array($variables['tid'], variable_get('forum_containers', array()))) {
      $variables['topics'] = theme('forum_topic_list', $variables);
    }
    else {
      $variables['topics'] = '';
    }

    //  $variables['topics'] = theme('forum_topic_list', $variables);

    //if ($variables['tid'] && !in_array($variables['tid'], variable_get('forum_containers', array()))) {
    //  $variables['topics'] = theme('forum_topic_list', $variables);
    //}
    //else {
    //  $variables['topics'] = '';
    //}

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
    //$variables['topics'] = '';
  }
}
