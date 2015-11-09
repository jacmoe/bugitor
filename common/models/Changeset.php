<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%changeset}}".
 *
 * @property integer $id
 * @property string $unique_ident
 * @property string $revision
 * @property string $author
 * @property integer $user_id
 * @property integer $scm_id
 * @property string $commit_date
 * @property string $message
 * @property integer $short_rev
 * @property string $parents
 * @property string $branches
 * @property string $tags
 * @property integer $add
 * @property integer $edit
 * @property integer $del
 * @property integer $branch_count
 * @property integer $tag_count
 * @property integer $parent_count
 *
 * @property Change[] $changes
 * @property Repository $scm
 * @property User $user
 * @property ChangesetIssue[] $changesetIssues
 * @property PendingChangeset[] $pendingChangesets
 */
class Changeset extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%changeset}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique_ident', 'revision', 'message'], 'required'],
            [['user_id', 'scm_id', 'short_rev', 'add', 'edit', 'del', 'branch_count', 'tag_count', 'parent_count'], 'integer'],
            [['commit_date'], 'safe'],
            [['message'], 'string'],
            [['unique_ident', 'revision', 'author', 'parents', 'branches', 'tags'], 'string', 'max' => 255],
            [['unique_ident'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'unique_ident' => Yii::t('app', 'Unique Ident'),
            'revision' => Yii::t('app', 'Revision'),
            'author' => Yii::t('app', 'Author'),
            'user_id' => Yii::t('app', 'User ID'),
            'scm_id' => Yii::t('app', 'Scm ID'),
            'commit_date' => Yii::t('app', 'Commit Date'),
            'message' => Yii::t('app', 'Message'),
            'short_rev' => Yii::t('app', 'Short Rev'),
            'parents' => Yii::t('app', 'Parents'),
            'branches' => Yii::t('app', 'Branches'),
            'tags' => Yii::t('app', 'Tags'),
            'add' => Yii::t('app', 'Add'),
            'edit' => Yii::t('app', 'Edit'),
            'del' => Yii::t('app', 'Del'),
            'branch_count' => Yii::t('app', 'Branch Count'),
            'tag_count' => Yii::t('app', 'Tag Count'),
            'parent_count' => Yii::t('app', 'Parent Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChanges()
    {
        return $this->hasMany(Change::className(), ['changeset_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScm()
    {
        return $this->hasOne(Repository::className(), ['id' => 'scm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangesetIssues()
    {
        return $this->hasMany(ChangesetIssue::className(), ['changeset_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendingChangesets()
    {
        return $this->hasMany(PendingChangeset::className(), ['changeset_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ChangesetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChangesetQuery(get_called_class());
    }
}
