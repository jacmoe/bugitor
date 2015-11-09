<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[RelatedIssue]].
 *
 * @see RelatedIssue
 */
class RelatedIssueQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return RelatedIssue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RelatedIssue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}