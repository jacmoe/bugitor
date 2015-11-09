<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Repository]].
 *
 * @see Repository
 */
class RepositoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Repository[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Repository|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}