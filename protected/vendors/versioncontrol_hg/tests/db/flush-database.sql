-- This script resets all registered repositories.
-- Run this, then run cron, to get a clean read-out of all your Mercurial repositories.
DELETE FROM versioncontrol_commits
  WHERE EXISTS (
    SELECT * FROM versioncontrol_hg_commits hg WHERE versioncontrol_commits.vc_op_id = hg.vc_op_id
  );
DELETE FROM versioncontrol_branch_operations
  WHERE EXISTS (
    SELECT * FROM versioncontrol_branches br
    WHERE
      versioncontrol_branch_operations.branch_id = br.branch_id AND
      EXISTS (
        SELECT * FROM versioncontrol_repositories rep
        WHERE
          br.repo_id = rep.repo_id AND
          vcs = 'hg'
      )
  );
DELETE FROM versioncontrol_tag_operations
  WHERE EXISTS (
    SELECT * FROM versioncontrol_operations o
    WHERE
      versioncontrol_tag_operations.vc_op_id = o.vc_op_id AND
      EXISTS (
        SELECT * FROM versioncontrol_repositories rep
        WHERE
          o.repo_id = rep.repo_id AND
          vcs = 'hg'
      )
  );
DELETE FROM versioncontrol_branches
  WHERE EXISTS (
    SELECT * FROM versioncontrol_repositories rep
    WHERE
      versioncontrol_branches.repo_id = rep.repo_id AND
      vcs = 'hg'
  );
DELETE FROM versioncontrol_operations
  WHERE EXISTS (
    SELECT * FROM versioncontrol_repositories rep
    WHERE
      versioncontrol_operations.repo_id = rep.repo_id AND
      vcs = 'hg'
  );
TRUNCATE versioncontrol_hg_commits;
TRUNCATE versioncontrol_hg_commit_actions;
TRUNCATE versioncontrol_hg_tags;
UPDATE versioncontrol_hg_repositories SET latest_rev = NULL;