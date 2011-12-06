<?php
abstract class SCMBackend extends CComponent
{
    public $name;
    public $repository;
    public $directory;
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

    public function getRepository()
    {
        return $this->repository;
    }
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function getDirectory()
    {
        return $this->directory;
    }
    public function setDirectory($directory)
    {
        $this->directory = $directory;
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