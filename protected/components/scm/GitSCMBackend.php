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
        $diff = '';
        if ($to !== null) {
          return stream_get_contents($this->git('diff', "$from..$to", '--', $path));
        }
        return stream_get_contents($this->git('diff', "$from^..$from", '--', $path));
        
    }

    protected function log($start = 0, $end = '', $limit = 100)
    {
        $git_executable = "\"" . Yii::app()->config->get('git_executable') . "\"";
        //          revison short_rev tree username email date subject parents
        $sep = uniqid();
        $parentsep = uniqid();
        $endsep = uniqid();
        $format =   "$sep%n%H%n%h%n%T%n%an%n%ae%n%ai%n%p%n$parentsep%n%s%n$endsep";
        $cmd = "{$git_executable} --git-dir={$this->local_path}/.git --work-tree={$this->local_path} log --name-status --pretty=format:{$format}";
        if ($limit !== null) {
          if (is_int($limit)) {
            $cmd .= " --max-count=$limit";
          } else {
            $cmd .= " --since=$limit";
          }
        }
        $fp = popen($cmd, 'r');

        fgets($fp); # discard leading $sep
        // corresponds to the file_adds, file_copies, file_modes, file_dels
        // in the template above
        static $file_status_order = array('M', 'C', 'A', 'D');

        while (true) {
            $entry = array();

            $entry['revision'] = trim(fgets($fp));

            $entry['short_rev'] = trim(fgets($fp));

            $entry['tree'] = trim(fgets($fp));

            $changeby = trim(fgets($fp));
            $entry['author'] = $changeby;
            $this->arr_users[] = $changeby;
            
            $entry['email'] = trim(fgets($fp));
            
            $entry['date'] = trim(fgets($fp));
            
            $parent = array();
            while (($line = fgets($fp)) !== false) {
                $line = rtrim($line, "\r\n");
                if ($line == $parentsep) {
                    break;
                }
                $parent[] = $line;
            }
            $theparent = join("\n", $parent);

            $entry['parent'] = $theparent;

            $changelog = array();
            while (($line = fgets($fp)) !== false) {
                $line = rtrim($line, "\r\n");
                if ($line == $endsep) {
                    break;
                }
                $changelog[] = $line;
            }
            $thechangelog = join("\n", $changelog);

            $entry['message'] = $thechangelog;

            $files = array();
            while (($line = fgets($fp)) !== false) {
                $line = trim($line, "\r\n");
                if ($line == $sep) {
                    break;
                }
                if (strlen($line)) {
                    $file = array();
                    $file['status'] = $line[0];
                    $file['name'] = trim(substr($line, 1));
                    $files[] = $file;
                }
            }
            $entry['files'] = $files;
            
            // add entry to array of entries
            $entries[] = $entry;

            if ($line === false) {
                break;
            }
        } //while true
        $fp = null;

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
        return $commits;
    }

    public function cloneRepository()
    {
        $this->run_tool('git', 'string', array('clone', $this->url, $this->local_path));
    }
    
    public function pullRepository()
    {
        //TODO: I wonder if this really works? =)
        stream_get_contents($this->git('fetch'));
        stream_get_contents($this->git('merge', 'origin/master'));
    }
    
    public function getRepositoryId()
    {
        //FIXME: is there a better way to do this?
        $rev_list = stream_get_contents($this->git('rev-list', '--parents', 'HEAD'));
        $rev_list = str_replace("\n", " ", $rev_list);
        $rev_list = explode(" ", $rev_list);
        return trim($rev_list[(count($rev_list)-2)]);
    }
    
    public function getLastRevision()
    {
        $git_executable = "\"" . Yii::app()->config->get('git_executable') . "\"";
        $cmd = "{$git_executable} --git-dir={$this->local_path}/.git --work-tree={$this->local_path} log --max-count=1 --pretty=format:%H";
        return stream_get_contents(popen($cmd, 'r'));
    }
    
    public function getChanges($start = 0, $end = '', $limit = 100)
    {
        return $this->log($start = 0, $end = '', $limit);
    }

    public function getFileContents($path, $revision)
    {
        $git_executable = "\"" . Yii::app()->config->get('git_executable') . "\"";
        $cmd = "{$git_executable} --git-dir={$this->local_path}/.git --work-tree={$this->local_path} show {$revision}:{$path}";
        return stream_get_contents(popen($cmd, 'r'));
    }

    public function getLastRevisionOf($path)
    {
        $git_executable = "\"" . Yii::app()->config->get('git_executable') . "\"";
        $cmd = "{$git_executable} --git-dir={$this->local_path}/.git --work-tree={$this->local_path} log --max-count=1 --pretty=format:%H {$this->local_path}/{$path}";
        return stream_get_contents(popen($cmd, 'r'));
    }
    
    public function getParents($revision)
    {
        $git_executable = "\"" . Yii::app()->config->get('git_executable') . "\"";
        $format = "%P";
        $cmd = "{$git_executable} --git-dir={$this->local_path}/.git --work-tree={$this->local_path} log --pretty=format:%p, {$revision}";
        $out = stream_get_contents(popen($cmd, 'r'));
        $out = str_replace("\n", "", $out);
        return rtrim($out, ',');
    }
    
    public function getUsers()
    {
        return $this->arr_users;
    }
}
