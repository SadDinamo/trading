<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\TksPreferencies;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name.' - '.TksPreferencies::findByPreferenceName('ClientType')->Value,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Настройки', 'url' => ['/tkspreferencies/index']],
            ['label' => 'TKS invest', 'items' => [
                '<li class="dropdown-header">Внешние прямые запросы:</li>',
                ['label' => 'TKS - tickers', 'url' => ['/tinkoffinvest/allmarketstocks']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">Var_Dump справочники</li>',
                ['label' => 'TKS - stocks', 'url' => ['/tinkoffinvest/stocks']],
                ['label' => 'TKS - bonds', 'url' => ['/tinkoffinvest/bonds']],
                ['label' => 'TKS - ETFs', 'url' => ['/tinkoffinvest/etfs']],
                ['label' => 'TKS - currencies', 'url' => ['/tinkoffinvest/currencies']],
                ['label' => 'TKS - portfolio', 'url' => ['/tinkoffinvest/portfolio']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">Внутренняя база:</li>',
                ['label' => 'DB - tickers', 'url' => ['/tksinvesttickers/index']],
            ]],
            ['label' => 'test', 'items' => [
                '<li class="dropdown-header">Покупки инсайдеров FINWIZ</li>',
                ['label' => 'Var_dump', 'url' => ['/parser/test']],
                '<li class="divider"></li>',


                '<li class="dropdown-header">1:</li>',
                ['label' => 'test1', 'url' => ['#']],
                '<li class="divider"></li>',
                '<li class="dropdown-header">2:</li>',
                ['label' => 'test2', 'url' => ['#']],
                ['label' => 'drop2', 'options' => ['class' => 'dropright'], 'items' => [['label' => '1', 'url' => '#'], ['label' => '2', 'url' => '#']]],
            ]],
            //['label' => 'About', 'url' => ['/site/about']],
            //['label' => 'Contact', 'url' => ['/site/contact']],
        ];
        // if (Yii::$app->user->isGuest) {
        //     $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        //     $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        // } else {
        //     $menuItems[] = '<li>'
        //         . Html::beginForm(['/site/logout'], 'post')
        //         . Html::submitButton(
        //             'Logout (' . Yii::$app->user->identity->username . ')',
        //             ['class' => 'btn btn-link logout']
        //         )
        //         . Html::endForm()
        //         . '</li>';
        // }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>