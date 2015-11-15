<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\Nav;
use yii\helpers\Html;

?>

<?= Nav::widget([
    'options' => [
        'class' => 'nav-tabs nav-justified',
        'style' => 'margin-bottom: 15px',
    ],
    'items' => [
        [
            'label'   => Html::tag('i', '', ['class' => 'fa fa-dashboard']) . ' ' . Yii::t('app', 'Overview'),
            'url'     => ['project/overview', 'identifier' => $identifier],
        ],
        [
            'label'   => Html::tag('i', '', ['class' => 'fa fa-rocket']) . ' ' . Yii::t('app', 'Activity'),
            'url'     => ['project/activity', 'identifier' => $identifier],
        ],
        [
            'label'   => Html::tag('i', '', ['class' => 'fa fa-map']) . ' ' . Yii::t('app', 'Roadmap'),
            'url'     => ['project/roadmap', 'identifier' => $identifier],
        ],
        [
            'label'   => Html::tag('i', '', ['class' => 'fa fa-ticket']) . ' ' . Yii::t('app', 'Issues'),
            'url'     => ['project/issues', 'identifier' => $identifier],
        ],
        [
            'label'   => Html::tag('i', '', ['class' => 'fa fa-wrench']) . ' ' . Yii::t('app', 'Settings'),
            'url'     => ['project/settings', 'identifier' => $identifier],
            'visible' => \Yii::$app->user->can('project_settings'),
        ],
    ],//items
    'encodeLabels' => false,
]) ?>
