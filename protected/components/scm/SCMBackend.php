<?php
abstract class SCMBackend extends CComponent
{
    public $name;
    public $repository;
    public $username;
    public $password;
    
    /*public function __construct($repository)
	{
        $this->repository = $repository;
        echo $this->repository;
    }*/
    
    abstract public function log($start = 0, $end = '', $limit = 100);
    
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