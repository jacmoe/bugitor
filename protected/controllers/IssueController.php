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
 * Copyright (C) 2009 - 2011 Bugitor Team
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

class IssueController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'rights', // perform access control for CRUD operations
        );
    }

    public function allowedActions() {
        return 'view, index, upload, getcomment, editcomment, addwatcher';
    }

    public function actionUpload($parent_id) {
        if (isset($_FILES)) {
            if (!isset($_FILES['file'])) {
                $data = array('error' => 'File was a lot bigger than 2MB. If not, another error occurred. Try again.');
                echo json_encode($data);
                exit(0);
            }
            $filename = $_FILES['file']['name'];
            $filetype = $_FILES['file']['type'];
            $tempname = $_FILES['file']['tmp_name'];
            $error = $_FILES['file']['error'];
            $filesize = $_FILES['file']['size'];
            if ($filesize > (1024 * 1024 * 2)) {
                $data = array('error' => 'File is bigger than 2MB');
                echo json_encode($data);
                exit(0);
            }
            $path = Yii::getPathOfAlias('webroot.uploads') . '/' . $parent_id . '/';
            if (!is_dir($path)) {
                mkdir($path);
            }
            $filename = basename($_FILES['file']['name']);

            if (file_exists($path . $filename)) {
                $data = array('error' => 'File exists - either delete the old version or upload a file with another name.');
                echo json_encode($data);
                exit(0);
            }

            if (move_uploaded_file($_FILES['file']['tmp_name'], $path . $filename)) {
                //$data = array('result' => $filename);
            } else {
                $data = array('error' => 'Failed to save');
                echo json_encode($data);
                exit(0);
            }

            if (0 == $error) {

                $attachment = new Attachment;
                $attachment->issue_id = $parent_id;
                $attachment->name = $filename;
                $attachment->size = $filesize;

                $attachment->created = date("Y-m-d H:i:s", time());
                $attachment->user_id = Yii::app()->user->id;
                if ($attachment->validate()) {
                    $attachment->save(false);
                    $issue = $this->loadModel($parent_id, true);
                    $issue->updated_by = Yii::app()->user->id;
                    if ($issue->validate()) {
                        $comment = new Comment();
                        $comment->issue_id = $parent_id;
                        $comment->content = 'A file was attached: ' . $attachment->name;
                        $comment->create_user_id = Yii::app()->user->id;
                        if ($comment->validate())
                            $comment->save(false);
                        $issue->addToActionLog($issue->id, Yii::app()->user->id, 'attachment', $this->createUrl('issue/view', array('id' => $issue->id, 'identifier' => $issue->project->identifier, '#' => 'note-' . $issue->commentCount)), $comment->id);
                    }
                    $issue->save(false);
                    $out = CHtml::openTag('ul');
                    $out .= CHtml::openTag('li', array('class' => 'icon icon-attachment'));
                    $out .= CHtml::link($attachment->name, Yii::app()->baseUrl . '/uploads/'.$parent_id.'/'.$attachment->name);
                    $out .= CHtml::openTag('small');
                    $out .= CHtml::openTag('i');
                    $out .= ' ('.Bugitor::getReadableFileSize($attachment->size).')';
                    $out .= CHtml::closeTag('i');
                    $out .= CHtml::closeTag('small');
                    $out .= ' - Added by ' . Bugitor::link_to_user($attachment->user);
                    $out .= CHtml::closeTag('li');
                    $out .= CHtml::closeTag('ul');
                echo $out;
                exit(0);
            } else {
                $data = array('error' => 'Failed to save');
                echo json_encode($data);
                exit(0);
            }
            } else {
                $data = array('error' => 'Failed to save');
                echo json_encode($data);
                exit(0);
            }
        } // if isset($_FILES)
    }

    public function actionRemoveWatcher($watcher_id) {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['remove_watcher'])) {
                $user = User::model()->findByPk((int)$_POST['remove_watcher']);
                if ($user) {
                    $issue = $this->loadModel($issue_id);
                    if ($issue->watchedBy($user->id)) {
                        Watcher::model()->deleteAllByAttributes(array('user_id' => $user->id, 'issue_id' => $issue->id));
                    }
                    $issue = $this->loadModel($issue_id);
                    $this->renderPartial('_watchers', array('model' => $issue), false, true);
                }
            }
        }
    }
    
    public function actionAddWatcher($issue_id) {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['add_watcher'])) {
                $user = User::model()->findByPk((int)$_POST['add_watcher']);
                if ($user) {
                    $issue = $this->loadModel($issue_id);
                    if (!$issue->watchedBy($user->id)) {
                        $watcher = new Watcher();
                        $watcher->issue_id = $issue->id;
                        $watcher->user_id = $user->id;
                        $watcher->save();
                    }
                    $issue = $this->loadModel($issue_id);
                    $this->renderPartial('_watchers', array('model' => $issue), false, true);
                }
            }
        }
    }

    public function actionWatch() {
        if(Yii::app()->request->isAjaxRequest){
            if(isset($_POST['id'])) {
                $issue = $this->loadModel($_POST['id']);
                if($issue->watchedBy(Yii::app()->user->id)) {
                    Watcher::model()->deleteAllByAttributes(array('user_id' => Yii::app()->user->id, 'issue_id' => $issue->id));
                } else {
                    $watcher = new Watcher();
                    $watcher->issue_id = $issue->id;
                    $watcher->user_id = Yii::app()->user->id;
                    $watcher->save();
                }
                $issue = $this->loadModel($_POST['id']);
                $this->renderPartial('_watchers', array('model' => $issue), false, true);
            }
        }
    }

    public function actionMassEdit() {
        if(Yii::app()->request->isAjaxRequest){
            if(isset($_POST['ids'])) {
                foreach($_POST['ids'] as $val) {
                    switch($_POST['type']) {
                        case 'priority':
                            $issue = $this->loadModel($val, true);
                            $issue->issue_priority_id = $_POST['val'];
                            $issue->updated_by = Yii::app()->user->id;
                            if($issue->validate()) {
                                $comment = new Comment();
                                $comment->issue_id = $issue->id;
                                $comment->content = '_(Mass Edit) No comments for this change_';
                                $comment->create_user_id = Yii::app()->user->id;
                                if($comment->validate())
                                    $comment->save(false);

                                $issue->buildCommentDetails($comment->id);
                                $issue->addToActionLog($issue->id, Yii::app()->user->id, 'change', $this->createUrl('issue/view', array('id' => $issue->id, 'identifier' => $issue->project->identifier, '#' => 'note-'.$issue->commentCount)), $comment->id);

                                $issue->save(false);
                            }
                            break;
                        case 'milestone':
                            $issue = $this->loadModel($val, true);
                            $issue->milestone_id = $_POST['val'];
                            $issue->updated_by = Yii::app()->user->id;
                            if($issue->validate()) {
                                $comment = new Comment();
                                $comment->issue_id = $issue->id;
                                $comment->content = '_(Mass Edit) No comments for this change_';
                                $comment->create_user_id = Yii::app()->user->id;
                                if($comment->validate())
                                    $comment->save(false);

                                $issue->buildCommentDetails($comment->id);
                                $issue->addToActionLog($issue->id, Yii::app()->user->id, 'change', $this->createUrl('issue/view', array('id' => $issue->id, 'identifier' => $issue->project->identifier, '#' => 'note-'.$issue->commentCount)), $comment->id);

                                $issue->save(false);
                            }
                            break;
                        case 'category':
                            $issue = $this->loadModel($val, true);
                            $issue->issue_category_id = $_POST['val'];
                            $issue->updated_by = Yii::app()->user->id;
                            if($issue->validate()) {
                                $comment = new Comment();
                                $comment->issue_id = $issue->id;
                                $comment->content = '_(Mass Edit) No comments for this change_';
                                $comment->create_user_id = Yii::app()->user->id;
                                if($comment->validate())
                                    $comment->save(false);

                                $issue->buildCommentDetails($comment->id);
                                $issue->addToActionLog($issue->id, Yii::app()->user->id, 'change', $this->createUrl('issue/view', array('id' => $issue->id, 'identifier' => $issue->project->identifier, '#' => 'note-'.$issue->commentCount)), $comment->id);

                                $issue->save(false);
                            }
                            break;
                        default:
                            break;
                    }
                }
            }
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        if (isset($_GET['identifier'])){
            $_GET['projectname'] = Project::getProjectNameFromIdentifier($_GET['identifier']);
        }
        $this->layout = '//layouts/column1';
        $issue = Issue::model()->with(array('comments','tracker','user', 'issueCategory', 'issuePriority', 'milestone', 'assignedTo', 'updatedBy','project'))->findByPk((int) $id);//$this->loadModel($id, true);
        $this->render('view', array(
            'model' => $issue,
        ));
    }

    protected function createComment($issue) {
        $comment = new Comment;
        if (isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];
            if ($issue->addComment($comment)) {
                Yii::app()->user->setFlash('commentSubmitted', "Your comment has been added.");
                //$this->refresh();
            }
        }
        return $comment;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Issue;

        $project_name = '';
        if (isset($_GET['identifier'])){
            $project_name = $_GET['projectname'] = Project::getProjectNameFromIdentifier($_GET['identifier']);
            $project_name .= ' - ';
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Issue'])) {
            $model->attributes = $_POST['Issue'];
            if ($model->save()) {
                //TODO: check if the user wants to be a watcher of all created issues ?
                $watcher = new Watcher();
                $watcher->issue_id = $model->id;
                $watcher->user_id = Yii::app()->user->id;
                $watcher->save();
                $model->addToActionLog($this->id,Yii::app()->user->id,'new', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $model->project->identifier)));
                Yii::app()->user->setFlash('success',"Issue was succesfully created");
                $this->redirect(array('view', 'id' => $model->id, 'identifier' => $model->project->identifier));
            } else {
                Yii::app()->user->setFlash('error',"There was an error creating the issue.");
            }
        }

        $this->render('create', array(
            'model' => $model,
            'project_name' => $project_name,
        ));
    }

    public function actionMove($id) {
        $model = Issue::model()->with(array('project', 'tracker'))->findByPk((int) $id);
        $_GET['projectname'] = $model->project->name;
        if (isset($_POST['Issue'])) {
            $model->project_id = $_POST['Issue']['project_id'];
            // milestone and category are connected to project
            // they need to be set to null
            $model->milestone_id = null;
            $model->issue_category_id = null;
            if($model->wasModified()) {
                if($model->validate()) {

                    $comment = new Comment;
                    $comment->content = '_Issue was moved between projects_';
                    $comment->issue_id = $model->id;
                    $comment->create_user_id = Yii::app()->user->id;
                    $comment->update_user_id = Yii::app()->user->id;

                    if($comment->validate()) {
                        $comment->save(false);
                        $model->updated_by = $comment->create_user_id;
                    }

                    if($model->save(false)) {

                        $project = Project::model()->findByPk((int)$model->project_id);
                        $model->buildCommentDetails($comment->id);
                        $model->addToActionLog($model->id,Yii::app()->user->id,'change', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $project->identifier, '#' => 'note-'.$model->commentCount)), $comment->id);

                        Yii::app()->user->setFlash('success',"Issue was succesfully moved");

                        $this->redirect(array('view', 'id' => $model->id, 'identifier' => $project->identifier));


                    }else {
                        Yii::app()->user->setFlash('error',"There was an error moving the issue");
                    }
                }
            }
        }
        
        $this->render('move', array(
            'model' => $model,
            'project_name' => $model->project->name,
        ));
    }

    public function actionGetcomment($id){
        $model = Comment::model()->findbyPk((int)$id);
        if(null != $model)
            echo $model->content;
    }
    
    public function actionEditcomment(){
        $model = Comment::model()->findbyPk((int)$_POST['id']);
        if(null != $model) {
            $model->content = $_POST['value'];
            $model->update_user_id = Yii::app()->user->id;
            if($model->save()) {
                echo Yii::app()->textile->textilize($model->content);
            }
            else {
                echo 'Error';
            }
        }
    }
    
    public function actionComment($id) {
        $this->layout = '//layouts/column1';

        $model = Issue::model()->with(array('comments','tracker','user', 'issueCategory', 'issuePriority', 'milestone', 'assignedTo', 'updatedBy', 'project'))->findByPk((int) $id);

        $_GET['projectname'] = $model->project->name;

        $comment = new Comment;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $comment_made = false;
        if (isset($_POST['Issue'])) {
            $model->attributes = $_POST['Issue'];
            if (($_POST['Comment'] !== '')) {
                $comment->content = $_POST['Comment'];
                $comment->issue_id = $model->id;
                $comment->create_user_id = Yii::app()->user->id;
                $comment->update_user_id = Yii::app()->user->id;
                $comment_made = true;
            }
            if($model->wasModified()||($comment_made)) {
                if($comment_made) {
                    $model->updated_by = $comment->create_user_id;
                } else {
                    $model->updated_by = Yii::app()->user->id;
                }
                if ($model->validate()) {

                    if(!$comment_made) {
                        $comment->content = '_No comments for this change_';
                        $comment->issue_id = $model->id;
                        $comment->create_user_id = Yii::app()->user->id;
                        $comment->update_user_id = Yii::app()->user->id;
                    }

                    if($comment->validate()) {
                        $comment->save(false);
                        $model->updated_by = $comment->create_user_id;
                    }

                    $has_details = $model->buildCommentDetails($comment->id);

                    $model->save(false);


                    $model->sendNotification($model->id, $comment->id, $model->updated_by);

                    if($has_details) {
                        if($model->status == 'swIssue/resolved') {
                            $model->addToActionLog($model->id,Yii::app()->user->id,'resolved', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $model->project->identifier, '#' => 'note-'.$model->commentCount)), $comment->id);
                        } elseif($model->status == 'swIssue/rejected') {
                            $model->addToActionLog($model->id,Yii::app()->user->id,'rejected', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $model->project->identifier, '#' => 'note-'.$model->commentCount)), $comment->id);
                        } else {
                            $model->addToActionLog($model->id,Yii::app()->user->id,'change', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $model->project->identifier, '#' => 'note-'.$model->commentCount)), $comment->id);
                        }
                    } else {
                        $model->addToActionLog($model->id,Yii::app()->user->id,'note', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $model->project->identifier, '#' => 'note-'.$model->commentCount)), $comment->id);
                    }

                    Yii::app()->user->setFlash('success',"Issue was succesfully updated");
                    $this->redirect(array('view', 'id' => $model->id, 'identifier' => $model->project->identifier));
                } else {
                    Yii::app()->user->setFlash('error',"There was an error updating the issue");
                }
            } else {
                Yii::app()->user->setFlash('success',"No changes detected");
                $this->redirect(array('view', 'id' => $model->id, 'identifier' => $model->project->identifier));
            }
        }

        $this->render('comment', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $this->layout = '//layouts/column1';
        
        $model = Issue::model()->with(array('comments','tracker','user', 'issueCategory', 'issuePriority', 'milestone', 'assignedTo', 'updatedBy', 'project'))->findByPk((int) $id);

        $_GET['projectname'] = $model->project->name;

        $comment = new Comment;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $comment_made = false;
        if (isset($_POST['Issue'])) {
            $model->attributes = $_POST['Issue'];
            if (($_POST['Comment'] !== '')) {
                $comment->content = $_POST['Comment'];
                $comment->issue_id = $model->id;
                $comment->create_user_id = Yii::app()->user->id;
                $comment->update_user_id = Yii::app()->user->id;
                
                //TODO: check if the user wants to be
                // a watcher of issues where they comment!
                if(!$model->watchedBy()) {
                    $watcher = new Watcher();
                    $watcher->issue_id = $model->id;
                    $watcher->user_id = Yii::app()->user->id;
                    $watcher->save();
                }
                
                $comment_made = true;
            }
            if($model->wasModified()||($comment_made)) {
                if($comment_made) {
                    $model->updated_by = $comment->create_user_id;
                } else {
                    $model->updated_by = Yii::app()->user->id;
                }
                if ($model->validate()) {

                    if(!$comment_made) {
                        $comment->content = '_No comments for this change_';
                        $comment->issue_id = $model->id;
                        $comment->create_user_id = Yii::app()->user->id;
                        $comment->update_user_id = Yii::app()->user->id;
                    }

                    if($comment->validate()) {
                        $comment->save(false);
                        $model->updated_by = $comment->create_user_id;
                    }

                    $has_details = $model->buildCommentDetails($comment->id);

                    $model->save(false);

                    
                    $model->sendNotification($model->id, $comment->id, $model->updated_by);

                    if($has_details) {
                        if($model->status == 'swIssue/resolved') {
                            $model->addToActionLog($model->id,Yii::app()->user->id,'resolved', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $model->project->identifier, '#' => 'note-'.$model->commentCount)), $comment->id);
                        } elseif($model->status == 'swIssue/rejected') {
                            $model->addToActionLog($model->id,Yii::app()->user->id,'rejected', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $model->project->identifier, '#' => 'note-'.$model->commentCount)), $comment->id);
                        } else {
                            $model->addToActionLog($model->id,Yii::app()->user->id,'change', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $model->project->identifier, '#' => 'note-'.$model->commentCount)), $comment->id);
                        }
                    } else {
                        $model->addToActionLog($model->id,Yii::app()->user->id,'note', $this->createUrl('issue/view', array('id' => $model->id, 'identifier' => $model->project->identifier, '#' => 'note-'.$model->commentCount)), $comment->id);
                    }

                    Yii::app()->user->setFlash('success',"Issue was succesfully updated");
                    $this->redirect(array('view', 'id' => $model->id, 'identifier' => $model->project->identifier));
                } else {
                    Yii::app()->user->setFlash('error',"There was an error updating the issue");
                }
            } else {
                Yii::app()->user->setFlash('success',"No changes detected");
                $this->redirect(array('view', 'id' => $model->id, 'identifier' => $model->project->identifier));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            
            Yii::app()->user->setFlash('success', "Issue " . $id . " has been deleted.");

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                //$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
                $this->redirect(array('issue/index', 'identifier' => $_GET['identifier']));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function getProjects() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name, identifier";
        $results = Project::model()->findAll($Criteria);
        $project_list = array();
        foreach ($results as $result) {
            $project_list[$result->identifier] = $result->name;
        }
        return $project_list;
    }

    public function getTrackerFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name";
        $results = Tracker::model()->findAll($Criteria);
        $tracker_list = array();
        foreach ($results as $result) {
            $tracker_list[$result->name] = $result->name;
        }
        return $tracker_list;
    }

    public function getPriorityFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name";
        $results = IssuePriority::model()->findAll($Criteria);
        $priority_list = array();
        foreach ($results as $result) {
            $priority_list[$result->name] = $result->name;
        }
        return $priority_list;
    }

    public function getUserFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "username";
        $Criteria->order = 'username';
        $results = User::model()->findAll($Criteria);
        $user_list = array();
        foreach ($results as $result) {
            $user_list[$result->username] = $result->username;
        }
        return $user_list;
    }

    public function getMemberFilter() {
        $results = Project::model()->findByAttributes(array('identifier' => $_GET['identifier']))->getMembers();
        $user_list = array();
        foreach ($results as $result) {
            $user_list[$result->user->username] = $result->user->username;
        }
        return $user_list;
    }
    
    public function getMilestoneFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name,  effective_date";
        $Criteria->order = 'effective_date';
        if (isset($_GET['identifier'])) {
            $Criteria->compare('project_id', $this->getProject($_GET['identifier']), true);
        }
        $results = Milestone::model()->findAll($Criteria);
        $milestone_list = array();
        foreach ($results as $result) {
            $milestone_list[$result->name] = $result->name;
        }
        return $milestone_list;
    }

    public function getCategoryFilter() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name";
        $Criteria->order = 'name';
        if (isset($_GET['identifier'])) {
            $Criteria->compare('project_id', $this->getProject($_GET['identifier']), true);
        }
        $results = IssueCategory::model()->findAll($Criteria);
        $category_list = array();
        foreach ($results as $result) {
            $category_list[$result->name] = $result->name;
        }
        return $category_list;
    }

    public function getTrackerSelectList() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name, id";
        $results = Tracker::model()->findAll($Criteria);
        $tracker_list = array();
        foreach ($results as $result) {
            $tracker_list[$result->id] = $result->name;
        }
        return $tracker_list;
    }

    public function getPrioritySelectList() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name, id";
        $results = IssuePriority::model()->findAll($Criteria);
        $priority_list = array();
        foreach ($results as $result) {
            $priority_list[$result->id] = $result->name;
        }
        return $priority_list;
    }

    public function getMemberSelectList() {
        $results = Project::model()->findByAttributes(array('identifier' => $_GET['identifier']))->getMembers();
        $user_list = array();
        foreach ($results as $result) {
            $user_list[$result->user->id] = $result->user->username;
        }
        return $user_list;
    }

    public function getUserSelectList() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "username, id";
        $Criteria->order = 'username';
        $results = User::model()->findAll($Criteria);
        $user_list = array();
        foreach ($results as $result) {
            $user_list[$result->id] = $result->username;
        }
        return $user_list;
    }

    public function getMilestoneSelectList($project_id, $filter = false) {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name, title, id, effective_date, project_id";
        $Criteria->order = 'effective_date';
        $Criteria->compare('project_id', $project_id);
        $milestone_list = array();
        $results = Milestone::model()->findAll($Criteria);
        foreach ($results as $result) {
            if($filter) {
                if(strtotime($result->effective_date) >= strtotime(date("Y-m-d")))
                    $milestone_list[$result->id] = $result->name . ' : ' . $result->title;
            } else {
                $milestone_list[$result->id] = $result->name . ' : ' . $result->title;
            }
        }
        return $milestone_list;
    }

    public function getCategorySelectList() {
        $Criteria = new CDbCriteria();
        $Criteria->select = "name, id";
        $Criteria->order = 'name';
        if (isset($_GET['identifier'])) {
            $Criteria->compare('project_id', $this->getProject($_GET['identifier']), true);
        }
        $results = IssueCategory::model()->findAll($Criteria);
        $category_list = array();
        foreach ($results as $result) {
            $category_list[$result->id] = $result->name;
        }
        return $category_list;
    }

    /**
     * Lists all models.
     */
    public function actionIndex($identifier = '') {
        if($identifier !== '') $_GET['projectname'] = Project::getProjectNameFromIdentifier($identifier);

        $issueFilter = array('1' => 'Open Issues', '2' => 'Closed Issues');
        //$_GET['issueFilter'] = '1';

        $model = new Issue('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Issue']))
            $model->attributes = $_GET['Issue'];

        // page size drop down changed
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);  // would interfere with pager and repetitive page size change
        }
        $this->render('index', array(
            'model' => $model,
            'issueFilter' => $issueFilter,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $this->block_robots = true;

        $model = new Issue('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Issue']))
            $model->attributes = $_GET['Issue'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $withComments=false) {

        if($withComments) {
            $model = Issue::model()->with(
                    array('comments' => array('with' => 'author'))
                    )->findbyPk((int)$id);
        } else {
            $model = Issue::model()->findByPk((int) $id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    private function getProject($identifier) {
        $project = Project::model()->findByAttributes(array('identifier' => $identifier));
        return $project->id;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'issue-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
