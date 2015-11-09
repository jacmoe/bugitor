<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%comment_detail}}".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property string $change
 *
 * @property Comment $comment
 */
class CommentDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id'], 'required'],
            [['comment_id'], 'integer'],
            [['change'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'comment_id' => Yii::t('app', 'Comment ID'),
            'change' => Yii::t('app', 'Change'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comment::className(), ['id' => 'comment_id']);
    }

    /**
     * @inheritdoc
     * @return CommentDetailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommentDetailQuery(get_called_class());
    }
}
