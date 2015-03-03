<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "relation_type".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 *
 * @property RelatedIssue[] $relatedIssues
 */
class RelationType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%relation_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedIssues()
    {
        return $this->hasMany(\common\models\RelatedIssue::className(), ['relation_type_id' => 'id']);
    }
}
