<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[AuthorUser]].
 *
 * @see AuthorUser
 */
class AuthorUserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return AuthorUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuthorUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}