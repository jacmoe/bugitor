<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "related_issue".
 *
 * @property integer $issue_from
 * @property integer $issue_to
 * @property integer $relation_type_id
 *
 * @property Issue $issueFrom
 * @property Issue $issueTo
 * @property RelationType $relationType
 */
class RelatedIssue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'related_issue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue_from', 'issue_to', 'relation_type_id'], 'required'],
            [['issue_from', 'issue_to', 'relation_type_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'issue_from' => Yii::t('app', 'Issue From'),
            'issue_to' => Yii::t('app', 'Issue To'),
            'relation_type_id' => Yii::t('app', 'Relation Type ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssueFrom()
    {
        return $this->hasOne(\common\models\Issue::className(), ['id' => 'issue_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIssueTo()
    {
        return $this->hasOne(\common\models\Issue::className(), ['id' => 'issue_to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationType()
    {
        return $this->hasOne(\common\models\RelationType::className(), ['id' => 'relation_type_id']);
    }
}
