<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Changeset]].
 *
 * @see Changeset
 */
class ChangesetQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Changeset[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Changeset|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}