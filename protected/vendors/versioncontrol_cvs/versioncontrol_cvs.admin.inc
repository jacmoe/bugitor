<?php
// $Id: versioncontrol_cvs.admin.inc,v 1.19 2009/03/27 15:01:26 jpetso Exp $
/**
 * @file
 * CVS backend for Version Control API - Provides CVS commit information and
 * account management as a pluggable backend.
 *
 * This file contains the administrative user interface customizations
 * for accounts and repositories.
 *
 * Copyright 2006, 2007 Derek Wright ("dww" , http://drupal.org/user/46549)
 * Copyright 2007 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 */

define('VERSIONCONTROL_CVS_MIN_PASSWORD_LENGTH', 5);

/**
 * Implementation of hook_form_alter(): Add elements to various
 * administrative forms that the Version Control API provides.
 */
function versioncontrol_cvs_form_alter(&$form, $form_state, $form_id) {
  if ($form['#id'] == 'versioncontrol-repository-form' && $form['#vcs'] == 'cvs') {
    versioncontrol_cvs_repository_admin_form_alter($form, $form_state, $form_id);
  }
  else if ($form['#id'] == 'versioncontrol-account-form' && $form['#vcs'] == 'cvs') {
    versioncontrol_cvs_account_form_alter($form, $form_state, $form_id);
  }
}


/**
 * Add CVS specific elements to the add/edit repository form.
 */
function versioncontrol_cvs_repository_admin_form_alter(&$form, $form_state, $form_id) {
  $repository = $form['#repository'];

  $form['#versioncontrol_cvs'] = TRUE;
  $form['#updated'] = isset($repository) ? $repository['cvs_specific']['updated'] : 0;

  $form['repository_information']['root']['#description'] = t(
    'The CVSROOT of this repository. Examples: /path or :pserver:user:password@server:/path.'
  );
  $form['repository_information']['modules'] = array(
    '#type' => 'textfield',
    '#title' => t('Modules'),
    '#description' => t('Separate multiple CVS modules with spaces. If the "Automatic log retrieval" update method is enabled, only commits in these modules will be recorded. (Leave empty to record all commits, including those in CVSROOT.)'),
    '#default_value' => isset($repository) ? implode(' ', $repository['cvs_specific']['modules']) : '',
    '#weight' => 7,
    '#size' => 40,
    '#maxlength' => 255,
  );
  $form['repository_information']['update_method'] = array(
    '#type' => 'radios',
    '#title' => t('Update method'),
    '#description' => t('Automatic log retrieval requires cron.'),
    '#default_value' => isset($repository)
                        ? $repository['cvs_specific']['update_method']
                        : VERSIONCONTROL_CVS_UPDATE_CRON,
    '#weight' => 10,
    '#options' => array(
      VERSIONCONTROL_CVS_UPDATE_CRON => t('Automatic log retrieval.'),
      VERSIONCONTROL_CVS_UPDATE_XCVS => t('Use external script to insert data.'),
    ),
  );

  $form['cvs_export_information'] = array(
    '#type' => 'fieldset',
    '#title' => t('Account export information'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#weight' => 2,
  );
  $form['cvs_export_information']['run_as_user'] = array(
    '#type' => 'textfield',
    '#title' => t('Run as different system user'),
    '#description' => t('If this is empty, the exported account data will cause server-side CVS to be run with the system user corresponding to the authenticated CVS account name. If this field is not empty and you specify a different system username here, the exported account data will cause CVS to run as this user instead.'),
    '#default_value' => isset($repository)
                        ? $repository['cvs_specific']['run_as_user']
                        : 'drupal-cvs',
    '#weight' => 0,
    '#size' => 40,
    '#maxlength' => 255,
  );
}

/**
 * Implementation of hook_versioncontrol_repository_submit():
 * Extract repository data from the repository editing/adding form's
 * submitted values, and add it to the @p $repository array. Later, that array
 * will be passed to hook_versioncontrol_repository() as part of the repository
 * insert/update procedure.
 */
function versioncontrol_cvs_versioncontrol_repository_submit(&$repository, $form, $form_state) {
  if (!isset($form['#versioncontrol_cvs'])) {
    return;
  }
  $modules = trim($form_state['values']['modules']);
  $modules = empty($modules) ? array() : explode(' ', $modules);

  $repository['cvs_specific'] = array(
    'modules'       => $modules,
    'updated'       => $form['#updated'],
    'update_method' => $form_state['values']['update_method'],
    'run_as_user'   => $form_state['values']['run_as_user'],
  );
}

/**
 * Implementation of hook_versioncontrol_alter_repository_list():
 * Add CVS specific columns into the list of CVS repositories.
 * By changing the @p $header and @p $rows_by_repo_id arguments,
 * the repository list can be customized accordingly.
 *
 * @param $vcs
 *   The unique string identifier for the version control system that
 *   the passed repository list covers.
 * @param $repositories
 *   An array of repositories of the given version control system.
 *   Array keys are the repository ids, and array values are the
 *   repository arrays like returned from versioncontrol_get_repository().
 * @param $header
 *   A list of columns that will be passed to theme('table').
 * @param $rows_by_repo_id
 *   An array of existing table rows, with repository ids as array keys.
 *   Each row already includes the generic column values, and for each row
 *   there is a repository with the same repository id given in the
 *   @p $repositories parameter.
 */
function versioncontrol_cvs_versioncontrol_alter_repository_list($vcs, $repositories, &$header, &$rows_by_repo_id) {
  if ($vcs != 'cvs') {
    return;
  }
  $header[] = t('Modules');
  $header[] = t('Update method');
  $header[] = t('Last updated');

  foreach ($rows_by_repo_id as $repo_id => &$row) {
    if (strpos($repositories[$repo_id]['root'], ':pserver:') !== FALSE) {
      $host_start_index = strpos($repositories[$repo_id]['root'], '@') + 1;
      $row['root'] = check_plain(substr($repositories[$repo_id]['root'], $host_start_index));
    }

    $modules = array();
    foreach ($repositories[$repo_id]['cvs_specific']['modules'] as $module) {
      $modules[] = check_plain($module);
    }
    $row['modules'] = theme('item_list', $modules);

    if ($repositories[$repo_id]['cvs_specific']['update_method'] == VERSIONCONTROL_CVS_UPDATE_XCVS) {
      $row['update_method'] = t('external script');
    }
    else if ($repositories[$repo_id]['cvs_specific']['update_method'] == VERSIONCONTROL_CVS_UPDATE_CRON) {
      $row['update_method'] = t('logs (!fetch)', array(
        '!fetch' => l(t('fetch now'), 'admin/project/versioncontrol-repositories/update/cvs/'. $repo_id)
      ));
    }
    $row['updated'] = $repositories[$repo_id]['cvs_specific']['updated']
      ? format_date($repositories[$repo_id]['cvs_specific']['updated'], 'small')
      : t('never');
  }
}


/**
 * Add CVS specific elements to the edit/register user account form.
 */
function versioncontrol_cvs_account_form_alter(&$form, $form_state, $form_id) {
  $form['#versioncontrol_cvs'] = TRUE;

  if (empty($form['#original_username'])) { // creating the account
    $description = t('Choose a password to access the CVS repository with.');
  }
  else { // editing the account
    $description = t('To change the current CVS password, enter the new password in both fields.');
  }
  $form['account']['account_password'] = array(
    '#type' => 'password_confirm',
    '#title' => t('CVS password'),
    '#description' => $description,
    '#weight' => 10,
  );
  $form['#validate'][] = 'versioncontrol_cvs_account_form_validate';
}

/**
 * Additional validation for the edit/register user account form.
 */
function versioncontrol_cvs_account_form_validate($form, &$form_state) {
  if (!empty($form['#original_username']) && empty($form_state['values']['account_password'])) {
    return; // The (existing) user didn't change the password.
  }
  else if (drupal_strlen($form_state['values']['account_password']) < VERSIONCONTROL_CVS_MIN_PASSWORD_LENGTH) {
    form_set_error('account_password', t('The CVS password you have chosen is too short (it must be at least !min characters long).', array('!min' => VERSIONCONTROL_CVS_MIN_PASSWORD_LENGTH)));
  }
}

/**
 * Implementation of hook_versioncontrol_account_submit():
 * Extract account data from the account edit/register form's submitted
 * values, and add it to the @p $additional_data array. Later, that array
 * will be passed to hook_versioncontrol_account() as part of the account
 * insert/update procedure.
 */
function versioncontrol_cvs_versioncontrol_account_submit(&$additional_data, $form, $form_state) {
  if (!isset($form['#versioncontrol_cvs']) || empty($form_state['values']['account_password'])) {
    return;
  }
  $additional_data['cvs_specific']['password'] = crypt($form_state['values']['account_password']);
}
