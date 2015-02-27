<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%action_log}}".
 *
 * @property integer $id
 * @property string $type
 * @property integer $author_id
 * @property string $theDate
 * @property string $url
 * @property integer $project_id
 * @property string $subject
 * @property string $description
 *
 * @property Project $project
 * @property User $author
 */
class ActionLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%action_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'author_id', 'theDate', 'url', 'project_id', 'subject', 'description'], 'required'],
            [['author_id', 'project_id'], 'integer'],
            [['theDate'], 'safe'],
            [['description'], 'string'],
            [['type', 'url', 'subject'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'author_id' => Yii::t('app', 'Author ID'),
            'theDate' => Yii::t('app', 'The Date'),
            'url' => Yii::t('app', 'Url'),
            'project_id' => Yii::t('app', 'Project ID'),
            'subject' => Yii::t('app', 'Subject'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
}
