<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "pending_changeset".
 *
 * @property integer $id
 * @property integer $changeset_id
 *
 * @property Changeset $changeset
 */
class PendingChangeset extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pending_changeset';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['changeset_id'], 'required'],
            [['changeset_id'], 'integer']
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChangeset()
    {
        return $this->hasOne(\common\models\Changeset::className(), ['id' => 'changeset_id']);
    }
}
