<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[IssuePriority]].
 *
 * @see IssuePriority
 */
class IssuePriorityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return IssuePriority[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return IssuePriority|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}