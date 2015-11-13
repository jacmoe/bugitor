<?php

namespace frontend\controllers;

class ProjectController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOverview($identifier)
    {
        return $this->render('overview');
    }

    public function actionIssues($identifier)
    {
        return $this->render('issues');
    }

    public function actionRoadmap($identifier)
    {
        return $this->render('roadmap');
    }

    public function actionActivity($identifier)
    {
        return $this->render('activity');
    }

    public function actionUpdate($identifier)
    {
        return $this->render('update');
    }

}
