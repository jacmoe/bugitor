<?php

//Yii::import('application.vendors.pyrus.vendor.php.*');
//require_once('VersionControl/Svn.php');

class SVNSCMBackend extends SCMLocalBackend
{
    public $name = 'svn';

    protected function log($start = 0, $end = '', $limit = 100)
    {
        if('' == $end) {
            $end = 'HEAD';
        }
        $limit = 1;
        
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
