<?php
namespace app\commands;

/**
 * Class ItsMyProject
 */
class ItsMyProject extends \yii\rbac\Rule
{

    public $name = 'project.its-mine';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return $user === $params['Contract']->seller_id;
    }
}