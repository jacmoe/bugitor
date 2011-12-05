<?php
// $Id: cvs_to_versioncontrol_cvs_update.php,v 1.9 2009/01/10 14:44:27 jpetso Exp $

/**
 * @file
 * Administrative page for handling updates from cvs module to
 * versioncontrol_cvs module.
 *
 * Place this file in the root of your Drupal installation (ie, the same
 * directory as index.php), point your browser to
 * "http://yoursite/cvs_to_versioncontrol_cvs_update.php" and follow the
 * instructions.
 *
 * If you are not logged in as administrator, you will need to modify the access
 * check statement below. Change the TRUE to a FALSE to disable the access
 * check. After finishing the upgrade, be sure to open this file and change the
 * FALSE back to a TRUE!
 */

// Enforce access checking?
$access_check = TRUE;

/**
 * Convert CVS repositories.
 */
function cvs_to_versioncontrol_cvs_update_1() {
  $count = 0;
  $repos = db_query("SELECT * FROM {cvs_repositories} ORDER BY rid");

  while ($repo = db_fetch_object($repos)) {
    db_query("INSERT INTO {versioncontrol_repositories} (repo_id, name, vcs, root, authorization_method, url_backend) VALUES (%d, '%s', '%s', '%s', '%s', '%s')", $repo->rid, $repo->name, 'cvs', $repo->root, 'versioncontrol_admin', 'versioncontrol_default_urls');
    db_query("INSERT INTO {versioncontrol_repository_metadata} (repo_id, weight, registration_message) VALUES (%d, %d, '%s')", $repo->rid, $count, '');
    // TODO: Best effort to convert the file/branch/tag placeholders to the new format.
    $file_view = str_replace(array('%file', '%revision'), array('%path', '%revision'), $repo->newurl);
    $diff = str_replace(array('%file', '%old', '%revision'), array('%path', '%old-revision', '%new-revision'), $repo->diffurl);
    db_query("INSERT INTO {versioncontrol_repository_urls} (repo_id, commit_view, file_log_view, file_view, directory_view, diff, tracker) VALUES (%d, '%s', '%s', '%s', '%s', '%s', '%s')", $repo->rid, '', '', $file_view, '', $diff, $repo->trackerurl);
    db_query("INSERT INTO {versioncontrol_account_status_strings} (repo_id, default_condition_description, default_condition_error, motivation_description, user_notification_email, admin_notification_email, approved_email, pending_email, declined_email, disabled_email) VALUES (%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", $repo->rid, '', '', '', '', '', '', '', '', '');
    db_query("INSERT INTO {versioncontrol_cvs_repositories} (repo_id, modules, update_method, updated, run_as_user) VALUES (%d, '%s', %d, %d, '%s')", $repo->rid, serialize(explode(' ', $repo->modules)), $repo->method, $repo->updated, serialize('drupal-cvs'));
    $count++;
  }

  return array(array('success' => TRUE, 'query' => t('Converted @count repositories.', array('@count' => $count))));
}

/**
 * Convert CVS users.
 */
function cvs_to_versioncontrol_cvs_update_2() {

  // This determines how many users will be processed in each batch run. A reasonable
  // default has been chosen, but you may want to tweak depending on your setup.
  $limit = 200;

  // Multi-part update
  if (!isset($_SESSION['cvs_to_versioncontrol_cvs_update_2'])) {
    $_SESSION['cvs_to_versioncontrol_cvs_update_2'] = 0;
    $_SESSION['cvs_to_versioncontrol_cvs_update_2_max'] = db_result(db_query("SELECT COUNT(*) FROM {cvs_accounts} WHERE cvs_user <> '' AND uid <> 0"));
  }

  $default_repo = $_SESSION['cvs_to_versioncontrol_cvs_update_default_repo'];

  // Map CVS module statuses to Version Control API statuses.
  $status_map = array(
    CVS_PENDING => VERSIONCONTROL_ACCOUNT_STATUS_PENDING,
    CVS_APPROVED => VERSIONCONTROL_ACCOUNT_STATUS_APPROVED,
    CVS_DECLINED => VERSIONCONTROL_ACCOUNT_STATUS_DECLINED,
    CVS_QUEUED => VERSIONCONTROL_ACCOUNT_STATUS_QUEUED,
    CVS_DISABLED => VERSIONCONTROL_ACCOUNT_STATUS_DISABLED,
  );

  // Pull the next batch of users.
  $cvs_accounts = db_query_range("SELECT * FROM {cvs_accounts} WHERE cvs_user <> '' AND uid <> 0 ORDER BY cvs_user", $_SESSION['cvs_to_versioncontrol_cvs_update_2'], $limit);

  // Loop through each co-maintainer.
  while ($cvs_account = db_fetch_object($cvs_accounts)) {
    $repos = array();

    // Pull the repos that the cvs user is associated with.
    $user_repos = db_query("SELECT DISTINCT rid FROM {cvs_messages} WHERE cvs_user = '%s'", $cvs_account->cvs_user);
    while ($user_repo = db_fetch_object($user_repos)) {
      $repos[] = $user_repo->rid;
    }
    // Add default repo if one exists, and the user isn't associated with any other repos.
    if (empty($repos) && $default_repo) {
      $repos[] = $default_repo;
    }
    // Add a CVS user for each repo.
    foreach ($repos as $repo) {
      // This check is necessary because Version Control only allows one user account
      // per Drupal user per repository, but CVS module did not have this restriction.
      if (!db_result(db_query("SELECT uid FROM {versioncontrol_accounts} WHERE uid = %d AND repo_id = %d", $cvs_account->uid, $repo))) {
        db_query("INSERT INTO {versioncontrol_accounts} (uid, repo_id, username) VALUES (%d, %d, '%s')", $cvs_account->uid, $repo, $cvs_account->cvs_user);
        db_query("INSERT INTO {versioncontrol_cvs_accounts} (uid, repo_id, password) VALUES (%d, %d, '%s')", $cvs_account->uid, $repo, $cvs_account->pass);
        db_query("INSERT INTO {versioncontrol_account_status_users} (uid, repo_id, motivation, status) VALUES (%d, %d, '%s', %d)", $cvs_account->uid, $repo, $cvs_account->motivation, $status_map[$cvs_account->status]);
      }
    }

    $_SESSION['cvs_to_versioncontrol_cvs_update_2']++;

  }

  if ($_SESSION['cvs_to_versioncontrol_cvs_update_2'] >= $_SESSION['cvs_to_versioncontrol_cvs_update_2_max']) {
    $count = $_SESSION['cvs_to_versioncontrol_cvs_update_2_max'];
    unset($_SESSION['cvs_to_versioncontrol_cvs_update_2']);
    unset($_SESSION['cvs_to_versioncontrol_cvs_update_2_max']);
    unset($_SESSION['cvs_to_versioncontrol_cvs_update_default_repo']);
    return array(array('success' => TRUE, 'query' => t('Converted @count CVS user entries.', array('@count' => $count))));
  }
  return array('#finished' => $_SESSION['cvs_to_versioncontrol_cvs_update_2'] / $_SESSION['cvs_to_versioncontrol_cvs_update_2_max']);
}

/**
 * Convert project repository data.
 */
function cvs_to_versioncontrol_cvs_update_3() {

  // This determines how many projects will be processed in each batch run. A reasonable
  // default has been chosen, but you may want to tweak depending on your setup.
  $limit = 100;

  // Multi-part update
  if (!isset($_SESSION['cvs_to_versioncontrol_cvs_update_3'])) {
    $_SESSION['cvs_to_versioncontrol_cvs_update_3'] = 0;
    $_SESSION['cvs_to_versioncontrol_cvs_update_3_max'] = db_result(db_query("SELECT COUNT(*) FROM {cvs_projects}"));
  }

  // Pull the next batch of users.
  $projects = db_query_range("SELECT p.nid, p.rid, p.directory, r.modules FROM {cvs_projects} p INNER JOIN {cvs_repositories} r ON p.rid = r.rid ORDER BY p.nid", $_SESSION['cvs_to_versioncontrol_cvs_update_3'], $limit);

  // Loop through each project.
  while ($project = db_fetch_object($projects)) {
    // Add the repo module, and chop off the trailing slash.
    $directory = '/'. trim($project->modules) .
      drupal_substr($project->directory, 0, drupal_strlen($project->directory) - 1);
    db_query("INSERT INTO {versioncontrol_project_projects} (nid, repo_id, directory) VALUES (%d, %d, '%s')", $project->nid, $project->rid, $directory);
    $_SESSION['cvs_to_versioncontrol_cvs_update_3']++;
  }

  if ($_SESSION['cvs_to_versioncontrol_cvs_update_3'] >= $_SESSION['cvs_to_versioncontrol_cvs_update_3_max']) {
    $count = $_SESSION['cvs_to_versioncontrol_cvs_update_3_max'];
    unset($_SESSION['cvs_to_versioncontrol_cvs_update_3']);
    unset($_SESSION['cvs_to_versioncontrol_cvs_update_3_max']);
    return array(array('success' => TRUE, 'query' => t('Converted @count project repository entries.', array('@count' => $count))));
  }
  return array('#finished' => $_SESSION['cvs_to_versioncontrol_cvs_update_3'] / $_SESSION['cvs_to_versioncontrol_cvs_update_3_max']);
}

/**
 * Perform one update and store the results which will later be displayed on
 * the finished page.
 *
 * @param $module
 *   The module whose update will be run.
 * @param $number
 *   The update number to run.
 *
 * @return
 *   TRUE if the update was finished. Otherwise, FALSE.
 */
function update_data($module, $number) {

  $function = "cvs_to_versioncontrol_cvs_update_$number";
  $ret = $function();

  // Assume the update finished unless the update results indicate otherwise.
  $finished = 1;
  if (isset($ret['#finished'])) {
    $finished = $ret['#finished'];
    unset($ret['#finished']);
  }

  // Save the query and results for display by update_finished_page().
  if (!isset($_SESSION['update_results'])) {
    $_SESSION['update_results'] = array();
  }
  if (!isset($_SESSION['update_results'][$module])) {
    $_SESSION['update_results'][$module] = array();
  }
  if (!isset($_SESSION['update_results'][$module][$number])) {
    $_SESSION['update_results'][$module][$number] = array();
  }
  $_SESSION['update_results'][$module][$number] = array_merge($_SESSION['update_results'][$module][$number], $ret);

  return $finished;
}

function update_selection_page() {
  $output = '';
  $output .= '<p>Click Update to start the update process.</p>';

  drupal_set_title('CVS module to Version Control/CVS module update');
  // Use custom update.js.
  drupal_add_js(update_js(), 'inline');
  $output .= drupal_get_form('update_script_selection_form');

  return $output;
}

function update_script_selection_form() {
  $form = array();

  $options = array(0 => '<none>');
  $repos = db_query("SELECT rid, name FROM {cvs_repositories}");
  while ($repo = db_fetch_object($repos)) {
    $options[$repo->rid] = check_plain($repo->name);
  }
  if (count($options) > 1) {
    $form['default_repo'] = array(
      '#type' => 'select',
      '#title' => 'Default repository',
      '#options' => $options,
      '#description' => 'The script can only migrate CVS user accounts if the CVS user has previously made at least one commit to a repository for which they have access.  If a CVS user has never made a commit to any repository, you can add them to a default repository by selecting one above. If you leave this set to &lt;none&gt;, then CVS user accounts in this category will not be migrated',
    );
  }
  else {
    $form['default_repo'] = array(
      '#type' => 'value',
      '#value' => 0,
    );
  }

  $form['has_js'] = array(
    '#type' => 'hidden',
    '#default_value' => FALSE,
    '#attributes' => array('id' => 'edit-has_js'),
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Update',
  );
  return $form;
}

function update_update_page() {
  // Set the installed version so updates start at the correct place.
  $_SESSION['update_remaining'][] = array('module' => 'versioncontrol_cvs', 'version' => 1);
  $_SESSION['update_remaining'][] = array('module' => 'versioncontrol_cvs', 'version' => 2);

  // Set the default repo.
  $_SESSION['cvs_to_versioncontrol_cvs_update_default_repo'] = $_POST['default_repo'];

  // Keep track of total number of updates
  if (isset($_SESSION['update_remaining'])) {
    $_SESSION['update_total'] = count($_SESSION['update_remaining']);
  }

  if ($_POST['has_js']) {
    return update_progress_page();
  }
  else {
    return update_progress_page_nojs();
  }
}

function update_progress_page() {
  // Prevent browser from using cached drupal.js or update.js
  drupal_add_js('misc/progress.js', 'core', 'header', FALSE, TRUE);
  drupal_add_js(update_js(), 'inline');

  drupal_set_title('Updating');
  $output = '<div id="progress"></div>';
  $output .= '<p id="wait">Please wait while your site is being updated.</p>';
  return $output;
}

/**
 * Can't include misc/update.js, because it makes a direct call to update.php.
 *
 * @return unknown
 */
function update_js() {
  return "
  if (Drupal.jsEnabled) {
    $(document).ready(function() {
      $('#edit-has-js').each(function() { this.value = 1; });
      $('#progress').each(function () {
        var holder = this;

        // Success: redirect to the summary.
        var updateCallback = function (progress, status, pb) {
          if (progress == 100) {
            pb.stopMonitoring();
            window.location = window.location.href.split('op=')[0] +'op=finished';
          }
        }

        // Failure: point out error message and provide link to the summary.
        var errorCallback = function (pb) {
          var div = document.createElement('p');
          div.className = 'error';
          $(div).html('An unrecoverable error has occured. You can find the error message below. It is advised to copy it to the clipboard for reference. Please continue to the <a href=\"cvs_to_versioncontrol_cvs_update.php?op=error\">update summary</a>');
          $(holder).prepend(div);
          $('#wait').hide();
        }

        var progress = new Drupal.progressBar('updateprogress', updateCallback, \"POST\", errorCallback);
        progress.setProgress(-1, 'Starting updates');
        $(holder).append(progress.element);
        progress.startMonitoring('cvs_to_versioncontrol_cvs_update.php?op=do_update', 0);
      });
    });
  }
  ";
}

/**
 * Perform updates for one second or until finished.
 *
 * @return
 *   An array indicating the status after doing updates. The first element is
 *   the overall percentage finished. The second element is a status message.
 */
function update_do_updates() {
  while (isset($_SESSION['update_remaining']) && ($update = reset($_SESSION['update_remaining']))) {
    $update_finished = update_data($update['module'], $update['version']);
    if ($update_finished == 1) {
      // Dequeue the completed update.
      unset($_SESSION['update_remaining'][key($_SESSION['update_remaining'])]);
      $update_finished = 0; // Make sure this step isn't counted double
    }
    if (timer_read('page') > 1000) {
      break;
    }
  }

  if ($_SESSION['update_total']) {
    $percentage = floor(($_SESSION['update_total'] - count($_SESSION['update_remaining']) + $update_finished) / $_SESSION['update_total'] * 100);
  }
  else {
    $percentage = 100;
  }

  // When no updates remain, clear the caches in case the data has been updated.
  if (!isset($update['module'])) {
    cache_clear_all('*', 'cache', TRUE);
    cache_clear_all('*', 'cache_page', TRUE);
    cache_clear_all('*', 'cache_menu', TRUE);
    cache_clear_all('*', 'cache_filter', TRUE);
    drupal_clear_css_cache();
  }

  return array($percentage, isset($update['module']) ? 'Updating '. $update['module'] .' module' : 'Updating complete');
}

/**
 * Perform updates for the JS version and return progress.
 */
function update_do_update_page() {
  global $conf;

  // HTTP Post required
  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    drupal_set_message('HTTP Post is required.', 'error');
    drupal_set_title('Error');
    return '';
  }

  // Error handling: if PHP dies, the output will fail to parse as JSON, and
  // the Javascript will tell the user to continue to the op=error page.
  list($percentage, $message) = update_do_updates();
  print drupal_to_js(array('status' => TRUE, 'percentage' => $percentage, 'message' => $message));
}

/**
 * Perform updates for the non-JS version and return the status page.
 */
function update_progress_page_nojs() {
  drupal_set_title('Updating');

  $new_op = 'do_update';
  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Error handling: if PHP dies, it will output whatever is in the output
    // buffer, followed by the error message.
    ob_start();
    $fallback = '<p class="error">An unrecoverable error has occurred. You can find the error message below. It is advised to copy it to the clipboard for reference. Please continue to the <a href="cvs_to_versioncontrol_cvs_update.php?op=error">update summary</a>.</p>';
    print theme('maintenance_page', $fallback, FALSE, TRUE);

    list($percentage, $message) = update_do_updates();
    if ($percentage == 100) {
      $new_op = 'finished';
    }

    // Updates successful; remove fallback
    ob_end_clean();
  }
  else {
    // Abort the update if the necessary modules aren't installed.
    if (!module_exists('versioncontrol') || !module_exists('versioncontrol_cvs') || !module_exists('cvs')) {
      print update_finished_page(FALSE);
      return NULL;
    }

    // This is the first page so return some output immediately.
    $percentage = 0;
    $message = 'Starting updates';
  }

  drupal_set_html_head('<meta http-equiv="Refresh" content="0; URL=cvs_to_versioncontrol_cvs_update.php?op='. $new_op .'">');
  $output = theme('progress_bar', $percentage, $message);
  $output .= '<p>Updating your site will take a few seconds.</p>';

  // Note: do not output drupal_set_message()s until the summary page.
  print theme('maintenance_page', $output, FALSE);
  return NULL;
}

function update_finished_page($success) {
  drupal_set_title('CVS module to Version Control/CVS module update.');
  // NOTE: we can't use l() here because the URL would point to 'update.php?q=admin'.
  $links[] = '<a href="'. base_path() .'">Main page</a>';
  $links[] = '<a href="'. base_path() .'?q=admin">Administration pages</a>';

  // Report end result
  if ($success) {
    $output = '<p>Updates were attempted. If you see no failures below, you should remove cvs_to_versioncontrol_cvs_update.php from your Drupal root directory. Otherwise, you may need to update your database manually. All errors have been <a href="index.php?q=admin/reports/dblog">logged</a>.</p>';
  }
  else {
    $output = '<p class="error">The update process was aborted prematurely. All other errors have been <a href="index.php?q=admin/reports/dblog">logged</a>. You may need to check the <code>watchdog</code> database table manually.</p>';
    $output .= '<p class="error">This has most likely occurred because the Version Control/CVS module or the old CVS module is not <a href="index.php?q=admin/build/modules">properly installed</a>.</p>';
  }

  $output .= theme('item_list', $links);

  if ($success) {
    $output .= "<h4>Some things to take care of now:</h4>\n";
    $output .= "<ul>\n";
    $output .= "<li>Visit the <a href=\"index.php?q=admin/project/versioncontrol-repositories\">Version Control repository administration page</a>, click 'Edit' for each of your repositories, and check the settings -- the script may not have migrated them all correctly, and there are also new settings that weren't supported in the CVS module.</li>\n";
    $output .= "<li>Visit the <a href=\"index.php?q=admin/project/versioncontrol-settings\">Version control settings page</a>, and make any necessary adjustments.</li>\n";
    $output .= "<li>If you're all done with the old CVS module, <a href=\"index.php?q=admin/build/modules\">disable/uninstall it</a>.</li>\n";
    $output .= "</ul>\n";
  }

  // Output a list of queries executed
  if (!empty($_SESSION['update_results'])) {
    $output .= '<div id="update-results">';
    $output .= '<h2>The following queries were executed</h2>';
    foreach ($_SESSION['update_results'] as $module => $updates) {
      $output .= '<h3>'. $module .' module</h3>';
      foreach ($updates as $number => $queries) {
        $output .= '<h4>Update #'. $number .'</h4>';
        $output .= '<ul>';
        foreach ($queries as $query) {
          if ($query['success']) {
            $output .= '<li class="success">'. $query['query'] .'</li>';
          }
          else {
            $output .= '<li class="failure"><strong>Failed:</strong> '. $query['query'] .'</li>';
          }
        }
        if (!count($queries)) {
          $output .= '<li class="none">No queries</li>';
        }
        $output .= '</ul>';
      }
    }
    $output .= '</div>';
    unset($_SESSION['update_results']);
  }

  return $output;
}

function update_info_page() {
  drupal_set_title('CVS module to Version Control/CVS module update.');
  $output = "<ol>\n";
  $output .= "<li>Use this script to <strong>upgrade an existing CVS module installation to the Version Control/CVS module</strong>. You don't need this script when installing Version Control/CVS from scratch.</li>";
  $output .= "<li>Before doing anything, backup your database. This process will change your database and its values.</li>\n";
  $output .= "<li>Make sure the Version Control/CVS module and the old CVS module are <a href=\"index.php?q=admin/build/modules\">properly installed</a>.</li>\n";
  $output .= "<li>Make sure this file is placed in the root of your Drupal installation (the same directory that index.php is in) and <a href=\"cvs_to_versioncontrol_cvs_update.php?op=selection\">run the database upgrade script</a>. <strong>Don't upgrade your database twice as it will cause problems!</strong></li>\n";
  $output .= "</ol>";
  $output .= "<h2>Caveats</h2>\n";
  $output .= "<ul>\n";
  $output .= "<li>Version Control API only allows one CVS user per Drupal user account for each repository, so only the first CVS user account will be migrated if there are duplicates</li>\n";
  $output .= "<li>The anonymous user (uid 0) will not be migrated.</li>\n";
  $output .= "<li>The script may not accurately translate the URL information for CVS web links.</li>\n";
  $output .= "<li>For purposes of generating CVSROOT/passwd, the 'run as user' will be 'drupal-cvs' -- to change the user, manually edit the value in <code>function cvs_to_versioncontrol_cvs_update_1()</code></li>\n";
  $output .= "<li>While Version Control API has support for recording deletion of files, the CVS module does not -- only file addition and modification data will be migrated.</li>\n";
  $output .= "<li>While Version Control API has support for recording metadata about branching and tagging operations, CVS module does not -- no data will be migrated in this sense.</li>\n";
  $output .= "</ul>\n";
  return $output;
}

function update_access_denied_page() {
  drupal_set_title('Access denied');
  return '<p>Access denied. You are not authorized to access this page. Please log in as the admin user (the first user you created). If you cannot log in, you will have to edit <code>cvs_to_versioncontrol_cvs_update.php</code> to bypass this access check. To do this:</p>
<ol>
 <li>With a text editor find the cvs_to_versioncontrol_cvs_update.php file on your system. It should be in the main Drupal directory that you installed all the files into.</li>
 <li>There is a line near top of cvs_to_versioncontrol_cvs_update.php that says <code>$access_check = TRUE;</code>. Change it to <code>$access_check = FALSE;</code>.</li>
 <li>As soon as the update is done, you should remove cvs_to_versioncontrol_cvs_update.php from your main installation directory.</li>
</ol>';
}

include_once './includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
drupal_maintenance_theme();

// Access check:
if (($access_check == FALSE) || ($user->uid == 1)) {

  $op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
  switch ($op) {
    case 'Update':
      $output = update_update_page();
      break;

    case 'finished':
      $output = update_finished_page(TRUE);
      break;

    case 'error':
      $output = update_finished_page(FALSE);
      break;

    case 'do_update':
      $output = update_do_update_page();
      break;

    case 'do_update_nojs':
      $output = update_progress_page_nojs();
      break;

    case 'selection':
      $output = update_selection_page();
      break;

    default:
      $output = update_info_page();
      break;
  }
}
else {
  $output = update_access_denied_page();
}

if (isset($output)) {
  print theme('maintenance_page', $output);
}
