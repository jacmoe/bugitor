<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Issue]].
 *
 * @see \common\models\Issue
 */
class IssueQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Issue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Issue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}