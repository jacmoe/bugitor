<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Watcher]].
 *
 * @see Watcher
 */
class WatcherQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Watcher[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Watcher|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}