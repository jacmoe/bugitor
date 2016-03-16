<?php
$module = Yii::$app->controller->module ? Yii::$app->controller->module->id : null;
if($module != 'docs') {
    $view->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
}
