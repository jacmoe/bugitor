<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\widgets\Alert;
use app\helpers\Bugitor;
nullref\datatable\DataTableAsset::register($view);
AppAsset::register($view);
$view->beginPage();
