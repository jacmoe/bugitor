<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
app\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <header class="navbar navbar-inverse navbar-static" id="top">
        <div class="container">
            <div id="main-nav-head" class="navbar-header">
                <a href="<?= Yii::$app->homeUrl ?>" class="navbar-brand">
                    <span>
                        <img src="<?= Yii::getAlias('@web/bugitor_white.svg') ?>"
                            alt="Bugitor"
                            width="48" height="48" />
                        Bugitor</span>
                </a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><i class="fa fa-inverse fa-bars"></i></button>
            </div>
            <div class="navbar-collapse collapse navbar-right">
                <?php

                // main navigation
                $controller = Yii::$app->controller ? Yii::$app->controller->id : null;
                echo Nav::widget([
                    'id' => 'main-nav',
                    'encodeLabels' => false,
                    'options' => ['class' => 'nav navbar-nav navbar-main-menu'],
                    'activateItems' => true,
                    'activateParents' => true,
                    'dropDownCaret' => '<span class="caret"></span>',
                    'items' => [
                        ['label' => 'Docs', 'url' => ['/docs/page'], 'options' => ['title' => 'Bugitor Documentation']],
                            Yii::$app->user->isGuest ? (
                                 ['label' => 'Login', 'url' => ['/user/login']]
                             ) : (
                                 '<li>'
                                 . Html::beginForm(['/user/logout'], 'post')
                                 . Html::submitButton(
                                     'Logout (' . Yii::$app->user->identity->username . ')',
                                     ['class' => 'btn btn-link']
                                 )
                                 . Html::endForm()
                                 . '</li>'
                             )
                    ],// items
                ]); ?>
            </div>
        </div>
    </header>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
