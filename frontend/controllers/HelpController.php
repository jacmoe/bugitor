<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class HelpController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index'], 'roles' => ['?', '@']],
                ],
            ],
        ];
    }

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
