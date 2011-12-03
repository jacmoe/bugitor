<?php
abstract class SCMBackend extends CComponent
{
    private $_repository_path;
    
    public $name;
    public $repository;
    public $username;
    public $password;
    
    public function __construct($repository_path)
	{
        $this->_repository_path = $repository_path;
        echo $_repository_path;
    }
    
    abstract public function getName();

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