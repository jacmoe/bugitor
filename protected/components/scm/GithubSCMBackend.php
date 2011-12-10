<?php
Yii::import('application.vendors.php-github-api.lib.Github.*');
//require_once()'Autoloader.php');
require_once('Client.php');
require_once('HttpClientInterface.php');
require_once('HttpClient.php');
require_once('HttpClient/Curl.php');
require_once('ApiInterface.php');
require_once('Api.php');
require_once('Api/Commit.php');


class GithubSCMBackend extends SCMBackend
{
    private $_github = null;

    public $name = 'github';

    /*public function __construct()
    {
        //Github_Autoloader::register();
    }*/

    private function getGithub()
    {
        if(null !== $this->_github){
            return $this->_github;
        } else {
            $this->_github = new Github_Client();
            return $this->_github;
        }
    }

    public function getDiff($path, $from, $to = null)
    {
    } 

    protected function log($start = 0, $end = '', $limit = 100)
    {
        $commits = $this->getGithub()->getCommitApi()->getBranchCommits('jacmoe', 'highlighter', 'master');
        /*Array
        (
            [parents] => Array
                (
                    [0] => Array
                        (
                            [id] => f45bad6555162cafa55f3b47d10e3ed5efa4dee5
                        )
        
                    [1] => Array
                        (
                            [id] => 3441f2ce5061843906b4bc1ea701068a0cd04492
                        )
        
                )
        
            [author] => Array
                (
                    [name] => Jacob Moen
                    [login] => jacmoe
                    [email] => mail@jacmoe.dk
                )
        
            [url] => /jacmoe/highlighter/commit/db7d468ee1920a8e0bbe27093797950743faa6cc
            [id] => db7d468ee1920a8e0bbe27093797950743faa6cc
            [committed_date] => 2011-11-07T23:45:53-08:00
            [authored_date] => 2011-11-07T23:45:53-08:00
            [message] => Merge pull request #3 from rchavik/2.0
        
        upgrade for croogo 1.4 branch (cakephp 2.0)
            [tree] => a7d0fb165be51340683a2c1bce7977cd587d130e
            [committer] => Array
                (
                    [name] => Jacob Moen
                    [login] => jacmoe
                    [email] => mail@jacmoe.dk
                )
        
        )*/
        /*
            revision
            short_rev
            branches
            branch_count
            tags
            tag_count
            parents
            parent_count
            files
                name
                status
            message
        */
        return $commits[0];
    }

    public function getRepositoryId()
    {
        return $this->repositoryId;
    }
    
    public function getLastRevision()
    {
        return $this->lastRevision;
    }
    
    public function getLastRevisionOf($path)
    {
    }
    
    public function getFileContents($path, $revision)
    {
    }
    
    public function getChanges($start = 0, $end = '', $limit = 100)
    {
        return $this->log($start, $end, $limit);
    }
    
    public function getParents($revision)
    {
    }

    public function getUsers()
    {
        return $this->arr_users;
    }
}
