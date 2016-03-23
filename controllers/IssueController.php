<?php
namespace app\controllers;
/*
* This file is part of
*  _                 _ _
* | |__  _   _  __ _(_) |_ ___  _ __
* | '_ \| | | |/ _` | | __/ _ \| '__|
* | |_) | |_| | (_| | | || (_) | |
* |_.__/ \__,_|\__, |_|\__\___/|_|
*              |___/
*                 issue tracker
*
*	Copyright (c) 2009 - 2016 Jacob Moen
*	Licensed under the MIT license
*/

use Yii;
use app\models\Issue;
use app\models\search\IssueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * IssueController implements the CRUD actions for Issue model.
 */
class IssueController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index','view'],
                        'roles' => ['?', '@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['issue.create'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['issue.update'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['issue.delete'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Issue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new IssueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Issue model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Issue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Issue();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Issue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Issue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Issue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Issue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Issue::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
