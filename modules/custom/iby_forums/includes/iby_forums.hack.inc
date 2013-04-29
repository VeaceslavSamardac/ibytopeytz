<?php
/*
Since there's a hook for flushing the cache, but none for re-updating the cache, maybe we need to look into a way of detecting a "no cache" situation in the "update" cache process...
This presents some problems, as we need to be able to rely on the cache being there for sorting....

 Thoughts:
  - We need to hook up to the clear cache function, so that we have a way of regerating everything
      This is probably close to the tags way of looking up

  - I think the best way to do the pr. action update is by doing the following:
  case cid
    +1 to comment
    set nid

  case nid
    +1 to topic (it's a node)
    set tid

  case tid
    +1 to forum
*/

/* Hooking into the sq_flags_api to create a downwards cache */

function iby_forums_cleanup_comments() {
  $query = db_select("comment", "c");
  $query->fields('c', array('nid'));
  $query->distinct('c.nid');
  $res = $query->execute();
  
  $nids = array();
  foreach($res as $row) $nids[] = $row->nid;
  
  foreach($nids as $nid) {
    $query = db_select("comment", "c");
    $query->fields('c');
    $query->orderBy('c.cid', 'ASC');
    $query->condition('c.nid', $nid);
    $res = $query->execute();
    
    $counter = 1;
    foreach($res as $row) {
      db_query("UPDATE comment SET thread='".int2vancode($counter)."/' WHERE nid='".$row->nid."' AND cid='".$row->cid."'");
      $counter++;
    }
  }
}

function iby_forums_flush_caches() {
  iby_forums_cleanup_comments();
  $cache_tables = array();
  if(db_table_exists('iby_forum_flags_cache')) $cache_tables[] = 'iby_forum_flags_cache';
  if(db_table_exists('iby_forum_tags_cache')) $cache_tables[] = 'iby_forum_tags_cache';
  return $cache_tables;
}

function iby_forums_sq_flags_cache_init() {
  $row = db_select('iby_forum_flags_cache', 'fc')->fields('fc')->range(0,1)->execute()->fetchAll();
  if(!count($row)) _iby_forums_sq_flags_cache_clean();
  $row = db_select('iby_forum_tags_cache', 'tc')->fields('tc')->range(0,1)->execute()->fetchAll();
  if(!count($row)) _iby_forums_sq_tags_cache_populate();
}

function _iby_forums_sq_flags_cache_clean() {
  sq_flags_api_cron();

  $res = db_query("INSERT INTO iby_forum_flags_cache (flag_type, entity_type, entity_id, total)
                     SELECT flag_type, entity_type, entity_id, COUNT(*) FROM sq_flags GROUP BY flag_type, entity_type, entity_id");

  
//  // Next step - update nodes from comments:
//  $res = db_query("INSERT INTO iby_forum_flags_cache (flag_type, entity_type, entity_id, total)
//                     SELECT flag_type, 'node', c.nid, SUM(fc.total) AS total_sum
//                       FROM iby_forum_flags_cache fc
//                         JOIN comment c ON c.cid = fc.entity_id AND fc.entity_type = 'comment'
//                       WHERE fc.entity_type='comment'
//                       GROUP BY fc.flag_type, c.nid 
//                     ON DUPLICATE KEY UPDATE total = total + VALUES(total)");
//
//  // Next step - update taxonomy_terms from forum_index (nodes):
//  $res = db_query("INSERT INTO iby_forum_flags_cache (flag_type, entity_type, entity_id, total)
//                     SELECT flag_type, 'taxonomy_term', fi.tid, SUM(fc.total) AS total_sum
//                       FROM iby_forum_flags_cache fc
//                         JOIN forum_index fi ON fi.nid = fc.entity_id AND fc.entity_type = 'node'
//                       WHERE fc.entity_type='node'
//                       GROUP BY fc.flag_type, fi.tid 
//                     ON DUPLICATE KEY UPDATE total = total + VALUES(total)");
}

function _iby_forums_sq_tags_cache_populate() {
  // NOTE: ALSO REMOVE TAGS ON ENTITIES NOT LONGER AVAILABLE!

  // Comments, nodes, taxonomies
  $res = db_query("DELETE FROM field_data_field_tags WHERE entity_type='taxonomy_term' AND bundle='forums' AND entity_id
                     NOT IN (SELECT tid FROM taxonomy_term_hierarchy)");
  $res = db_query("DELETE FROM field_revision_field_tags WHERE entity_type='taxonomy_term' AND bundle='forums' AND entity_id
                     NOT IN (SELECT tid FROM taxonomy_term_hierarchy)");
  
  $res = db_query("DELETE FROM field_data_field_tags WHERE entity_type='node' AND bundle='forum' AND entity_id
                     NOT IN (SELECT nid FROM forum)");
  $res = db_query("DELETE FROM field_revision_field_tags WHERE entity_type='node' AND bundle='forum' AND entity_id
                     NOT IN (SELECT nid FROM forum)");
  
  $res = db_query("DELETE FROM field_data_field_tags WHERE entity_type='comment' AND bundle='comment_node_forum' AND entity_id
                     NOT IN (SELECT cid FROM comment)");
  $res = db_query("DELETE FROM field_revision_field_tags WHERE entity_type='comment' AND bundle='comment_node_forum' AND entity_id
                     NOT IN (SELECT cid FROM comment)");
 
  $res = db_query("DELETE FROM taxonomy_index WHERE nid NOT IN (SELECT nid FROM node)");

  $res = db_query('SELECT tid, name FROM taxonomy_term_data WHERE name IN (SELECT name FROM taxonomy_term_data WHERE vid=1 GROUP BY name HAVING COUNT(distinct tid) > 1 ORDER BY COUNT(tid))');

  if($res) {
    $dups = array();
    foreach($res as $row) {
      $dups[strtolower($row->name)][] = $row->tid;
    }

    foreach($dups as $name=>$tids) {
      sort($tids);
      $keep_tid = array_pop($tids);

      $res = db_query("UPDATE field_data_field_tags SET field_tags_tid=".$keep_tid." WHERE field_tags_tid IN ('".implode("','",$tids)."')");
      $res = db_query("UPDATE field_revision_field_tags SET field_tags_tid=".$keep_tid." WHERE field_tags_tid IN ('".implode("','",$tids)."')");
      $res = db_query("UPDATE taxonomy_index SET tid=".$keep_tid." WHERE tid IN ('".implode("','",$tids)."')");
      $res = db_query("DELETE FROM taxonomy_term_data WHERE vid=1 AND tid IN ('".implode("','",$tids)."')");
      $res = db_query("DELETE FROM taxonomy_term_hierarchy WHERE tid IN ('".implode("','",$tids)."')");
    }
  }

  $res = db_query("TRUNCATE TABLE iby_forum_tags_cache");

  $res = db_query("INSERT IGNORE INTO iby_forum_tags_cache (field_tags_tid, tid)
                   SELECT field_tags_tid, entity_id FROM field_data_field_tags WHERE entity_type='taxonomy_term' AND bundle='forums'");

  $res = db_query("INSERT IGNORE INTO iby_forum_tags_cache (field_tags_tid, nid)
                   SELECT field_tags_tid, entity_id FROM field_data_field_tags WHERE entity_type='node' AND bundle='forum'");

  $res = db_query("INSERT IGNORE INTO iby_forum_tags_cache (field_tags_tid, cid)
                   SELECT field_tags_tid, entity_id FROM field_data_field_tags WHERE entity_type='comment' AND bundle='comment_node_forum'");

  $res = db_query("UPDATE iby_forum_tags_cache tc SET tc.nid = (SELECT c.nid FROM comment c WHERE c.cid=tc.cid) WHERE tc.cid > 0");

  $res = db_query("UPDATE iby_forum_tags_cache tc SET tc.tid = (SELECT fi.tid FROM forum_index fi WHERE fi.nid=tc.nid) WHERE tc.nid > 0");

  $res = db_query("DELETE FROM iby_forum_tags_cache WHERE tid IS NULL");

  $cur_cid_parents = array();
  $cur_top_cid = 0;

  $sub_query = db_select('iby_forum_tags_cache', 'tc')->fields('tc', array('nid'))->condition('tc.cid', 0, '>')->distinct();
  
  $query = db_select('comment', 'c')->fields('c', array('cid', 'pid'));
  $query->addExpression('SUBSTRING(c.thread, 1, (LENGTH(c.thread) - 1))', 'torder');
  $query->condition('c.nid', $sub_query, 'IN');
  $query->orderBy('c.nid', 'ASC');
  $query->orderBy('torder', 'ASC');
  $res = $query->execute();
  if($res) {
    foreach($res as $row) {
      if($row->pid && !in_array($row->cid, $cur_cid_parents)) {
        $cur_cid_parents[] = $row->cid;

      } else {
        if(count($cur_cid_parents)) {
          db_update('iby_forum_tags_cache')->fields(array('pcid' => $cur_top_cid))->condition('cid', $cur_cid_parents, 'IN')->execute();
        }
        $cur_top_cid = $row->cid;
        $cur_cid_parents = array();
      }
    }
  }

//  
//  $filtered_cids = array();
//  
//  if(isset($query_cache['cids'])) {
//    foreach($query_cache['cids'] as $filter_cid) {
//      foreach($cid_top_parents as $top_cid=>$child_cids) {
//        if(($top_cid == $filter_cid) || in_array($filter_cid, $child_cids)) {
//          if(!in_array($top_cid, $filtered_cids)) $filtered_cids[] = $top_cid;
//          foreach($child_cids as $child_cid) if(!in_array($child_cid, $filtered_cids)) $filtered_cids[] = $child_cid;
//        }
//      }
//    }
//  }
}


function _iby_forums_get_comment_pcid($cid, $nid=false) {
  $cur_top_cid = array();
  $cur_cid_parents = array();

  if(!$nid) {
    $query = db_select('comment', 'c')->fields('c', array('nid'));
    $query->condition('c.cid', $cid);
    $nid = $query->execute()->fetchField(0);
  }

  $query = db_select('comment', 'c')->fields('c', array('cid', 'pid'));
  $query->addExpression('SUBSTRING(c.thread, 1, (LENGTH(c.thread) - 1))', 'torder');
  $query->condition('c.nid', $nid);
  $query->orderBy('torder', 'ASC');
  $res = $query->execute();
  if($res) {
    foreach($res as $row) {
      if($row->pid && isset($cur_cid_parents[$cur_top_cid]) && is_array($cur_cid_parents[$cur_top_cid]) && !in_array($row->cid, $cur_cid_parents[$cur_top_cid])) {
        $cur_cid_parents[$cur_top_cid][] = $row->cid;

      } else {
        $cur_top_cid = $row->cid;
        $cur_cid_parents[$cur_top_cid] = array();
      }
    }
  }

  foreach($cur_cid_parents as $pcid=>$cids) {
    if(in_array($cid, $cids)) return $pcid;
  }

  return $cid;
}

function iby_forums_comment_insert($comment) {
  $pcid = _iby_forums_get_comment_pcid($comment->cid);

  $query = db_select('forum_index', 'fi')->fields('fi', array('tid'));
  $query->condition('fi.nid', $comment->nid);
  $tid = $query->execute()->fetchField(0);

  if(isset($comment->field_tags['und']) && is_array($comment->field_tags['und']) && count($comment->field_tags['und'])) {
    foreach($comment->field_tags['und'] as $field_tag) {
      $keys = array('field_tags_tid' => $field_tag['tid'], 'tid' => $tid, 'nid' => $comment->nid, 'cid' => $comment->cid, 'pcid' => $pcid);
      db_merge('iby_forum_tags_cache')->key($keys)->fields($keys)->execute();
    }
  }

  //$commands = array();
  //$commands[] = ajax_command_invoke(NULL, 'goto_comment', array($comment->cid));//array('goto_url' => $_SERVER['REQUEST_URI']));
  ////$commands[] = ajax_command_invoke(NULL, 'console_log', array(var_export($form, true)));
  //$page = array('#type' => 'ajax', '#commands' => $commands);
  //ajax_deliver($page);
  //exit;
}

function iby_forums_node_presave($node) {
  global $user;
  $forum = sqtools_get_lang_value($node->taxonomy_forums);
  $forum_tid = $forum[0]['tid'];
  if(!sqtools_is_admin($user) && !_iby_forums_check_access(false, false, "forum", "contribute to", false, $forum_tid)) exit();
}

function iby_forums_comment_presave($comment) {
  global $user;
  if(!sqtools_is_admin($user) && !_iby_forums_check_access(false, false, "node", "contribute to", false, false, $comment->nid)) exit();
}

function iby_forums_node_insert($node) {
  if(isset($node->forum_tid) && $node->forum_tid) {
    if(isset($node->field_tags['und']) && is_array($node->field_tags['und']) && count($node->field_tags['und'])) {
      foreach($node->field_tags['und'] as $field_tag) {
        $keys = array('field_tags_tid' => $field_tag['tid'], 'tid' => $node->forum_tid, 'nid' => $node->nid, 'cid' => 0, 'pcid' => 0);
        db_merge('iby_forum_tags_cache')->key($keys)->fields($keys)->execute();
      }
    }
  }

  //$commands = array();
  //$commands[] = ajax_command_invoke(NULL, 'goto_node', array($node->nid));//array('goto_url' => $_SERVER['REQUEST_URI']));
  ////$commands[] = ajax_command_invoke(NULL, 'console_log', array(var_export($form, true)));
  //$page = array('#type' => 'ajax', '#commands' => $commands);
  //ajax_deliver($page);
  //exit;
  ////drupal_goto('node/'.$node->nid);
}

function iby_forums_taxonomy_term_insert($tax) {
  //echo "<pre>"; var_dump($tax); echo "</pre>"; exit;
  //$commands = array();
  //$commands[] = ajax_command_invoke(NULL, 'goto_forum', array($tax->tid));//array('goto_url' => $_SERVER['REQUEST_URI']));
  //$page = array('#type' => 'ajax', '#commands' => $commands);
  //ajax_deliver($page);
  //exit;
}

function iby_forums_taxonomy_term_updatet($tax) {
  //echo "update:<pre>"; var_dump($tax); echo "</pre>"; exit;
  //$commands = array();
  //$commands[] = ajax_command_invoke(NULL, 'goto_forum', array($tax->tid));//array('goto_url' => $_SERVER['REQUEST_URI']));
  //$page = array('#type' => 'ajax', '#commands' => $commands);
  //ajax_deliver($page);
  //exit;
}

function iby_forums_sq_flags_set_after($args) {
  $cid = false;
  $nid = false;
  $tid = false;

  if($args['entity_type'] == 'comment') $cid = $args['entity_id'];
  elseif($args['entity_type'] == 'node') $nid = $args['entity_id'];
  elseif($args['entity_type'] == 'taxonomy_term') $tid = $args['entity_id'];

  if($cid) {
    $comment = db_select('comment', 'c')->fields('c', array('nid'))->condition('c.cid', $cid)->execute()->fetchAssoc();
    if(isset($comment['nid'])) $nid = $comment['nid'];
    db_query("INSERT INTO iby_forum_flags_cache (flag_type, entity_type, entity_id, total)
                VALUES('".$args['flag_type']."', 'comment', '".$cid."', 1)
                ON DUPLICATE KEY UPDATE total=total+1");
  }

  if($nid) {
    $node = db_select('forum_index', 'fi')->fields('fi', array('tid'))->condition('fi.nid', $nid)->execute()->fetchAssoc();
    if(isset($node['tid'])) $tid = $node['tid'];
    db_query("INSERT INTO iby_forum_flags_cache (flag_type, entity_type, entity_id, total)
                VALUES('".$args['flag_type']."', 'node', '".$nid."', 1)
                ON DUPLICATE KEY UPDATE total=total+1");
  }

  if($tid) {
    db_query("INSERT INTO iby_forum_flags_cache (flag_type, entity_type, entity_id, total)
                VALUES('".$args['flag_type']."', 'taxonomy_term', '".$tid."', 1)
                ON DUPLICATE KEY UPDATE total=total+1");
  }
}


function iby_forums_sq_flags_delete_after($args) {
  $cid = false;
  $nid = false;
  $tid = false;

  if($args['entity_type'] == 'comment') $cid = $args['entity_id'];
  elseif($args['entity_type'] == 'node') $nid = $args['entity_id'];
  elseif($args['entity_type'] == 'taxonomy_term') $tid = $args['entity_id'];

  if($cid) {
    $comment = db_select('comment', 'c')->fields('c', array('nid'))->condition('c.cid', $cid)->execute()->fetchAssoc();
    if(isset($comment['nid'])) $nid = $comment['nid'];
    db_query("UPDATE iby_forum_flags_cache SET total=total-1 WHERE flag_type='".$args['flag_type']."' AND entity_type='comment' AND entity_id='".$cid."'");
  }

  if($nid) {
    $node = db_select('forum_index', 'fi')->fields('fi', array('tid'))->condition('fi.nid', $nid)->execute()->fetchAssoc();
    if(isset($node['tid'])) $tid = $node['tid'];
    db_query("UPDATE iby_forum_flags_cache SET total=total-1 WHERE flag_type='".$args['flag_type']."' AND entity_type='node' AND entity_id='".$nid."'");
  }

  if($tid) {
    db_query("UPDATE iby_forum_flags_cache SET total=total-1 WHERE flag_type='".$args['flag_type']."' AND entity_type='taxonomy_term' AND entity_id='".$tid."'");
  }

  db_query("DELETE FROM iby_forum_flags_cache WHERE total=0");
}



function iby_forums_get_tagged_comments($tags_tids) {

}

function _iby_forums_get_tid_from_name($term, $vid=1) {
  $tids = _iby_forums_get_tids_from_names(array($term), $vid);
  return array_shift($tids);
}

function _iby_forums_get_tids_from_names($terms=array(), $vid=1) {
  // Now create a clean IN string
  $q_tags_str = "";
  foreach($terms as $term) $q_tags_str .= "'".addslashes(strtolower($term))."',";
  $q_tags_str = substr($q_tags_str, 0, -1);

  // Check if we have the vid as a stored variable
  if($vid) $tags_vid = $vid;
  else {
    $tags_vid = variable_get('iby_tags_vid', 0);
    if(!$tags_vid) {
      // If no vid was found - find it
      $_query = db_select('taxonomy_vocabulary', 'tv');
      $_query->condition('machine_name', 'tags');
      $_query->fields('tv', array('vid'));
      $tags_vid = $_query->execute()->fetchField();
      
      // Store if found - otherwise just return
      if($tags_vid) variable_set('iby_tags_vid', $tags_vid);
      else return;
    }
  }
  
  // Now we will do actual tag lookup
  $_query = db_select('taxonomy_term_data', 'ttd');
  $_query->fields('ttd', array('tid'));
  //$_query->condition(db_and()->condition('vid', $tags_vid)->condition('LOWER\(name\)', $q_tags, "IN"));
  $_query->where("vid='".$tags_vid."' AND LOWER(name) IN (".$q_tags_str.")");
  $_query->orderBy('ttd.tid', 'ASC');
  $_query->distinct();

  $res = $_query->execute();
  
  $tids = array();
  foreach($res as $row) $tids[] = $row->tid;

  return $tids;
  //echo $_query->__toString();exit;
  //if(count($tids)) $query->condition(db_and()->condition(''))
  //echo "tags_vid: ".$tags_vid." <pre>"; var_dump($res); exit;
}

function _iby_forums_get_parent_tid($tid) {
  $query = db_select('taxonomy_term_hierarchy', 'th');
  $query->fields('th', array('parent'));
  $query->condition('th.tid', $tid);
  $query->join('taxonomy_term_data', 'td', 'td.tid = th.tid AND vid=2');
  $new_tid = $query->execute()->fetchField();
  if($new_tid) return $new_tid;
  else return $tid;
}

function _iby_forums_get_parent_tid_by_nid($nid) {
  $query = db_select('forum_index', 'f');
  $query->fields('f', array('tid'));
  $query->condition('f.nid', $nid);
  $tid = $query->execute()->fetchField();

  return _iby_forums_get_parent_tid($tid);
}

function _iby_forums_get_tid_by_nid($nid) {
  $query = db_select('forum_index', 'f');
  $query->fields('f', array('tid'));
  $query->condition('f.nid', $nid);
  $tid = $query->execute()->fetchField();

  return $tid;
}

function _iby_forums_get_parent_tid_by_cid($cid) {
  $query = db_select('comment', 'c');
  $query->fields('c', array('nid'));
  $query->condition('c.cid', $cid);
  $nid = $query->execute()->fetchField();
 
  return _iby_forums_get_parent_tid_by_nid($nid);
}

function _iby_forums_get_nid_by_cid($cid) {
  $query = db_select('comment', 'c');
  $query->fields('c', array('nid'));
  $query->condition('c.cid', $cid);
  $nid = $query->execute()->fetchField();
 
  return $nid;
}

function _iby_forums_get_tid_by_cid($cid) {
  $nid = _iby_forums_get_nid_by_cid($cid);
  return _iby_forums_get_tid_by_nid($nid);
}



function iby_forums_taxonomy_term_presave($term) {
  $tids = _iby_forums_get_tids_from_names(array("test"), $term->vid);
  if(count($tids)) $term->tid = array_shift($tids);
}

// Copied from the devel_module
function _iby_forums_rebuild_node_comment_statistics() {
  // Empty table
  db_truncate('node_comment_statistics')->execute();

  // TODO: DBTNG. Ignore keyword is Mysql only? Is only used in the rare case when
  // two comments on the same node share same timestamp.
  $sql = "
    INSERT IGNORE INTO {node_comment_statistics} (nid, cid, last_comment_timestamp, last_comment_name, last_comment_uid, comment_count) (
      SELECT c.nid, c.cid, c.created, c.name, c.uid, c2.comment_count FROM {comment} c
      JOIN (
        SELECT c.nid, MAX(c.created) AS created, COUNT(*) AS comment_count FROM {comment} c WHERE status = 1 GROUP BY c.nid
      ) AS c2 ON c.nid = c2.nid AND c.created = c2.created
      JOIN (
        SELECT c.nid FROM {comment} c WHERE c.status = 1 GROUP BY c.nid
      ) AS c3 ON c.nid = c3.nid
    )";
  db_query($sql, array(':published' => COMMENT_PUBLISHED));

  // Insert records into the node_comment_statistics for nodes that are missing.
  $query = db_select('node', 'n');
  $query->leftJoin('node_comment_statistics', 'ncs', 'ncs.nid = n.nid');
  $query->addField('n', 'changed', 'last_comment_timestamp');
  $query->addField('n', 'uid', 'last_comment_uid');
  $query->addField('n', 'nid');
  $query->condition('n.status', '1');
  $query->addExpression('0', 'comment_count');
  $query->addExpression('NULL', 'last_comment_name');
  $query->isNull('ncs.comment_count');

  db_insert('node_comment_statistics')
    ->from($query)
    ->execute();
}
