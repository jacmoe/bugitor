<?php

namespace frontend\controllers;

use Yii;
use common\models\Project;
use common\models\search\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ProjectController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOverview($identifier)
    {
        return $this->render('overview', ['model' => $this->findModel($identifier)]);
    }

    public function actionIssues($identifier)
    {
        return $this->render('issues', ['model' => $this->findModel($identifier)]);
    }

    public function actionRoadmap($identifier)
    {
        return $this->render('roadmap', ['model' => $this->findModel($identifier)]);
    }

    public function actionActivity($identifier)
    {
        return $this->render('activity', ['model' => $this->findModel($identifier)]);
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'overview' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
        $model->loadDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['overview', 'identifier' => $model->identifier]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($identifier)
    {
        return $this->render('update');
    }

    /**
     * Finds the Issue model based on its identifier.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $identifier
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($identifier)
    {
        if (($model = Project::find()->where(['identifier' => $identifier])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
