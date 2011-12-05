<?php

class VersioncontrolGitOperationController extends VersioncontrolOperationController {

  /**
   * Extend the base query with the git backend's additional data in
   * {versioncontrol_git_operations}.
   *
   * @return SelectQuery
   */
  protected function buildQueryBase($ids, $conditions) {
    $query = parent::buildQueryBase($ids, $conditions);
    $alias = $this->addTable($query, 'versioncontrol_git_operations', 'vcgo', 'base.vc_op_id = vcgo.vc_op_id');
    $query->fields($alias, drupal_schema_fields_sql('versioncontrol_git_operations'));
    return $query;
  }
}