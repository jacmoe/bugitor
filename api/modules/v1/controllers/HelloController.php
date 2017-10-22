<?php

namespace api\modules\v1\controllers;

use yii\rest\Controller;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;

class HelloController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
        ];
        
        // add QueryParamAuth for authentication
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    public function actionIndex()
    {
        return ['message' => 'Hello world.'];
    }

    public function actionView($id)
    {
        return ['message' => 'Hello ' . ($id ?: 'world')];
    }
}
