<?php
echo Breadcrumbs::widget([
    'links' => isset($view->params['breadcrumbs']) ? $view->params['breadcrumbs'] : [],
]);
