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
        $model = Project::find()->identifier($identifier)->one();
        return $this->render('overview', ['model' => $model]);
    }

    public function actionIssues($identifier)
    {
        $model = Project::find()->identifier($identifier)->one();
        return $this->render('issues', ['model' => $model]);
    }

    public function actionRoadmap($identifier)
    {
        $model = Project::find()->identifier($identifier)->one();
        return $this->render('roadmap', ['model' => $model]);
    }

    public function actionActivity($identifier)
    {
        $model = Project::find()->identifier($identifier)->one();
        return $this->render('activity', ['model' => $model]);
    }

    public function actionSettings($identifier)
    {
        $model = Project::find()->identifier($identifier)->one();
        return $this->render('settings', ['model' => $model]);
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
        $model = Project::find()->identifier($identifier)->one();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['settings', 'identifier' => $model->identifier]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the Project model based on its id.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
