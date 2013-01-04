<?php
// $Id: versioncontrol_svn.forms.inc,v 1.11 2009/03/27 15:04:32 jpetso Exp $
/**
 * @file
 * Subversion backend for Version Control API - Provides Subversion commit
 * information and account management as a pluggable backend.
 *
 * Copyright 2007 by Jakob Petsovits ("jpetso", http://drupal.org/user/56020)
 * Copyright 2007 by Adam Light ("aclight", http://drupal.org/user/86358)
 */


/**
 * Implementation of hook_form_alter(): Add elements to various
 * administrative forms that the Version Control API provides.
 */
function versioncontrol_svn_form_alter(&$form, $form_state, $form_id) {
  if ($form['#id'] == 'versioncontrol-repository-form' && $form['#vcs'] == 'svn') {
    versioncontrol_svn_repository_admin_form_alter($form, $form_state, $form_id);
  }
}


/**
 * Add SVN specific elements to the add/edit repository form.
 */
function versioncontrol_svn_repository_admin_form_alter(&$form, $form_state, $form_id) {
  $repository = $form['#repository'];
  $svn_specific = isset($repository) ? $repository['svn_specific'] : array(
    'updated'       => 0,
    'last_revision' => 0,
    'auth_username' => '',
    'auth_password' => '',
    'update_method' => VERSIONCONTROL_SVN_UPDATE_CRON,
    'path_trunk'    => '/%project/trunk',
    'path_branches' => '/%project/branches/%branch',
    'path_tags'     => '/%project/tags/%branch/%tag',
  );

  $form['#versioncontrol_svn'] = TRUE;
  $form['#updated'] = $svn_specific['updated'];
  $form['#last_revision'] = $svn_specific['last_revision'];

  $form['repository_information']['root']['#description'] = t(
    'The URL of this repository. Example: file:///svnroot/repo'
  );

  $form['repository_information']['svn_authentication'] = array(
    '#type' => 'fieldset',
    '#title' => t('Authentication'),
    '#description' => t('If authentication is required in order to be retrieve commit logs and other information from the repository, you need to supply a username and password that will be passed to the \'svn\' executable as \'--username\' and \'--password\' options.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#weight' => 10,
  );
  $form['repository_information']['svn_authentication']['auth_username'] = array(
    '#type' => 'textfield',
    '#title' => t('Username'),
    '#description' => t('Leave empty in order to fetch logs (and other information) anonymously.'),
    '#default_value' => $svn_specific['auth_username'],
    '#weight' => 1,
    '#size' => 40,
    '#maxlength' => 128,
  );
  $form['repository_information']['svn_authentication']['auth_password'] = array(
    '#type' => 'password',
    '#title' => t('Password'),
    '#description' => t('If empty, the password will not be changed.'),
    '#default_value' => $svn_specific['auth_password'],
    '#weight' => 2,
    '#size' => 40,
    '#maxlength' => 128,
  );

  $form['repository_information']['update_method'] = array(
    '#type' => 'radios',
    '#title' => t('Update method'),
    '#description' => t('Automatic log retrieval requires cron.'),
    '#default_value' => $svn_specific['update_method'],
    '#weight' => 12,
    '#options' => array(
      VERSIONCONTROL_SVN_UPDATE_CRON => t('Automatic log retrieval.'),
      //VERSIONCONTROL_SVN_UPDATE_XSVN => t('Use external script to insert data.'),
    ),
  );

  $form['svn_repository_layout'] = array(
    '#type' => 'fieldset',
    '#title' => t('Repository layout'),
    '#description' => t('In order to recognize branches and tags, the Subversion backend needs to know where the trunk, branches and tags directories are located in this repository.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#weight' => 2,
  );
  $form['svn_repository_layout']['path_trunk'] = array(
    '#type' => 'textfield',
    '#title' => t('Trunk directory'),
    '#description' => t('Specify the path of the trunk directory here. Use %project as a placeholder for a directory name (probably the project name) that also occurs in the branches and tags paths.'),
    '#default_value' => $svn_specific['path_trunk'],
    '#weight' => 0,
    '#size' => 40,
    '#maxlength' => 255,
  );
  $form['svn_repository_layout']['path_branches'] = array(
    '#type' => 'textfield',
    '#title' => t('Branches directory'),
    '#description' => t('Specify the path of the branches directory here. Use %branch as a placeholder for the branch name and %project for the directory name that was specified in the trunk directory.'),
    '#default_value' => $svn_specific['path_branches'],
    '#weight' => 1,
    '#size' => 40,
    '#maxlength' => 255,
  );
  $form['svn_repository_layout']['path_tags'] = array(
    '#type' => 'textfield',
    '#title' => t('Tags directory'),
    '#description' => t('Specify the path of the tags directory here. Use %tags as a placeholder for the tag name, %branch for the branch name and %project for the directory name that was specified in the trunk directory.'),
    '#default_value' => $svn_specific['path_tags'],
    '#weight' => 2,
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
function versioncontrol_svn_versioncontrol_repository_submit(&$repository, $form, $form_state) {
  if (!isset($form['#versioncontrol_svn'])) {
    return;
  }

  $repository['svn_specific'] = array(
    'updated'       => $form['#updated'],
    'last_revision' => $form['#last_revision'],
    'update_method' => $form_state['values']['update_method'],
    'path_trunk'    => $form_state['values']['path_trunk'],
    'path_branches' => $form_state['values']['path_branches'],
    'path_tags'     => $form_state['values']['path_tags'],
    'auth_username' => $form_state['values']['auth_username'],
  );
  if (empty($form_state['values']['auth_username'])) {
    $repository['svn_specific']['auth_password'] = '';
  }
  else if (!empty($form_state['values']['auth_password'])) {
    $repository['svn_specific']['auth_password'] = str_rot13($form_state['values']['auth_password']);
  }
}

/**
 * Implementation of hook_versioncontrol_alter_repository_list():
 * Add SVN specific columns into the list of Subversion repositories.
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
function versioncontrol_svn_versioncontrol_alter_repository_list($vcs, $repositories, &$header, &$rows_by_repo_id) {
  if ($vcs != 'svn') {
    return;
  }
  $header[] = t('Update method');
  $header[] = t('Last updated');

  foreach ($rows_by_repo_id as $repo_id => &$row) {
    if ($repositories[$repo_id]['svn_specific']['update_method'] == VERSIONCONTROL_SVN_UPDATE_XSVN) {
      $row['update_method'] = t('external script');
      $row['updated'] = t('n/a');
    }
    else if ($repositories[$repo_id]['svn_specific']['update_method'] == VERSIONCONTROL_SVN_UPDATE_CRON) {
      $row['update_method'] = t('logs (!fetch)', array(
        '!fetch' => l(t('fetch now'), 'admin/project/versioncontrol-repositories/update/svn/'. $repo_id)
      ));
      $row['updated'] = $repositories[$repo_id]['svn_specific']['updated']
        ? t('!date (r!revision)', array(
            '!date' => format_date($repositories[$repo_id]['svn_specific']['updated'], 'small'),
            '!revision' => $repositories[$repo_id]['svn_specific']['last_revision'],
          ))
        : t('never');
    }
  }
}
