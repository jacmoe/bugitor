<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\widgets\Alert;
use app\helpers\Bugitor;
$asset = AppAsset::register($view);
$view->beginPage();
$uber_logo = $asset->baseUrl . '/img/uberspace.png';
