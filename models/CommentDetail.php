<?php
namespace app\models;
/*
* This file is part of
*  _                 _ _
* | |__  _   _  __ _(_) |_ ___  _ __
* | '_ \| | | |/ _` | | __/ _ \| '__|
* | |_) | |_| | (_| | | || (_) | |
* |_.__/ \__,_|\__, |_|\__\___/|_|
*              |___/
*                 issue tracker
*
*	Copyright (c) 2009 - 2016 Jacob Moen
*	Licensed under the MIT license
*/

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
            [['change'], 'string', 'max' => 255],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::className(), 'targetAttribute' => ['comment_id' => 'id']],
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
     * @return \app\models\query\CommentDetailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\CommentDetailQuery(get_called_class());
    }
}
