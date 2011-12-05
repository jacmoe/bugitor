<?php

//Yii::import('application.vendors.pyrus.vendor.php.*');
//require_once('VersionControl/Svn.php');

class SVNSCMBackend extends SCMLocalBackend
{
    public $name = 'svn';

    protected function svn()
    {
        $args = func_get_args();
        return $this->run_tool('svn', 'read', $args);
    }
    
    public function getDiff($revision, $path = '')
    {
        $revision_comp = $revision - 1;
        $fp = $this->svn('diff', '-r', "{$revision}:{$revision_comp}", $this->repository);
        //$fp = $this->svn('diff', '-r', "{$revision}", $this->repository);
        $diff = stream_get_contents($fp);
        return $diff;
    }

    protected function info()
    {
        $fp = $this->svn('info', '--xml', $this->repository);
        $info = stream_get_contents($fp);

        //print_r($log);
        
        $xml_info = new SimpleXMLElement($info);
        
        print_r($xml_info);
        
    }
    
    protected function log($start = 0, $end = '', $limit = 100)
    {
        if('' == $end) {
            $end = 'HEAD';
        }
        $limit = 1;
        $fp = $this->svn('log', '--verbose', '--xml', $this->repository);
        $log = stream_get_contents($fp);

        //print_r($log);
        
        $xml_entries = new SimpleXMLElement($log);
        
        //print_r($xml_entries);
        
        $entries = array();
        
        foreach($xml_entries as $xml_entry){
            echo $xml_entry['revision'] . "\n";
            echo $xml_entry->date . "\n";
            echo $xml_entry->msg . "\n";
            foreach($xml_entry->paths as $paths) {
                foreach($paths as $path) {
                    echo $path['kind'] . "\n";
                    echo $path['action'] . "\n";
                    echo $path->path . "\n";
                }
            }
        }
        
        //print_r($this->info());
        
        print_r($this->getDiff(31));
        
        return $entries;
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

    public function cloneRepository($local_path)
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
