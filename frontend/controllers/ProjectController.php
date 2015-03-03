<?php

namespace frontend\controllers;

use common\models\Project;
use common\models\ProjectSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
{
    public $layout = 'main.html';
	/**
	 * Lists all Project models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ProjectSearch;
		$dataProvider = $searchModel->search($_GET);

        Url::remember();
		return $this->render('index.html', [
			'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
		]);
	}

	/**
	 * Displays a single Project model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        Url::remember();
        return $this->render('view.html', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Project model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Project;

		try {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(Url::previous());
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
            $model->addError('_exception', $msg);
		}
        return $this->render('create.html', ['model' => $model,]);
	}

	/**
	 * Updates an existing Project model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load($_POST) && $model->save()) {
            return $this->redirect(Url::previous());
		} else {
			return $this->render('update.html', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Deletes an existing Project model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		return $this->redirect(Url::previous());
	}

	/**
	 * Finds the Project model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Project the loaded model
	 * @throws HttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Project::findOne($id)) !== null) {
			return $model;
		} else {
			throw new HttpException(404, 'The requested page does not exist.');
		}
	}
}
