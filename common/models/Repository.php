<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%repository}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $url
 * @property string $local_path
 * @property string $name
 * @property string $identifier
 * @property integer $status
 *
 * @property Changeset[] $changesets
 * @property Project $project
 */
class Repository extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%repository}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'name', 'identifier'], 'required'],
            [['project_id', 'status'], 'integer'],
            [['url', 'local_path', 'name', 'identifier'], 'string', 'max' => 255],
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
            'project_id' => Yii::t('app', 'Project ID'),
            'url' => Yii::t('app', 'Url'),
            'local_path' => Yii::t('app', 'Local Path'),
            'name' => Yii::t('app', 'Name'),
            'identifier' => Yii::t('app', 'Identifier'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangesets()
    {
        return $this->hasMany(Changeset::className(), ['scm_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @inheritdoc
     * @return RepositoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RepositoryQuery(get_called_class());
    }
}
