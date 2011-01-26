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

    function parseCommitMessage($msg) {
        // Parse the commit message and look for commands;
        // returns each recognized command and its args in an array

        $close = array('resolves', 'resolved', 'close', 'closed',
            'closes', 'fix', 'fixed', 'fixes',
            'Resolves', 'Resolved', 'Close', 'Closed',
            'Closes', 'Fix', 'Fixed', 'Fixes');
        $refs = array('addresses', 'references', 'referenced',
            'refs', 'ref', 'see', 're',
            'Addresses', 'References', 'Referenced',
            'Refs', 'Ref', 'See', 'Re');

        $cmds = join('|', $close) . '|' . join('|', $refs);
        $timepat = '(?:\s*\((?:spent|sp)\s*(-?[0-9]*(?:\.[0-9]+)?)\s*(?:hours?|hrs)?\s*\))?';
        $tktref = "(?:#|(?:(?:#|issue|bug):?\s*))([a-z]*[0-9]+)$timepat";

        $pat = "(?P<action>(?:$cmds))\s*(?P<ticket>$tktref(?:(?:[, &]*|\s+and\s+)$tktref)*)";

        $M = array();
        $actions = array();

        if (preg_match_all("/$pat/smi", $msg, $M, PREG_SET_ORDER)) {
            foreach ($M as $match) {
                if (in_array($match['action'], $close)) {
                    $action = 'close';
                } else {
                    $action = 'ref';
                }
                $tickets = array();
                $T = array();
                if (preg_match_all("/$tktref/smi", $match['ticket'],
                $T, PREG_SET_ORDER)) {
                    foreach ($T as $tmatch) {
                        if (isset($tmatch[2])) {
                            // [ action, ticket, spent ]
                            $actions[] = array($action, $tmatch[1], $tmatch[2]);
                        } else {
                            // [ action, ticket ]
                            $actions[] = array($action, $tmatch[1]);
                        }
                    }
                }
            }
        }
        return $actions;
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

    public function run($args) {
        // Check if we have a lock already. If not set one which
        // expires automatically after 10 minutes.
        if (Yii::app()->mutex->lock('HandleRepositoriesCommand', 600))
        {
            try {
                if(Yii::app()->config->get('python_path') !== '')
                    putenv(Yii::app()->config->get('python_path'));

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

                            // just return after a clone
                            Yii::app()->mutex->unlock();
                            return;
                        }

                        if($repository->status === '1') {
                            echo 'User need to perform author user matching';
                            $this->grabChanges(0, 'tip', 0);
                            $this->fillUsersTable();
                            Yii::app()->mutex->unlock();
                            return;
                        }

                        $fp = $this->run_tool('hg', 'read', array('log', '-r0', '-R', $repository->local_path, '--cwd', $repository->local_path, '--template', '{node}'));
                        $unique_id = fgets($fp);
                        $fp = null;

                        $fp = $this->run_tool('hg', 'read', array('log', '-r"tip"', '-R', $repository->local_path, '--cwd', $repository->local_path, '--template', '{rev}'));
                        $last_revision = fgets($fp);
                        $fp = null;

                        if($repository->status === '2') {
                            echo 'performing initial import';
                            $this->doInitialImport($unique_id, $last_revision, $repository->id);
                            $repository->status = 3;
                            $repository->save();
                            Yii::app()->mutex->unlock();
                            return;
                        }

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
