<?php

Yii::import('application.components.scm.*');

class SCM extends CApplicationComponent
{
    private $_backend = array(
        'hg' => null,
        'git' => null,
        'svn' => null,
        'github' => null,
        'bitbucket' => null,
    );

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

    public function getBackend($versionController = null)
    {
        if(null == $versionController) {
            $versionController = 'hg';
        }
        if($this->_backend[$versionController] !== null)
            return $this->_backend[$versionController];
        else
        {
            if(isset($this->backendMap[$versionController]))
                return $this->_backend[$versionController] = Yii::createComponent($this->backendMap[$versionController], $this);
            else
                throw new CException(Yii::t('yii','SCM does not support {backend}.',
                    array('{backend}' => $backend)));
        }
    }
}
