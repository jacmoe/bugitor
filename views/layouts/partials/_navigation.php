<?php
// main navigation
$controller = Yii::$app->controller ? Yii::$app->controller->id : null;
$module = Yii::$app->controller->module ? Yii::$app->controller->module->id : null;
echo Nav::widget([
    'id' => 'main-nav',
    'encodeLabels' => false,
    'options' => ['class' => 'nav navbar-nav navbar-main-menu'],
    'activateItems' => true,
    'activateParents' => true,
    'dropDownCaret' => '<span class="caret"></span>',
    'items' => [
        ['label' => 'Dashboard', 'url' => ['/site/index'], 'options' => ['title' => 'Projects'], 'active' => ($controller == 'site')],
        ['label' => 'Projects', 'url' => ['/project/index'], 'options' => ['title' => 'Projects'], 'active' => (($controller == 'project') || ($controller == 'activity') || ($controller == 'issue'))],
        ['label' => 'Docs', 'url' => ['/docs/page'], 'options' => ['title' => 'Bugitor Documentation'], 'active' => ($module == 'docs')],
        Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/user/login']]
         ) : (
            '<li>' . Html::a('Logout', ['/user/logout'], ['data-method' => 'post']).'</li>'
         )
    ],// items
]);