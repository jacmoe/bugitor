<?php
abstract class SCMBackend extends CComponent
{
    public $name;
    public $local_path;
    public $url;
    public $username;
    public $password;
    
    public $repositoryId;
    public $lastRevision;
    public $changes;
    
    /*public function __construct($repository)
	{
        $this->repository = $repository;
        echo $this->repository;
    }*/
    
    public abstract function getRepositoryId();
    public abstract function getLastRevision();
    public abstract function getDiff($path, $from, $to = null);
    
    public abstract function getChanges($start = 0, $end = '', $limit = 100);
    
    public abstract function getParents($revision);
    
    public function getName()
    {
        return $this->name;
    }

    public function getLocalPath()
    {
        return $this->local_path;
    }
    public function setLocalPath($local_path)
    {
        $this->local_path = $local_path;
    }

    public function getUrl()
    {
        return $this->url;
    }
    public function setUrl($url)
    {
        $this->url= $url;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}