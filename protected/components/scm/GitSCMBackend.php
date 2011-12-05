<?php

class GitSCMBackend extends SCMLocalBackend
{
    private $_git = null;
    
    public $name = 'git';

    protected function git()
    {
        $args = func_get_args();
        $a = array("-y", "-R", $this->repository, "--cwd", $this->repository);
        foreach ($args as $arg) {
            $a[] = $arg;
        }

        return $this->run_tool('git', 'read', $a);
    }

    public function getDiff($revision, $path)
    {
    } 

    protected function log($start = 0, $end = '', $limit = 100)
    {
        if('' == $end) {
            $end = 'HEAD';
        }
        $limit = 1;
        $commits = array();
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
        return $commits;
    }

    public function cloneRepository()
    {
        
    }
    
    public function pullRepository()
    {
        
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
        return $this->log($startRevision);
    }
}
