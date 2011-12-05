<?php

class VersioncontrolGitBackend extends VersioncontrolBackend {

  public $classesEntities = array(
      'repo' => 'VersioncontrolGitRepository',
      'account' => 'VersioncontrolGitAccount',
      'operation' => 'VersioncontrolGitOperation',
      'item' => 'VersioncontrolGitItem',
    );

  public $classesControllers = array(
    'operation' => 'VersioncontrolGitOperationController',
  );

  public function __construct() {
    parent::__construct();
    $this->name = 'Git';
    $this->description = t('Git is a fast, scalable, distributed revision control system with an unusually rich command set that provides both high-level operations and full access to internals.');
    $this->capabilities = array(
        // Use the commit hash for to identify the commit instead of an individual
        // revision for each file.
        VERSIONCONTROL_CAPABILITY_ATOMIC_COMMITS
    );
  }

  /**
   * Overwrite to get short sha-1's
   */
  public function formatRevisionIdentifier($revision, $format = 'full') {
    switch ($format) {
      case 'short':
        // Let's return only the first 7 characters of the revision identifier,
        // like git log --abbrev-commit does by default.
        return substr($revision, 0, 7);
      case 'full':
      default:
        return $revision;
    }
  }

}
