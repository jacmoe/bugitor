<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Attachment]].
 *
 * @see Attachment
 */
class AttachmentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Attachment[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Attachment|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}