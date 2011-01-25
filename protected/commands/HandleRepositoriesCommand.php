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

//if (php_sapi_name() != 'cli') {
//  set_exception_handler('mtrack_last_chance_saloon');
//  error_reporting(E_NOTICE|E_ERROR|E_WARNING);
//  ini_set('display_errors', false);
//  set_time_limit(300);
//}
    private $repopath;

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

        $entries = array();

        $sep = uniqid();

        $fp = $this->hg('log',
                '--rev', $start.':'.$end, '--template', $sep . '\n{node|short}\n{rev}\n{branches}\n{tags}\n{file_adds}\n{file_copies}\n{file_mods}\n{file_dels}\n{author|email}\n{date|hgdate}\n{desc}\n', '-l '.$limit);

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

    public function run($args) {
        // Check if we have a lock already. If not set one which
        // expires automatically after 10 minutes.
        if (Yii::app()->mutex->lock('HandleRepositoriesCommand', 600))
        {
            try {
                if(Yii::app()->config->get('python_path') !== '')
                    putenv(Yii::app()->config->get('python_path'));

                //echo ini_get('max_execution_time') . "\n";
                $projects = Project::model()->with(array('repositories'))->findAll();
                foreach($projects as $project) {
                    foreach($project->repositories as $repository) {
                        if($repository->status === '0') {
                            $this->run_tool('hg', 'read', array('clone', $repository->url, $repository->local_path));
                            $repository->status = 1;
                            $repository->save();
                            // just return after a clone
                            Yii::app()->mutex->unlock();
                            return;
                        }
                        $this->repopath = $repository->local_path;
                        $this->hg('pull');
                        $this->hg('update');

                        $fp = $this->run_tool('hg', 'read', array('log', '-r0', '-R', $repository->local_path, '--cwd', $repository->local_path, '--template', '{node}'));
                        $unique_id = fgets($fp);
                        $fp = null;

                        $fp = $this->run_tool('hg', 'read', array('log', '-r"tip"', '-R', $repository->local_path, '--cwd', $repository->local_path, '--template', '{rev}'));
                        $last_revision = fgets($fp);
                        $fp = null;

                        $criteriac = new CDbCriteria();
                        $criteriac->compare('scm_id', $repository->id);
                        $criteriac->select='max(short_rev) as maxRev';
                        $last_revision_stored = Changeset::model()->find($criteriac);
                        //echo 'Last revision in db: ' . $last_revision_stored->maxRev . "\n";
                        //return;

                        $entries = $this->grabChanges(550, 'tip');

                        foreach($entries as $entry) {

                            $criteria = new CDbCriteria();
                            $criteria->select = "unique_ident";
                            $criteria->compare('unique_ident', $unique_id . $entry['revision']);

                            if(!Changeset::model()->exists($criteria)) {

                                $fp = $this->run_tool('hg', 'read', array('parents', '-r' . $entry['short_rev'], '-R', $repository->local_path, '--cwd', $repository->local_path, '--template', '{node|short}'));
                                $parent = fgets($fp);
                                $fp = null;

                                $changeset = new Changeset;
                                $changeset->unique_ident = $unique_id . $entry['revision'];
                                $changeset->revision = $entry['revision'];
                                $changeset->user_id = 1; //FIXME! $entry['author']
                                $changeset->scm_id = $repository->id;
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
                                        if($change->validate())
                                            $change->save(false);
                                    }
                                    $changeset->add = $change_add;
                                    $changeset->del = $change_del;
                                    $changeset->edit = $change_edit;
                                    $changeset->update();

                                }
                                
                            }
                        }

                    }
                }

                // and after that release the lock...
                Yii::app()->mutex->unlock();

            } catch (Exception $e) {
                echo 'Exception caught: ',  $e->getMessage(), "\n";
                Yii::app()->mutex->unlock();
            }
        } else {
            echo 'Nothing to. Exiting...';
        }
    }

}

