<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "issue".
 *
 * @property integer $id
 * @property integer $tracker_id
 * @property integer $project_id
 * @property string $subject
 * @property string $description
 * @property integer $issue_category_id
 * @property integer $user_id
 * @property integer $issue_priority_id
 * @property integer $version_id
 * @property integer $assigned_to
 * @property string $created
 * @property string $modified
 * @property integer $done_ratio
 * @property string $status
 * @property integer $closed
 * @property integer $pre_done_ratio
 * @property integer $updated_by
 * @property integer $last_comment
 *
 * @property Attachment[] $attachments
 * @property ChangesetIssue[] $changesetIssues
 * @property Comment[] $comments
 * @property User $assignedTo
 * @property IssueCategory $issueCategory
 * @property IssuePriority $issuePriority
 * @property Project $project
 * @property Tracker $tracker
 * @property User $updatedBy
 * @property User $user
 * @property Version $version
 * @property RelatedIssue[] $relatedIssues
 * @property Watcher[] $watchers
 * @property User[] $users
 */
class Issue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%issue}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tracker_id', 'project_id', 'subject', 'description', 'user_id', 'issue_priority_id', 'status', 'closed', 'pre_done_ratio'], 'required'],
            [['tracker_id', 'project_id', 'issue_category_id', 'user_id', 'issue_priority_id', 'version_id', 'assigned_to', 'done_ratio', 'closed', 'pre_done_ratio', 'updated_by', 'last_comment'], 'integer'],
            [['description'], 'string'],
            [['created', 'modified'], 'safe'],
            [['subject', 'status'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tracker_id' => Yii::t('app', 'Tracker ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'subject' => Yii::t('app', 'Subject'),
            'description' => Yii::t('app', 'Description'),
            'issue_category_id' => Yii::t('app', 'Issue Category ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'issue_priority_id' => Yii::t('app', 'Issue Priority ID'),
            'version_id' => Yii::t('app', 'Version ID'),
            'assigned_to' => Yii::t('app', 'Assigned To'),
            'created' => Yii::t('app', 'Created'),
            'modified' => Yii::t('app', 'Modified'),
            'done_ratio' => Yii::t('app', 'Done Ratio'),
            'status' => Yii::t('app', 'Status'),
            'closed' => Yii::t('app', 'Closed'),
            'pre_done_ratio' => Yii::t('app', 'Pre Done Ratio'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'last_comment' => Yii::t('app', 'Last Comment'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(\common\models\Attachment::className(), ['issue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangesetIssues()
    {
        return $this->hasMany(\common\models\ChangesetIssue::className(), ['issue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(\common\models\Comment::className(), ['issue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedTo()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'assigned_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssueCategory()
    {
        return $this->hasOne(\common\models\IssueCategory::className(), ['id' => 'issue_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssuePriority()
    {
        return $this->hasOne(\common\models\IssuePriority::className(), ['id' => 'issue_priority_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(\common\models\Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTracker()
    {
        return $this->hasOne(\common\models\Tracker::className(), ['id' => 'tracker_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersion()
    {
        return $this->hasOne(\common\models\Version::className(), ['id' => 'version_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedIssues()
    {
        return $this->hasMany(\common\models\RelatedIssue::className(), ['issue_to' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWatchers()
    {
        return $this->hasMany(\common\models\Watcher::className(), ['issue_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(\common\models\User::className(), ['id' => 'user_id'])->viaTable('watcher', ['issue_id' => 'id']);
    }
}
