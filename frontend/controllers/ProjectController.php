<?php

namespace frontend\controllers;

use Yii;
use common\models\Project;
use common\models\search\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;

class ProjectController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index', 'overview', 'issues', 'activity', 'roadmap'], 'roles' => ['?', '@']],
                    ['allow' => true, 'actions' => ['settings', 'create'], 'roles' => ['@']],
                ],
            ],
        ];
    }

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

        if ($model->load(Yii::$app->request->post())) {

            // store the current logo
            $old_logo = $model->logo;

            $image = UploadedFile::getInstance($model, 'image');
            if (!is_null($image))
            {
                // store the source file name
                $model->logoname = $image->name;
                $ext = end((explode(".", $image->name)));

                // generate a unique file name
                $model->logo = Yii::$app->security->generateRandomString().".{$ext}";

                $path = Yii::$app->basePath . '/web/uploads/' . $model->logo;
            }
            if($model->save()) {
                if (!is_null($image)) {
                    // save the new logo
                    $image->saveAs($path);
                    // get rid of the old logo
                    unlink(Yii::$app->basePath . '/web/uploads/' . $old_logo);
                }
                return $this->render('settings', ['model' => $model]);
            } else {
                Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Something went wrong and the settings was not saved.'));
            }
         } else {
             return $this->render('settings', ['model' => $model]);
         }
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

        if ($model->load(Yii::$app->request->post())) {
            $image = UploadedFile::getInstance($model, 'image');
            if (!is_null($image))
            {
                // store the source file name
                $model->logoname = $image->name;
                $ext = end((explode(".", $image->name)));

                // generate a unique file name
                $model->logo = Yii::$app->security->generateRandomString().".{$ext}";

                $path = Yii::$app->basePath . '/web/uploads/' . $model->logo;
            }
            $model->setAttribute('owner', \Yii::$app->user->identity->id);
            if($model->save()) {
                if (!is_null($image)) {
                    $image->saveAs($path);
                }
                return $this->redirect(['overview', 'identifier' => $model->identifier]);
            } else {
                Yii::$app->getSession()->setFlash('danger', Yii::t('app', 'Something went wrong and the settings was not saved.'));
            }
        } else {
            return $this->render('create', [
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
