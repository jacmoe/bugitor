<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Tracker]].
 *
 * @see Tracker
 */
class TrackerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Tracker[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tracker|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}