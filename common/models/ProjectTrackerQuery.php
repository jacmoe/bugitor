<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ProjectTracker]].
 *
 * @see ProjectTracker
 */
class ProjectTrackerQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ProjectTracker[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProjectTracker|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}