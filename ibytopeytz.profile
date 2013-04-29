<?php
/**
 * @file
 * Install profile for the Haven project.
 */

/* --- HOOKS ---------------------------------------------------------------- */

/**
 * Implements hook_form_FORM_ID_alter().
 */
function ibytopeytz_form_install_configure_form_alter(&$form, $form_state) {
  // basic site information
  $form['site_information']['site_name']['#default_value'] = '';
  $form['site_information']['site_mail']['#default_value'] = 'qwe@qwe.qwe';

  // set the admin username and password to make reinstalls less painful
  $form['admin_account']['account']['name']['#default_value'] = 'admin';
  $form['admin_account']['account']['mail']['#default_value'] = 'qwe@qwe.qwe';

  $form['admin_account']['account']['pass']['#type'] = 'hidden';
  $form['admin_account']['account']['pass']['#value'] = '1234';

  $form['admin_account']['account']['pass_help']['#markup'] = '<p><strong>'. t('The admin password has been set automatically.') .'</strong></p>';

  // set default timezone
  $form['server_settings']['site_default_country']['#default_value'] = 'DK';
  $form['server_settings']['date_default_timezone']['#default_value'] = 'Europe/Copenhagen';

  // disable automatic client timezone detection
  unset($form['server_settings']['date_default_timezone']['#attributes']);

  // don't send update e-mails
  $form['update_notifications']['update_status_module']['#default_value'] = array(1);
}
