<?php

namespace frontend\controllers;

class HelpController extends \yii\web\Controller
{
    public function actionIndex()
    {
        \Yii::$app->mailqueue->compose()
            ->setFrom('root@example.tst')
            ->setTo('jacmoe@example.tst')
            ->setSubject('Message subject')
            ->setTextBody('Plain text content')
            ->setHtmlBody('<b>HTML content</b>')
            ->queue();
        return $this->render('index');
    }

}
