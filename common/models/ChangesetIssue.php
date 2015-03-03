<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%changeset_issue}}".
 *
 * @property integer $id
 * @property integer $changeset_id
 * @property integer $issue_id
 *
 * @property Changeset $changeset
 * @property Issue $issue
 */
class ChangesetIssue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%changeset_issue}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['changeset_id', 'issue_id'], 'required'],
            [['changeset_id', 'issue_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'changeset_id' => Yii::t('app', 'Changeset ID'),
            'issue_id' => Yii::t('app', 'Issue ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangeset()
    {
        return $this->hasOne(Changeset::className(), ['id' => 'changeset_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssue()
    {
        return $this->hasOne(Issue::className(), ['id' => 'issue_id']);
    }
}
