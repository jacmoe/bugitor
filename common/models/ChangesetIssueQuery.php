<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ChangesetIssue]].
 *
 * @see ChangesetIssue
 */
class ChangesetIssueQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ChangesetIssue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ChangesetIssue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}