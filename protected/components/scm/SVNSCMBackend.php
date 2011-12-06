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
    
    public function __construct()
    {
        $executable = Yii::app()->config->get('svn_executable');
        $this->executable = "\"" . $executable . "\"";
    }

    public function getDiff($path, $from, $to = null)
    {
        if(null === $to) {
            $to = $from - 1;
        }
        $fp = $this->svn('diff', '-r', "{$from}:{$to}", $this->local_path);
        //$fp = $this->svn('diff', '-r', "{$revision}", $this->local_path);
        $diff = stream_get_contents($fp);
        return $diff;
    }

    protected function info()
    {
        $fp = $this->svn('info', '--xml', $this->local_path);
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
        $fp = $this->svn('log', '--verbose', '--xml', $this->local_path);
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
        
        //print_r($this->getDiff(31));
        
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

    public function cloneRepository()
    {
        stream_get_contents($this->svn('checkout',
          $this->url,
          $this->local_path));
    }
    
    public function pullRepository()
    {
        $this->run_tool('svn', 'read', array('update', $this->local_path));        
    }
    
    public function getParents($revision)
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
    
    public function getChanges($start = 0, $end = '', $limit = 100)
    {
        return $this->log($start, $end, $limit);
    }
    
    public function getUsers()
    {
        return $this->arr_users;
    }
}
