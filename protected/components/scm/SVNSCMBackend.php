<?php

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
        $fp = null;
        if ($to !== null) {
          $fp = $this->svn('diff', '-r', $from, '-r', $to,
            "$this->local_path$path", "--git");
        } else {
          $fp = $this->svn('diff', '-c', $from,
            "$this->local_path$path@$from", "--git");
        }
        $diff = stream_get_contents($fp);
        $diff = implode("\n", array_slice(explode("\n", $diff), 2));
        return $diff;
    }

    protected function info()
    {
        $fp = $this->svn('info', '--xml', $this->local_path);
        $info = stream_get_contents($fp);
        $xml_info = new SimpleXMLElement($info);
        return $xml_info;
    }

    protected function log($start = 0, $end = null, $limit = 100)
    {
        if(($this->getLastRevision() < $end) && (null !== $end)) {
            $end = $this->getLastRevision();
        }
        if($this->getLastRevision() < $start) {
            $start = 0;
        }
        //FIXME: could we do this better?
        if(0 !== $start) {
            if(null !== $end) {
                $fp = $this->svn('log', '--verbose', '--xml', '-r', "{$start}:{$end}", '--limit', $limit, $this->local_path);
            } else {
                $fp = $this->svn('log', '--verbose', '--xml', '-r', $start, '--limit', $limit, $this->local_path);
            }
        } else {
            $fp = $this->svn('log', '--verbose', '--xml', '--limit', $limit, $this->local_path);
        }
        $log = stream_get_contents($fp);

        $xml_entries = new SimpleXMLElement($log);

        $this->arr_users = array();

        $entries = array();
        foreach($xml_entries as $xml_entry) {
            $entry = array();
            $entry['revision'] = (int)$xml_entry['revision'];
            $entry['short_rev'] = (int)$xml_entry['revision'];
            $entry['branches'] = null;
            $entry['branch_count'] = 0;
            $entry['tags'] = null;
            $entry['tag_count'] = 0;
            $entry['parents'] = null;
            $entry['parent_count'] = 1; //FIXME: what to do about parents??
            $entry['date'] = date("Y-m-d H:i:s", strtotime((string)$xml_entry->date));
            $entry['message'] = (string)$xml_entry->msg;
            $files = array();
            foreach($xml_entry->paths as $paths) {
                foreach($paths as $path) {
                    if('file' == $path['kind']) {
                        $file = array();
                        $file['name'] = (string)$path;
                        $file['status'] = (string)$path['action'];
                        $files[] = $file;
                    }
                }
            }
            if(!(empty($files))) {
                $entry['files'] = $files;
                $entry['file_count'] = count($files);
            } else {
                $entry['file_count'] = 0;
            }

            $entry['author'] = (string)$xml_entry->author;
            $this->arr_users[] = (string)$xml_entry->author;
            $entries[] = $entry;

        }

        return $entries;
    }

    public function cloneRepository()
    {
        stream_get_contents($this->svn('checkout',
          $this->url,
          $this->local_path));
    }

    public function pullRepository()
    {
        stream_get_contents($this->svn('update',
          $this->local_path));
    }

    public function getParents($revision)
    {
        return '';
    }

    public function getRepositoryId()
    {
        $info = $this->info();
        return $info->entry->repository->uuid;
    }

    public function getLastRevision()
    {
        $info = $this->info();
        return $info->entry['revision'];
    }

    public function getLastRevisionOf($path)
    {
        $fp = $this->run_tool('svn', 'read', array('log', $this->local_path . $path, '--limit', 1, '--xml'));
        $rev_info = stream_get_contents($fp);
        $info = new SimpleXMLElement($rev_info);
        return $info->logentry['revision'];
    }

    public function getChanges($start = 0, $end = '', $limit = 100)
    {
        return $this->log($start, $end, $limit);
    }

    public function getUsers()
    {
        return $this->arr_users;
    }

    public function getFileContents($path, $revision)
    {
        $fp = $this->svn('cat', '-r', $revision,
          $this->local_path . $path);
        return stream_get_contents($fp);
    }
}
