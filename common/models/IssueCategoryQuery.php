<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[IssueCategory]].
 *
 * @see IssueCategory
 */
class IssueCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return IssueCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return IssueCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}