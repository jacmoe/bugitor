<?php

Yii::import('application.components.scm.*');

class SCM extends CApplicationComponent
{
    private $_backend;
    public $versionController = 'hg';
	
	public $backendMap = array(
		'hg' => 'HgSCMBackend',    // Mercurial
		'git' => 'GitSCMBackend',   // Git
		'svn' => 'SVNSCMBackend',    // Subversion (SVN)
		'github' => 'GithubSCMBackend',  // Github API
		'bitbucket' => 'BitbucketSCMBackend', // Bitbucket API
	);

    public $backendList = array(
        'hg' => 'Mercurial', 
        'git' => 'Git',
        'svn' => 'SVN',
        'github' => 'Github',
        'bitbucket' => 'Bitbucket',
    );
    
    public function init() {
        parent::init();
    }
    
    public function getBackend()
    {
        if($this->_backend !== null)
            return $this->_backend;
        else
        {
            $backend = $this->versionController;
            if(isset($this->backendMap[$backend]))
                return $this->_backend = Yii::createComponent($this->backendMap[$backend], $this);
            else
                throw new CException(Yii::t('yii','SCM does not support {backend}.',
                    array('{backend}' => $backend)));
        }
    }
}