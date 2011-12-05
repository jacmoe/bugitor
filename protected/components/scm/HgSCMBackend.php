<?php
class HgSCMBackend extends SCMLocalBackend
{
    public $name = 'hg';
    
    public $arr_users = array();

    protected function hg()
    {
        $args = func_get_args();
        $a = array("-y", "-R", $this->repository, "--cwd", $this->repository);
        foreach ($args as $arg) {
            $a[] = $arg;
        }

        return $this->run_tool('hg', 'read', $a);
    }
    
    public function getDiff($revision, $path)
    {
        $cmd = "{$this->executable} diff --git -c{$revision} -R {$this->repository} --cwd {$this->repository} {$path}";
        $diff = stream_get_contents(popen($cmd, 'r'));
        return $diff;
    } 


    protected function log($start = 0, $end = '', $limit = 100)
    {
        if('' == $end) {
            $end = 'tip';
        }

        $this->arr_users = array();

        $entries = array();

        $sep = uniqid();

        if (0 === $limit) {
            $fp = $this->hg('log', '--rev', $start . ':' . $end, '--template', $sep . '\n{node|short}\n{rev}\n{branches}\n{tags}\n{parents}\n{file_mods}\n{file_copies}\n{file_adds}\n{file_dels}\n{author|email}\n{date|hgdate}\n{desc}\n');
        } else {
            $fp = $this->hg('log', '--rev', $start . ':' . $end, '--template', $sep . '\n{node|short}\n{rev}\n{branches}\n{tags}\n{parents}\n{file_mods}\n{file_copies}\n{file_adds}\n{file_dels}\n{author|email}\n{date|hgdate}\n{desc}\n', '-l ' . $limit);
        }

        fgets($fp); # discard leading $sep
        // corresponds to the file_adds, file_copies, file_modes, file_dels
        // in the template above
        static $file_status_order = array('M', 'C', 'A', 'D');

        $count = 0;
        while (true) {
            $entry = array();

            $entry['revision'] = trim(fgets($fp));

            $entry['short_rev'] = trim(fgets($fp));

            $branches = array();
            foreach (preg_split('/\s+/', trim(fgets($fp))) as $b) {
                if (strlen($b)) {
                    $branches[] = $b;
                }
            }
            if (!count($branches)) {
                $entry['branches'] = 'default';
                $entry['branch_count'] = 1;
            } else {
                $entry['branches'] = implode(',', $branches);
                $entry['branch_count'] = count($branches);
            }

            $tags = array();
            foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
                if (strlen($t)) {
                    if ('tip' !== $t)
                        $tags[] = $t;
                }
            }
            if (!count($tags)) {
                $entry['tags'] = '';
                $entry['tag_count'] = 0;
            } else {
                $entry['tags'] = implode(',', $tags);
                $entry['tag_count'] = count($tags);
            }

            $parents = array();
            foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
                if (strlen($t)) {
                    $parents[] = $t;
                }
            }
            if (!count($parents)) {
                $entry['parents'] = '';
                $entry['parent_count'] = 0;
            } else {
                $entry['parents'] = implode(',', $parents);
                $entry['parent_count'] = count($parents);
            }

            $files = array();
            foreach ($file_status_order as $status) {
                foreach (preg_split('/\s+/', trim(fgets($fp))) as $t) {
                    if (strlen($t)) {
                        $file = array();
                        $file['name'] = $t;
                        $file['status'] = $status;
                        $files[] = $file;
                    }
                }
            }

            $entry['files'] = $files;

            $changeby = trim(fgets($fp));
            $entry['author'] = $changeby;
            $this->arr_users[] = $changeby;

            list($ts) = preg_split('/\s+/', fgets($fp));
            //FIXME: format date the way we want the date
            //$entry['date'] = date("Y-m-d H:i:s", $ts);

            $changelog = array();
            while (($line = fgets($fp)) !== false) {
                $line = rtrim($line, "\r\n");
                //$line = rtrim($line, "\n");
                if ($line == $sep) {
                    break;
                }
                $changelog[] = $line;
            }
            $thechangelog = join("\n", $changelog);

            $entry['message'] = $thechangelog;

            // add entry to array of entries
            $entries[] = $entry;

            if ($line === false) {
                break;
            }
        } //while true
        $fp = null;

        return $entries;
    }

    public function cloneRepository($local_path)
    {
        $this->run_tool('hg', 'read', array('clone', $this->repository, $local_path));        
    }

    public function pullRepository()
    {
        $this->hg('pull');
    }

    public function getRepositoryId()
    {
        $fp = $this->run_tool('hg', 'read', array('log', '-r0', '-R', $this->repository, '--cwd', $this->repository, '--template', '{node}'));
        $this->repositoryId = fgets($fp);
        return $this->repositoryId;
    }
    
    public function getLastRevision()
    {
        $fp = $this->run_tool('hg', 'read', array('log', '-rtip', '-R', $this->repository, '--cwd', $this->repository, '--template', '{rev}'));
        $this->lastRevision = fgets($fp);
        return $this->lastRevision;
    }
    
    public function getChanges($start = 0, $end = '', $limit = 100)
    {
        return $this->log($start, $end, $limit);
    }
    
    public function getParents($revision)
    {
        $fp = $this->run_tool('hg', 'read', array('parents', '-r' . $revision, '-R', $this->repositoryId, '--cwd', $this->repository, '--template', '{rev}:{node|short}'));
        $parents = fgets($fp);
        return $parents;
    }

    public function getUsers()
    {
        return $this->arr_users;
    }
}