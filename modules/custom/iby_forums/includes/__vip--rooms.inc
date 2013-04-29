<?php
/**
 * Process variables for challenges__forums.tpl.php
 *
 * The $variables array contains the following arguments:
 * - $forums
 * - $forums_ended
 * - $topics
 * - $parents
 * - $tid
 * - $sortby
 * - $forum_per_page
 *
 * @see challenges__forums.tpl.php
 */

function iby_forums_preprocess_vip__rooms__forums(&$variables) {
  global $user;

  // O.k... So this is where i need to do the magic....

  // Wanted breadcrumb = Home > Challenges > Challenge #1

  $vid = variable_get('forum_nav_vocabulary', 0);
  $vocabulary = taxonomy_vocabulary_load($vid);
  $title = !empty($vocabulary->name) ? $vocabulary->name : '';

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







