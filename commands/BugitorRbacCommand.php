<?php
namespace app\commands;

use rmrevin\yii\rbac\RbacFactory;

/**
 * Class BugitorRbacCommand
 */
class BugitorRbacCommand extends \rmrevin\yii\rbac\Command
{

    protected function rules()
    {
        return [
            //RbacFactory::Rule('project.its-mine', '\app\commands\ItsMyProject'),
        ];
    }

    protected function getUserComponent()
    {
        $user = \Yii::$app->user;
        if (!$user instanceof \yii\web\User) {
            throw new \yii\base\InvalidConfigException(
                sprintf('You should configure "%s" component before executing this command.', 'user')
            );
        }

        return $user;
    }

    protected function roles()
    {
        return [
            RbacFactory::Role('admin', 'Administrator'),
            RbacFactory::Role('manager', 'Project Manager'),
            RbacFactory::Role('member', 'Project Member'),
            RbacFactory::Role('user', 'User'),
            RbacFactory::Role('guest', 'Guest'),
        ];
    }

    protected function permissions()
    {
        return [
            /** Project */
            RbacFactory::Permission('project.access', 'Access project'),
            RbacFactory::Permission('project.create', 'Create project'),
            RbacFactory::Permission('project.update', 'Update project'),
            //RbacFactory::Permission('project.update.own', 'Update owned project', 'project.its-mine'),
            RbacFactory::Permission('project.delete', 'Delete project'),
            //RbacFactory::Permission('project.delete.own', 'Delete owned project', 'project.its-mine'),
            /** Issue */
            RbacFactory::Permission('issue.access', 'Access issue'),
            RbacFactory::Permission('issue.create', 'Create issue'),
            RbacFactory::Permission('issue.update', 'Update issue'),
            //RbacFactory::Permission('issue.update.own', 'Update owned issue', 'issue.its-mine'),
            RbacFactory::Permission('issue.delete', 'Delete issue'),
            //RbacFactory::Permission('issue.delete.own', 'Delete owned issue', 'issue.its-mine'),
            /** User */
            RbacFactory::Permission('user.admin', 'Administrate users'),
        ];
    }

    protected function inheritanceRoles()
    {
        return [
            'admin' => [
                'manager',
            ],
            'manager' => [
                'member',
            ],
            'member' => [
                'user',
            ],
            'user' => [
                'guest',
            ],
            'guest' => [],
        ];
    }

    protected function inheritancePermissions()
    {
        return [
            'admin' => [
                'user.admin',
            ],
            'manager' => [
                'project.delete',
                'project.create',
                'issue.delete',
            ],
            'member' => [
                'issue.update',
                'project.update',
            ],
            'user' => [
                'issue.create',
            ],
            'guest' => [
                'project.access',
                'issue.access',
            ],
        ];
    }
}