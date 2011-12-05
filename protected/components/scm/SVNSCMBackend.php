<?php

Yii::import('application.vendors.pyrus.vendor.php.*');
require_once('VersionControl/Svn.php');

class SVNSCMBackend extends SCMLocalBackend
{
    public $name = 'svn';

    protected function log($start = 0, $end = '', $limit = 100)
    {
        if('' == $end) {
            $end = 'HEAD';
        }
        $limit = 1;
        
        //$svnstack = &PEAR_ErrorStack::singleton('VersionControl_SVN');

        $options = array('fetchmode' => VERSIONCONTROL_SVN_FETCHMODE_ASSOC);
        $svn = VersionControl_SVN::factory('log', $options);

        // Define any switches and aguments we may need
        $switches = array('verbose' => 'true');
        $args = array($this->repository);

        // Run command
        if ($commits = $svn->run($args, $switches))
        {
            return $commits[0];
        } else {
            return null;
        }
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
