<?php
/**
 * Process variables for tips--tricks--forums.tpl.php
 */
function iby_forums_preprocess_tips__tricks__forums(&$variables) {
  $query_cache = &drupal_static('iby_forum_tagging', array());

  global $user;

  // O.k... So this is where i need to do the magic....

  // Wanted breadcrumb = Home > Challenges > Challenge #1

  $vid = variable_get('forum_nav_vocabulary', 0);
  $vocabulary = taxonomy_vocabulary_load($vid);
  $title = !empty($vocabulary->name) ? $vocabulary->name : '';

  $forums = array();

  // Answered problems
  $query = db_select('forum_index', 'f')->extend('PagerDefault');//->extend('TableSort');
  $query->join('node', 'n', 'n.status = 1 AND n.nid = f.nid');
  $query->fields('f');
  if(isset($_GET['type']) && in_array($_GET['type'], array('continence', 'ostomy')))
    $query->join('field_data_field_type', 'ft', "ft.entity_id = f.nid AND ft.entity_type = 'node' AND ft.bundle = 'forum' AND ft.field_type_value = '".addslashes($_GET['type'])."'");
  $query->join('comment', 'c', 'c.nid = f.nid');

  $query->condition('f.tid', $variables['tid']);
  if(isset($query_cache['nids'])) $query->condition('f.nid', $query_cache['nids'], 'IN');
  $query->groupBy('f.nid');
  //$query->orderBy('f.sticky', 'DESC')->limit(3);
  
  $result = $query->execute();
  $nids = array();
  foreach ($result as $record) $nids[] = $record->nid;
  
  $variables['topics_solved'] = entity_load('node', $nids);
  //echo "<pre>";var_dump($variables['topics']);exit;


  // Problems without answers
  $query = db_select('forum_index', 'f')->extend('PagerDefault');//->extend('TableSort');
  $query->join('node', 'n', 'n.status = 1 AND n.nid = f.nid');
  $query->fields('f');
  if(isset($_GET['type']) && in_array($_GET['type'], array('continence', 'ostomy')))
    $query->join('field_data_field_type', 'ft', "ft.entity_id = f.nid AND ft.entity_type = 'node' AND ft.bundle = 'forum' AND (ft.field_type_value = 'all' OR ft.field_type_value = '".addslashes($_GET['type'])."')");
  $query->leftJoin('comment', 'c', 'c.nid = f.nid');
  $query->addExpression('COUNT(c.cid)', 'comment_count');
  $query->condition('f.tid', $variables['tid']);
  $query->condition('comment_count', "0", "=");
  //if(isset($query_cache['nids'])) $query->condition('f.nid', $query_cache['nids'], 'IN');
  $query->groupBy('f.nid');
  $query->orderBy('f.created', 'DESC');
  //$query->orderBy('f.sticky', 'DESC')->limit(3);
  
  $result = $query->execute();
  $nids = array();
  foreach ($result as $record) {
    $nids[] = $record->nid;
  }
  
  $variables['topics_unsolved'] = entity_load('node', $nids);
  //echo "<pre>";var_dump($variables['topics']);exit;


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


  $variables['forums_defined'] = 1;//count($variables['forums']) || count($variables['parents']);

  $variables['forums'] = "";

  $variables['topics'] = theme('forum_topic_list', $variables);

  //$variables['theme_hook_suggestions'][] = 'forums__topics';
  //$variables['theme_hook_suggestions'][] = 'forums__' . $variables['tid'];
  //$variables['theme_hook_suggestions'][] = 'forums__topics__' . $variables['tid'];

}
