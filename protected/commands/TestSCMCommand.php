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

class TestSCMCommand extends CConsoleCommand {

    public function run($args) {
        echo "\n";
        
        /*$hg = Yii::app()->scm->getBackend();
        
        if(php_uname('s') == "Windows NT") {
            //$hg->setExecutable("C:/program files/TortoiseHg/hg.exe");
            $hg->local_path = "C:/wamp/topics";
        } else {
            //$hg->setExecutable("/usr/bin/hg");
            $hg->local_path = "/home/stealth977/tracker.ogitor.org/repositories/bugitor";
            if(Yii::app()->config->get('python_path'))
              putenv(Yii::app()->config->get('python_path'));
        }
        
        echo $hg->name;
        echo "\n---------------------------------------------------\n";

        $entries = $hg->getChanges($hg->getLastRevision(), null, 1);
        foreach($entries as $entry) {
            foreach($entry['files'] as $file) {
                echo "\n---------------------------------------------------\n";
                echo "Diff for" . $file['name'] . ":\n\n";
                echo $hg->getDiff($file['name'], $entry['revision']);
                echo "\n";
                echo $hg->getFileContents($file['name'], $entry['revision']);
            }
        }
        echo "\n---------------------------------------------------\n";
        print "Repository ID: " . $hg->getRepositoryId() . "\n";
        print "Last Revision: " . $hg->getLastRevision() . "\n";
        echo $hg->getLastRevisionOf("themes/sassy") . "\n";*/

       
        $git = Yii::app()->scm->getBackend('git');
        $git->local_path = "C:/wamp/www/foundation";
        echo $git->name;
        echo "\n---------------------------------------------------\n";

        $git_entries = $git->getChanges(1, null, 10);
        print_r($git_entries);
        $diff = $git->getDiff('styles-compiled/1024.css', '1a40a7a9e3b2a4cf124ccd2e7156f480e8cc8f0d');
        echo "\n";
        print_r($diff);
        /*echo "Contents of \"README.md\" at revision {$git->getLastRevisionOf("README.md")}:\n\n";
        echo $git->getFileContents("README.md", $git->getLastRevisionOf("README.md")) . "\n";
        echo "Repository Id: " . $git->getRepositoryId() . "\n";
        echo "Last Revision: " . $git->getLastRevision() . "\n";
        echo "Last Revision of \"README.md\": " . $git->getLastRevisionOf("README.md") . "\n";
        echo "Parents of \"{$git->getLastRevisionOf("README.md")}\":\n";*/
        echo $git->getParents("762f80631a711cd21c5dffcdf4316594a099bf72") . "\n";
        echo "\n---------------------------------------------------\n";

        $git->local_path = "C:/wamp/www/shitting";
        $git->url = "git://github.com/crodas/php-git.git";
        //$git->cloneRepository();
        $git->pullRepository();



/*
        $github = Yii::app()->scm->getBackend('github');
        echo $github->name;
        echo "\n---------------------------------------------------\n";
        $github_entries = $github->getChanges(1);
        print_r($github_entries);
        echo "\n---------------------------------------------------\n";

        $bitbucket = Yii::app()->scm->getBackend('bitbucket');
        echo $bitbucket->name;
        echo "\n---------------------------------------------------\n";
        $bitbucket->repository = "jacmoes";
        require(dirname(__FILE__) . '/../../credentials.php');
        $bitbucket->setCredentials($user, $pass);
        $bitbucket_entries = $bitbucket->getChanges(1);
        print_r($bitbucket_entries);
        echo "\n---------------------------------------------------\n";*/

        /*$svn = Yii::app()->scm->getBackend('svn');
        echo $svn->name;
        echo "\n---------------------------------------------------\n";
        $svn->url = "file:///C:\wamp\shit";
        $svn->local_path = "C:/wamp/shittest";
        echo "Local path: " . $svn->getLocalPath() . "\n";
        $svn->pullRepository();
        echo "Repository Id: ". $svn->getRepositoryId() . "\n";
        echo "Last Revision: ". $svn->getLastRevision() . "\n";
        $svn_entries = $svn->getChanges($svn->getLastRevision(), null, 1);
        //print_r($svn_entries);
        foreach($svn_entries as $svn_entry) {
            foreach($svn_entry['files'] as $file) {
                echo "\n---------------------------------------------------\n";
                echo "Diff for" . $file['name'] . ":\n\n";
                echo $svn->getDiff($file['name'], $svn_entry['revision']);
                echo "\n";
                echo $svn->getFileContents($file['name'], $svn_entry['revision']);
            }
        }
        echo $svn->getLastRevisionOf("/trunk/fawefaw.txt") . "\n";
        //$svn->cloneRepository();
        echo "\n---------------------------------------------------\n";*/

    }

}

