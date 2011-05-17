<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2011 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php

class HandleRepositoriesCommand extends CConsoleCommand {

// function courtesy of the mtrack project
private function run_tool($toolname, $mode, $args = null)
{
    global $FORKS;

    $tool = Yii::app()->config->get('hg_executable');
    if (!strlen($tool)) {
        $tool = $toolname;
    }
    if (PHP_OS == 'Windows' && strpos($tool, ' ') !== false) {
        $tool = '"' . $tool . '"';
    }
    $cmd = $tool;
    if (is_array($args)) {
        foreach ($args as $arg) {
            if (is_array($arg)) {
                foreach ($arg as $a) {
                    $cmd .= ' ' . escapeshellarg($a);
                }
            } else {
                $cmd .= ' ' . escapeshellarg($arg);
            }
        }
    }
    if (!isset($FORKS[$cmd])) {
        $FORKS[$cmd] = 0;
    }
    $FORKS[$cmd]++;
    if (false) {
        if (php_sapi_name() == 'cli') {
            echo $cmd, "\n";
        } else {
            //error_log($cmd);
            //echo htmlentities($cmd) . "<br>\n";
        }
    }

    switch ($mode) {
    case 'read': return popen($cmd, 'r');
    case 'write': return popen($cmd, 'w');
    case 'string': return stream_get_contents(popen($cmd, 'r'));
    case 'proc':
        $pipedef = array(
            0 => array('pipe', 'r'),
             1 => array('pipe', 'w'),
             2 => array('pipe', 'w'),
        );
        $proc = proc_open($cmd, $pipedef, $pipes);
        return array($proc, $pipes);
    }
}

    private $repopath;
    private $arr_users;

    private function hg()
    {
        $args = func_get_args();
        $a = array("-y", "-R", $this->repopath, "--cwd", $this->repopath);
        foreach ($args as $arg) {
            $a[] = $arg;
        }

        return $this->run_tool('hg', 'read', $a);
    }

    function grabChanges($start = 0, $end = 'tip', $limit = 100){

        $this->arr_users = array();

        $entries = array();

        $sep = uniqid();

        if(0 === $limit) {
            $fp = $this->hg('log',
                    '--rev', $start.':'.$end, '--template', $sep . '\n{node|short}\n{rev}\n{branches}\n{tags}\n{file_adds}\n{file_copies}\n{file_mods}\n{file_dels}\n{author|email}\n{date|hgdate}\n{desc}\n');
        } else {
            $fp = $this->hg('log',
                    '--rev', $start.':'.$end, '--template', $sep . '\n{node|short}\n{rev}\n{branches}\n{tags}\n{file_adds}\n{file_copies}\n{file_mods}\n{file_dels}\n{author|email}\n{date|hgdate}\n{desc}\n', '-l '.$limit);
        }

        fgets($fp); # discard leading $sep

        // corresponds to the file_adds, file_copies, file_modes, file_dels
        // in the template above
        static $file_status_order = array('A', 'C', 'M', 'D');

        $count = 0;
        while (true) {
            $entry = array();

            $entry['revision'] = trim(fgets($fp));

            $entry['short_rev'] = trim(fgets($fp));

            $branches = array();
            foreach (preg_split('/\s+/', trim(fgets($fp))) as $b) {
                if (strlen($b)) {
                    $branches[] = $b;
                }
            }
            if (!count($branches)) {
                $entry['branches'] = 'default';
            } else {
                    $entry['branches'] = implode(',', $branches);
            }

            $tags = array();
            foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
                if (strlen($t)) {
                    if('tip' !== $t)
                        $tags[] = $t;
                }
            }
            if (!count($tags)) {
                $entry['tags'] = '';
            } else {
                    $entry['tags'] = implode(',', $tags);
            }

            $files = array();
            foreach ($file_status_order as $status) {
                foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
                    if (strlen($t)) {
                        $file = array();
                        $file['name'] = $t;
                        $file['status'] = $status;
                        $files[] = $file;
                    }
                }
            }

            $entry['files'] = $files;

            $changeby = trim(fgets($fp));
            $entry['author'] = $changeby;
            $this->arr_users[] = $changeby;

            list($ts) = preg_split('/\s+/', fgets($fp));
            //FIXME: format date the way we want the date
            $entry['date'] = date("Y-m-d H:i:s", $ts);
            $changelog = array();
            while (($line = fgets($fp)) !== false) {
                $line = rtrim($line, "\r\n");
                if ($line == $sep) {
                    break;
                }
                $changelog[] = $line;
            }
            $thechangelog = join("\n", $changelog);

            $entry['message'] = $thechangelog;

            // add entry to array of entries
            $entries[] = $entry;

            if ($line === false) {
                break;
            }
        } //while true
        $fp = null;

        return $entries;
    }

    public function fillUsersTable() {
        $authors = array();
        foreach($this->arr_users as $key=>$val) {
            $authors[$val] = true;
        }
        $authors = array_keys($authors);
        
        foreach($authors as $author) {
            $criteria = new CDbCriteria();
            $criteria->compare('author', $author);
            if(!AuthorUser::model()->exists($criteria)) {
                $author_user = new AuthorUser;

                $author_user->author = $author;

                $criteria_user = new CDbCriteria();
                $criteria_user->compare('username', $author);
                $user = User::model()->find($criteria_user);
                if($user) {
                    $author_user->user_id = $user->id;
                }
                if($author_user->validate(array('author'))) {
                    $author_user->save(false);
                }
            }
        }
    }

    public function handleUser($author) {
        $criteria_user = new CDbCriteria();
        $criteria_user->compare('author', $author);
        $author_user = AuthorUser::model()->find($criteria_user);
        if($author_user) {
            return $author_user->user_id;
        }
        return null;
    }

    public function doInitialImport($unique_id, $last_revision, $repository_id) {
        $start_rev = 0;
        while($start_rev < $last_revision) {
            echo 'Importing  from revision "' . $start_rev . '" to "' . ($start_rev + 25) . "\"\n";
            $this->importChanges($start_rev, $unique_id, $repository_id);
            $start_rev = $start_rev + 25;
            sleep(1);
        }
    }

    public function importChanges($start_rev, $unique_id, $repository_id) {
        
        $entries = $this->grabChanges($start_rev, 'tip', 50);

        $this->fillUsersTable();

        foreach($entries as $entry) {

            $criteria = new CDbCriteria();
            $criteria->select = "unique_ident";
            $criteria->compare('unique_ident', $unique_id . $entry['revision']);

            if(!Changeset::model()->exists($criteria)) {

                $fp = $this->run_tool('hg', 'read', array('parents', '-r' . $entry['short_rev'], '-R', $this->repopath, '--cwd', $this->repopath, '--template', '{node|short}'));
                $parent = fgets($fp);
                $fp = null;

                $changeset = new Changeset;
                $changeset->unique_ident = $unique_id . $entry['revision'];
                $changeset->revision = $entry['revision'];
                $changeset->author = $entry['author'];
                $changeset->user_id = $this->handleUser($entry['author']);
                $changeset->scm_id = $repository_id;
                $changeset->commit_date = $entry['date'];
                $changeset->message = $entry['message'];
                $changeset->short_rev = $entry['short_rev'];
                $changeset->branch = $entry['branches'];
                $changeset->tags = $entry['tags'];
                $changeset->parent = $parent;

                if($changeset->validate()) {
                    $changeset->save(false);

                    $this->addToActionLog($changeset);
                    $this->importChangeset($changeset);

                    $change_edit = $change_del = $change_add = 0;

                    foreach($entry['files'] as $file) {
                        $change = new Change;
                        $change->changeset_id = $changeset->id;
                        switch ($file['status']) {
                            case 'M':
                                $change_edit++;
                                break;
                            case 'A':
                                $change_add++;
                                break;
                            case 'D':
                                $change_del++;
                                break;
                            default:
                                break;
                        }
                        $change->path = $file['name'];
                        $change->action = $file['status'];
                        $fp = $this->run_tool('hg', 'read', array('diff', '-r' . $changeset->short_rev, '-R', $this->repopath, '--cwd', $this->repopath, '--git', $change->path));
                        $diff = fgets($fp);
                        echo $diff;
                        $change->diff = $diff;
                        $fp = null;
                        if($change->validate()) {
                            $change->save(false);
                        } else {
                            return false;
                        }
                    }
                    $changeset->add = $change_add;
                    $changeset->del = $change_del;
                    $changeset->edit = $change_edit;
                    $changeset->update();

                } else { // changeset validate
                    return false;
                }
            } // if changeset doesn't exist
        } // foreach entries
        return true;
    }

    public function importChangeset($changeset) {

        $commit_date_in_seconds = Time::makeUnix($changeset->commit_date);
        // offset initial by 1 second to make date later than associated changeset.
        $commit_date_in_seconds++;

        $preg_string_refs = '/(?:refs|ref|references|see) #?(\d+)(\#ic\d*){0,1}(( #?and|#?or|,) #?(\d+)(\#ic\d*){0,1}){0,}/';
        $preg_string_closes = '/(?:fix(?:ed|es)|close(?:d|s)|fix|close) #?(\d+)(\#ic\d*){0,1}(( #?and|#?+or|,) #?(\d+)(\#ic\d*){0,1}){0,}/';

        $issues_to_be_referenced = array();
        $num_refs = preg_match_all($preg_string_refs, strtolower($changeset->message), $matches_refs, PREG_SET_ORDER);
        if($num_refs > 0) {
            for ($i = 0; $i < count($matches_refs); $i++) {

                if(count($matches_refs[$i]) == 6) {
                    $issues_to_be_referenced[] = $matches_refs[$i][1];
                    $issues_to_be_referenced[] = $matches_refs[$i][5];
                }
                else if(count($matches_refs[$i]) == 2) {
                        $issues_to_be_referenced[] = $matches_refs[$i][1];
                    }
            }
        }
        $criteria_ref = new CDbCriteria();
        $criteria_ref->compare('project_id', $changeset->scm->project_id);
        
        $issues_to_ref = Issue::model()->findAllByPk($issues_to_be_referenced, $criteria_ref);
        foreach($issues_to_ref as $issue_ref) {

            $comment = new Comment;
            $comment->content = 'Referenced in rev:'.$changeset->revision;
            $comment->issue_id = $issue_ref->id;
            $comment->create_user_id = $changeset->user_id;
            $comment->update_user_id = $changeset->user_id;
            if($comment->validate(array('content', 'issue_id', 'create_user_id', 'update_user_id'))) {
                $comment->created = date("Y-m-d\TH:i:s\Z", ($commit_date_in_seconds));
                $comment->modified = date("Y-m-d\TH:i:s\Z", ($commit_date_in_seconds));
                $comment->save(false);

                $issue_ref->detachBehavior('BugitorTimestampBehavior');

                if($issue_ref->validate(array('updated_by', 'closed'))) {
                    if( Time::makeUnix($issue_ref->modified) < Time::makeUnix($changeset->commit_date)) {
                        $issue_ref->modified = date("Y-m-d\TH:i:s\Z", ($commit_date_in_seconds));
                        $issue_ref->updated_by = $changeset->user_id;
                    }

                    $issue_ref->save(false);

                    $issue_ref = Issue::model()->findByPk($issue_ref->id);
                    $issue_ref->addToActionLog($issue_ref->id, $changeset->user_id, 'note', '/projects/'.$issue_ref->project->identifier.'/issue/view/'.$issue_ref->id.'#note-'.$issue_ref->commentCount, $comment);
                    $issue_ref->sendNotifications($issue_ref->id, $comment, $issue_ref->updated_by);

                    $changeset_issue = new ChangesetIssue;
                    $changeset_issue->changeset_id = $changeset->id;
                    $changeset_issue->issue_id = $issue_ref->id;
                    if($changeset_issue->validate())
                        $changeset_issue->save(false);
                } // issue_ref validate
            } // comment validate
            $commit_date_in_seconds++;
        } // foreach issues to reference

        $issues_to_be_closed = array();
        $num_closes = preg_match_all($preg_string_closes, strtolower($changeset->message), $matches_closes, PREG_SET_ORDER);
        if($num_closes > 0) {
            for ($i = 0; $i < count($matches_closes); $i++) {

                if(count($matches_closes[$i]) == 6) {
                    $issues_to_be_closed[] = $matches_closes[$i][1];
                    $issues_to_be_closed[] = $matches_closes[$i][5];
                }
                else if(count($matches_closes[$i]) == 2) {
                        $issues_to_be_closed[] = $matches_closes[$i][1];
                    }
            }
        }

        $criteria_close = new CDbCriteria();
        $criteria_close->compare('project_id', $changeset->scm->project_id);
        
        $issues_to_close = Issue::model()->findAllByPk($issues_to_be_closed, $criteria_close);
        foreach($issues_to_close as $issue_close) {

            $comment = new Comment;
            if($issue_close->closed === 0) {
                $comment->content = 'Applied in rev:'.$changeset->revision;
            } else {
                $comment->content = 'Referenced in rev:'.$changeset->revision;
            }
            $comment->issue_id = $issue_close->id;
            $comment->create_user_id = $changeset->user_id;
            $comment->update_user_id = $changeset->user_id;

            if($comment->validate(array('content', 'issue_id', 'create_user_id', 'update_user_id'))) {
                $comment->created = date("Y-m-d\TH:i:s\Z", ($commit_date_in_seconds));
                $comment->modified = date("Y-m-d\TH:i:s\Z", ($commit_date_in_seconds));
                $comment->save(false);

                if($issue_close->closed !== 1) {
                    $issue_close->status = 'swIssue/resolved';
                }

                $issue_close->detachBehavior('BugitorTimestampBehavior');

                if($issue_close->validate(array('updated_by', 'closed'))) {
                    if( Time::makeUnix($issue_close->modified) < Time::makeUnix($changeset->commit_date)) {
                        $issue_close->modified = date("Y-m-d\TH:i:s\Z", ($commit_date_in_seconds));
                        $issue_close->updated_by = $changeset->user_id;
                    }

                    $issue_close->save(false);

                    if($issue_close->closed === 0) {
                        $issue_close->addToActionLog($issue_close->id, $changeset->user_id, 'resolved', '/projects/'.$issue_close->project->identifier.'/issue/view/'.$issue_close->id.'#note-'.$issue_close->commentCount, $comment);
                    } else {
                        $issue_close->addToActionLog($issue_close->id, $changeset->user_id, 'note', '/projects/'.$issue_close->project->identifier.'/issue/view/'.$issue_close->id.'#note-'.$issue_close->commentCount, $comment);
                    }

                    $issue_close->sendNotifications($issue_close->id, $comment, $issue_close->updated_by);

                    $changeset_issue = new ChangesetIssue;
                    $changeset_issue->changeset_id = $changeset->id;
                    $changeset_issue->issue_id = $issue_close->id;
                    if($changeset_issue->validate())
                        $changeset_issue->save(false);
                } // issue_close validate
            } // comment validate
            $commit_date_in_seconds++;
        } // foreach issue to close
        
    }

    public function addToActionLog($changeset) {
        $actionLog = new ActionLog;
        $actionLog->author_id = $changeset->user_id;
        $actionLog->project_id = $changeset->scm->project_id;
        $actionLog->description = $changeset->message;
        $actionLog->subject = 'Revision ' . $changeset->revision . ' (' . $changeset->scm->name . ')';
        $actionLog->type = 'changeset';
        $actionLog->theDate = $changeset->commit_date;
        $actionLog->url = '/projects/'. $changeset->scm->project->identifier . '/changeset/view/' . $changeset->id;
        if($actionLog->validate())
            $actionLog->save(false);
    }
    public function run($args) {
        // Check if we have a lock already. If not set one which
        // expires automatically after 10 minutes.
        if (Yii::app()->mutex->lock('HandleRepositoriesCommand', 600))
        {
            try {
//                if(Yii::app()->config->get('python_path') !== '')
//                    putenv(Yii::app()->config->get('python_path'));

                $projects = Project::model()->with(array('repositories'))->findAll();
                foreach($projects as $project) {
                    foreach($project->repositories as $repository) {

                        $this->repopath = $repository->local_path;

                        if($repository->status === '0') {
                            // clone repository
                            $this->run_tool('hg', 'read', array('clone', $repository->url, $repository->local_path));
                            $repository->status = 1;
                            $repository->save();
                            // fill author user table
                            $this->grabChanges(0, 'tip', 0);
                            $this->fillUsersTable();

                            continue;
                        }

                        if($repository->status === '1') {
                            // user need to check author_user table
                            continue;
                        }

                        // repository has been cloned and author_user table checked

                        $fp = $this->run_tool('hg', 'read', array('log', '-r0', '-R', $repository->local_path, '--cwd', $repository->local_path, '--template', '{node}'));
                        $unique_id = fgets($fp);
                        $fp = null;

                        $fp = $this->run_tool('hg', 'read', array('log', '-rtip', '-R', $repository->local_path, '--cwd', $repository->local_path, '--template', '{rev}'));
                        $last_revision = fgets($fp);
                        $fp = null;

                        if($repository->status === '2') {
                            // perform initial import
                            $this->doInitialImport($unique_id, $last_revision, $repository->id);
                            $repository->status = 3;
                            $repository->save();
                            continue;
                        }

                        // repository status is OK (3)
                        // normal maintenance work ...

                        $this->hg('pull');
                        $this->hg('update');

                        $criteriac = new CDbCriteria();
                        $criteriac->compare('scm_id', $repository->id);
                        $criteriac->select ='max(short_rev) as maxRev';
                        $last_revision_stored = Changeset::model()->find($criteriac);
                        $start_rev = 0;
                        if(null !== $last_revision_stored->maxRev) {
                            //echo 'Last revision in db: ' . $last_revision_stored->maxRev . "\n";
                            $start_rev = $last_revision_stored->maxRev + 1;
                        }

                        if($start_rev <= $last_revision)
                            $this->importChanges($start_rev, $unique_id, $repository->id);

                    }
                }

                // and after that release the lock...
                Yii::app()->mutex->unlock();

            } catch (Exception $e) {
                echo 'Exception caught: ',  $e->getMessage(), "\n";
                Yii::app()->mutex->unlock();
            }
        } else {
            echo 'Nothing to do. Exiting...';
        }
    }

}

