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

    public function run($args) {
        // Check if we have a lock already. If not set one which
        // expires automatically after 10 minutes.
        if (Yii::app()->mutex->lock(__CLASS__, 600))
        {
            //echo ini_get('max_execution_time') . "\n";
            $repositories =  Repository::model()->findAll();
            foreach($repositories as $repository) {
                if($repository->status === '0') {
                    if(Yii::app()->config->get('python_path') !== '')
                        putenv(Yii::app()->config->get('python_path'));
                    $this->run_tool('hg', 'read', array('clone', $repository->url, $repository->local_path));
                    $repository->status = 1;
                    $repository->save();
                }
                $this->repopath = $repository->local_path;
                $this->hg('pull');
                $this->hg('update');
                //echo $this->run_tool('hg' , 'read', array('incoming', $repository->url, $repository->local_path));
            }

            // and after that release the lock...
            Yii::app()->mutex->unlock();
        } else {
            echo 'Nothing to. Exiting...';
        }
    }

}
