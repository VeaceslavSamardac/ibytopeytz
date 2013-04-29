<?php
/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined, e.g. $node->body becomes $body. When needing to access
 * a field's raw values, developers/themers are strongly encouraged to use these
 * variables. Otherwise they will have to explicitly specify the desired field
 * language, e.g. $node->body['en'], thus overriding any language negotiation
 * rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 */

if(substr($node_url, 0, 9) == "/error/40") {
  $path = substr($_SERVER['REQUEST_URI'],1);
  $type = arg(0, $path);
  $object_id = arg(1, $path);

  $tid = false;

  if(($type == "forum") && $object_id) $tid = _iby_forums_get_parent_tid($object_id);
  elseif(($type == "node") && $object_id) $tid = _iby_forums_get_parent_tid_by_nid($object_id);

  if($tid) {
    $tax = taxonomy_term_load($tid);
    $forum_slug = StringTools::slug($tax->name);
    $alias = 'error/'.$forum_slug;
    $path = drupal_lookup_path('source', $alias);

    if($path) {
      $node = node_load(arg(1, $path));
      $title = $node->title;
      $_content = $node->body['und'][0]['value'];
    }
  }
}

?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
<!--
  <?php print render($title_prefix); ?>
    <?php if ($title && ($page || $view_mode == 'full' )) :?>
      <h3 style="font-size:15px; color:#00B0CA; border-bottom: 1px dotted #666; margin-bottom: 20px; padding-bottom: 10px;"><?php print $title; ?></h3>
    <?php endif; ?>
  <?php print render($title_suffix); ?> 
 -->
  <?php if (!empty($tags)) :?>
    <?php print theme('links', array('links' => $tags)); ?>
  <?php endif; ?>
  <div class="content content-node <?php if (!empty($media)) print 'include-media'; ?>" style="font-size: 12px; width:560px;"<?php print $content_attributes; ?> >
    <?php if (!$page && ($view_mode != 'full') ): ?>
      <h2<?php print $title_attributes; ?> class="node-content-title"  style="font-size:12px; font-weight:bold;"><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>

    <?php
      // We hide the comments and links now so that we can render them later.
      if(isset($_content)) echo $_content;
      else {
          hide($content['links']);
          hide($content['comments']);
          echo render($content);
      }

    ?>
    <?php 
    if (!$page) {
      print render($content['links']); 
    }
    ?>
  </div>

</div>
