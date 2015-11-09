<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[RelationType]].
 *
 * @see RelationType
 */
class RelationTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return RelationType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RelationType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}