<?php
function iby_forums_recent_mark_ajax($mark=false) {
  global $user;

  $nodes = array();
  $taxonomy_terms = array();

  if($mark == "all") {
    db_query("DELETE FROM sq_flags WHERE uid=".$user->uid." AND flag_type IN ('read', 'read_all')");
    db_query("INSERT INTO sq_flags (flag_type, entity_type, entity_id, uid, timestamp) VALUES('read_all', 'nodes', 0, ".$user->uid.", ".time().")");

  } else {

    if(isset($_POST['nodes'])) {
      foreach($_POST['nodes'] as $node) {
        if(intval($node)) {
          $nodes[] = intval($node);
          db_query("DELETE FROM sq_flags WHERE uid=".$user->uid." AND flag_type = 'read' AND entity_id = ".intval($node));
          db_query("INSERT INTO sq_flags (flag_type, entity_type, entity_id, uid, timestamp) VALUES('read', 'node', ".intval($node).", ".$user->uid.", ".time().")");
        }
      }
    }

    if(isset($_POST['taxonomy_terms'])) {
      foreach($_POST['taxonomy_terms'] as $taxonomy_term) {
        if(intval($taxonomy_term)) {
          $taxonomy_terms[] = intval($taxonomy_term);
          db_query("DELETE FROM sq_flags WHERE uid=".$user->uid." AND flag_type = 'read' AND entity_id = ".intval($taxonomy_term));
          db_query("INSERT INTO sq_flags (flag_type, entity_type, entity_id, uid, timestamp) VALUES('read', 'taxonomy_term', ".intval($taxonomy_term).", ".$user->uid.", ".time().")");
        }
      }
    }
  }
    
  $returnArgs = array('taxonomy_terms'=>$taxonomy_terms, 'nodes'=>$nodes);

  if(isset($_GET['cb']) && $_GET['cb']) {
    $args = array();
    $commands[] = ajax_command_invoke(NULL, $_GET['cb'], array($returnArgs));
    $page = array('#type' => 'ajax', '#commands' => $commands);
    ajax_deliver($page);

  } else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($returnArgs);

  }

}

function iby_forums_recent_access_init() {
  global $user;

  $timer = new Timer(true);

  $userRoleIds = array_keys($user->roles);

  $cache = &drupal_static('recent_forums_access', array());

  if(sqtools_is_admin($user)) {
    $query = db_select("taxonomy_term_hierarchy");
    $query->fields('taxonomy_term_hierarchy', array('tid'));
    $query->condition(db_or()->condition("tid", 40)->condition("parent", array(2,5,92), "IN"));
    $cache = $query->execute()->fetchCol();

  } else {
    $query = db_select("taxonomy_term_hierarchy", "th");
    $query->fields('th', array('tid'));
    $query->fields('r', array('field_allowed_roles_value'));
    $query->leftJoin('field_data_field_allowed_roles', 'r', 'r.entity_id=th.tid AND r.deleted=0');

    $query->addExpression('IF(th.tid=40, 40, th.parent)', 'forum_parent');

    $query->condition(db_or()->condition('r.field_allowed_roles_value', $userRoleIds, 'IN')->condition('r.field_allowed_roles_value', NULL));

    $dbOr = db_or();
    
    if(user_access("challenges full", $user) || user_access("create challenges", $user) || user_access("edit challenges", $user)
       || user_access("contribute to challenges", $user) || user_access("read challenges", $user)) {
      $dbOr->condition('th.parent', 2);
    }
    
    if(user_access("tips tricks full", $user) || user_access("create tips tricks", $user) || user_access("edit tips tricks", $user)
       || user_access("contribute to tips tricks", $user) || user_access("read tips tricks", $user)) {
      $dbOr->condition('th.tid', 40);
    }
    
    if(user_access("vip rooms full", $user) || user_access("create vip rooms", $user) || user_access("edit vip rooms", $user)
       || user_access("contribute to vip rooms", $user) || user_access("read vip rooms", $user)) {
    
      $query->leftJoin('field_data_field_vip_private', 'vp', 'vp.entity_id=th.tid');
      $query->leftJoin('field_data_field_vip_members', 'vm', 'vm.entity_id=th.tid');
    
      $dbOr->condition(db_and()->condition('th.parent', 5)
                       ->condition(db_or()->condition(db_and()->condition('vp.field_vip_private_value', '1')->condition('vm.field_vip_members_value', $user->uid))
                                   ->condition('vp.field_vip_private_value', '0')));
    }
    
    if(user_access("forums full", $user) || user_access("create forums", $user) || user_access("edit forums", $user)
       || user_access("contribute to forums", $user) || user_access("read forums", $user)) {
      $dbOr->condition('th.parent', 92);
    }
    
    $query->condition($dbOr);

    $query->groupBy('th.tid');
    $query->groupBy('forum_parent');

    $cache = $query->execute()->fetchCol();

  }
  //echo "<!-- permissions took:".$timer->strTime()." -->\n";
}

function iby_forums_recent_new($maxstamp=false) {
  $cache = &drupal_static('recent_new', array());

  if(isset($cache['result'])) return $cache['result'];

  global $user;

  $accessCache = &drupal_static('recent_forums_access', array());
  if(!$accessCache) iby_forums_recent_access_init();

  $timer = new Timer(true);

  $sql = "SELECT th.tid,
                 IF(n.created>0, COALESCE( MAX(c.created), MAX(n.created), 0 ), fc.field_created_value) AS compare_stamp,
                 (SELECT COALESCE(MAX(fn.timestamp),0) FROM sq_flags AS fn WHERE
                   ((fn.flag_type IN ('view', 'read') AND fn.entity_type=IF(n.created>0, 'node', 'taxonomy_term') AND fn.entity_id=COALESCE(n.nid, th.tid,0))
                   OR fn.flag_type='read_all')
                   AND fn.uid > 0 AND fn.uid=".$user->uid.") AS last_visit

          FROM taxonomy_term_hierarchy AS th
            LEFT JOIN field_data_field_created AS fc ON fc.entity_id=th.tid AND fc.entity_type='taxonomy_term' AND fc.bundle='forums' AND fc.deleted=0
            LEFT JOIN forum AS f ON f.tid=th.tid
            LEFT JOIN forum_index AS n ON n.nid=f.nid
            LEFT JOIN comment AS c ON c.nid=n.nid

          WHERE th.tid IN (0,".implode(",", $accessCache).")
          GROUP BY th.tid, n.nid
          HAVING last_visit = 0 OR last_visit < compare_stamp
         ";
  $result = db_query($sql);
  $cache['result'] = $result->rowCount();

  return $cache['result'];
}

function iby_forums_recent_get_filters() {
  $allowedOrderValues = array('marked', 'section', 'forum', 'node', 'last_activity', 'comments');
  $allowedTypes = array("all", "continence", "ostomy");

  $filters = (object)array();

  if(isset($_GET['c']) && $_GET['c']) $_SESSION['recent_activity_filters'] = $filters;

  $filters->time_limit = mktime(0,0,0,date("m"),(date("d")-7),date("Y"));
  $filters->type = false;
  $filters->order_by = false;
  $filters->direction = false;
  $filters->limit = 25;

  if(isset($_GET['tl'])) $filters->time_limit = $_SESSION['recent_activity_filters']->time_limit = intval($_GET['tl']);
  elseif(isset($_SESSION['recent_activity_filters']->time_limit)) $filters->time_limit = intval($_SESSION['recent_activity_filters']->time_limit);
  
  if(isset($_GET['type']) && in_array($_GET['type'], $allowedTypes)) $filters->type = $_SESSION['recent_activity_filters']->type = $_GET['type'];
  elseif(isset($_SESSION['recent_activity_filters']->type) && in_array($_SESSION['recent_activity_filters']->type, $allowedTypes)) $filters->type = $_SESSION['recent_activity_filters']->type;
  
  if(isset($_GET['o']) && in_array($_GET['o'], $allowedOrderValues)) $filters->order_by = $_SESSION['recent_activity_filters']->o = $_GET['o'];
  elseif(isset($_SESSION['recent_activity_filters']->o) && in_array($_SESSION['recent_activity_filters']->o, $allowedOrderValues)) $filters->order_by = $_SESSION['recent_activity_filters']->o;
  
  if(isset($_GET['d'])) $_GET['d'] = strtoupper($_GET['d']);
  if(isset($_GET['d']) && in_array($_GET['d'], array("ASC", "DESC"))) $filters->direction = $_SESSION['recent_activity_filters']->d = $_GET['d'];
  elseif(isset($_SESSION['recent_activity_filters']->d) && in_array($_SESSION['recent_activity_filters']->d, array("ASC", "DESC"))) $filters->direction = $_SESSION['recent_activity_filters']->d;
  
  if(isset($_GET['l']) && intval($_GET['l'])) $filters->limit = $_SESSION['recent_activity_filters']->l = intval($_GET['l']);
  elseif(isset($_SESSION['recent_activity_filters']->l) && intval($_SESSION['recent_activity_filters']->l)) $filters->limit = intval($_SESSION['recent_activity_filters']->l);

  return $filters;
}

function iby_forums_recent() {
  global $user;

  $accessCache = &drupal_static('recent_forums_access', array());
  if(!$accessCache) iby_forums_recent_access_init();

  $filters = iby_forums_recent_get_filters();

  $timer = new Timer(true);

  $query = db_select("taxonomy_term_data", "td")->extend('PagerDefault');
  $query->fields('td');
  $query->fields('th', array('parent'));
  $query->fields('n', array('nid', 'title'));

  $query->addField('td', 'name', 'forum_name');
  $query->addField('n', 'created', 'node_created');
  $query->addField('n', 'uid', 'node_uid');
  $query->addField('c', 'uid', 'comment_uid');
  $query->addField('u', 'name', 'last_contributor');

  $query->addExpression('COALESCE(fc.field_created_value, 0)', 'forum_created');
  $query->addExpression("IF( COALESCE(tdp.name, 'Tips & Tricks') = 'Chat forums', 'Forums', COALESCE(tdp.name, 'Tips & Tricks') )", 'section_name');
  $query->addExpression('COALESCE(fcu.field_creator_uid_value, 0)', 'forum_creator_uid');
  $query->addExpression('COALESCE(MAX(c.created),0)', 'latest_comment');
  $query->addExpression('COALESCE(c.uid, n.uid, fcu.field_creator_uid_value, 0)', 'last_contributor_uid');
  $query->addExpression('GREATEST(COALESCE(s.field_sticky_value,0), COALESCE(sg.field_sticky_global_value, 0))', 'is_sticky');
  $query->addExpression('GREATEST(COALESCE(fc.field_created_value,0), COALESCE(MAX(n.created),0), COALESCE(MAX(c.created),0))', 'last_activity');
  $query->addExpression('COUNT(DISTINCT c.cid)', 'comments');
  $query->addExpression('IF(COALESCE(f.timestamp, 0) > 0, 1, 0)', 'starmarked');
  $query->addExpression('IF(th.tid=40, 40, th.parent)', 'forum_parent');
  $query->addExpression("COALESCE(n.title, '')", "node_title");

	$query->leftJoin("taxonomy_term_hierarchy", "th", "th.tid = td.tid");
  $query->leftJoin("forum_index", "fi", "fi.tid = td.tid");
  $query->leftJoin("field_data_field_created", "fc", "fc.entity_id=td.tid AND fc.entity_type='taxonomy_term' AND fc.bundle='forums' AND fc.deleted=0");
  $query->leftJoin("node", "n", "n.nid = fi.nid AND n.status=1");
  $query->leftJoin("comment", "c", "c.nid=n.nid AND c.status=1");
	$query->leftJoin("taxonomy_term_data", "tdp", "tdp.tid = th.parent");
  $query->leftJoin("field_data_field_sticky", "s", "s.entity_id=n.nid");
  $query->leftJoin("field_data_field_sticky_global", "sg", "sg.entity_id=n.nid");
  $query->leftJoin("field_data_field_creator_uid", "fcu", "fcu.entity_id=td.tid AND fcu.entity_type='taxonomy_term' AND fcu.bundle='forums' AND fcu.deleted=0");
  $query->leftJoin("users", "u", "u.uid = COALESCE(c.uid, n.uid, fcu.field_creator_uid_value, 0)");
  $query->leftJoin("sq_flags", "f", "f.uid = ".$user->uid." AND f.flag_type='starmark' AND f.entity_type=IF(n.nid > 0, 'node', 'taxonomy_term') AND f.entity_id=IF(n.nid > 0, n.nid, td.tid)");

  if(in_array($filters->type, array("continence", "ostomy"))) {
    $query->leftJoin("field_data_field_type", "ftn", "ftn.bundle='forum' AND ftn.deleted=0 AND ftn.entity_type='node' AND ftn.entity_id=n.nid");
    $query->leftJoin("field_data_field_type", "ftt", "ftt.bundle='forum' AND ftt.deleted=0 AND ftt.entity_type='taxonomy_term' AND ftt.entity_id=td.tid");
    $query->condition(db_or()->condition("ftn.field_type_value", array($filters->type, "all"), "IN")->condition("ftt.field_type_value", array($filters->type, "all"), "IN"));
  }

  $query->condition("th.tid", $accessCache, "IN");

  if($filters->time_limit) {
    $query->having('last_activity > '.$filters->time_limit.' OR is_sticky = 1');
  }

  $query->groupBy("th.tid");
  $query->groupBy("n.nid");
  $query->orderBy("is_sticky", "DESC");

  if($filters->order_by) {
    $orderDirections = array('ASC'=>"DESC",'DESC'=>"ASC");

    if(!in_array($filters->direction, array('DESC', 'ASC'))) $filters->direction = "ASC";

    if($filters->order_by == "marked") {
      if($filters->direction == "ASC") $filters->direction = "DESC";
      $query->orderBy("starmarked", $filters->direction);
    }
    elseif($filters->order_by == "section") $query->orderBy("section_name", $filters->direction);
    elseif($filters->order_by == "forum") $query->orderBy("td.name", $filters->direction);
    elseif($filters->order_by == "node") $query->orderBy("node_title", $filters->direction);
    elseif($filters->order_by == "last_activity") $query->orderBy("last_activity", $orderDirections[$filters->direction]);
    elseif($filters->order_by == "comments") $query->orderBy("comments", $orderDirections[$filters->direction]);
    elseif($filters->order_by == "user") $query->orderBy("last_contributor", $filters->direction);
  }

  if(!$filters->order_by || ($filters->order_by != "last_activity")) $query->orderBy("last_activity", "DESC");

  if(($filters->limit <= 100) && ($filters->limit > 0)) $query->limit($filters->limit);
  else $query->limit(25);

  //$query->limit(5);

  $count_query = db_select("taxonomy_term_hierarchy", "th");
  $count_query->addExpression('th.tid', 'tid');
  $count_query->condition("th.tid", $accessCache, "IN");
  $count_query->leftJoin("forum", "f", "f.tid=th.tid");
  $count_query->leftJoin("forum_index", "n", "n.nid=f.nid");
  $count_query->leftJoin("comment", "c", "c.nid=n.nid");
  $count_query->groupBy('th.tid');
  $count_query->groupBy('n.nid');
  if($filters->time_limit) {
    $count_query->addExpression('GREATEST(COALESCE(fc.field_created_value,0), COALESCE(MAX(n.created),0), COALESCE(MAX(c.created),0))', 'last_activity');
    $count_query->leftJoin("field_data_field_created", "fc", "fc.entity_id=th.tid AND fc.entity_type='taxonomy_term' AND fc.bundle='forums' AND fc.deleted=0");
    $count_query->having('last_activity > '.$filters->time_limit);//GREATEST(COALESCE(fc.field_created_value,0), COALESCE(MAX(n.created),0), COALESCE(MAX(c.created),0))', $tl, '>');
  }
  $count = $count_query->execute()->rowCount();

  $count_query2 = db_select("users", "us");
  $count_query2->addExpression($count, "count");
  $count_query2->range(0,1);

  $query->setCountQuery($count_query2);

  $results = $query->execute()->fetchAll();

  //echo "<!-- middle time:".$timer->strTime()." -->\n";

  foreach($results as $index=>$result) {

    if($results[$index]->nid) {
      $query = db_select("sq_flags", "f");
      $query->addExpression('COALESCE(MAX(f.timestamp),0)', 'last_visit');
      $query->condition(
                        db_or()->condition(
                                           db_and()
                                           ->condition('f.flag_type', array('view', 'read'), 'IN')
                                           ->condition('f.entity_type', 'node')
                                           ->condition('f.entity_id', $result->nid)
                                           )
                        ->condition('f.flag_type', 'read_all')
                        );
      $query->condition('f.uid', $user->uid);
      $query->condition('f.uid', 0, '>');

      $res = $query->execute();
      foreach($res as $flag) {
        $results[$index]->last_visit = $flag->last_visit;
      }
      
      $results[$index]->new_comments = 0;
      if($results[$index]->comments && $results[$index]->nid) {
        $query = db_select("comment", "c");
      
        $query->addExpression('COALESCE(COUNT(c.cid),0)', 'new_comments');
        $query->condition('c.nid', $results[$index]->nid);
        $query->condition('c.created', intval($results[$index]->last_visit), '>');
        $res = $query->execute();
        foreach($res as $flag) {
          $results[$index]->new_comments = $flag->new_comments;
        }
      }

    } else {
      $query = db_select("sq_flags", "f");
      $query->addExpression('COALESCE(MAX(f.timestamp),0)', 'last_visit');


      $query->condition(
                        db_or()->condition(
                                           db_and()
                                           ->condition('f.flag_type', array('view', 'read'), 'IN')
                                           ->condition('f.entity_type', 'taxonomy_term')
                                           ->condition('f.entity_id', $result->tid)
                                           )
                        ->condition('f.flag_type', 'read_all')
                        );
      $query->condition('f.uid', $user->uid);
      $query->condition('f.uid', 0, '>');

      $res = $query->execute();
      foreach($res as $flag) {
        $results[$index]->last_visit = $flag->last_visit;
      }
    }
    
    if(!$results[$index]->last_visit) $results[$index]->new_content = true;
    elseif($results[$index]->last_activity > $results[$index]->last_visit) $results[$index]->new_content = true;
    else $results[$index]->new_content = false;

    if($result->comment_uid) {
      $query = db_select('comment', 'c');
      $query->fields('c', array('uid'));
      $query->condition('c.nid', $results[$index]->nid);
      $query->orderBy('c.created', 'DESC');
      $query->range(0,1);
      $res = $query->execute()->fetchCol();
      $results[$index]->last_contributor_uid = $res[0];
    } elseif($result->node_uid) $results[$index]->last_contributor_uid = $result->node_uid;
    elseif($result->forum_creator_uid) $results[$index]->last_contributor_uid = $result->forum_creator_uid;
    
    $results[$index]->last_contributor = user_load($results[$index]->last_contributor_uid);
    //  //$query->leftJoin("field_data_field_creator_uid", "fcu", "fcu.entity_id=ti.tid AND fcu.entity_type='taxonomy_term' AND fcu.bundle='forums' AND fcu.deleted=0");
  }

  
  //echo "<!-- it took:".$timer->strTime()." -->\n";
  return theme("recent_activity", array('activities' => $results, 'time'=>sprintf("%0.6f", (microtime(true)-$start))));
}
