<?php
// main navigation
$controller = Yii::$app->controller ? Yii::$app->controller->id : null;
$module = Yii::$app->controller->module ? Yii::$app->controller->module->id : null;
$items = [
    ['label' => 'Dashboard', 'url' => ['/site/index'], 'options' => ['title' => 'Dashboard'], 'active' => ($controller == 'site')],
    ['label' => 'Projects', 'url' => ['/project/index'], 'options' => ['title' => 'Projects'], 'active' => ($controller == 'project')],
    ['label' => 'Issues', 'url' => ['/issue/index'], 'options' => ['title' => 'Issues'], 'active' => ($controller == 'issue')],
    ['label' => 'Activity', 'url' => ['/activity/index'], 'options' => ['title' => 'Activity'], 'active' => ($controller == 'activity')],
    ['label' => 'Docs', 'url' => ['/docs/page'], 'options' => ['title' => 'Bugitor Documentation'], 'active' => ($module == 'docs')]];
    if(\Yii::$app->user->can('user.admin')) {
        $items[] = ['label' => 'User Admin', 'url' => ['/user/admin'], 'options' => ['title' => 'User Administration'], 'active' => ($module == 'user')];
    }
    if(Yii::$app->user->isGuest) {
        $items[] = ['label' => 'Login', 'url' => ['/user/login']];
    } else {
        $items[] = '<li>' . Html::a('Logout', ['/user/logout'], ['data-method' => 'post']).'</li>';
    }

echo Nav::widget([
    'id' => 'main-nav',
    'encodeLabels' => false,
    'options' => ['class' => 'nav navbar-nav navbar-main-menu'],
    'activateItems' => true,
    'activateParents' => true,
    'dropDownCaret' => '<span class="caret"></span>',
    'items' => $items,
]);