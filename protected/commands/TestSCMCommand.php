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
        
        $hg = Yii::app()->scm->getBackend();
        
        if(php_uname('s') == "Windows NT") {
            $hg->setExecutable("C:/PROGRA~1/TortoiseHg/hg.exe");
            $hg->repository = "C:/wamp/newogitor";
        } else {
            $hg->setExecutable("/usr/bin/hg");
            $hg->repository = "/home/stealth977/tracker.ogitor.org/repositories/bugitor";
            if(Yii::app()->config->get('python_path'))
              putenv(Yii::app()->config->get('python_path'));
        }
        
        echo $hg->name;
        echo "\n---------------------------------------------------\n";
        
        $entries = $hg->getChanges(1);
        print_r($entries);
        
        echo "\n---------------------------------------------------\n";
        $git = Yii::app()->scm->getBackend('git');
        
        $git->setExecutable("C:/PROGRA~1/Git/bin/git.exe");
        $git->repository = "C:/wamp/www/foundation";
        echo $git->name;
        echo "\n---------------------------------------------------\n";

        $git_entries = $git->getChanges(1);
        print_r($git_entries);
        echo "\n---------------------------------------------------\n";

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
        echo "\n---------------------------------------------------\n";
    }

}

