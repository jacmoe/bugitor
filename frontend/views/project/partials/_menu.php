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

?>

<?= Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px',
    ],
    'items' => [
        [
            'label'   => Yii::t('app', 'Overview'),
            'url'     => ['project/overview', 'identifier' => 'bugitor'],
        ],
        [
            'label'   => Yii::t('app', 'Activity'),
            'url'     => ['project/activity', 'identifier' => 'bugitor'],
        ],
        [
            'label'   => Yii::t('app', 'Roadmap'),
            'url'     => ['project/roadmap', 'identifier' => 'bugitor'],
        ],
        [
            'label'   => Yii::t('app', 'Issues'),
            'url'     => ['project/issues', 'identifier' => 'bugitor'],
        ],
    ],//items
]) ?>
