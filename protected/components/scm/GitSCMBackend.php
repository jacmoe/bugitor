<?php
Yii::import('application.vendors.pyrus.vendor.php.*');
require_once('VersionControl/Git.php');

class GitSCMBackend extends SCMLocalBackend
{
    private $_git = null;
    
    public $name = 'git';

    private function getGit()
    {
        if(null !== $this->_git){
            return $this->_git;
        } else {
            $this->_git = new VersionControl_Git($this->repository);
            $this->_git->setGitCommandPath($this->executable);
            return $this->_git;
        }
    }

    public function log($start = 0, $end = '', $limit = 100)
    {
        if('' == $end) {
            $end = 'HEAD';
        }
        $commits = $this->getGit()->getCommits('master', $limit, $start);
        return $commits;
    }
}
