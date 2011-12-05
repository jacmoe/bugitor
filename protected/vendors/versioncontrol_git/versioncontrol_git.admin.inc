<?php
// $Id: versioncontrol_git.admin.inc,v 1.12.2.3 2010/12/09 00:27:23 marvil07 Exp $
/**
 * @file
 * Git backend for Version Control API - Provides Git commit information and
 * account management as a pluggable backend.
 *
 * Copyright 2008 by Jimmy Berry ("boombatower", http://drupal.org/user/214218)
 * Copyright 2009 by Cornelius Riemenschneider ("CorniI", http://drupal.org/user/136353)
 */

/**
 * Implementation of hook_form_alter(): Add elements to various
 * administrative forms that the Version Control API provides.
 */
function versioncontrol_git_form_alter(&$form, $form_state, $form_id) {
  if ($form['#id'] == 'versioncontrol-repository-form' && $form['#vcs'] == 'git') {
    versioncontrol_git_repository_admin_form_alter($form, $form_state, $form_id);
  }
}

/**
 * Add Git specific elements to the add/edit repository form.
 */
function versioncontrol_git_repository_admin_form_alter(&$form, $form_state, $form_id) {
  $repository = isset($form['#repository'])? $form['#repository']: null;

  $form['versioncontrol_git'] = array(
    '#type' => 'value',
    '#value' => TRUE,
  );
  $form['repository_information']['update_method'] = array(
    '#type' => 'radios',
    '#title' => t('Update method'),
    '#description' => t('Automatic log retrieval requires cron.'),
    '#default_value' => !is_null($repository)
                        ? $repository->update_method
                        : VERSIONCONTROL_GIT_UPDATE_CRON,
    '#weight' => 9,
    '#options' => array(
      VERSIONCONTROL_GIT_UPDATE_CRON => t('Automatic log retrieval.'),
      VERSIONCONTROL_GIT_UPDATE_XGIT => t('Use external script to insert data.'),
    ),
  );
  $form['repository_information']['root']['#description'] = t('An absolute path to your bare repository.');
  $form['#validate'][] = 'versioncontrol_git_repository_validate';
}

/**
 * Validation of the repository root.
 */
function versioncontrol_git_repository_validate($form, &$form_state) {
  // create a non-in-db-repo just for the validation
  $repo = new VersioncontrolGitRepository(new VersioncontrolGitBackend());
  $repo->root = $form_state['values']['root'];
  if (!$repo->isValidGitRepo()) {
    form_set_error('root', t('The specified path is not a valid Git bare repository.'));
  }
}

/**
 * Implementation of hook_versioncontrol_repository_submit():
 * Extract Git specific repository additions from the repository
 * editing/adding form's submitted values.
 */
function versioncontrol_git_versioncontrol_repository_submit(&$repository, $form, $form_state) {
  if (!isset($form['versioncontrol_git'])) {
    return array();
  }

  $update_method = (int)$form_state['values']['update_method'];
  $repository->update_method = $update_method;

  // provide defaulf values on repo create
  if (!isset($repository->updated)) {
    $repository->updated = 0;
  }
  if (!isset($repository->locked)) {
    $repository->locked = 0;
  }
}

/**
 * Implementation of hook_versioncontrol_alter_repository_list():
 * Add Git specific columns into the list of Git repositories.
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
function versioncontrol_git_versioncontrol_alter_repository_list($vcs, $repositories, &$header, &$rows_by_repo_id) {
  if ($vcs != 'git') {
    return;
  }
  $header[] = t('Update method');
  $header[] = t('Last updated');
  $header[] = t('Locking');

  foreach ($rows_by_repo_id as $repo_id => $row) {
    if ($repositories[$repo_id]->update_method == VERSIONCONTROL_GIT_UPDATE_XGIT) {
      $rows_by_repo_id[$repo_id][] = t('external script');
    }
    if ($repositories[$repo_id]->update_method == VERSIONCONTROL_GIT_UPDATE_CRON) {
      $rows_by_repo_id[$repo_id][] = t('logs (!fetch)', array(
        '!fetch' => l(t('fetch now'), 'admin/project/versioncontrol-repositories/update/git/'. $repo_id)
      ));
    }
    $rows_by_repo_id[$repo_id][] = $repositories[$repo_id]->updated
      ? format_date($repositories[$repo_id]->updated, 'small')
      : t('never');
    $rows_by_repo_id[$repo_id][] = t('Locking (!lock)', array(
        '!lock' => l(t('Clear lock now'), 'admin/project/versioncontrol-repositories/clearlock/git/'. $repo_id)
      ));
  }
}
