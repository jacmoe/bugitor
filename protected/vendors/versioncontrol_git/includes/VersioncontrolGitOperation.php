<?php

class VersioncontrolGitOperation extends VersioncontrolOperation {

  public $author_name = '';

  public $committer_name = '';

  public $parent_commit = '';

  public $merge = FALSE;

  protected function backendInsert() {
    db_insert('versioncontrol_git_operations')
      ->fields(array(
        'vc_op_id' => $this->vc_op_id,
        'author_name' => $this->author_name,
        'committer_name' => $this->committer_name,
        'parent_commit' => $this->parent_commit,
        'merge' => (int) $this->merge,
      ))
      ->execute();
  }

  protected function backendUpdate() {
    db_update('versioncontrol_git_operations')
      ->fields(array(
        'author_name' => $this->author_name,
        'committer_name' => $this->committer_name,
        'parent_commit' => $this->parent_commit,
        'merge' => (int) $this->merge,
      ))
      ->condition('vc_op_id', $this->vc_op_id)
      ->execute();
  }

  /**
   * Implementation of abstract method.
   */
  public function getSelectedLabel($target_item) {
  // TODO: implement tag support here, tags>branch?
  // better not, after looking it again current code is OK.
  // just take the first branch, dunno what else we should do here...
  // jpetso knows neither :P
    return $this->labels[0];
  }

}