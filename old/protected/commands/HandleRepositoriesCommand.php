<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2013 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php

class HandleRepositoriesCommand extends CConsoleCommand {

    private $SCMBackend = null;

    public function fillUsersTable($repository_id) {
        $authors = array();
        $arr_users = array();
        $arr_users = $this->SCMBackend->getUsers();
        foreach ($arr_users as $key => $val) {
            $authors[$val] = true;
        }
        $authors = array_keys($authors);

        foreach ($authors as $author) {
            $criteria = new CDbCriteria();
            $criteria->compare('author', $author);
            //$criteria->compare('repository_id', $repository_id);
            if (!AuthorUser::model()->exists($criteria)) {
                $author_user = new AuthorUser;

                $author_user->author = $author;
                $author_user->repository_id = $repository_id;

                $criteria_user = new CDbCriteria();
                $criteria_user->compare('username', $author);
                $user = User::model()->find($criteria_user);
                if ($user) {
                    $author_user->user_id = $user->id;
                }
                if ($author_user->validate(array('author'))) {
                    $author_user->save(false);
                }
            }
        }
    }

    public function workPendingChangesets() {
        $pending_changesets = PendingChangeset::model()->findAll();
        if ($pending_changesets) {
            foreach ($pending_changesets as $pending_changeset) {
                $changeset = Changeset::model()->findByPk((int) $pending_changeset->changeset_id);
                if ($changeset) {
                    $user_id = $this->handleUser($changeset->author);
                    if ($user_id) {
                        $changeset->user_id = $user_id;
                        if ($changeset->validate()) {
                            $changeset->save(false);
                            $this->processChangeset($changeset);
                            $this->addToActionLog($changeset);
                            $pending_changeset->delete();
                        };
                    } // if there is a user_id for the pending changeset
                } // if it exists
            } // foreach pending changeset
        } // if pending changesets
    }

    public function handleUser($author) {
        $criteria_user = new CDbCriteria();
        $criteria_user->compare('author', $author);
        $author_user = AuthorUser::model()->find($criteria_user);
        if ($author_user) {
            return $author_user->user_id;
        }
        return null;
    }

    public function doInitialImport($unique_id, $last_revision, $repository_id) {
        $start_rev = 0;
        while ($start_rev < $last_revision) {
            echo 'Importing  from revision "' . $start_rev . '" to "' . ($start_rev + 25) . "\"\n";
            $this->importChangesets($start_rev, $unique_id, $repository_id);
            $start_rev = $start_rev + 25;
            sleep(1);
        }
    }

    public function importChangesets($start_rev, $unique_id, $repository_id) {

        echo 'Importing  from revision "' . $start_rev . '" to "' . ($start_rev + 50) . "\"\n";

        $entries = $this->SCMBackend->getChanges($start_rev, 'tip', 50);

        $this->fillUsersTable($repository_id);

        foreach ($entries as $entry) {

            $criteria = new CDbCriteria();
            $criteria->select = "unique_ident";
            $criteria->compare('unique_ident', $unique_id . $entry['revision']);

            if (!Changeset::model()->exists($criteria)) {

                $changeset = new Changeset;
                $changeset->unique_ident = $unique_id . $entry['revision'];
                $changeset->revision = $entry['revision'];
                $changeset->author = $entry['author'];
                $changeset->user_id = $this->handleUser($entry['author']);
                $changeset->scm_id = $repository_id;
                $changeset->commit_date = $entry['date'];
                $changeset->message = $entry['message'];
                if(!$changeset->message) $changeset->message = 'No message.';
                $changeset->short_rev = $entry['short_rev'];
                $changeset->branches = $entry['branches'];
                $changeset->tags = $entry['tags'];
                $changeset->branch_count = $entry['branch_count'];
                $changeset->tag_count = $entry['tag_count'];
                $changeset->parent_count = $entry['parent_count'];
                if ($entry['parent_count'] === 0) {
                    $changeset->parents = $this->SCMBackend->getParents($entry['short_rev']);
                    if ($changeset->parents == '') {
                        $changeset->parent_count = 0;
                    } else {
                        $changeset->parent_count = 1;
                    }
                    $fp = null;
                } else {
                    $changeset->parents = $entry['parents'];
                }

                if ($changeset->validate()) {
                    $changeset->save(false);

                    // If user_id is not present,
                    // do not add and import
                    // add the changeset->id to a to-be-done table instead
                    if (null != $changeset->user_id) {
                        $this->addToActionLog($changeset);
                        $this->processChangeset($changeset);
                    } else {
                        $pending = new PendingChangeset();
                        $pending->changeset_id = $changeset->id;
                        $pending->save();
                    }

                    $change_edit = $change_del = $change_add = 0;

                    foreach ($entry['files'] as $file) {
                        $change = new Change;
                        $change->changeset_id = $changeset->id;
                        switch ($file['status']) {
                            case 'M':
                                $change_edit++;
                                break;
                            case 'A':
                                $change_add++;
                                break;
                            case 'D':
                                $change_del++;
                                break;
                            default:
                                break;
                        }
                        $change->path = $file['name'];
                        $change->action = $file['status'];
                        if ('M' === $change->action) {
                            $change->diff = $this->SCMBackend->getDiff($change->path, $changeset->short_rev);
                        } elseif('A' === $change->action) {
                            $change->diff = $this->SCMBackend->getFileContents($change->path, $changeset->short_rev);
                        } elseif('D' === $change->action) {
                            $change->diff = $this->SCMBackend->getFileContents($change->path, $this->SCMBackend->getLastRevisionOf($change->path));
                        } else {
                            $change->diff = '';
                        }
                        $fp = null;
                        if ($change->validate()) {
                            $change->save(false);
                        } else {
                            return false;
                        }
                    }
                    $changeset->add = $change_add;
                    $changeset->del = $change_del;
                    $changeset->edit = $change_edit;
                    $changeset->update();
                } else { // changeset validate
                    return false;
                }
            } // if changeset doesn't exist
        } // foreach entries
        return true;
    }

    public function processChangeset($changeset) {

        $commit_date_in_seconds = Time::makeUnix($changeset->commit_date);
        // offset initial by 1 second to make date later than associated changeset.
        $commit_date_in_seconds++;

        $preg_string_refs =   '/(?:refs|ref|references|see) #?(\d+)(\#ic\d*){0,1}(( #?and|#?+or|,) #?(\d+)(\#ic\d*){0,1}){0,}/';
        $preg_string_closes = '/(?:fix(?:ed|es)|close(?:d|s)|fix|close) #?(\d+)(\#ic\d*){0,1}(( #?and|#?+or|,) #?(\d+)(\#ic\d*){0,1}){0,}/';

        $issues_to_be_referenced = array();
        $num_refs = preg_match_all($preg_string_refs, strtolower($changeset->message), $matches_refs, PREG_SET_ORDER);
        if ($num_refs > 0) {
            for ($i = 0; $i < count($matches_refs); $i++) {

                if (count($matches_refs[$i]) == 6) {
                    $issues_to_be_referenced[] = $matches_refs[$i][1];
                    $issues_to_be_referenced[] = $matches_refs[$i][5];
                } else if (count($matches_refs[$i]) == 2) {
                    $issues_to_be_referenced[] = $matches_refs[$i][1];
                }
            }
        }
        $criteria_ref = new CDbCriteria();
        $criteria_ref->compare('project_id', $changeset->scm->project_id);

        $issues_to_ref = Issue::model()->findAllByPk($issues_to_be_referenced, $criteria_ref);
        foreach ($issues_to_ref as $issue_ref) {

            $comment = new Comment;
            $comment->content = 'Referenced in rev:' . $changeset->revision;
            $comment->issue_id = $issue_ref->id;
            $comment->create_user_id = $changeset->user_id;
            $comment->update_user_id = $changeset->user_id;
            if ($comment->validate(array('content', 'issue_id', 'create_user_id', 'update_user_id'))) {
                $comment->created = date("Y-m-d H:i:s", ($commit_date_in_seconds));
                $comment->modified = date("Y-m-d H:i:s", ($commit_date_in_seconds));
                $comment->save(false);

                $issue_ref->detachBehavior('BugitorTimestampBehavior');

                if ($issue_ref->validate(array('updated_by', 'closed'))) {
                    if (Time::makeUnix($issue_ref->modified) < Time::makeUnix($changeset->commit_date)) {
                        $issue_ref->modified = date("Y-m-d H:i:s", ($commit_date_in_seconds));
                        $issue_ref->updated_by = $changeset->user_id;
                    }

                    $issue_ref->save(false);

                    $issue_ref = Issue::model()->findByPk($issue_ref->id);
                    $issue_ref->addToActionLog($issue_ref->id, $changeset->user_id, 'note', '/projects/' . $issue_ref->project->identifier . '/issue/view/' . $issue_ref->id . '#note-' . $issue_ref->commentCount, $comment->id);
                    $issue_ref->sendNotification($issue_ref->id, $comment->id, $issue_ref->updated_by);

                    $changeset_issue = new ChangesetIssue;
                    $changeset_issue->changeset_id = $changeset->id;
                    $changeset_issue->issue_id = $issue_ref->id;
                    if ($changeset_issue->validate())
                        $changeset_issue->save(false);
                } // issue_ref validate
            } // comment validate
            $commit_date_in_seconds++;
        } // foreach issues to reference

        $issues_to_be_closed = array();
        $num_closes = preg_match_all($preg_string_closes, strtolower($changeset->message), $matches_closes, PREG_SET_ORDER);
        if ($num_closes > 0) {
            for ($i = 0; $i < count($matches_closes); $i++) {

                if (count($matches_closes[$i]) == 6) {
                    $issues_to_be_closed[] = $matches_closes[$i][1];
                    $issues_to_be_closed[] = $matches_closes[$i][5];
                } else if (count($matches_closes[$i]) == 2) {
                    $issues_to_be_closed[] = $matches_closes[$i][1];
                }
            }
        }

        $criteria_close = new CDbCriteria();
        $criteria_close->compare('project_id', $changeset->scm->project_id);

        $issues_to_close = Issue::model()->findAllByPk($issues_to_be_closed, $criteria_close);
        foreach ($issues_to_close as $issue_close) {

            $was_closed_by_commit = false;
            $comment = new Comment;
            if ($issue_close->closed == 0) {
                $comment->content = 'Applied in rev:' . $changeset->revision;
                $was_closed_by_commit = true;
            } else {
                $comment->content = 'Referenced in rev:' . $changeset->revision;
            }
            $comment->issue_id = $issue_close->id;
            $comment->create_user_id = $changeset->user_id;
            $comment->update_user_id = $changeset->user_id;

            if ($comment->validate(array('content', 'issue_id', 'create_user_id', 'update_user_id'))) {
                $comment->created = date("Y-m-d H:i:s", ($commit_date_in_seconds));
                $comment->modified = date("Y-m-d H:i:s", ($commit_date_in_seconds));
                $comment->save(false);

                if ($issue_close->closed != 1) {
                    $issue_close->status = 'swIssue/resolved';
                }

                $issue_close->detachBehavior('BugitorTimestampBehavior');

                if ($issue_close->validate(array('updated_by', 'closed'))) {
                    if (Time::makeUnix($issue_close->modified) < Time::makeUnix($changeset->commit_date)) {
                        $issue_close->modified = date("Y-m-d H:i:s", ($commit_date_in_seconds));
                        $issue_close->updated_by = $changeset->user_id;
                    }

                    $issue_close->save(false);

                    if ($was_closed_by_commit) {
                        $issue_close->addToActionLog($issue_close->id, $changeset->user_id, 'resolved', '/projects/' . $issue_close->project->identifier . '/issue/view/' . $issue_close->id . '#note-' . $issue_close->commentCount, $comment->id);
                    } else {
                        $issue_close->addToActionLog($issue_close->id, $changeset->user_id, 'note', '/projects/' . $issue_close->project->identifier . '/issue/view/' . $issue_close->id . '#note-' . $issue_close->commentCount, $comment->id);
                    }

                    $issue_close->sendNotification($issue_close->id, $comment->id, $issue_close->updated_by);

                    $changeset_issue = new ChangesetIssue;
                    $changeset_issue->changeset_id = $changeset->id;
                    $changeset_issue->issue_id = $issue_close->id;
                    if ($changeset_issue->validate())
                        $changeset_issue->save(false);
                } // issue_close validate
            } // comment validate
            $commit_date_in_seconds++;
        } // foreach issue to close
    }

    public function addToActionLog($changeset) {
        $actionLog = new ActionLog;
        $actionLog->author_id = $changeset->user_id;
        $actionLog->project_id = $changeset->scm->project_id;
        $actionLog->description = $changeset->message;
        $actionLog->subject = 'Revision ' . $changeset->revision . ' (' . $changeset->scm->name . ')';
        $actionLog->type = 'changeset';
        $actionLog->theDate = $changeset->commit_date;
        $actionLog->url = '/projects/' . $changeset->scm->project->identifier . '/changeset/view/' . $changeset->id;
        //TODO: fix url when in subdirectory
        //$actionLog->url = Yii::app()->config->get('hostname').'projects/' . $changeset->scm->project->identifier . '/changeset/view/' . $changeset->id;
        if ($actionLog->validate())
            $actionLog->save(false);
    }

    public function processRepository($repository) {

        $this->SCMBackend = Yii::app()->scm->getBackend($repository->type);

        $this->SCMBackend->url = $repository->url;
        $this->SCMBackend->local_path = $repository->local_path;
        
        if ($repository->status === '0') {
            // clone repository
            $this->SCMBackend->cloneRepository();
            $repository->status = 1;
            $repository->save();

            // fill author user table
            //FIXME: Do this more efficiently!
            $this->SCMBackend->getChanges(0, 'tip', 0);
            $this->fillUsersTable($repository->id);

            return;
        }

        if ($repository->status === '1') {
            // user need to check author_user table
            //TODO: put a link so that the user can set progress
            return;
        }

        // repository has been cloned and author_user table checked

        $this->SCMBackend->pullRepository();
        //TODO: do we really need hg update?
        //$this->SCMBackend('update');
        
        // get the unique repository id
        $unique_id = $this->SCMBackend->getRepositoryId();

        // get last revision
        $last_revision = $this->SCMBackend->getLastRevision();

        if ($repository->status === '2') {
            // perform initial import
            //TODO: this consumes too much memory!
            //$this->SCMBackend('pull');
            //$this->SCMBackend('update');
            //$this->doInitialImport($unique_id, $last_revision, $repository->id);
            $repository->status = 3;
            $repository->save();
            //continue;
        }

        // repository status is OK (3)
        // normal maintenance work ...

        $this->workPendingChangesets();

        $criteriac = new CDbCriteria();
        $criteriac->compare('scm_id', $repository->id);
        $criteriac->select = 'max(short_rev) as maxRev';
        $last_revision_stored = Changeset::model()->find($criteriac);
        $start_rev = 0;
        if (null !== $last_revision_stored->maxRev) {
            //echo 'Last revision in db: ' . $last_revision_stored->maxRev . "\n";
            $start_rev = $last_revision_stored->maxRev + 1;
        }

        if ($start_rev <= $last_revision)
            $this->importChangesets($start_rev, $unique_id, $repository->id);
    }

    public function run($args) {

        Yii::app()->setTimeZone("UTC");
        
        $run = false;
        $locks = true;
        if(isset($args[1])) {
            if($args[1] == 'nolock') {
                $run = true;
                $locks = false;
                echo "no locks set\n";
            }
        } else {
            $run = Yii::app()->mutex->lock('HandleRepositoriesCommand', 600);
        }
        
        
        if ($run) {
            try {
                if(Yii::app()->config->get('python_path'))
                  putenv(Yii::app()->config->get('python_path'));

                if(count($args) > 0) {
                    $project = Project::model()->findByPk((int)$args[0]);
                    if($project) {
                        echo 'Handling repositories for ' . $project->name . "\n";
                        foreach ($project->repositories as $repository) {
                            $this->processRepository($repository);
                        }
                    } else {
                        echo "Invalid project ID\n";
                    }
                } else {
                    echo "Processing repositories for all projects..\n";
                    $projects = Project::model()->with(array('repositories'))->findAll();
                    foreach ($projects as $project) {
                        echo 'Handling repositories for ' . $project->name . "\n";
                        foreach ($project->repositories as $repository) {
                            $this->processRepository($repository);
                        }
                    }
                }

                if($locks) Yii::app()->mutex->unlock();
            } catch (Exception $e) {
                echo 'Exception caught: ', $e->getMessage(), "\n";
                if($locks) Yii::app()->mutex->unlock();
            }
        } else {
            echo 'Nothing to do. Exiting...';
        }
    }

}
