<?php

class GitSCMBackend extends SCMLocalBackend
{
    private $_git = null;
    
    public $name = 'git';

  protected function git()
  {
    $args = func_get_args();
    $a = array(
      "--git-dir={$this->local_path}/.git",
      "--work-tree={$this->local_path}",
    );

    /*if ($this->local_path != $this->url) {
      $a[] = "--work-tree=$this->url";
    }*/
    foreach ($args as $arg) {
      $a[] = $arg;
    }

    return $this->run_tool('git', 'read', $a);
  }

    public function __construct()
    {
        $executable = Yii::app()->config->get('git_executable');
        $this->executable = "\"" . $executable . "\"";
    }
    
    public function getDiff($path, $from, $to = null)
    {
    } 

    protected function log($start = 0, $end = '', $limit = 100)
    {
        $args = array();
        $args[] = 'master';
        if ($limit !== null) {
          if (is_int($limit)) {
            $args[] = "--max-count=$limit";
          } else {
            $args[] = "--since=$limit";
          }
        }
        $args[] = "--no-color";
        $args[] = "--name-status";
        $args[] = "--date=rfc";

        $commits = array();
        $fp = $this->git('log', $args);
        $commits = stream_get_contents($fp);
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
    
    public function getChanges($start = 0, $end = '', $limit = 100)
    {
        return $this->log($start = 0, $end = '', $limit);
    }

    public function getFileContents($path, $revision)
    {
        
    }

    public function getLastRevisionOf($path)
    {
        
    }
    
    public function getParents($revision)
    {
        
    }
    
    public function getUsers()
    {
        
    }
}
