<?php

namespace frontend\controllers;

class ProjectController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOverview()
    {
        return $this->render('overview');
    }

    public function actionIssues()
    {
        return $this->render('issues');
    }

    public function actionRoadmap()
    {
        return $this->render('roadmap');
    }

    public function actionActivity()
    {
        return $this->render('activity');
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

}
