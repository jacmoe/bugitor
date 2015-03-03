<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "author_user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $author
 *
 * @property User $user
 */
class AuthorUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%author_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['author'], 'required'],
            [['author'], 'string', 'max' => 255],
            [['author'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'author' => Yii::t('app', 'Author'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }
}