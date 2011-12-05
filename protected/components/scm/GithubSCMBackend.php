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

    public function getDiff($revision, $path)
    {
    } 

    protected function log($start = 0, $end = '', $limit = 100)
    {
        $commits = $this->getGithub()->getCommitApi()->getBranchCommits('jacmoe', 'highlighter', 'master');
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
    
    public function getChanges($startRevision)
    {
        return $this->log();
    }
}