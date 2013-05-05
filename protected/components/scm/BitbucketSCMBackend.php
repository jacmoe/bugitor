<?php
Yii::import('application.vendors.bitbucket-api-library.*');
Yii::import('application.vendors.bitbucket-api-library.lib.*');
require_once('bbApi.php');
require_once('lib/__changesets.php');

class BitbucketSCMBackend extends SCMBackend
{
    private $_userName;
    private $_password;
    private $_bitbucket = null;
    private $_authenticated = false;

    public $name = 'bitbucket';

    private function getBitbucket()
    {
        if(null !== $this->_bitbucket){
            return $this->_bitbucket;
        } else {
            $this->_bitbucket = new bbApi();
            return $this->_bitbucket;
        }
    }

    private function authenticate()
    {
        if(false === $this->_authenticated)
        {
            $this->getBitbucket()->authenticate($this->_userName, $this->_password);
            $this->_authenticated = true;
        }
    }

    public function getDiff($path, $from, $to = null)
    {
    }

    protected function log($start = 0, $end = '', $limit = 50)
    {
        $this->authenticate();

        $commits = array();

        $changesets = new bbApiChangesets($this->getBitbucket());

        $changeList = $changesets->show($this->url, null, null, $start, $limit);

        //print_r($changeList);
        /*stdClass Object
        (
            [count] => 178
            [start] => 1
            [limit] => 15
            [changesets] => Array
                (
                    [0] => stdClass Object
                        (
                            [node] => b736ca574017
                            [files] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [type] => added
                                            [file] => .hgignore
                                        )

                                    [644] => stdClass Object
                                        (
                                            [type] => added
                                            [file] => themes/freshy2/views/layouts/main_1.php
                                        )

                                )

                            [raw_author] => jacmoe2
                            [utctimestamp] => 2010-12-02 12:38:14+00:00
                            [author] => jacmoe
                            [timestamp] => 2010-12-02 13:38:14
                            [raw_node] => b736ca574017da021ca81016f1ac8882af453741
                            [parents] => Array
                                (
                                )

                            [branch] => default
                            [message] => Initial commit
                            [revision] => 0
                            [size] => -1
                        )

                )

        )*/
        foreach($changeList->changesets as $key => $val)
        {
            $commit = array();
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
            $commit['revision'] = $val->revision;
            $commit['node'] = $val->node;
            $commit['raw_node'] = $val->raw_node;
            $commit['timestamp'] = $val->timestamp;
            $commit['utc_timestamp'] = $val->utctimestamp;
            $commit['branch'] = $val->branch;
            $commit['message'] = $val->message;
            $commit['author'] = $val->author;
            foreach($val->parents as $parent)
            {
                //echo "Parent: " . $parent;
            }
            foreach($val->files as $file)
            {
                //print_r($file);
            }
            $commits[] = $commit;
        }
/*        $changeList = $changesets->show($this->repository);
        //$changeList = $changesets->show($repository, null, 'dc580ce65088');
        //print_r($changeList);


        $count = $changeList->count;
        $loop = (int)($count / 15) - 1;

        $looper = 0;
        $counter = 0;

        while($counter <= $loop)
        {
            $changeList = $changesets->show($this->repository, null, null, $looper);

            foreach($changeList->changesets as $key => $val)
            {
                $commit = array();
                $commit['revision'] = $val->revision;
                $commit['node'] = $val->node;
                $commit['raw_node'] = $val->raw_node;
                $commit['timestamp'] = $val->timestamp;
                $commit['utc_timestamp'] = $val->utctimestamp;
                $commit['branch'] = $val->branch;
                $commit['message'] = $val->message;
                $commit['author'] = $val->author;
                foreach($val->parents as $parent)
                {
                    //echo "Parent: " . $parent;
                }
                foreach($val->files as $file)
                {
                    //print_r($file);
                }
                $commits[] = $commit;
            }
            $counter++;
            $looper += 15;
        }

        $remaining = $count - $looper;

        if($remaining > 0)
        {
            $changeList = $changesets->show($this->repository, null, null, 'tip', $remaining - 1);

            foreach($changeList->changesets as $key => $val)
            {
                $commit = array();
                $commit['revision'] = $val->revision;
                $commit['node'] = $val->node;
                $commit['raw_node'] = $val->raw_node;
                $commit['timestamp'] = $val->timestamp;
                $commit['utc_timestamp'] = $val->utctimestamp;
                $commit['branch'] = $val->branch;
                $commit['message'] = $val->message;
                $commit['author'] = $val->author;
                foreach($val->parents as $parent)
                {
                    //echo "Parent: " . $parent;
                }
                foreach($val->files as $file)
                {
                    //print_r($file);
                }
                $commits[] = $commit;
            }
        }*/

        return $commits;
    }

    public function getRepositoryId()
    {
        return $this->repositoryId;
    }

    public function getLastRevision()
    {
        return $this->lastRevision;
    }

    public function getLastRevisionOf($path)
    {
    }

    public function getFileContents($path, $revision)
    {
    }

    public function getChanges($start = 0, $end = '', $limit = 50)
    {
        return $this->log($start, $end, $limit);
    }

    public function getParents($revision)
    {
    }

    public function getUsers()
    {
        return $this->arr_users;
    }

    public function setCredentials($username, $password)
    {
        $this->_userName = $username;
        $this->_password = $password;
    }
}
