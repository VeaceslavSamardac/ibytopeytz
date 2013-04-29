<?php
function sq_wysiwyg_images_easy_image_upload_plugin() {
  $plugins['easy_image_upload'] = array(
    'title' => t('SQ Easy Image Upload'),
    // The 'icon file' is the icon that appears in the Wysiwyg toolbar.
    'icon file' => 'sq_easy_image.png',
    'icon title' => t('Upload and add image'),
    'settings' => array(),
  );
  return $plugins;
}
