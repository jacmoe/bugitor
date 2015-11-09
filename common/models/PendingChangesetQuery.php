<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[PendingChangeset]].
 *
 * @see PendingChangeset
 */
class PendingChangesetQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PendingChangeset[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PendingChangeset|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}